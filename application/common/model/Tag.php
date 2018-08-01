<?php

namespace app\common\model;

class Tag extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Tag';

}