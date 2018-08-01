<?php

return [
    // URL普通方式参数 用于自动生成
    'url_common_param'       => true,
    // URL伪静态后缀
    'url_html_suffix'        => '',
    // 自动搜索控制器
    'controller_auto_search' => true,

    // jzAdmin配置
    'jzadmin'                => [
        // 登录验证码
        'login_captcha'       => false,
        // 登录失败超过10则1天后重试
        'login_failure_retry' => true,
        // 是否同一账号同一时间只能在一个地方登录
        'login_unique'        => false,
    ],

    // 上传配置
    'upload'                 => [
        // 上传地址,默认是本地上传
        'uploadurl' => 'ajax/upload',
        // CDN地址
        'cdnurl' => '',
        // 文件保存格式
        'savekey'   => '/uploads/{year}{mon}{day}/{filemd5}{.suffix}',
        // 最大可上传大小
        'maxsize'   => '10mb',
        // 可上传的文件类型
        'mimetype'  => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx',
        // 是否支持批量上传
        'multiple'  => false,
    ]
];