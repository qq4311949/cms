<?php

namespace app\common\model;

class AuthGroup extends Base {

    public static function init() {

        self::event('before_insert', function($data) {
            parent::_trim($data, 'rules', ',');
            parent::_switch($data, 'status');
        });

        self::event('before_update', function($data) {
            parent::_trim($data, 'rules', ',');
            parent::_switch($data, 'status');
        });
    }

    public function getUpdateTimeAttr($value) {
        return date('Y-m-d H:i:s', $value);
    }

    public function users() {
        return $this->belongsToMany('Admin', 'auth_group_access', 'uid', 'group_id');
    }
}