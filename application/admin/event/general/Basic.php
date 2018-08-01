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

class Basic extends BaseEvent {

    public function beforeUpdate($data) {
        $this->_init($data);
    }

    protected function _init($data) {
        parent::_switch($data, 'is_i18n');
    }
}