<?php

namespace app\common\model;

class Archive extends Base {

    // 注册事件观察者
    protected $observerClass = 'app\admin\event\cms\Archive';

    public function getContentAttr($value) {
        return htmlspecialchars_decode($value);
    }

    public function channel() {
        return $this->belongsTo('Channel', 'channel_id', 'id')->field('name');
    }

    public function tag() {
        return $this->belongsTo('Tag', 'tag_id', 'id')->field('name');
    }

    public static function getList($id) {
        $where = [];
        $langId = parent::getLangIdBySite();
        if ($langId) {
            $where['lang_id'] = $langId;
        }
        if ($id) {
            $where['channel_id'] = $id;
        }
        $where['status'] = 1;
        $rows = self::where($where)->order('sort', 'desc')->field('id,title,image,intro')->select();
        return !empty($rows) ? $rows->toArray() : [];
    }

    public static function getHotList() {
        $where = [];
        $langId = parent::getLangIdBySite();
        if ($langId) {
            $where['lang_id'] = $langId;
        }
        $where['tag_id'] = 1;//热门
        $where['status'] = 1;
        $rows = self::where($where)->order('sort', 'desc')->field('id,title,image,intro')->limit(8)->select();
        return !empty($rows) ? $rows->toArray() : [];
    }

    /**
     * 获取单页详情
     * @param $route
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getPageInfo($route) {
        $where = [];
        $where['c.route'] = $route;
        $where['c.lang_id'] = session('admin.lang_id');
        $where['c.status'] = 1;
        $where['a.status'] = 1;
        $row = self::alias('a')
            ->join('channel c', 'c.id = a.channel_id')
            ->where($where)
            ->find();
        return !empty($row) ? $row->toArray() : [];
    }

}