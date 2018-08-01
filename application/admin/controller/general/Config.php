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

class Config extends Backend {

    protected $model = null;
    protected $langs = [];

    public function initialize() {
        parent::initialize();
        // 设置过滤方法
        $this->request->filter(['htmlspecialchars']);
        $this->model = model('Config');
    }

    public function index() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            parent::filter($data);
            $res = $this->model->allowField(true)->isUpdate(true)->save($data);
            if (!$res) {
                $this->error('编辑失败');
            }
            $this->success('编辑成功');
        }
        $this->view->info = $this->model->getByLangId(session('admin.lang_id'));
        return $this->fetch();
    }

}