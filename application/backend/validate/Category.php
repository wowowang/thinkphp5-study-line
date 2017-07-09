<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/9
 * Time: 11:05
 */

namespace app\backend\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name'=>'require',
        'level'=>'number|between:1,999',
        'remark'=>"require",
    ];

    protected $message = [
        'name.require'=>"分类名称不能为空",
        'level.number'=>"必须为数字",
        'level.between'=>"请输入1到999之间的数字",
        'remark.require'=>"描述信息不能为空",
    ];
}