<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        $data = ['name'=>'thinkphp','url'=>'thinkphp.cn'];
        return json(['data'=>$data,'code'=>1,'message'=>'操作完成2']);
    }

    /**
     * 打印配置信息
     * [1] 当前应用的配置会替换掉默认的配置信息
     */
    public function configInfo()
    {
        $default_conf = [
            'name' => "TIM"
        ];

        $self_conf = [
            'name' => "Tinywan"
        ];
        // 以上说明自定的配置会替换掉默认的配置
        dump(array_merge($default_conf,$self_conf));
        var_dump(config());
    }


    public function test()
    {
        return 'test';
    }


    public function notify()
    {
        if (false === Request::instance()->isGet()) {
            exit('不是 GET 请求 tiNYWAN');
        }
        $request = Request::instance();
        $action = $request->get('action');
        $ip = $request->get('ip');
        $id = $request->get('id');
        $app = $request->get('app');
        $appname = $request->get('appname');
        $time = $request->get('time');
        $usrargs = $request->get('usrargs');
        $node = $request->get('node');

    }

}
