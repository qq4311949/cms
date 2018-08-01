<?php

namespace app\common\library;

use app\common\model\Admin;
use com\Random;
use com\Tree;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Session;

class Auth extends \com\Auth {

    protected $_error = '';
    protected $requestUri = '';
    protected $breadcrumb = [];
    protected $logined = false; //登录状态

    public function __construct() {
        parent::__construct();
    }

    public function __get($name) {
        return Session::get('admin.' . $name);
    }

    /**
     * 管理员登录
     * @param array $data
     * @param int $expire 有效时长
     * @return bool
     */
    public function login(array $data, $expire = 86400) {
        $admin = Admin::getByUsername($data['username']);
        if (!$admin) {
            $this->setError('用户名错误');
            return false;
        }
        if (Config::get('jzadmin.login_failure_retry') && $admin->failure >= 10 && time() - $admin->update_at < 86400) {
            $this->setError('请1天后再重试');
            return false;
        }
        if ($admin->password != md5(md5($data['password']) . $admin->salt)) {
            $admin->failure++;
            $admin->save();
            $this->setError('密码错误');
            return false;
        }
        $admin->failure = 0;
        $admin->login_at = time();
        $admin->token = Random::uuid();
        $admin->save();

        $lang = model('Lang')->getDefaultLang();
        Session::set("admin", array_merge($admin->toArray(), ['lang_id' => $lang['id']]));
        return true;
    }

    /**
     * 注销登录
     */
    public function logout() {
        $admin = Admin::get(intval($this->id));
        if (!$admin) {
            return true;
        }
        $admin->token = '';
        $admin->save();
        Session::delete("admin");
        Cookie::delete("keeplogin");
        return true;
    }

    public function check($name, $uid = '', $relation = 'or', $mode = 'url') {
        return parent::check($name, $this->id, $relation, $mode);
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return bool
     */
    public function match($arr = []) {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return FALSE;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower(Request()->action()), $arr) || in_array('*', $arr)) {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }

    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function isLogin() {
        if ($this->logined) {
            return true;
        }
        $admin = Session::get('admin');
        if (!$admin) {
            return false;
        }
        //判断是否同一时间同一账号只能在一个地方登录
        if (Config::get('jzadmin.login_unique')) {
            $my = Admin::get($admin['id']);
            if (!$my || $my['token'] != $admin['token']) {
                return false;
            }
        }
        $this->logined = true;
        return true;
    }

    /**
     * 获取当前请求的URI
     * @return string
     */
    public function getRequestUri() {
        return $this->requestUri;
    }

    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setRequestUri($uri) {
        $this->requestUri = $uri;
    }

    /**
     * 获取用户组
     *
     * @param null $uid
     * @return array
     */
    public function getGroups($uid = null) {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getGroups($uid);
    }

    /**
     * 获取规则列表
     *
     * @param null $uid
     * @return array
     */
    public function getRuleList($uid = null) {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getRuleList($uid);
    }

    /**
     * 获取用户详情
     *
     * @param null $uid
     * @return mixed|null|static
     * @throws \think\exception\DbException
     */
    public function getUserInfo($uid = null) {
        $uid = is_null($uid) ? $this->id : $uid;

        return $uid != $this->id ? Admin::get(intval($uid)) : Session::get('admin');
    }

    /**
     * 获取规则ids
     *
     * @param null $uid
     * @return array
     */
    public function getRuleIds($uid = null) {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getRuleIds($uid);
    }

    public function getAllRuleIds() {
        return parent::getRuleIds();
    }

    /**
     * 是否超级管理员
     *
     * @return bool
     */
    public function isSuperAdmin() {
        return in_array('*', $this->getRuleIds()) ? TRUE : FALSE;
    }

    /**
     * 获取管理员所属于的分组ID
     * @param int $uid
     * @return array
     */
    public function getGroupIds($uid = null) {
        $groups = $this->getGroups($uid);
        $groupIds = [];
        foreach ($groups as $K => $v) {
            $groupIds[] = (int)$v['group_id'];
        }
        return $groupIds;
    }

    /**
     * 获得面包屑导航
     * @param string $path
     * @return array
     */
    public function getBreadCrumb($path = '') {
        if ($this->breadcrumb || !$path)
            return $this->breadcrumb;
        $path_rule_id = 0;
        foreach ($this->rules as $rule) {
            $path_rule_id = $rule['name'] == $path ? $rule['id'] : $path_rule_id;
        }
        if ($path_rule_id) {
            $this->breadcrumb = Tree::instance()->init($this->rules)->getParents($path_rule_id, true);
            foreach ($this->breadcrumb as $k => &$v) {
                $v['url'] = url($v['name']);
            }
        }
        return $this->breadcrumb;
    }

    /**
     * 获取左侧菜单栏
     *
     * @param array $params URL对应的badge数据
     * @return string
     */
    public function getSidebar($params = [], $fixedPage = 'dashboard') {
        $module = request()->module();
        // 读取管理员当前拥有的权限节点
        $userRule = $this->getRuleList();
        // 必须将结果集转换为数组
        $ruleList = \app\common\model\AuthRule::where('status', '1')->where('is_menu', 1)->order('sort', 'desc')->select()->toArray();
        foreach ($ruleList as $k => &$v) {
            if (!in_array($v['name'], $userRule)) {
                unset($ruleList[$k]);
                continue;
            }
            $v['url'] = '/' . $module . '/' . $v['name'];
        }
        // 构造菜单数据
        Tree::instance()->init($ruleList);
        $menu = Tree::instance()->getTreeMenu(0);
        return $menu;
    }

    /**
     * 设置错误信息
     *
     * @param $error 错误信息
     * @return $this
     */
    public function setError($error) {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError() {
        return $this->_error ?: '';
    }

}
