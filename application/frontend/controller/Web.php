<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/27
 * Time: 22:01
 */
namespace app\frontend\controller;
use think\Controller;

class Web extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}