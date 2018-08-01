<?php

namespace app\admin\event\cms;

use app\common\event\BaseEvent;

class Message extends BaseEvent {

    public function beforeInsert($data) {
        $this->_init($data);
    }

    public function beforeUpdate($data) {
        $this->_init($data);
    }

    protected function _init($data) {
        parent::_switch($data, 'status');
    }
}