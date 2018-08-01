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

class Lang extends BaseEvent {

    public function beforeInsert($data) {
        $this->_init($data);
    }

    public function afterInsert($data) {
    	$arr['lang_id'] = $data['id'];
    	model('Config')->allowField(true)->isUpdate(false)->save($arr);
    }

    public function beforeUpdate($data) {
        $this->_init($data);
    }

    public function afterDelete($data) {
    	$arr['lang_id'] = $data['id'];
    	model('Config')->destroy($arr);
        model('Channel')->destroy($arr);
    }

    protected function _init($data) {
        parent::_switch($data, 'status');
    }
}