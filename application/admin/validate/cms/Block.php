<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Block extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'title' => 'require|max:50',
        'content' => 'require',
        'status' => '_switch:1'
    ];

    protected $scene = [
        'add' => ['title', 'content', 'status'],
        'edit' => ['id', 'title', 'content', 'status']
    ];
}