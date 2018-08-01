<?php

namespace app\common\model;

class Message extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Message';

    public function getHeaderList() {
        lang();
        $rows['num'] = $this->count();
        $rows['list'] = $this->order('id desc')->limit(2)->select();
        return $rows;
    }

}