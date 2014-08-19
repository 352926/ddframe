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
        $hooks = get_value($this->config, $name);
        if (!is_array($hooks)) {
            return;
        }

        foreach ($hooks as $hook) {
            $file = get_value($hook, 'file');

            if (!$file) {
                continue;
            }

            $path = get_value($hook, 'path');
            $path = $path ? $path . '/' : '';
            $file = __APP__ . 'hooks/' . $path . '/' . $file . '.php';

            if (!check_file($file)) {
                continue;
            }

            require_once $file;

            $class = get_value($hook, 'class');
            if (!$class || !class_exists($class)) {
                continue;
            }

            $cls = new $class($this->DD);

            $fn = get_value($hook, 'function');
            if ($fn) {
                $cls->$fn();
            }
        }
    }
}