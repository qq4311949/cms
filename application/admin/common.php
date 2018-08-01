<?php

// 应用公共文件
use think\facade\Url;

if (!function_exists('_url')) {

    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function _url($url = '', $vars = '', $suffix = true, $domain = false) {
        $url = Url::build($url, $vars, $suffix, $domain);
        if (strpos($url, '.')) {
            $url = str_replace('.', '/', $url);
        }
        return $url;
    }

}

if (!function_exists('build_toolbar')) {

    /**
     * 生成表格操作按钮栏
     * @param array $btns 按钮组
     * @param array $params 参数
     * @return string
     */
    function build_toolbar($btns = NULL, $params = []) {
        $auth = \app\common\library\Auth::instance();
        $controller = str_replace('.', '/', strtolower(think\facade\Request::instance()->controller()));
        $btns = $btns ?: ['refresh', 'add', 'edit', 'del'];
        $btns = is_array($btns) ? $btns : explode(',', $btns);

        $btnAttr['refresh'] = ['', 'am-btn am-btn-primary', 'am-icon-refresh', '刷新'];
        $btnAttr['add']     = [_url('add'), 'am-btn am-btn-default am-btn-success', 'am-icon-plus', '添加'];
        $btnAttr['dels']    = [_url('del'), 'am-btn am-btn-default am-btn-danger', 'am-icon-trash-o', '删除'];

        $flag = false;
        if (isset($params['id']) && $params['id']) {
            $flag = true;
            $btnAttr['edit']= [_url('edit', ['id' => $params['id']]), 'am-btn am-btn-default am-btn-xs am-text-secondary', 'am-icon-pencil-square-o', '编辑'];
            $btnAttr['del'] = [_url('del', ['id' => $params['id']]), 'am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only', 'am-icon-trash-o', '删除'];
        }
        $htmls = [];
        foreach ($btns as $k => $v) {
            //如果未定义或没有权限
            if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}"))) {
                continue;
            }
            list($href, $class, $icon, $text) = $btnAttr[$v];
            $html = '<button type="button" class="' . $class . '">';
            if ($flag) {
                if ($v == 'del') {
                    $html .= '<a href="' . $href . '" class="am-text-danger">';
                } else {
                    $html .= '<a href="' . $href . '">';
                }
            } else {
                $html .= '<a href="' . $href . '" class="am-text-white">';
            }
            $html .=         '<span class="' . $icon . '"></span> ' . $text;
            $html .=     '</a>';
            $html .= '</button>';
            $htmls[] = $html;
        }
        return implode(' ', $htmls);
    }
}

