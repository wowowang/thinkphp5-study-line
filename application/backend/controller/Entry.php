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
use think\Controller;
use think\Request;

class Entry extends Common
{
    //个人
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 修改密码
     * @param Request $request
     * @return mixed
     */
    public function modPass(Request $request)
    {
        if($request->isPost()){
            $res = (new Admin())->changePassword(input("post."));
            if($res["valid"]){
                //success 密码修改成功，则清除session的登录信息
                session(null);
                $this->success($res['msg'], "backend/entry/index");
                exit;
            }else{
                // fail
                $this->error($res["msg"]);exit;
            }
        }
        return $this->fetch();
    }
}
