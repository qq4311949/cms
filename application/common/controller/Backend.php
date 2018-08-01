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
use app\common\library\Auth;
use think\facade\Config;
use think\facade\Hook;
use think\facade\Session;

class Backend extends Controller {

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];

    /**
     * 布局模板
     * @var string
     */
    protected $layout = 'default';

    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;

    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id';

    /**
     * 默认分页数
     */
    protected $pageNum = '';

    /**
     * 语言列表
     */
    protected $langs = [];

    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    public function initialize() {

        Hook::add('app_end', 'app\\admin\\behavior\\AdminLog');
        $moduleName = $this->request->module();
        $controllerName = strtolower($this->request->controller());
        $actionName = strtolower($this->request->action());

        $path = str_replace('.', '/', $controllerName) . '/' . $actionName;

        // 定义是否AJAX请求
        !defined('IS_AJAX') && define('IS_AJAX', $this->request->isAjax());

        $this->auth = Auth::instance();

        // 设置当前请求的URI
        $this->auth->setRequestUri($path);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)) {
            //检测是否登录
            if (!$this->auth->isLogin()) {
                $url = Session::get('referer');
                $url = $url ? $url : $this->request->url();
                $this->error('请先登录', _url('Login/index', ['url' => $url]));
            }
            // 判断是否需要验证权限
            if (!$this->auth->match($this->noNeedRight)) {
                // 判断控制器和方法判断是否有对应权限
                if (!$this->auth->check($path)) {
                    $this->error('您没有权限进行此操作');
                }
            }
        }
        // 设置菜单数据
        $this->view->sidebar = $this->auth->getSidebar();
        // 设置面包屑导航数据
        $breadcrumb = $this->auth->getBreadCrumb($path);
        array_pop($breadcrumb);
        $this->view->breadcrumb = $breadcrumb;

        // 如果有使用模板布局
        if ($this->layout) {
            $this->view->engine->layout('layout/' . $this->layout);
        }

        // 上传配置
        $upload = \app\common\model\Config::upload();

        Config::set('upload', array_merge(Config::get('upload'), $upload));

        // 配置信息
        $config = [
            'upload'         => $upload,
            'modulename'     => $moduleName,
            'controllername' => $controllerName,
            'actionname'     => $actionName,
            'jsname'         => 'admin/' . str_replace('.', '/', $controllerName),
            'moduleurl'      => rtrim(url("/{$moduleName}", '', false), '/'),
            'jzadmin'        => Config::get('jzadmin'),
            'referer'        => Session::get("referer")
        ];

        $config = array_merge($config, Config::get('template.tpl_replace_string'));
        // 渲染配置信息
        $this->assign('config', json_encode($config));
        // 语言列表
        $this->langs = model('Lang')->getList();
        $this->view->langs = $this->langs;
        // 顶部消息列表
        $this->view->messages = model('Message')->getHeaderList();
        // 默认分页数
        $this->pageNum = Config::get('paginate.list_rows');
        // 设置过滤方法
        $this->request->filter(['htmlspecialchars']);
    }

    /**
     * 过滤层
     * @param $data
     */
    protected function filter($data) {
        $controllerName = $this->request->controller();
        // 多级控制器处理
        if (strpos($this->request->controller(), '.')) {
            list($dir, $controller) = explode('.', $controllerName);
            $controllerName = strtolower($dir) . '\\' . ucfirst($controller);
        }
        $class = '\\app\\' . $this->request->module() . '\\validate\\' . $controllerName;
        $validate = new $class;
        if (!$validate->scene($this->request->action())->check($data)) {
            // 非法参数
            $this->error($validate->getError());
        }
    }

    /**
     * 生成查询所需要的条件,排序方式
     * @param mixed $searchFields 快速查询的字段
     * @param boolean $relationSearch 是否关联查询
     * @return array
     */
    protected function buildParams($searchFields = null, $relationSearch = null) {
        $searchFields = is_null($searchFields) ? $this->searchFields : $searchFields;
        $relationSearch = is_null($relationSearch) ? $this->relationSearch : $relationSearch;
        $search = $this->request->get("search", '');
        $sort = $this->request->get("sort", "id");
        $order = $this->request->get("order", "DESC");
        $offset = $this->request->get("offset", 0);
        $limit = $this->request->get("limit", 0);

        $where = [];
        $tableName = '';
        if ($relationSearch) {
            if (!empty($this->model)) {
                $tableName = $this->model->getQuery()->getTable() . ".";
            }
            $sort = stripos($sort, ".") === false ? $tableName . $sort : $sort;
        }

        if ($search) {
            $searchArr = is_array($searchFields) ? $searchFields : explode(',', $searchFields);
            foreach ($searchArr as $k => &$v) {
                $v = stripos($v, ".") === false ? $tableName . $v : $v;
            }
            unset($v);
            $where[] = [implode("|", $searchArr), "LIKE", "%{$search}%"];
        }

        $where = function($query) use ($where) {
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    call_user_func_array([$query, 'where'], $v);
                } else {
                    $query->where($v);
                }
            }
        };
        return [$where, $sort, $order, $offset, $limit];
    }
}