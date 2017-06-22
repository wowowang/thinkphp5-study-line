<?php
namespace app\index\controller;

use FFMpeg\FFMpeg;
use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        $data = ['name'=>'thinkphp','url'=>'thinkphp.cn'];
//        return ['data'=>$data,'code'=>1,'message'=>'操作完成'];
        return json(['data'=>$data,'code'=>1,'message'=>'操作完成2']);
    }

    public function phalcon()
    {
        $res = Db::query('select * from tour_admin_node where id=?', [166]);
        print_r($res);
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

    public function ffmepg(){
        $test = new \my\Test();
        $test->sayHello();
    }
}
