<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/6/26
 * Time: 10:58
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\ffmpeg\controller;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
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

    /**
     * 扩展文件php-ffmepg 使用
     */
    public function phpFfmpeg()
    {
        $inPutPath = 'F:\Tinywan\Video\CY000076862-149388351320170504153833.mp4';    //本地磁盘路径
        $outPutPath = "F:\Tinywan\Video\outVideo";   //视频输出路径
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($inPutPath);
        var_dump($ffmpeg);
        return "php_ffmpeg";
    }

    public function FFProbe()
    {
        $ffprobe = FFProbe::create();
        $MP4Path = 'F:\Tinywan\Video\CY000076862-149388351320170504153833.mp4';

        //获取一个视频的编码格式 h264
        $probeArr['视频时长'] = $ffprobe->format($MP4Path)->get('duration');
        //视频信息
        $probeArr['视频信息']['index'] = $ffprobe->streams($MP4Path)->videos()->first()->get('index');
        $probeArr['视频信息']['视频格式'] = $ffprobe->streams($MP4Path)->videos()->first()->get('codec_name');
        $probeArr['视频信息']['全视频格式'] = $ffprobe->streams($MP4Path)->videos()->first()->get('codec_long_name');
        $probeArr['视频信息']['画质'] = $ffprobe->streams($MP4Path)->videos()->first()->get('profile');
        $probeArr['视频信息']['类型'] = $ffprobe->streams($MP4Path)->videos()->first()->get('codec_type');
        $probeArr['视频信息']['codec_tag_string'] = $ffprobe->streams($MP4Path)->videos()->first()->get('codec_tag_string');
        $probeArr['视频信息']['codec_tag'] = $ffprobe->streams($MP4Path)->videos()->first()->get('codec_tag');
        $probeArr['视频信息']['宽度'] = $ffprobe->streams($MP4Path)->videos()->first()->get('width');
        $probeArr['视频信息']['高度'] = $ffprobe->streams($MP4Path)->videos()->first()->get('height');
        $probeArr['视频信息']['has_b_frames'] = $ffprobe->streams($MP4Path)->videos()->first()->get('has_b_frames');
        $probeArr['视频信息']['显示比例'] = $ffprobe->streams($MP4Path)->videos()->first()->get('display_aspect_ratio');
        $probeArr['视频信息']['采样纵横比'] = $ffprobe->streams($MP4Path)->videos()->first()->get('sample_aspect_ratio');
        $probeArr['视频信息']['level'] = $ffprobe->streams($MP4Path)->videos()->first()->get('level');
        $probeArr['视频信息']['r_frame_rate'] = $ffprobe->streams($MP4Path)->videos()->first()->get('r_frame_rate');
        $probeArr['视频信息']['avg_frame_rate'] = $ffprobe->streams($MP4Path)->videos()->first()->get('avg_frame_rate');
        $probeArr['视频信息']['time_base'] = $ffprobe->streams($MP4Path)->videos()->first()->get('time_base');
        $probeArr['视频信息']['disposition'] = $ffprobe->streams($MP4Path)->videos()->first()->get('disposition');
        //音频信息
        $probeArr['音频信息']['index'] = $ffprobe->streams($MP4Path)->audios()->first()->get('index');
        $probeArr['音频信息']['音频格式'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_name');
        $probeArr['音频信息']['全音频格式'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_long_name');
        $probeArr['音频信息']['类型'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_type');
        $probeArr['音频信息']['codec_time_base'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_time_base');
        $probeArr['音频信息']['codec_tag_string'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_tag_string');
        $probeArr['音频信息']['codec_tag'] = $ffprobe->streams($MP4Path)->audios()->first()->get('codec_tag');
        $probeArr['音频信息']['sample_fmt'] = $ffprobe->streams($MP4Path)->audios()->first()->get('sample_fmt');
        $probeArr['音频信息']['sample_rate'] = $ffprobe->streams($MP4Path)->audios()->first()->get('sample_rate');
        $probeArr['音频信息']['channels'] = $ffprobe->streams($MP4Path)->audios()->first()->get('channels');
        $probeArr['音频信息']['bits_per_sample'] = $ffprobe->streams($MP4Path)->audios()->first()->get('bits_per_sample');
        $probeArr['音频信息']['"r_frame_rate'] = $ffprobe->streams($MP4Path)->audios()->first()->get('"r_frame_rate');
        $probeArr['音频信息']['"avg_frame_rate'] = $ffprobe->streams($MP4Path)->audios()->first()->get('"avg_frame_rate');
        $probeArr['音频信息']['时间基准'] = $ffprobe->streams($MP4Path)->audios()->first()->get('time_base');
        $probeArr['音频信息']['start_time'] = $ffprobe->streams($MP4Path)->audios()->first()->get('start_time');
        $probeArr['音频信息']['disposition'] = $ffprobe->streams($MP4Path)->audios()->first()->get('disposition');
        var_dump($probeArr);
    }
}