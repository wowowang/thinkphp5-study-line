<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index(){
        return " index Index index";
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
}