<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;

use app\common\model\Admin;
use Faker\Factory;
use think\Controller;
use think\Request;

class Login extends Controller
{
    /**
     * 登录
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        //临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        //1 验证数据
        if ($request->isPost()) {
            $res = (new Admin())->login(input("post."));
            if ($res['valid']) {
                //success
                $this->success($res['msg'], "backend/entry/index");
                exit;
            } else {
                //fail
                $this->error($res['msg']);
                exit;
            }
        }
        return $this->fetch("index");
    }

    /**
     * 邮箱注册
     */
    public function emailRegister()
    {
        $res = (new Admin())->emailRegister(input("post."));
        if (!$res["valid"]) {
            //密码验证、邮箱发送成功
            $this->success($res['msg'], "backend/login/login");
        } else {
            $this->error($res["msg"]);
            exit;
        }
    }

    /**
     * 邮箱注册URL验证
     * @param Request $request
     */
    public function emailRegisterUrlValid(Request $request)
    {
        if ($request->isGet()) {
            $res = (new Admin())->emailRegisterUrlValid(input("get."));
            if ($res["valid"]) {
                //success 把目前的邮箱地址保存在session中
                $this->success($res['msg'], "backend/entry/index");
            } else {
                $this->error($res['msg'], "backend/login/login");
            }
        }
    }

    /**
     * 忘记密码
     * forgot-password
     */
    public function forgotPassword()
    {
        $res = (new Admin())->checkSendEmail(input("post."));
        if (!$res["valid"]) {
            //密码验证、邮箱发送成功
            $this->success($res['msg'], "backend/login/login");
        } else {
            $this->error($res["msg"]);
            exit;
        }
    }

    /**
     * 邮箱回调地址的有效性检测
     */
    public function checkEmailUrlValid(Request $request)
    {
        if ($request->isGet()) {
            $res = (new Admin())->checkEmailUrlValid(input("get."));
            if ($res["valid"]) {
                //success 把目前的邮箱地址保存在session中
                $this->view->engine->layout(false);
                return $this->fetch("resetpassword", [
                    'email' => $res['email']
                ]);
            } else {
                $this->success($res['msg'], "backend/login/login");
            }
        }
    }

    /**
     * 根据邮箱修改密码界面
     * @param Request $request
     */
    public function reSetPassword(Request $request)
    {
        if ($request->isPost()) {
            $res = (new Admin())->reSetPassword(input("post."));
            if ($res['valid']) {
                //success
                $this->success($res['msg'], "backend/entry/index");
                exit;
            } else {
                //fail
                $this->error($res['msg']);
                exit;
            }
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session(null);
        return $this->redirect("backend/login/login");
    }


    public function faker()
    {
        $faker = Factory::create($locale = 'zh_CN');
        echo $faker->name . "<br/>";
        echo $faker->address . "<br/>";
        echo $faker->userName . "<br/>";
        echo $faker->userAgent . "<br/>";
        echo $faker->country . "<br/>";
    }
}
