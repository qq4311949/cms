<?php

namespace app\common\model;

class Lang extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\general\Lang';

    public static function getDefaultLang() {
    	$row = self::where('status', 1)->order('sort', 'desc')->order('id', 'desc')->find();
    	return !empty($row) ? $row->toArray() : [];
    }

    public static function getList() {
    	$rows = self::where('status', 1)->order('sort', 'desc')->order('id', 'desc')->select();
    	return !empty($rows) ? $rows->toArray() : [];
    }

    public static function getListWithCache() {
        if (cache('__LANG_LIST__')) {
            return cache('__LANG_LIST__');
        } else {
            return self::getList();
        }
    }
    
}