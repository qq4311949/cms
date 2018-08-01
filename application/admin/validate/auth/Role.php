<?php

namespace app\admin\validate\auth;

use app\common\validate\Base;

class Role extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'title' => 'require|max:100',
        'rules' => 'require|max:255',
        'remark' => 'max:255',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['title', 'rules', 'remark', 'status'],
        'edit' => ['id', 'title', 'rules', 'remark', 'status'],
    ];
}