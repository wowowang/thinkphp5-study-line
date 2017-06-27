<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */
namespace app\frontend\controller;

use app\frontend\model\User;
use think\Controller;
use think\Loader;

class Index extends Controller
{
    public function index(){
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

    /**
     * User Model
     */
    public function userModelTest1(){
        // [1]
        $res = User::get(27);
        var_dump($res->toArray());
        // [2]
        $user = new User();
        $res = $user::get(28);
        var_dump($res->toArray());
    }

    /**
     * 使用Loader 查询数据
     */
    public function userModelTest2(){
        // 使用Loader
        $user1 = Loader::model("User");
        $user = model("User");
        $res = $user::get(28);
        var_dump($res->toArray());
    }

    /**
     * 使用模型查询数据
     */
    public function userModelTest3(){
        // 【1】直接查询
        $user = User::get(27); // 返回为一个对象
        var_dump($user->username);
        // 【2】通过闭包查询
        $user1 = User::get(function ($query){
            $query->where("username","eq","tinywan001");
        });
        var_dump($user1->toArray());
        // 【3】静态方法
        $user2 = new User();
        $res3 = $user2->where('username',"tinywan001")
            ->field("user_id,username")
            ->find();
        var_dump($res3->toArray());
    }

    /**
     * 使用模型查询更新数据
     */
    public function userModelTest4(){
        // 【1】直接查询
        $user = User::update([
            "username" => "33333333333"
        ],["user_id"=>27]);
        var_dump($user);
    }
}