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

use think\Controller;
use think\Request;

class Common extends Controller
{
    //
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //执行登录验证
        #$_SESSION["admin"]["admin_id"];
        if(!session('admin.admin_id')){
            $this->redirect("backend/login/login");
        }
    }
}
