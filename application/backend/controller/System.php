<?php

namespace app\backend\controller;

use think\Controller;

class System extends Controller
{
    /**
     * 系统配置
     */
    public function config()
    {
        $this->assign('sub_title',"系统配置");
        return $this->fetch();
    }

    /**
     * 系统Log
     * @return mixed
     */
    public function actionLog()
    {
        $this->assign('sub_title',"系统日志记录");
        return $this->fetch();
    }
}
