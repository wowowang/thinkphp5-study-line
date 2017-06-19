<?php
namespace app\wechat\controller;

use EasyWeChat\Foundation\Application;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $data = ['name' => 'Wechat', 'url' => 'thinkphp.cn'];
        return json(['data' => $data, 'code' => 1, 'message' => 'wechat code']);
    }

    public function server()
    {
        $options = [
            'debug'  => true,
            'app_id' => 'wx94c43716d8a91f3f',
            'secret' => 'd4624c36b6795d1d99dcf0547af5443d',
            'token'  => 'easywechat',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => CONF_PATH.'/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];
        $app = new Application($options);
        $app->server->setMessageHandler(function ($message){
            return "您好！欢迎关注我!";
        });
        return $app->server->serve()->send();
    }

}
