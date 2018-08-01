<?php

namespace app\admin\controller\auth;

use app\common\controller\Backend;

/**
 * 用户管理
 * Class Admin
 * @package app\admin\controller\auth
 */
class User extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('Admin');
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function index() {
        $fields = 'u.id,u.username,u.email,u.telephone,g.title as group_name,u.status,u.update_at';
        $list = $this->model->alias('u')
            ->join('auth_group_access a', 'a.uid = u.id')
            ->join('auth_group g', 'g.id = a.group_id')
            ->where('u.id', '>', 1)
            ->field($fields)
            ->paginate($this->pageNum);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 用户添加
     * @return mixed|void
     */
    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            // 主表字段验证添加
            $res = $this->model->allowField(true)->isUpdate(false)->save($data);
            if ($res) {
                // 中间表添加
                $this->model->groups()->attach($data['group_id']);
                $this->success('添加成功', _url('index'));
            } else {
                $this->error('添加失败');
            }
        } else {
            $groups = model('AuthGroup')->where('status', 1)->select();
            $this->assign('groups', $groups);
            return $this->fetch();
        }
    }

    /**
     * 用户编辑
     * @return mixed|void
     */
    public function edit() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $info = $this->model->where('id', $data['id'])->cache(3)->find();
            if (empty($info)) {
                $this->error('该用户不存在');
            }
            if ($data['password'] == '') {
                unset($data['password']);
            } else {
                if ($info['salt'] == '') {
                    $data['password'] = md5($data['password']);
                } else {
                    $data['password'] = md5(md5($data['password']) . $info['salt']);
                }
            }
            $res = $this->model->allowField(true)->isUpdate(true)->save($data);
            if ($res !== false) {
                // 删除中间表数据
                $this->model->groups()->detach();
                // 添加中间表数据
                $this->model->groups()->attach($data['group_id']);
                $this->success('编辑成功', _url('index'));
            } else {
                $this->error('编辑失败');
            }
        } else {
            $id = $this->request->get('id', 0, 'int');
            if (empty($id)) {
                return $this->error('非法参数');
            }
            $info = $this->model->alias('u')
                ->join('auth_group_access a', 'a.uid = u.id')
                ->where('u.id', $id)
                ->field('u.*,a.group_id')
                ->find();
            $this->assign('info', $info);
            $groups = model('AuthGroup')->where('status', 1)->select();
            $this->assign('groups', $groups);
            return $this->fetch();
        }
    }

    /**
     * 用户删除
     */
    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $user = $this->model->where('id', $id)->find();
        if (empty($user)) {
            $this->error('该用户不存在');
        }
        $res = $this->model->where('id', $id)->delete();
        if ($res) {
            // 中间表删除
            $user->groups()->detach();
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
