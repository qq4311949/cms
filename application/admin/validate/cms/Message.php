<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Message extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'name' => 'require|max:80',
        'sort' => 'require|integer',
        'status' => '_switch:1'
    ];

    protected $scene = [
        'add' => ['name', 'sort', 'status'],
        'edit' => ['id', 'name', 'sort', 'status']
    ];
}