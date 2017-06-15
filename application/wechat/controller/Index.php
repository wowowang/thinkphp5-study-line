<?php
namespace app\wechat\controller;

use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $data = ['name' => 'Wechat', 'url' => 'thinkphp.cn'];
        return json(['data' => $data, 'code' => 1, 'message' => 'wechat code']);
    }

}
