<?php

namespace app\admin\validate\general;

use app\common\validate\Base;

class Lang extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'name' => 'require|unique:lang|min:1|max:16',
        'alias' => 'require|min:1|max:16',
        'remark' => 'max:255',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['name', 'alias', 'remark', 'status'],
        'edit' => ['id', 'name', 'alias', 'remark', 'status'],
    ];
}