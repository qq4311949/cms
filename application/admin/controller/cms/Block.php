<?php

namespace app\admin\controller\cms;

use app\common\controller\Backend;

class Block extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('Block');
    }

    public function index() {
        $this->view->list = $this->model->where('lang_id', session('admin.lang_id'))->order('id desc')->paginate($this->pageNum);
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
            $this->view->channeltree = action('admin/cms/Channel/tree');
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
            $this->view->channeltree = action('admin/cms/Channel/tree');
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
}
