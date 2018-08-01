<?php
// +----------------------------------------------------------------------
// | WebService 
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://www.ucaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Mr.Maybe <260591808@qq.com> <http://www.ucaijia.com>
// +----------------------------------------------------------------------

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;
use think\facade\Env;

class Skeleton extends Command {

    protected $stubList = [];

    protected $module = null;
    protected $directory = null;
    protected $controller = null;
    protected $table = null;

    protected function configure() {
        $this
            ->setName('skeleton')
            ->addOption('module', 'm', Option::VALUE_REQUIRED, 'module name', null)
            ->addOption('directory', 'd', Option::VALUE_REQUIRED, 'directory name', null)
            ->addOption('controller', 'c', Option::VALUE_REQUIRED, 'controller name', null)
            ->addOption('table', 't', Option::VALUE_REQUIRED, 'table name', null)
            ->setDescription('create skeleton by rule');
    }

    protected function execute(Input $input, Output $output) {
        // 模块名
        $this->module = $input->getOption('module') ?: '';
        // 多级控制器目录名
        $this->directory = $input->getOption('directory') ?: '';
        // 控制器名
        $this->controller = $input->getOption('controller');
        // 表明
        $this->table = $input->getOption('table') ?: '';

        $appNamespace = Env::get('APP_NAMESPACE');

        $data['controllerNamespace'] = "{$appNamespace}\\{$this->module}\\" . Config::get('url_controller_layer') . '\\' . ($this->directory ?: '');
        $data['controllerName'] = ucfirst($this->controller);

        $data['modelNamespace'] = "{$appNamespace}\\common\\model";
        $data['modelName'] = ucfirst($this->table);
        $data['observerClass'] = "{$appNamespace}\\{$this->module}\\event\\" . ($this->directory ? $this->directory . '\\' : '') . $data['controllerName'];

        $data['validateNamespace'] = "{$appNamespace}\\{$this->module}\\validate\\" . ($this->directory ?: '');
        $data['validateName'] = $data['controllerName'];

        $data['eventNamespace'] = "{$appNamespace}\\{$this->module}\\event\\" . ($this->directory ?: '');
        $data['eventName'] = $data['controllerName'];

        $controllerFile = APP_PATH . $this->module . '/' . Config::get('url_controller_layer') . '/' . ($this->directory ? $this->directory . '/' : '') . $data['controllerName'] . '.php';
        $this->writeToFile('controller', $data, $controllerFile);

        $modelFile = APP_PATH . 'common/model/' . $data['modelName'] . '.php';
        $this->writeToFile('model', $data, $modelFile);

        $validateFile = APP_PATH . $this->module . '/validate/' . ($this->directory ? $this->directory . '/' : '') . $data['controllerName'] . '.php';
        $this->writeToFile('validate', $data, $validateFile);

        $eventFile = APP_PATH . $this->module . '/event/' . ($this->directory ? $this->directory . '/' : '') . $data['controllerName'] . '.php';
        $this->writeToFile('event', $data, $eventFile);

        $htmlFile = APP_PATH . $this->module . '/view/' . ($this->directory ? $this->directory . '/' : '') . strtolower($this->controller) . '/index.html';
        $this->writeToFile('html/index', $data, $htmlFile);

        $htmlFile = APP_PATH . $this->module . '/view/' . ($this->directory ? $this->directory . '/' : '') . strtolower($this->controller) . '/add.html';
        $this->writeToFile('html/add', $data, $htmlFile);

        $htmlFile = APP_PATH . $this->module . '/view/' . ($this->directory ? $this->directory . '/' : '') . strtolower($this->controller) . '/edit.html';
        $this->writeToFile('html/edit', $data, $htmlFile);

        $output->info('skeleton success');
    }

    protected function checkName($var) {
        if (!preg_match('/^[A-Za-z]+$/', $var)) {
            return false;
        }
        return true;
    }

    /**
     * 获取基础模板
     * @param string $name
     * @return string
     */
    protected function getStub($name) {
        return __DIR__ . '/Skeleton/stubs/' . $name . '.stub';
    }

    /**
     * 写入到文件
     * @param string $name
     * @param array $data
     * @param string $pathname
     * @return mixed
     */
    protected function writeToFile($name, $data, $pathname) {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $content = $this->getReplacedStub($name, $data);

        if (!is_dir(dirname($pathname))) {
            mkdir(strtolower(dirname($pathname)), 0755, true);
        }
        return file_put_contents($pathname, $content);
    }

    /**
     * 获取替换后的数据
     * @param string $name
     * @param array $data
     * @return string
     */
    protected function getReplacedStub($name, $data) {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $search = $replace = [];
        foreach ($data as $k => $v) {
            $search[] = "{%{$k}%}";
            $replace[] = $v;
        }
        $stubname = $this->getStub($name);
        if (isset($this->stubList[$stubname])) {
            $stub = $this->stubList[$stubname];
        } else {
            $this->stubList[$stubname] = $stub = file_get_contents($stubname);
        }
        $content = str_replace($search, $replace, $stub);
        return $content;
    }

}