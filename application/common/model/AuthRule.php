<?php

namespace app\common\model;

class AuthRule extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\auth\Rule';
    
}