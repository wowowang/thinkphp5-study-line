<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:40
 */

namespace app\ffmpeg\controller;

use think\Controller;
use think\Db;
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
            'init_video' => '初始化',
            'video_cut' => '视频剪切',
            'video_concat' => '视频合并',
            'name' => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        return $this->fetch();
    }

    /**
     * 【1】初始化视频到live_video_copy表中
     * init_videotable4edit.php
     */
    public function initVideoTableEdit(Request $request)
    {
        $liveid = $request->get("liveid");
        if ($liveid == "") {
            return "请输入 liveid, 你可以在URL地址栏后添加：?liveid=CY00007";
        }
        # [1] 查询 LiveVideo 表
        $videolist = Db::table('livevideo')->where('liveId', $liveid)->select();
        if ($videolist) {
            $differentCount = 0;
            $copyCount = count($videolist);
            # [2] 循环插入livevideo_copy 表
            for ($k = 0; $k < count($videolist); $k++) {
                $findResult = Db::table('livevideo_copy')
                    ->where('liveId', $videolist[$k]['liveId'])
                    ->where("fileName", $videolist[$k]['fileName'])
                    ->find();
                # [3] continue本身并不跳出循环结构，只是放弃这一次循环,也就是说这个数据存在的话则不再重复插入哦
                if (count($findResult) > 0) continue;
                $data = [
                    'liveId' => $videolist[$k]['liveId'],
                    'deviceId' => $videolist[$k]['deviceId'],
                    'type' => $videolist[$k]['type'],
                    'state' => $videolist[$k]['state'],
                    'name' => $videolist[$k]['name'],
                    'note' => $videolist[$k]['note'],
                    'fileName' => $videolist[$k]['fileName'],
                    'fileTime' => $videolist[$k]['fileTime'],
                    'duration' => $videolist[$k]['duration'],
                    'fileSize' => $videolist[$k]['fileSize'],
                    'createTime' => $videolist[$k]['createTime'],
                    'pid' => -1,
                    'eidittype' => '0',
                    'editid' => '0',
                    'videoparts' => '',
                    'editresult' => '0',
                    'editmsg' => ''
                ];
                try {
                    Db::table('livevideo_copy')->insert($data);
                } catch (\Exception $e) {
                    Log::error('错误信息' . $e->getMessage());
                    exit('执行错误!');
                }
                $differentCount += 1;
            }
            $copiedRecordCount = (string)($differentCount) . '/' . (string)($copyCount);
        }
        #   [4] 返回总条数和成功插入的数据的百分比和自增主键(id)的值
        return $copiedRecordCount;
    }

    /**
     * @return \think\response\View
     * 【2】视频剪切
     * 思路图：
     * 【1】cut.html 通过各种途径获取到视频的基本信息，提交参数到videoCutOperation方法
     * 【2】videoCutOperation方法 根据视频ID从数据库获取视频的基本信息
     * 【3】shell 脚本 检查、输入参数、、目录、是否oss下载成功、视频剪切、截图、上传至阿里云oss
     * 【4】shell 脚本执行失败写入数据库记录,同时提示编辑页面信息
     * 【5】shell 脚本执行成功，则判断文件是否存在，获取视频时长、大小等信息，通知当前处理页面是否执行成功，插入数据库记录
     */
    public function videoCut()
    {
        $liveid = input('get.liveid');
        if ($liveid == "") {
            return "请输入 liveid, 你可以在URL地址栏后添加：?liveid=CY00007";
        }
        $this->assign([
            'title' => 'FFMpeg视频剪切',
            'video_path' => 'test_mp4_640k.mp4',
            'video_name' => 'test_mp4_640k',
        ]);
        return $this->fetch();
    }

    public function ajaxTest()
    {
        $liveId = input("post.liveid");
        return $this->fetch();
    }

    public function ajaxOpt(Request $request)
    {
        if ($request->isAjax()) {
            $liveId = $request->get("liveid");
            return json(["dfdsf", $liveId]);
        }
        return json_encode(["not data "]);
    }

    /**
     * 获取视频列表
     * http://122.224.187.165:8081/videos/tinywan123-149301089920170424131459.mp4
     * @param $liveId
     * @return string
     */
    public function getLiveVideoList(Request $request)
    {
        if ($request->isAjax()) {
            $liveId = $request->post("liveid");
            $result = Db::table('livevideo_copy')->where('liveId', $liveId)->select();

            if ($result) {
                for ($k = 0; $k < count($result); $k++) {
                    $data[$k]['desc'] = $result[$k]['name'];
                    $data[$k]['pid'] = $result[$k]["pid"];
                    $data[$k]['id'] = $result[$k]["id"];
                    if ($result[$k]['version'] == 1) {
                        $data[$k]['playpath'] = 'http://122.224.187.165:8081/videos/' . $result[$k]["fileName"] . '.mp4';
                        $data[$k]['thumbnailpath'] = 'http://122.224.187.165:8081/videos/' . $result[$k]["fileName"] . '.jpg';
                    } else {
                        $data[$k]['playpath'] = 'http://122.224.187.165:8081/videos/' . $result[$k]["fileName"] . '.mp4';
                        $data[$k]['thumbnailpath'] = 'http://122.224.187.165:8081/videos/' . $result[$k]["fileName"] . '.jpg';
                    }
                }
                return json($data);
            }
            return json(['null data']);
        } else {
            return "非Ajax 请求";
        }
    }

    /**
     * 【3】视频操作Ajax请求而非表单操作
     * @param Request $request
     * @return mixed
     */
    public function videoCutOperate(Request $request)
    {
        $start_time = $request->post("start_time");
        $end_time = $request->post("end_time");
        $old_name = $request->post("old_name");
        $edit_desc = $request->post("videodesc");
        $new_name = $request->post("new_name");
        $video_desc = $request->post("video_desc");

        $starttime = $start_time;
        $endtime = $end_time;
        $sourcefile = $old_name;
        $targetfile = $new_name;
        $videoid = $old_name;
        #   是一个时间戳
        $editid = $new_name;
        #   根据LiveId获取视频信息
        $origVideoInfo = $this->videoInfoFromVideoId($videoid);
        #   [1] 调试环节，测试是否成功获取到视频信息
//        $origVideoInfo["MSG"] = "根据LiveId获取视频信息";
//        $origVideoInfo["videoid"] = $videoid;
//        return json($origVideoInfo);
        $activityid = $origVideoInfo['liveid'];
        $filename = $origVideoInfo['filename'];

        Log::info('---#0-- activityid:' . $activityid . ' filename: ' . $filename);
        $activityid2 = $origVideoInfo['version'] == 1 ? substr($origVideoInfo['fileName'], 0, 7) : $origVideoInfo['liveId'];
        $cmdStr = ROOT_PATH . 'public/ffmpeg/script/check_location_cut.sh' . $activityid2 . ' ' . $sourcefile . ' ' . $starttime . ' ' . $endtime . '  ' . $editid . '  ' . $activityid . '& ';

        Log::info('---#2--- cutcmdstr:' . $cmdStr . "\n");

        exec($cmdStr, $results, $ret);
        $shellResult = -1;
        #   [2] 检测FFmpeg 脚本是否执行成功
        Log::info("FFmpeg 脚本是否执行成功");

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
            $this->saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);
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
            $this->saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc);
            return;
        }
        die;
    }

    /**
     * 根据liveId获取所有的视频信息
     * @param $videoid
     * @return mixed
     */
    public function videoInfoFromVideoId($videoid = 451)
    {
        $video = Db::table('livevideo_copy')->where('id', $videoid)->find();
        $result['liveid'] = $video['liveId'];
        $result['filename'] = $video["fileName"];
        $result['duration'] = $video["duration"];
        $result['filesize'] = $video["fileSize"];
        $result['version'] = $video["version"];
        return $result;
    }

    /**
     * 视频消息提示
     * @param $editCode
     * @return mixed|string
     */
    public function editResultMsg($editCode)
    {
        static $msg = [
            '0' => 'success',
            '-8' => 'oss download file failed',
            '-7' => 'ffmpeg cut file failed',
            '-6' => 'move file error',
            '-5' => 'create thumbnail error',
            '-1' => 'unknown error'
        ];

        $result = $msg[$editCode];
        if ($result == null){
            return "unknown code " . $editCode;
        }else{
            return $result;
        }
    }

    /**
     * 保存剪切视频记录到数据中
     * @param $activityid
     * @param $filename
     * @param $filesize
     * @param $duration
     * @param $pid
     * @param $editid
     * @param $editresultcode
     * @param $editmsg
     * @param $editdesc
     */
    private function saveCutResult2Db($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc)
    {
        $createtime = date('Y-m-d H:i:s', $editid);
        $filetime = date('Y-m-d H:i:s', time());
        $data = [
            'liveId' => $activityid,
            'deviceId' => "",
            'type' => "",
            'state' => "",
            'name' => $editdesc,
            'note' => "",
            'fileName' => $filename,
            'fileTime' => $filetime,
            'duration' => $duration,
            'fileSize' => $filesize,
            'createTime' => $createtime,
            'pid' => $pid,
            'eidittype' => 'cut',
            'editid' => $editid,
            'videoparts' => '',
            'editresult' => $editresultcode,
            'editmsg' => $editmsg
        ];
        try {
            Db::table('livevideo_copy')->insert($data);
            Log::info("保存剪切视频记录到数据 livevideo_copy 成功 success");
        } catch (\Exception $e) {
            Log::error('[--------2---------]保存剪切视频记录到数据中 livevideo_copy 错误信息' . $e->getMessage());
            exit('执行错误!');
        }
    }

    /**
     * @return mixed
     * ---------------------------------------------视频合并----------------------------------------------------
     */
    public function videoConcat()
    {
        $this->assign([
            'title' => '视频合并'
        ]);
        return $this->fetch();
    }
}