<?php

namespace app\admin\controller\auth;

use app\common\controller\Backend;
use com\Tree;

/**
 * 权限管理
 * Class Admin
 * @package app\admin\controller\auth
 */
class Rule extends Backend {

    protected $model = null;
    protected $rulelist = [];
    //无需要权限判断的方法
    protected $noNeedRight = ['tree'];

    public function initialize() {
        parent::initialize();
        $this->model = model('AuthRule');
        // 必须将结果集转换为数组
        $ruleList = $this->model->order('sort', 'desc')->order('id', 'asc')->select()->toArray();
        Tree::instance()->init($ruleList);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $ruletree = [];
        foreach ($this->rulelist as &$v) {
            if (!$v['is_menu'])
                continue;
            $ruletree[] = $v;
        }
        $this->view->assign('ruletree', $ruletree);
    }


    /**
     * 权限列表
     * @return mixed
     */
    public function index() {
        $this->assign('list', $this->rulelist);
        return $this->fetch();
    }

    /**
     * 权限添加
     * @return mixed|void
     */
    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            // 主表字段验证添加
            $res = $this->model->allowField(true)->isUpdate(false)->save($data);
            if (!$res) {
                $this->error('添加失败');
            }
            $this->success('添加成功', _url('index'));
        }
        return $this->fetch();
    }

    /**
     * 权限编辑
     * @return mixed|void
     */
    public function edit() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            // 主表字段验证添加
            $res = $this->model->allowField(true)->isUpdate(true)->save($data);
            if ($res === false) {
                $this->error('编辑失败');
            }
            $this->success('编辑成功', _url('index'));
        }
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $info = $this->model->get($id);
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 权限删除
     */
    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $res = $this->model->destroy($id);
        if (!$res) {
            $this->error('删除失败');
        }

        return $this->success('删除成功', _url('index'));
    }

    /**
     * 权限树列表
     * @return array
     */
    public function tree() {
        $rows = [];
        foreach ($this->rulelist as $key=>$rule) {
            $rows[$key]['id'] = $rule['id'];
            $rows[$key]['name'] = str_replace($rule['spacer']. ' ', '', $rule['title']);
            $rows[$key]['pId'] = $rule['pid'];
        }
        return $rows;
    }
}
