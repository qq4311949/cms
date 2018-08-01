<?php

namespace app\common\model;

class Basic extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\general\Basic';

}