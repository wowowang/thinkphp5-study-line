<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/23
 * Time: 21:06
 */

namespace app\admin\controller;

use  app\common\controller\Index as commonIndex;

class Index
{
    /**
     * 在这里设置的配置项全局都可以使用，如果是一个方法之内的则只能在当前方法有效
     * Index constructor.
     */
    public function __construct()
    {
        config("before","beforeAction");
    }

    public function index()
    {
        return "this is admin module";
    }

    /**
     * 如何访问common模块的方法
     * [1] 通过use 引入该文件，给一个别名commonIndex 实例化这个方法就可以使用了
     * @return string
     */
    public function commonModuleIndex()
    {
        echo "admin common";        // 先输出
        $common = new commonIndex();
        return $common->index();    //  后输出
    }

    /**
     * 打印配置信息
     */
    public function configInfo()
    {
        var_dump(config());
    }
}