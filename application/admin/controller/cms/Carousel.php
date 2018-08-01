<?php

namespace app\admin\controller\cms;

use app\common\controller\Backend;
use com\Tree;

class Carousel extends Backend {

    protected $model = null;
    //无需要权限判断的方法
    protected $noNeedRight = ['tree'];
    protected $carousellist = [];

    public function initialize() {
        parent::initialize();
        $this->model = model('Carousel');
        $carousellist = $this->model->where('lang_id', session('admin.lang_id'))->order('sort', 'desc')->select();
        Tree::instance()->init($carousellist);
        $this->carousellist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $this->view->assign('carouseltree', $this->tree());
    }

    public function index() {
        $this->view->list = $this->carousellist;
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
            $this->view->langs = model('Lang')->where('status', 1)->order('sort', 'desc')->select();
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
            $this->view->langs = model('Lang')->where('status', 1)->order('sort', 'desc')->select();
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
            $this->error('此选项不存在');
        }
        $res = $this->model->destroy($id);
        if (!$res) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

    public function tree() {
        $tree = [];
        foreach ($this->carousellist as &$v) {
            if (!$v['status'])
                continue;
            $tree[] = $v;
        }
        return $tree;
    }
}
