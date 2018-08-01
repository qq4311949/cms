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
use app\common\model\Archive;
use app\common\model\Block;
use app\common\model\Carousel;
use com\Tree;
use think\captcha\Captcha;
use think\facade\Config;

class Index extends Frontend {

    public function index() {
        // 轮播图
        $carousels = Carousel::getList();
        $this->view->carousels = Tree::instance()->init($carousels)->getTreeArray(0);
        // 热门商品
        $this->view->hots = Archive::getHotList();
        // About JIANLIANG METAL
        $this->view->about = Block::get(['title' => lang('about jianliang metal')]);
        // Quality Policy
        $this->view->quality = Block::get(['title' => lang('quality policy')]);
    	return $this->fetch();
    }

    public function verify() {
        $captcha = new Captcha(Config::get('captcha'));
        return $captcha->entry();
    }

    public function message() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $validate = new \app\index\validate\Message();
            if (!$validate->check($data)) {
                failure(0, $validate->getError());
            }
            $res = model('Message')->allowField(true)->isUpdate(false)->save($data);
            if (!$res) {
                failure(0);
            }
            return success(1);
        }
    }
}
