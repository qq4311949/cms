<?php
// +----------------------------------------------------------------------
// | WebService
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\index\validate;

use think\Validate;

class Message extends Validate {

    protected $rule = [
        'name' => 'require|max:20',
        'email' => 'require|email',
        'message' =>'require|max:255',
        'captcha' => 'require|captcha',
    ];

    protected $scene = [
        'index' => ['name', 'email', 'message', 'captcha'],
    ];
}