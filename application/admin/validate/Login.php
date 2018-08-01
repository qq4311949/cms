<?php
namespace app\admin\validate;

use app\common\validate\Base;

class Login extends Base {

	protected $rule = [
        'username' => 'require',
        'password' => 'require|min:6|max:12',
        '__token__' => 'token',
    ];

    protected $message = [
        'username.require' => '姓名不能为空',
        'password.require' => '密码不能为空',
        'password.min' => '密码最短6位',
        'password.max' => '密码最长12位',
        '__token__.token' => '令牌无效'
    ];

    protected $scene = [
        'login' => ['username', 'password', '__token__'],
    ];
}