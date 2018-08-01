<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use app\common\model\Config;
use app\common\model\Channel;
use think\facade\Lang;
use think\facade\Route;

class Frontend extends Controller {

    /**
     * 路由规则
     * @var string
     */
    protected $route = '';

    /**
     * 站点信息
     * @var string
     */
    protected $site = '';

    /**
     * 导航
     * @var string
     */
    protected $nav = '';

    /**
     * 导航列表
     * @var string
     */
    protected $navs = '';

    /**
     * 面包屑
     * @var array
     */
    protected $breadcrumbs = [];

    /**
     * 布局模板
     * @var string
     */
    protected $layout = 'index';

    public function initialize() {
        // 路由详情
        $routeInfo = $this->request->routeInfo();
        $this->route = isset($routeInfo['rule']) ? $routeInfo['rule'] : 'index';
        // 设置过滤规则
        $this->request->filter(['trim', 'htmlspecialchars']);
        // 设置允许语言列表
        $langList = \app\common\model\Lang::getList();
        $langs = array_column($langList, 'alias');
        Lang::setAllowLangList($langs);
        $lang = $this->request->get('lang', config('default_lang'), 'strip_tags');
        cookie('think_var', $lang);
        // 站点配置
        $this->site = Config::getInfoByLang($lang);
        cache('__SITE__', $this->site, ini_get('max_execution_time'));
        $this->assign('site', $this->site);
        // 导航配置
        $this->navs = Channel::getNavList();
        $this->assign('navs', $this->navs);

        // 如果有使用模板布局
        if ($this->layout) {
            if ($this->layout != 'index') {
                $productId = 0;
                foreach ($this->navs as $key=>$item) {
                    if ($key == 0 || strpos($this->route, $item['route']) === 0) {
                        $this->breadcrumbs[] = [
                            'name' => $item['name'],
                            'url' => strpos($item['route'], '/') === 0 ? $item['route'] : '/'.$item['route']
                        ];
                        if ($key > 0) {
                            $this->route = $item['route'];
                        }
                    }
                    if (strpos($item['route'], 'products') === 0) {
                        $productId = $item['id'];
                    }
                }
//                if ($this->layout == 'page') {
                    $sidebars = Channel::getSidebarList($productId);
                    $this->assign('sidebars', $sidebars);
//                }
                $this->assign('breadcrumbs', $this->breadcrumbs);
            }
            $this->view->engine->layout('layout/' . $this->layout);
        }

        $this->assign('route', $this->route);
    }

}