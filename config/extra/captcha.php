<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/8
 * Time: 14:03
 */
return [
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '111111111111111',
        // 验证码字体大小(px)
        'fontSize' => 30,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度
        'imageH'   => 50,
        // 验证码图片宽度
        'imageW'   => 300,
        // 验证码位数
        'length'   => 3,
        'bg'   => [243, 251, 254],
        // 验证成功后是否重置
        'reset'    => true
    ]
];


