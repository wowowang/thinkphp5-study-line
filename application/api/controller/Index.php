<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan
 * |  Date: 2017/2/20
 * |  Time: 8:36
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/
namespace app\api\controller;

use common\BaseRedis;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        $this->redirect('http://ssconsole.amaitech.com/',302);
    }

    public function test()
    {
        trace('Hello Test');
        return '111111111111';
    }

    public function phalcon()
    {
        phpinfo();
        die;
        return 'Phalcon 3.0';
    }

    public function notify()
    {
        if (false === Request::instance()->isGet()) {
            exit('不是 GET 请求');
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
