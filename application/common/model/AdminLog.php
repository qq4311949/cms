<?php

namespace app\common\model;

use app\common\library\Auth;

class AdminLog extends Base {

    //自定义日志标题
    protected static $title = '';
    //自定义日志内容
    protected static $content = '';

    public static function setTitle($title) {
        self::$title = $title;
    }

    public static function setContent($content) {
        self::$content = $content;
    }

    public static function record($title = '') {
        $auth = Auth::instance();
        $admin_id = $auth->isLogin() ? $auth->id : 0;
        $username = $auth->isLogin() ? $auth->username : '未知';
        $content = self::$content;
        if (!$content) {
            $content = request()->param();
            foreach ($content as $k => $v) {
                if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
                    unset($content[$k]);
                }
            }
        }
        $title = self::$title;
        if (!$title) {
            $title = [];
            $breadcrumb = Auth::instance()->getBreadcrumb();
            foreach ($breadcrumb as $k => $v) {
                $title[] = $v['title'];
            }
            $title = implode(' ', $title);
        }
        self::create([
            'title' => $title,
            'content' => !is_scalar($content) ? json_encode($content) : $content,
            'url' => request()->url(),
            'admin_id' => $admin_id,
            'username' => $username,
            'useragent' => request()->server('HTTP_USER_AGENT'),
            'ip' => ip2long(request()->ip())
        ]);
    }

    public function admin() {
        return $this->belongsTo('Admin', 'admin_id')->setEagerlyType(0);
    }

}
