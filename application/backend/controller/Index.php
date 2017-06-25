<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:57
 */

namespace app\backend\controller;

use think\Controller;
use think\View;

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
     * 以下列出了4中不同的赋值方式
     * @return mixed
     */
    public function indexFetch()
    {
        #【1】
        $this->assign('keys', "values");
        #【2】
        $this->view->version = "V0.01";
        #【3】
        View::share("viewName", "use think\\View");
        #【4】
        return $this->fetch("indexdemo",
            [
                'username' => "tinywan",
                'age' => 24
            ], ["STATIC" => "这是是static 替换掉后的内容"]);
    }

    /*
     *  系统变量原生标签
     */
    public function systemTag()
    {
        //var_dump($_SERVER);
        session('email', "tinwyan@123.com");
        cookie('username', "Tinywan");
        return $this->fetch();
    }

    /**
     * 变量输出调节器、模板循环标签
     */
    public function varOutput()
    {
        $this->assign('username', 'Tinywan');
        $this->assign('age', '24');
        $this->assign('email', '756684177@qq.com');
        $this->assign('time', time());
        $this->assign('a', 100);
        #   模板循环标签
        $age = ["Peter" => "35", "Ben" => "37", "Joe" => "43"];
        $lists = [
            'user1' => [
                'username' => "C语言",
                'age' => 98
            ],
            'user2' => [
                'username' => "PHP语言",
                'age' => 89
            ],
            'user3' => [
                'username' => "Lua语言",
                'age' => 100
            ]
        ];
        $this->assign('empty', "<h1>这里没有数据的</h1>");
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /**
     *  模板的布局、包含、继承
     */
    public function tplTest()
    {
        $lists = [
            'user1' => [
                'username' => "C语言",
                'age' => 98
            ],
            'user2' => [
                'username' => "PHP语言",
                'age' => 89
            ],
            'user3' => [
                'username' => "Lua语言",
                'age' => 100
            ]
        ];
        $this->assign('lists', $lists);
        $this->assign('title', "Composer安装");
        return $this->fetch('index');
    }

    /**
     * 图片上传
     */
    public function imageUpload()
    {
        return $this->fetch();
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>15673458,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
}