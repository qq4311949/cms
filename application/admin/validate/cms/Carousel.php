<?php

namespace app\admin\validate\cms;

use app\common\validate\Base;

class Carousel extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'title' => 'require|max:80',
        'image' => 'require|max:255',
        'sort' => 'require|integer'
    ];

    protected $scene = [
        'add' => ['title', 'image', 'sort'],
        'edit' => ['id', 'title', 'image', 'sort']
    ];
}