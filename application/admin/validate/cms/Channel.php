<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Channel extends Base {

    protected $rule = [
        'id' => 'require|integer|length:1,16777215',
        'type_id' => 'require|integer|gt:0',
        'name' => 'require|unique:lang|min:1|max:80',
        'pid' => 'require|integer|length:1,16777215',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['type_id', 'name', 'pid', 'intro', 'status'],
        'edit' => ['id', 'type_id', 'name', 'pid', 'intro', 'status'],
    ];
}