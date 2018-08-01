<?php

namespace app\admin\controller\auth;

use app\common\controller\Backend;

/**
 * 角色管理
 * Class Admin
 * @package app\admin\controller\auth
 */
class Role extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('AuthGroup');
    }

    /**
     * 角色列表
     * @return mixed
     */
    public function index() {
        $list = $this->model->paginate($this->pageNum);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 角色添加
     * @return mixed|void
     */
    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $res = $this->model->isUpdate(false)->save($data);
            if (!$res) {
                $this->error('添加失败');
            }
            $this->success('添加成功', _url('index'));
        }
        return $this->fetch();
    }

    /**
     * 角色编辑
     * @return mixed|void
     */
    public function edit() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $res = $this->model->isUpdate(true)->save($data);
            if ($res === false) {
                $this->error('编辑失败');
            }
            $this->success('编辑成功', _url('index'));
        } else {
            $id = $this->request->get('id', 0, 'int');
            if (empty($id)) {
                $this->error('非法参数');
            }
            $info = $this->model->get($id);
            $this->assign('info', $info);
            $roles = $this->model->where('status', 1)->select();
            $this->assign('roles', $roles);
            return $this->fetch();
        }
    }

    /**
     * 角色删除
     */
    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $res = $this->model->where('id', $id)->delete();
        if (!$res) {
            $this->error('删除失败');
        }
        $this->success('删除成功', _url('index'));
    }
}
