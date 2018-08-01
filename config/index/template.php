<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

return [
    'layout_on'    => true,
//    'layout_name'  => 'layout',
    // 视图输出字符串内容替换
    'tpl_replace_string' => [
        '__STATIC__' => '/static/index',
        '__IMG__' => '/static/index/img',
        '__CSS__' => '/static/index/css',
        '__JS__'  => '/static/index/js',
        '__LIB__' => '/static/index/lib',
    ],
];