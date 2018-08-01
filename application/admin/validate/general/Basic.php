<?php

namespace app\admin\validate\general;

use app\common\validate\Base;

class Basic extends Base {

    protected $rule = [
        'id' => 'require|integer|gt:0',
        'is_i18n' => '_switch:1',
        'version' => 'require',
        'timezone' => 'require',
    ];

    protected $scene = [
        'index' => ['id', 'is_i18n', 'version', 'timezone'],
    ];
}