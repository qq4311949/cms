<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\event\general;

use app\common\event\BaseEvent;

class Config extends BaseEvent {

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