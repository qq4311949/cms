<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Archive extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'channel_id' => 'require|integer|gt:0',
        'title' => 'require|max:80',
        'image' => 'max:255',
        'intro' => 'require|max:255',
        'content' => 'require',
        'sort' => 'require|integer',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'add' => ['channel_id', 'title', 'image', 'intro', 'content', 'sort', 'status'],
        'edit' => ['id', 'channel_id', 'title', 'image', 'intro', 'content', 'sort', 'status'],
    ];
}