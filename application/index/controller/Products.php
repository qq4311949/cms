<?php
// +----------------------------------------------------------------------
// | WebService
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\model\Archive;
use app\common\model\Channel;

class Products extends Frontend {

    protected $layout = '';

    public function initialize() {
        if ($this->request->action() == 'index') {
            $this->layout = 'list';
        } else {
            $this->layout = 'page';
        }
        parent::initialize();
    }

    /**
     * 获取产品列表
     * @param int id 分类id
     * @return mixed
     */
    public function index() {
        $id = $this->request->param('id', 0, 'int');
        $this->view->nav = Channel::getPageInfo($this->route);
        $this->view->list = Archive::getList($id);
        if ($id) {
            array_push($this->breadcrumbs, ['name' => model('Channel')->getNameById($id), 'url' => '']);
            $this->assign('breadcrumbs', $this->breadcrumbs);
        }
        return $this->fetch();
    }

    public function item() {
        $id = $this->request->param('id', 0, 'int');
        if (!$id) {
            $this->error('非法参数');
        }
        $info = Archive::get($id);
        array_push($this->breadcrumbs, ['name' => model('Channel')->getNameById($info['channel_id']), 'url' => url('index/Products/index', ['id' => $info['channel_id']])]);
        array_push($this->breadcrumbs, ['name' => $info['title'], 'url' => '']);
        $this->assign('info', $info);
        $this->assign('breadcrumbs', $this->breadcrumbs);
        return $this->fetch();
    }

}
