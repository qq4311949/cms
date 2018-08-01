<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\event\auth;

use app\common\event\BaseEvent;

class Rule extends BaseEvent {

    public function beforeInsert($data) {
        $this->_init($data);
    }

    public function beforeUpdate($data) {
        $this->_init($data);
    }

    protected function _init($data) {
        if ($data['pid'] == 0) {
            $data['level'] = 1;
        } else {
            $parentLevel = model('AuthRule')->where('id', $data['pid'])->value('level');
            $data['level'] = $parentLevel + 1;
        }
        parent::_switch($data, 'is_menu');
        parent::_switch($data, 'status');
    }
}