<?php

namespace app\common\model;

class Channel extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Channel';

    public function getContentAttr($value) {
        return htmlspecialchars_decode($value);
    }

    public function getNameById($id) {
        return $this->where('id', $id)->value('name');
    }

    public function channelType () {
        return $this->belongsTo('ChannelType', 'type_id', 'id')->field('name');
    }

    public function lang() {
        return $this->belongsTo('Lang', 'lang_id', 'id')->field('name');
    }

    public static function getNavList() {
        $where = [];
        $langId = parent::getLangIdBySite();
        if ($langId) {
            $where['lang_id'] = $langId;
        }
        $where['pid'] = 0;
        $where['status'] = 1;
        $rows = self::where($where)->order('sort', 'desc')->select();
        return !empty($rows) ? $rows->toArray() : [];
    }

    public static function getPageInfo($route, $id = 0) {
        $where = [];
        $where['route'] = $route;
        $langId = parent::getLangIdBySite();
        if ($langId) {
            $where['lang_id'] = $langId;
        }
        if ($id) {
            $where['channel_id'] = $id;
        }
        $where['pid'] = 0;
        $where['status'] = 1;
        $row = self::where($where)->find();
        return !empty($row) ? $row->toArray() : [];
    }

    public static function getSidebarList($pid) {
        $where = [];
        $where['pid'] = $pid;
        $where['status'] = 1;
        $rows = self::where($where)->order('sort', 'desc')->field('id,name')->select();
        if (!empty($rows)) {
            foreach ($rows as &$row) {
                $where = [];
                $where['channel_id'] = $row['id'];
                $where['status'] = 1;
                $items = Archive::where($where)->order('sort', 'desc')->field('id,title')->select();
                $row['archives'] = $items ? $items->toArray() : [];
            }
        }
        return $rows ? $rows->toArray() : [];
    }
    
}