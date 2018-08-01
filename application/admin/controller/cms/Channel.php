<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\cms;

use app\common\controller\Backend;
use com\Tree;
use think\Db;

class Channel extends Backend {

    protected $model = null;
    //无需要权限判断的方法
    protected $noNeedRight = ['tree'];
    protected $channellist = [];

    public function initialize() {
        parent::initialize();
        $this->model = model('Channel');
        // 必须将结果集转换为数组
        $channelList = $this->model->where('lang_id', session('admin.lang_id'))->order('sort', 'desc')->select();
        Tree::instance()->init($channelList);
        $this->channellist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
        $this->view->assign('channeltree', $this->tree());
    }

    public function index() {
        $this->view->list = $this->channellist;
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $res = $this->model->allowField(true)->isUpdate(false)->save($data);
            if (!$res) {
                $this->error('添加失败');
            }
            $this->success('添加成功', _url('index'));
        } else {
            $this->view->types = Db::name('channel_type')->select();
            return $this->fetch();
        }
    }

    public function edit() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $res = $this->model->allowField(true)->isUpdate(true)->save($data);
            if ($res === false) {
                $this->error('编辑失败');
            }
            $this->success('编辑成功', _url('index'));
        } else {
            $id = $this->request->get('id', 0, 'int');
            if (empty($id)) {
                $this->error('非法参数');
            }
            $this->view->info = $this->model->get($id);
            $this->view->types = Db::name('channel_type')->select();
            return $this->fetch();
        }
    }

    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $info = $this->model->get($id);
        if (empty($info)) {
            $this->error('该栏目不存在');
        }
        $res = $this->model->destroy($id);
        if (!$res) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

    public function tree() {
        $tree = [];
        foreach ($this->channellist as &$v) {
            if (!$v['status'])
                continue;
            $tree[] = $v;
        }
        return $tree;
    }

}