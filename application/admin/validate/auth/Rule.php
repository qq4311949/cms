<?php

namespace app\admin\validate\auth;

use app\common\validate\Base;

class Rule extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'is_menu' => '_switch:1',
        'pid' => 'require|number|gt:0',
        'name' => 'require|unique:auth_rule|max:80',
        'title' => 'require|max:20',
        'icon' => 'max:50',
        'sort' => 'require|number|egt:0',
        'condition' => 'max:100',
        'remark' => 'max:255',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['group_id', 'username', 'password', 'email', 'telephone', 'remark', 'status'],
        'edit' => ['id', 'group_id', 'username', 'password', 'email', 'telephone', 'remark', 'status'],
    ];
}