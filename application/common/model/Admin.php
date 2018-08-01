<?php

namespace app\common\model;

class Admin extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\auth\Admin';

    protected $auto = ['ip'];
    protected $insert = ['password'];
    protected $update = [];

    public function setIpAttr() {
        return ip2long(request()->ip());
    }

    public function setPasswordAttr($value) {
        return md5($value);
    }

    public function groups() {
        return $this->belongsToMany('AuthGroup', 'auth_group_access', 'group_id', 'uid');
    }
}