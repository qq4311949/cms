<?php

namespace app\common\model;

class Carousel extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Carousel';

    public function lang() {
        return $this->belongsTo('Lang', 'lang_id', 'id')->field('name');
    }

    public function getTitleById($id) {
        return $this->where('id', $id)->value('title');
    }

    public static function getList() {
        $where = [];
        $langId = parent::getLangIdBySite();
        if ($langId) {
            $where['lang_id'] = $langId;
        }
        $where['status'] = 1;
        $rows = self::where($where)->order('sort', 'desc')->select();
        return !empty($rows) ? $rows->toArray() : [];
    }
}