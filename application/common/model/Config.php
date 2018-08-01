<?php

namespace app\common\model;

class Config extends Base {

    public function getCopyrightAttr($value) {
        return htmlspecialchars_decode($value);
    }

    public function getTooltipAttr($value) {
        return htmlspecialchars_decode($value);
    }

    /**
     * 本地上传配置信息
     * @return array
     */
    public static function upload() {
        $config = config('upload');

        $upload = [
            'cdnurl' => $config['cdnurl'],
            'uploadurl' => $config['uploadurl'],
            'bucket' => 'local',
            'maxsize' => $config['maxsize'],
            'mimetype' => $config['mimetype'],
            'multipart' => [],
            'multiple' => $config['multiple'],
        ];
        return $upload;
    }

    public static function getInfoByLang($lang) {
        $where = [];
        $where['l.alias'] = $lang;
        $where['l.status'] = 1;
        $where['c.status'] = 1;
        $row = self::alias('c')
            ->join('lang l', 'l.id = c.lang_id')
            ->where($where)
            ->field('c.*')
            ->find();
        return $row;
    }

}