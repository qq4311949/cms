<?php

// 应用公共文件

use think\facade\Url;

if (!function_exists('_url')) {

    /**
     * Url生成
     * @param string $url 路由地址
     * @param string|array $vars 变量
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     * @return string
     */
    function _url($url = '', $vars = '', $suffix = true, $domain = false) {
        $url = Url::build($url, $vars, $suffix, $domain);
        $lang = request()->get('lang', '', 'strip_tags');
        if ($lang) {
            $url .= '?lang=' . $lang;
        }
        return $url;
    }

}
