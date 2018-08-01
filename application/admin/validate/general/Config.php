<?php

namespace app\admin\validate\general;

use app\common\validate\Base;

class Config extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'title' => 'max:255',
        'logo' => 'require|max:225',
        'ico' => 'require|max:255',
        'keywords' => 'max:255',
        'description' => 'max:255',
        'tooltip' => 'max:255',
        'copyright' => 'require',
        'status' => '_switch:1',
    ];

    protected $scene = [
        'index' => ['id', 'title', 'logo', 'ico', 'keywords', 'description', 'tooltip', 'copyright', 'status'],
    ];
}