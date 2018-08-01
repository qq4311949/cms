<?php

namespace app\common\event;

class BaseEvent {

    public function pass($msg) {
        return ['status' => 1, 'msg' => $msg];
    }

    public function fail($msg) {
        return ['status' => 0, 'msg' => $msg];
    }

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
}