<?php

namespace app\admin\event\cms;

use app\common\event\BaseEvent;

class Carousel extends BaseEvent {

    public function beforeInsert($data) {
        $this->_init($data);
        $data['lang_id'] = session('admin.lang_id');
    }

    public function beforeUpdate($data) {
        $this->_init($data);
    }

    protected function _init($data) {
        parent::_switch($data, 'status');
    }
}