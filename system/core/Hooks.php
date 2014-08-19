<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-8-19
 * Time: 17:15
 */

defined('VERSION') or exit('Forbidden');

class Hooks {
    private $config;
    private $DD;

    public function __construct() {
        $this->config = load_config('hooks');
        $this->DD = & get_instance();
    }

    public function load($name) {
        $hook = get_value($this->config, $name);
        if (!$hook) {
            return;
        }

        $file = get_value($hook, 'file');
        if (!$file) {
            return;
        }

        $path = get_value($hook, 'path');
        $path = $path ? $path . '/' : '';
        $file = __APP__ . 'hooks/' . $path . '/' . $file . '.php';

        if (!check_file($file)) {
            return;
        }

        require_once $file;

        $class = get_value($hook, 'class');
        if (!$class || !class_exists($class)) {
            return;
        }

        $cls = new $class($this->DD);

        $fn = get_value($hook, 'function');
        if ($fn) {
            $cls->$fn();
        }
    }
}