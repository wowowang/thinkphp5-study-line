<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:40
 */

namespace app\ffmpeg\controller;

use think\Controller;
use think\Log;
use think\Request;

class Index extends Controller
{
    /*
     * 视图输出测试
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return mixed
     * 视频编码首页
     */
    public function viewMain()
    {
        $this->assign([
            'video_cut' => '视频剪切',
            'video_concat' => '视频合并',
            'name' => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        return $this->fetch();
    }

    /**
     * @return \think\response\View
     * 视频剪切
     * 思路图：
     * 【1】cut.html 通过各种途径获取到视频的基本信息，提交参数到videoCutOperation方法
     * 【2】videoCutOperation方法 根据视频ID从数据库获取视频的基本信息
     * 【3】shell 脚本 检查、输入参数、、目录、是否oss下载成功、视频剪切、截图、上传至阿里云oss
     * 【4】shell 脚本执行失败写入数据库记录,同时提示编辑页面信息
     * 【5】shell 脚本执行成功，则判断文件是否存在，获取视频时长、大小等信息，通知当前处理页面是否执行成功，插入数据库记录
     */
    public function videoCut()
    {
        $this->assign([
            'title' => 'FFMpeg视频剪切',
            'video_path' =>  'test_mp4_640k.mp4',
            'video_name' =>  'test_mp4_640k',
        ]);
        return $this->fetch();
    }

    /**
     * 视频操作
     * @param Request $request
     * @return mixed
     */
    public function videoCutOperate(Request $request)
    {
        $start_time = $request->post("start_time");
        $end_time = $request->post("end_time");
        $edit_desc = $request->post("videodesc");
        $old_name = $request->post("old_name");
        $new_name = $request->post("new_name");

        $starttime = $start_time;
        $endtime = $end_time;
        $sourcefile = $old_name;
        $targetfile = $new_name;
        $videoid = $old_name;
        $editid = $new_name;
        #   根据LiveId获取视频信息
        var_dump($videoid);
        die;
        $origVideoInfo = $this->videoInfoFromVideoId($videoid);
        $activityid = $origVideoInfo['liveid'];
        $filename = $origVideoInfo['filename'];
        Log::info('---#0-- activityid:' . $activityid . ' filename: ' . $filename);
        $activityid2 = $origVideoInfo['version'] == 1 ? explode("-", $origVideoInfo['filename'])[0] : $origVideoInfo['liveid'];
        $cmdStr = ROOT_PATH . 'public/ffmpeg/script/check_location_cut.sh' . $activityid2 . ' ' . $sourcefile . ' ' . $starttime . ' ' . $endtime . '  ' . $editid . '  ' . $activityid . '& ';

        Log::info('---#2--- cutcmdstr:' . $cmdStr . "\n");

        exec($cmdStr, $results, $ret);
        $shellResult = -1;
        if (count($results) == 1) $shellResult = $results[0];
        #   编辑视频结果消息
        $shellResultMsg = $this->editResultMsg($shellResult);
        $resultVideoPathFile = "/home/www/videoedit/data/" . $editid . '.mp4';
        $resultImagePathFile = "/home/www/videoedit/data/" . $editid . '.jpg';
        $filename = $editid;

        //step 6
        if ($shellResult != '0') {
            Log::error('xxxx---ERROR---xxxx shell failed\n');
            $filesize = '0';
            $duration = '0';
            $pid = $videoid;
            $editresultcode = $shellResult;

            $editmsg = $shellResultMsg;
            $editdesc = $edit_desc;
            #   保存结果到数据库
            saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);
            return;
        }

        $filename = $editid;
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile))// && filesize($targetsnapfilename)==0))
        {
            Log::info("---#3----  save cut result to DBi\n\n");
            $filesize = filesize($resultVideoPathFile);
            $duration = getVideoDuration($resultVideoPathFile);

            Log::info("---#3.1----  save cut result to DBi videoduation is : $duration \n\n");
            $pid = $videoid;
            $editresultcode = $shellResult;
            if ($shellResult == '0') $editresultcode = '1';
            $editmsg = $shellResultMsg;
            $editdesc = $edit_desc;
            Log::info("---#3.11----  save cut11 result to DBi\n\n");
            saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);
            return;
        }
        die;
        $this->assign([
            'title' => 'FFMpeg视频剪切',
            'video_path' => MEDIA_PATH . 'test_mp4_640k.mp4'
        ]);
        return $this->fetch();
    }

    /**
     * 根据liveId获取所有的视频信息
     * @param $videoid
     * @return mixed
     */
    public function videoInfoFromVideoId($videoid = 16)
    {
        $video = Db::table('livevideo')->where('id',$videoid)->find();
        $result['liveid'] = $video['liveId'];
        $result['filename'] = $video["fileName"];
        $result['duration'] = $video["duration"];
        $result['filesize'] = $video["fileSize"];
        $result['version'] = $video["version"];
        return $result;
    }

    public function editResultMsg($editCode)
    {
        static $msg = array('0' => 'success',
            '-8' => 'oss download file failed',
            '-7' => 'ffmpeg cut file failed',
            '-6' => 'move file error',
            '-5' => 'create thumbnail error',
            '-1' => 'unknown error');


        $result = $msg[$editCode];

        if ($result == null)
            return "unknown code " . $editCode;
        else
            return $result;
    }

    # 保存剪切视频记录到数据中
    public function saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc)
    {
        //$url = "http://sansanweb.amailive.com/console/lives/{$activityid}/videos/create?version=1&liveId={$activityid}&deviceId={$pid}&streamName={$filename}&baseName={$filename}&duration=${DURATION}&fileSize=${FILE_SIZE}&fileTime=${FILE_TIME}";
        $createtime = date('Y-m-d H:i:s', $editid);
        $filetime = date('Y-m-d H:i:s', time());
        $cuttedVideo = new LiveVideoEdit(
            array(
                'liveid' => $activityid,
                // 'deviceid'=>$deviceid,
                // 'type'=>$type,
                // 'state' =>$state,
                'name' => $editdesc,
                // 'note'=>$note,
                'filename' => $filename,
                'filetime' => $filetime,
                'filesize' => $filesize,
                'duration' => $duration,
                // 'viewcount'=>$viewcount ,
                // 'playcount'=>$playcount ,
                // 'likecount' =>$likecount,
                // 'notecount'=>$notecount ,
                // 'savecount'=>$savecount,
                // 'sharecount' =>$sharecount,
                'createtime' => $createtime,
                'pid' => $pid,
                'eidittype' => 'cut',
                'editid' => $editid,
                'videoparts' => '',
                'editresult' => $editresultcode,
                'editmsg' => $editmsg
            )
        );
        $cuttedVideo->save();
    }

    /**
     * @return mixed
     * 视频合并
     */
    public function videoConcat()
    {
        $this->assign([
            'title' => '视频合并'
        ]);
        return $this->fetch();
    }
}