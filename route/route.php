<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('index', 'index/Index/index');
Route::get('verify','index/Index/verify');
Route::post('message', 'index/Index/message');
Route::get('about', 'index/About/index');
Route::get('products/item/:id', 'index/Products/item')->pattern(['id' => '\d+']);
Route::get('products/:id?', 'index/Products/index')->pattern(['id' => '\d+']);
Route::get('workshop', 'index/Workshop/index');
Route::get('quality', 'index/Quality/index');
Route::get('contact', 'index/Contact/index');

