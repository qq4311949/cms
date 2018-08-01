<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

return [
    // 布局模板
    'layout_on'    => true,
    // 视图输出字符串内容替换
    'tpl_replace_string' => [
        '__STATIC__' => '/static/admin',
        '__IMG__' => '/static/admin/img',
        '__CSS__' => '/static/admin/css',
        '__JS__' => '/static/admin/js',
        '__LIB__' => '/static/admin/libs',
    ],
];