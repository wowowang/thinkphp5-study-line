<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/6/26
 * Time: 10:58
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\ffmpeg\controller;
use think\Controller;
use think\Db;

class Demo extends Controller
{
    public function index()
    {
        echo 'db index';
    }

    public function queryDemo1()
    {
        // 是否为 GET 请求
        $videoid = 16;
        $video = Db::table('livevideo')->where('id',$videoid)->find();
        $result['liveid'] = $video['liveId'];
        $result['filename'] = $video["fileName"];
        $result['duration'] = $video["duration"];
        $result['filesize'] = $video["fileSize"];
        $result['version'] = $video["version"];
        var_dump($result);
        die;
    }
}