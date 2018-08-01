<?php

// 应用公共文件
use think\facade\Log;
use think\facade\Url;

if (!function_exists('failure')) {

    /**
     * 错误抛出函数
     * @param $code
     * @param string $msg
     */
    function failure($code, $msg = '') {
        if ($msg == '') {
            $arr = config("msg");
            $msg = $arr[$code];
        }
        $arr = [
            'ret' => (string)$code,
            'msg' => $msg,
            'time' => (string)time()
        ];
        echo json_encode($arr);
        die;
    }

}

if (!function_exists('success')) {

    /**
     * 返回函数
     * @param $code
     * @param $data
     * @param $url
     * @return \think\response\Json
     */
    function success($code, $data = [], $url = '') {
        $arr = config("msg");
        $msg = $arr[$code];
        $url = $url ?: $_SERVER['HTTP_REFERER'];
        $arr = [
            'ret' => (string)$code,
            'msg' => $msg,
            'url' => $url,
            'data' => is_array($data) ? $data : [],
            'time' => (string)time()
        ];
        return json($arr);
    }

}

if (!function_exists('randNumber')) {

    /**
     * 获取随机位数验证码
     * @param  integer $len 长度
     * @return string
     */
    function randNumber($len = 6) {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
        return $str;
    }

}

if (!function_exists('array_column')) {

    /**
     * array_column
     * @param $input
     * @param $columnKey
     * @param null $indexKey
     * @return array
     */
    function array_column($input, $columnKey, $indexKey = NULL) {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? TRUE : FALSE;
        $indexKeyIsNull = (is_null($indexKey)) ? TRUE : FALSE;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? TRUE : FALSE;
        $result = array();

        foreach ((array)$input AS $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : NULL;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : NULL;
            }
            if (!$indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key)) ? current($key) : NULL;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }

}


if (!function_exists('decimal2')) {

    /**
     * 两位小数
     * @param $num
     * @return string
     */
    function decimal2($num) {
        $Num = sprintf('%.2f', $num);
        return (string)$Num;
    }

}

if (!function_exists('transTime')) {

    /**
     * 时间戳转化函数
     * @param $timestamp
     * @return false|string
     */
    function transTime($timestamp) {
        if (date('Ymd') == date('Ymd', $timestamp)) {
            return '今天 ' . date('H:i', $timestamp);
        } else {
            return date('Y-m-d H:i', $timestamp);
        }
    }

}

if (!function_exists('hexChar')) {

    function hexChar($hex) {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function ($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }

}

if (!function_exists('hex2base64')) {

    function hex2base64($image) {
        return 'data:image/png;base64,' . base64_encode(pack('H*', $image));
    }

}

if (!function_exists('array_utf8_encode')) {

    /**
     * Encode array to utf8 recursively
     * @param $dat
     * @return array|string
     */
    function array_utf8_encode($dat) {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = array_utf8_encode($d);
        return $ret;
    }

}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s') {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null) {
        return \com\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string $url 资源相对地址
     * @return string
     */
    function cdnurl($url) {
        return preg_match("/^https?:\/\/(.*)/i", $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
    }

}


if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file) {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true) {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest) {
        if (!is_dir($dest)) {
            mkdir($dest, 0755);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($string) {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }
}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items 数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields) {
        if (!$items || !$fields)
            return $items;
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }

}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var 数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "") {
        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : var_export_short($key) . " => ")
                        . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }

}

