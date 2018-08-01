<?php
// +----------------------------------------------------------------------
// | WebService
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Backend;

class Index extends Backend {

    public function index() {
        return $this->fetch();
    }

}
