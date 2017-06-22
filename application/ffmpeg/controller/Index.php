<?php

namespace app\ffmpeg\controller;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use think\Controller;
use think\Log;
use think\Request;

class Index extends Controller
{
    /**
     * @return Request
     * @static
     */
    protected static function request()
    {
        $request = Request::instance();
        return $request;
    }

    public function index()
    {
        $data = ['name' => 'ffmpeg module', 'url' => 'https://github.com/PHP-FFMpeg/PHP-FFMpeg'];
        return json(['data' => $data, 'code' => 1, 'message' => '操作完成2']);
    }

    public function viewIndex()
    {
        $this->assign([
            'video_cut' => '视频剪切',
            'video_concat' => '视频合并',
            'name' => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        return view();
    }

    /**
     * 视频剪切
     * @return \think\response\View
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
            'title' => '视频剪切',
            'video_path' => MEDIA_PATH.'test_mp4_640k.mp4'
        ]);
        return view();
    }

    /*
     * videoCut【1】videoCut
     */
    public function videoCutOperation()
    {
        $request = self::request();
        $start_time = $request->post("start_time");
        $end_time = $request->post("end_time");
        $old_name = $request->post("old_name");
        $new_name = $request->post("new_name");
        $video_desc = $request->post("video_desc");
        $video_id = $request->post("video_id");
        //记录日志文件
        Log::error("videoCutOperation 开始\n");
        // 获取视频基本信息,这里我可以暂时写个接口调用既可以了
        //$origVideoInfo = videoInfoFromVideoId($video_id);
        //活动ID
        $activityid = "L00123";
        //文件名称
        $filename = "tinywan_video";
        $editid = $new_name;
        Log::error('---#0-获取视频基本信息- activityid:' . $activityid . ' filename: ' . $filename);
        $inPutPath = MEDIA_PATH.'test_mp4.mp4';    //视频输入路径
        $outPutPath = MEDIA_PATH.time().'test_mp4.mp4';   //视频输出路径

        //shell 脚本处理视频
        $sourcefile = $filename;
        $activityid2 = $activityid;
        $cmdStr = __DIR__ . '/usr/bin/check_download_cut.sh ' . $activityid2 . ' ' . $sourcefile . ' ' . $start_time . ' ' . $end_time . '  ' . $editid . '  ' . $activityid . '&  1>>' . $shell_logfilename . ' 2>>' . $shell_errorlog;
        system("{$cmdStr} > /dev/null 2>&1 &", $sysStatus);
        if ($sysStatus != 0) {
            Log::error('[' . time(). ']:' . '系统执行函数system()没有成功,返回状态码：' . $sysStatus);
        }
        //调用 cutvideo 方法处理视频
    }

    //视频合并
    public function videoConcat()
    {
        $this->assign([
            'title' => '视频合并'
        ]);
        return view();
    }

    public function cutvideo($videoid, $starttime, $endtime, $editid, $editdesc)
    {
        //videoCut【2】
        $origVideoInfo = videoInfoFromVideoId($videoid);

        $activityid = $origVideoInfo['liveid'];
        $filename = $origVideoInfo['filename'];

        logger('---#0-- activityid:' . $activityid . ' filename: ' . $filename);

        logger('--#1-- activityid: ' . $activityid . ' videoid: ' . $videoid . ' filename: ' . $filename . 'starttime:' . $starttime . ' endtime: ' . $endtime . ' editvideoid: ' . $editid . 'editdesc:   ' . $editdesc);

        $shell_logfilename = getlogfilename('shell');
        $shell_errorlog = getlogfilename("shell_error");

        $video_extention = '.mp4';
        $thumbnailext = '.jpg';

        $sourcefile = $filename;//. $video_extention;
        $targetfile = $editid;//. $video_extention;

        $activityid2 = $origVideoInfo['version'] == 1 ? explode("-", $origVideoInfo['filename'])[0] : $origVideoInfo['liveid'];

        $cmdStr = __DIR__ . '/check_download_cut.sh ' . $activityid2 . ' ' . $sourcefile . ' ' . $starttime . ' ' . $endtime . '  ' . $editid . '  ' . $activityid . '&  1>>' . $shell_logfilename . ' 2>>' . $shell_errorlog;

        logger('---#2--- cutcmdstr:' . $cmdStr . "\n");

        /*
         * videoCut【3】
         * shell 脚本 检查、输入参数、、目录、是否oss下载成功、视频剪切、截图、上传至阿里云oss
         */
        exec($cmdStr, $results, $ret);


        $shellResult = -1;
        if (count($results) == 1)
            $shellResult = $results[0];

        $shellResultMsg = $this->editResultMsg($shellResult);
        $resultVideoPathFile = "/var/www/videoedit/data/" . $editid . '.mp4';
        $resultImagePathFile = "/var/www/videoedit/data/" . $editid . '.jpg';


        $filename = $editid;
        $duration = '0';
        // videoCut【4】 shell 脚本执行失败
        if ($shellResult != '0') {
            logger('xxxx---ERROR---xxxx shell failed\n');
            $filesize = '0';
            $duration = '0';
            $pid = $videoid;
            $editresultcode = $shellResult;


            $editmsg = $shellResultMsg;
            $editdesc = $editdesc;

            saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);

            return;
        }

        $filename = $editid;
        $duration = '0';
        //videoCut【5】
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile))// && filesize($targetsnapfilename)==0))
        {
            logger('---#3----  save cut result to DBi\n\n');
            $filesize = filesize($resultVideoPathFile);
            $duration = getVideoDuration($resultVideoPathFile);

            logger("---#3.1----  save cut result to DBi videoduation is : $duration \n\n");
            $pid = $videoid;
            $editresultcode = $shellResult;
            if ($shellResult == '0')
                $editresultcode = '1';
            $editmsg = $shellResultMsg;
            $editdesc = $editdesc;

            logger('---#3.11----  save cut11 result to DBi\n\n');
            saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);
            return;
        }
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

    //视频数据库保存
    public function saveCutResult2Db($activityid,$filename,$filesize,$duration,$pid,$editid,$editresultcode,$editmsg,$editdesc){
        $createtime = date('Y-m-d H:i:s',$editid);
        $filetime =date('Y-m-d H:i:s',time());
        $cuttedVideo = new LiveVideoEdit(
            array(
                'liveid' =>$activityid,
                // 'deviceid'=>$deviceid,
                // 'type'=>$type,
                // 'state' =>$state,
                'name' =>$editdesc,
                // 'note'=>$note,
                'filename'=>$filename ,
                'filetime' =>$filetime,
                'filesize' =>$filesize ,
                'duration' =>$duration,
                // 'viewcount'=>$viewcount ,
                // 'playcount'=>$playcount ,
                // 'likecount' =>$likecount,
                // 'notecount'=>$notecount ,
                // 'savecount'=>$savecount,
                // 'sharecount' =>$sharecount,
                'createtime'=>$createtime ,
                'pid'=> $pid,
                'eidittype'=>'cut',
                'editid' =>$editid,
                'videoparts'=>'',
                'editresult'=>$editresultcode,
                'editmsg' =>$editmsg
            )
        );

        $cuttedVideo->save();
    }
}
