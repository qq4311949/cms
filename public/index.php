<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 定义根目录
define('ROOT_PATH', __DIR__ . '/../');

// 定义应用目录
define('APP_PATH', ROOT_PATH . 'application/');

// 定义配置目录
define('CONF_PATH', ROOT_PATH . 'config/');

// 安装包目录
define('INSTALL_PATH', APP_PATH . 'admin/command/Install/');

// 判断是否安装FastAdmin
if (!is_file(INSTALL_PATH . 'install.lock')) {
    header("location:./install.php");
    exit;
}

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
