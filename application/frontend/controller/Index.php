<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */

namespace app\frontend\controller;

use alidayu\AlibabaAliqinFcSmsNumSendRequest;
use alidayu\TopClient;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        trace("Hello socketlog");
        return " frontend Index index";
    }

    /**
     * view 使用
     * @return \think\response\View
     */
    public function indexView()
    {
        return view("index");
    }

    /**
     * fetch 使用
     * @return mixed
     */
    public function indexFetch()
    {
        return $this->fetch("hello");
    }

    public function aliyun()
    {
        $client = new TopClient();
        $request = new AlibabaAliqinFcSmsNumSendRequest();
        $client->appkey = "23651008";
        $client->secretKey = "1ef044c5017a0337da3b43e3a1236822";
// 短信内容参数
        $smsParams = [
            'code'    => rand(0000,9999),
            'product' => '测试的'
        ];

// 设置请求参数
        $request->setExtend("123456");
        $request->setSmsType("normal");
        $request->setSmsFreeSignName("阿里大于");
        $request->setSmsParam("{\"code\":\"1234\",\"product\":\"alidayu\"}");
        $request->setRecNum("13669361192");
        $request->setSmsTemplateCode("SMS_585014");
        $resp = $client->execute($request);

        print_r($resp);
    }
}