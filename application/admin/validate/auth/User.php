<?php

namespace app\admin\validate\auth;

use app\common\validate\Base;

class User extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'group_id' => 'require|number',
        'username' => 'require|unique:admin|min:1|max:16',
        'password' => 'min:1|max:16',
        'email' => 'require|email',
        'telephone' => 'require|mobile',
        'remark' => 'max:255',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['group_id', 'username', 'password', 'email', 'telephone', 'remark', 'status'],
        'edit' => ['id', 'group_id', 'username', 'password', 'email', 'telephone', 'remark', 'status'],
    ];
}