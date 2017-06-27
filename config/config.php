<?php
return [
    'app_status'          => 'office',    //home office
    'app_author'          => 'Tinywan',
    'app_email'          => '756684177@qq.com',
    'app_debug'              => true,
//    // 入口自动绑定模块
//    'auto_bind_module'       => true,
//    // 是否开启路由
//    'url_route_on'           => true,
//    // 是否强制使用路由
//    'url_route_must'         => false
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 视图基础目录，配置目录为所有模块的视图起始目录
        'view_base'    => '',
        // 当前模板的视图目录 留空为自动获取
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],
    // 默认模块名
    'default_module'         => 'frontend',
    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'socket',
        // 日志保存目录
//        'path'  => LOG_PATH,
        // 日志记录级别
//        'level' => [],
        'host'                => 'slog.thinkphp.cn',
        //日志强制记录到配置的client_id
        'force_client_ids'    => ['slog_b57a16'],
        //限制允许读取日志的client_id
        'allow_client_ids'    => ['slog_b57a16'],

    ],
];
