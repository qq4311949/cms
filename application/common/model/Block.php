<?php

namespace app\common\model;

class Block extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Block';

    public function getContentAttr($value) {
        return htmlspecialchars_decode($value);
    }

    public function channel() {
        return $this->belongsTo('Channel', 'channel_id', 'id')->field('name');
    }

}