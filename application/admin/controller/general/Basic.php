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

class Basic extends Backend {

    protected $model = null;

    public function initialize() {
        parent::initialize();
        $this->model = model('Basic');
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
        $this->view->info = $this->model->find(1);
        return $this->fetch();
    }

}