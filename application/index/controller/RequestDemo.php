<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan
 * |  Date: 2017/3/2
 * |  Time: 12:59
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\index\controller;


use think\Controller;
use \think\Request;
// http://127.0.0.1/amai-stream/public/index.php/index/request_demo/index
class RequestDemo extends Controller
{

    public function index()
    {
        $request = Request::instance();
        // 获取当前域名
        echo 'domain: ' . $request->domain() . '<br/>';
        // 获取当前入口文件
        echo 'file: ' . $request->baseFile() . '<br/>';
        // 获取当前URL地址 不含域名
        echo 'url: ' . $request->url() . '<br/>';
        // 获取包含域名的完整URL地址
        echo 'url with domain: ' . $request->url(true) . '<br/>';
        // 获取当前URL地址 不含QUERY_STRING
        echo 'url without query: ' . $request->baseUrl() . '<br/>';
        // 获取URL访问的ROOT地址
        echo 'root:' . $request->root() . '<br/>';
        // 获取URL访问的ROOT地址
        echo 'root with domain: ' . $request->root(true) . '<br/>';
        // 获取URL地址中的PATH_INFO信息
        echo 'pathinfo: ' . $request->pathinfo() . '<br/>';
        // 获取URL地址中的PATH_INFO信息 不含后缀
        echo 'pathinfo: ' . $request->path() . '<br/>';
        // 获取URL地址中的后缀信息
        echo 'ext: ' . $request->ext() . '<br/>';

        //设置/获取 模块/控制器/操作名称

        echo "当前模块名称是:" . $request->module();
        echo "当前控制器名称是:" . $request->controller();
        echo "当前操作名称是:" . $request->action();
    }

    /**
     * GET 方式接受参数
     * http://127.0.0.1/amai-stream/public/index.php/index/request_demo/getMethod?id=213123
     */
    public function getMethod()
    {
        // 是否为 GET 请求
        if(false === Request::instance()->isGet()){
            exit('不是 GET 请求');
        }
        exit('恭喜你！是 GET 请求');
        $id = Request::instance()->get('id');
        echo $id;
        $name = Request::instance()->get('name');
        echo "当前操作名称是:" . Request::instance()->action();
        // 获取所有的get变量（经过过滤的数组）
        $allFilterArr = request()::instance()->get();
        var_dump($allFilterArr);
        // 获取所有的get变量（原始数组）
        $allOldArr = request()::instance()->get(false);
        var_dump($allOldArr);

        // 助手函数 判断是否为 GET 请求
        if(request()->isGet()) exit('恭喜你！是 GET 请求');
    }

    /**
     * POST 方式接受参数
     * http://127.0.0.1/amai-stream/public/index.php/index/request_demo/getMethod?id=213123
     */
    public function postMethod()
    {
        // 是否为 POST 请求
        if(false === Request::instance()->isPost()){
            exit('不是 POST 请求');
        }
        exit('恭喜你！是 POST 请求');
        //需要注意的是，采用Filter ID 进行过滤的话，如果不符合过滤要求的话 会返回false，因此你需要配合默认值来确保最终的值符合你的规范。
        $id = Request::instance()->post('id','',FILTER_VALIDATE_INT);
        echo $id;
        $name = Request::instance()->post('name','',FILTER_VALIDATE_EMAIL);
        echo $name.'<br/>';
        $allFilterArr = Request::instance()->post(); // 获取经过过滤的全部post变量
        var_dump($allFilterArr);
        $allOldArr = Request::instance()->post(false); // 获取全部的post原始变量
        var_dump($allOldArr);
        // 助手函数 判断是否为 GET 请求
        if(request()->isPost()) exit('恭喜你！是 POST 请求');
    }

    //可以使用Request对象的header方法获取当前请求的HTTP 请求头信息，例如：
    public function header(){
        $info = Request::instance()->header();
        $agent = Request::instance()->header('user-agent');
        $agent = Request::instance()->header('User-Agent');
        $agent = Request::instance()->header('USER_AGENT');
    }
}