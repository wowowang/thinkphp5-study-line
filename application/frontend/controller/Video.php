<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/1
 * Time: 13:40
 */

namespace app\frontend\controller;


use FFMpeg\FFMpeg;

class Video
{
    public function index(){
        $ffmpeg = FFMpeg::create();
        var_dump($ffmpeg);
    }
}