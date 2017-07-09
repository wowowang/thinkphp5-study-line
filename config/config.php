<?php
return [
    'app_status'          => 'office',    //home office
    'app_author'          => 'Tinywan',
    'app_email'          => '756684177@qq.com',
    'app_debug'              => true,
//    // 入口自动绑定模块
//    'auto_bind_module'       => true,
//    // 是否开启路由
    'url_route_on'           => false,
//    // 是否强制使用路由
//    'url_route_must'         => false
    // 默认模块名
    'default_module'         => 'frontend',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '12345',
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
    ],
];
