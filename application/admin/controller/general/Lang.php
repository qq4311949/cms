<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\general;

use app\common\controller\Backend;

class Lang extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('Lang');
    }

    public function index() {
        $list = $this->model->order('id', 'desc')->paginate($this->pageNum);
        $this->assign('list', $list);
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
        }
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
                return $this->error('非法参数');
            }
            $info = $this->model->get($id);
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $lang = $this->model->where('id', $id)->find();
        if (empty($lang)) {
            $this->error('该语言不存在');
        }
        $res = $this->model->destroy($id);
        if (!$res) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

}