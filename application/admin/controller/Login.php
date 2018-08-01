<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\model\AdminLog;
use app\common\controller\Backend;

class Login extends Backend {

    protected $noNeedLogin = ['index', 'login'];
    protected $noNeedRight = ['logout'];

    public function index() {
        // 临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function login() {
        $data = $this->request->post();
        parent::filter($data);
        AdminLog::setTitle('登录');
        $result = $this->auth->login($data);
        if ($result === true) {
            $this->success('登陆成功', _url('Index/index'));
        } else {
            $msg = $this->auth->getError();
            $msg = $msg ? $msg : '用户名或密码错误';
            $this->error($msg);
        }
    }

    public function logout() {
        session(null);
        $this->redirect(_url('Login/index'));
    }
}