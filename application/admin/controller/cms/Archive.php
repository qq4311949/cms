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

class Archive extends Backend {

    protected $model = null;
    //无需要权限判断的方法
    protected $noNeedRight = ['tree'];

    public function initialize() {
        parent::initialize();
        $this->model = model('Archive');
        $this->view->channeltree = action('admin/cms/Channel/tree');
    }

    public function index() {
        $sqlBuild = $this->model->where('lang_id', session('admin.lang_id'));
        $channelId = $this->request->get('channel_id', 0, 'int');
        if ($channelId) {
            $sqlBuild = $sqlBuild->where('channel_id', $channelId);
        }
        $search = $this->request->get('search', '', 'trim,htmlspecialchars');
        if ($search) {
            $sqlBuild = $sqlBuild->where('title', 'like', "%{$search}%");
        }
        $list = $sqlBuild->order('sort', 'desc')->order('id', 'desc')->paginate($this->pageNum);
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
        } else {
            $this->view->tags = model('Tag')->where('status', 1)->order('sort', 'desc')->order('id', 'desc')->select();
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
            $this->view->tags = model('Tag')->where('status', 1)->order('sort', 'desc')->order('id', 'desc')->select();
            return $this->fetch();
        }
    }

    public function del() {
        $id = $this->request->get('id', 0, 'int');
        if (empty($id)) {
            $this->error('非法参数');
        }
        $info = $this->model->where('id', $id)->find();
        if (empty($info)) {
            $this->error('该栏目不存在');
        }
        $res = $this->model->where('id', $id)->delete();
        if (!$res) {
            $this->error('删除失败');
        }
        $this->success('删除成功', _url('index'));
    }

}