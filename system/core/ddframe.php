<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 19:35
 */

if (file_exists(__ROOT__ . 'development.lock')) {
    define('DEBUG', TRUE);
    $_CFG = array();
    if (file_exists(__ROOT__ . 'config/development/config.php')) {
        require_once __ROOT__ . 'config/development/config.php';
    }
    $_DEV_CFG = $_CFG;
    $xdebug_trace = isset($_DEV_CFG['xdebug_trace']) ? $_DEV_CFG['xdebug_trace'] : FALSE;

    if (defined('DEBUG') && $xdebug_trace && function_exists('xdebug_start_trace')) {
        xdebug_start_trace();
        register_shutdown_function(function () {
                $file = xdebug_stop_trace();
                print_r("<hr><font color=red>filename:{$file}</font><br>" . str_replace("\n", "<BR>", file_get_contents($file)));
            }
        );
    }
}

class ddframe {
    public $_CFG = array();

    public function run() {
        $this->load();
        $c = $this->get_controller();
        $m = $this->get_module();
        $a = $this->get_action();

        //

    }

    private function get_controller() {
        $c = _get('c', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $c ? $c : $this->_CFG['default_controller'];
    }

    private function get_module() {
        $m = _get('m', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $m ? $m : $this->_CFG['default_module'];
    }

    private function get_action() {
        $a = _get('a', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $a ? $a : $this->_CFG['default_action'];
    }

    private function load() {
        $_SYS_CFG = array();
        $_CFG = array();
        require_once __SYSTEM__ . 'core/config.php';
        require_once __ROOT__ . 'config/config.php';
        require_once __SYSTEM__ . 'core/helper.php';
        $this->_CFG = array_merge($_SYS_CFG, $_CFG);
    }

    public function config($name) {
        return isset($this->_CFG[$name]) ? $this->_CFG[$name] : '';
    }
}

$dd = new ddframe();
$dd->run();