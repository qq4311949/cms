<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Tag extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'name' => 'require|max:20',
        'alias' => 'require|max:20',
        'sort' => 'require|integer',
        'status' => '_switch:1'
    ];

    protected $scene = [
        'add' => ['name', 'alias', 'sort', 'status'],
        'edit' => ['id', 'name', 'alias', 'sort', 'status']
    ];
}