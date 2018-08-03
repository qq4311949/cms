<?php

namespace com;

use think\facade\Config;

/**
 * 通用的树型类
 * @author XiaoYao <476552238li@gmail.com>
 */
class Tree {

    protected static $instance;
    //默认配置
    protected $config = [];
    public $options = [];

    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = [];

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('│', '├', '└');
    public $nbsp = "&nbsp;";
    public $pidname = 'pid';

    public function __construct($options = []) {
        if ($config = Config::get('tree')) {
            $this->options = array_merge($this->config, $config);
        }
        $this->options = array_merge($this->config, $options);
    }

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return Tree
     */
    public static function instance($options = []) {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 初始化方法
     * @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','pid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','pid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','pid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','pid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','pid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','pid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','pid'=>3,'name'=>'三级栏目二')
     *      )
     */
    public function init($arr = [], $pidname = NULL, $nbsp = NULL) {
        $this->arr = $arr;
        if (!is_null($pidname))
            $this->pidname = $pidname;
        if (!is_null($nbsp))
            $this->nbsp = $nbsp;
        return $this;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function getChild($myid) {
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id']))
                continue;
            if ($value[$this->pidname] == $myid)
                $newarr[$value['id']] = $value;
        }
        return $newarr;
    }

    /**
     *
     * 获取树状数组
     * @param string $myid 要查询的ID
     * @param string $itemprefix 前缀
     * @return array
     */
    public function getTreeArray($myid, $itemprefix = '') {
        $childs = $this->getChild($myid);
        $n = 0;
        $data = [];
        $number = 1;
        if ($childs) {
            $total = count($childs);
            foreach ($childs as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $spacer = $itemprefix ? $itemprefix . $j : '';
                $value['spacer'] = $spacer;
                $data[$n] = $value;
                $data[$n]['childlist'] = $this->getTreeArray($id, $itemprefix . $k . $this->nbsp);
                $n++;
                $number++;
            }
        }
        return $data;
    }

    /**
     * 得到当前位置所有父辈数组
     * @param int
     * @return array
     */
    public function getParents($myid, $withself = FALSE) {
        $pid = 0;
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id']))
                continue;
            if ($value['id'] == $myid) {
                if ($withself) {
                    $newarr[] = $value;
                }
                $pid = $value[$this->pidname];
                break;
            }
        }
        if ($pid) {
            $arr = $this->getParents($pid, TRUE);
            $newarr = array_merge($arr, $newarr);
        }
        return $newarr;
    }

    /**
     * 将getTreeArray的结果返回为二维数组
     * @param array $data
     * @param string $field
     * @return array
     */
    public function getTreeList($data = [], $field = 'name') {
        $arr = [];
        foreach ($data as $k => $v) {
            $childlist = isset($v['childlist']) ? $v['childlist'] : [];
            unset($v['childlist']);
            $v[$field] = $v['spacer'] . ' ' . $v[$field];
            $v['haschild'] = $childlist ? 1 : 0;
            if ($v['id'])
                $arr[] = $v;
            if ($childlist) {
                $arr = array_merge($arr, $this->getTreeList($childlist, $field));
            }
        }
        return $arr;
    }

    /**
     * 菜单数据
     * @param int $myid
     * @return string
     */
    public function getTreeMenu($myid) {
        $str = '';
        $items = $this->getTreeArray($myid);
        if ($items) {
            foreach ($items as $item) {
                $pathinfo = strpos(request()->pathinfo(), '/') === false ? request()->pathinfo() . '/' . strtolower(Config('app.default_controller')) : request()->pathinfo();
                if (count($item['childlist']) == 0) {
                    $active = strpos($item['url'], $pathinfo) == 1 ? 'active' : '';
                    $str .= '<li class="tpl-left-nav-item">';
                    $str .=     '<a href="'. $item['url'] .'" class="nav-link '.$active.'">';
                    $str .=         '<i class="'. $item['icon'] .'"></i>';
                    $str .=         '<span>'. $item['title'] .'</span>';
                    $str .=     '</a>';
                    $str .= '</li>';
                } else {
                    $str .= '<li class="tpl-left-nav-item">';
                    $str .=     '<a href="javascript:;" class="nav-link tpl-left-nav-link-list">';
                    $str .=         '<i class="'. $item['icon'] .'"></i>';
                    $str .=         '<span>'. $item['title'] .'</span>';
                    $str .=         '<i class="am-icon-angle-right tpl-left-nav-more-ico am-fr am-margin-right"></i>';
                    $str .=     '</a>';

                    $_str = $style = '';
                    foreach ($item['childlist'] as $arr) {
                        $active = '';
                        if (strpos($arr['url'], $pathinfo) == 1) {
                            $active = 'active';
                            $style = 'style="display:block"';
                        }
                        $_str .=     '<li>';
                        $_str .=         '<a href="'. $arr['url'] .'" class="'. $active .'">';
                        $_str .=             '<i class="'. $arr['icon'] .'"></i>';
                        $_str .=             '<span>'. $arr['title'] .'</span>';
                        $_str .=         '</a>';
                        $_str .=     '</li>';
                    }

                    $str .=     '<ul class="tpl-left-nav-sub-menu" '.$style.'>';
                    $str .=         $_str;
                    $str .=     '</ul>';
                    $str .= '</li>';
                }
            }
        }
        return $str;
    }

}
