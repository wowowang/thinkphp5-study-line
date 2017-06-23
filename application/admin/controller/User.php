<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/23
 * Time: 21:19
 */

namespace app\admin\controller;
use app\common\controller\User as commonUser;

class User  extends commonUser
{
    public function demo()
    {
        return $this->showName("Tinywan");
    }
}