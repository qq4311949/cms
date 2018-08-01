<?php
// +----------------------------------------------------------------------
// | WebService
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\model\Channel;

class Workshop extends Frontend {

    protected $layout = 'page';

    public function index() {
        $this->view->info = Channel::getPageInfo($this->route);
    	return $this->fetch();
    }

}
