<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:57
 */
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return "index";
    }

    /**
     * view() 不需要继承 think\Controller
     * @return \think\response\View
     */
    public function indexView()
    {
        return view();
    }

    /**
     * fetch() 必须要继承 think\Controller
     * @return mixed
     */
    public function indexFetch()
    {
        return $this->fetch("indexdemo");
    }
}