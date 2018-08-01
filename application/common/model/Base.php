<?php

namespace app\common\model;

use think\Model;

class Base extends Model {

    // 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    // 设置默认值
    protected static function _switch($data, $keys) {
        $arr = [];
        if (!is_array($keys)) {
            $arr[] = $keys;
        }
        foreach ($arr as $val) {
            if (isset($data[$val]) && $data[$val]) {
                $data[$val] = 1;
            } else {
                $data[$val] = 0;
            }
        }
        return $data;
    }

    protected static function _trim($data, $key, $char) {
        $data[$key] = trim($data[$key], $char);
        return $data;
    }

    protected static function getLangIdBySite() {
        $site = cache('__SITE__');
        return isset($site['lang_id']) ? $site['lang_id'] : 0;
    }
}