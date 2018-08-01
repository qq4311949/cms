<?php

namespace app\admin\controller\cms;

use app\common\controller\Backend;

class Message extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('Message');
    }

    public function index() {
        $this->view->list = $this->model->order('id desc')->paginate($this->pageNum);
        return $this->fetch();
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
