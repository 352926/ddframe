<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 19:35
 */

if (file_exists(__CONFIG__ . 'development.lock')) {
    define('DEBUG', TRUE);
    $_CFG = array();
    if (file_exists(__CONFIG__ . 'development/config.php')) {
        require_once __CONFIG__ . 'development/config.php';
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

class DD {
    public static $_CFG = array();
    private $_msec = NULL;
    private $db = NULL;

    public function run() {
        $this->load();
        load_core('Security');

        $c = $this->get_controller();
        $m = $this->get_module();
        $a = $this->get_action();

        if (!is_dir(__C__ . $c)) {
            sys_err('SYS_C_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . __C__ . $c);
            return;
        }

        $contoller_file = __C__ . $c . '/' . $m . '.class.php';
        if (!file_exists($contoller_file) || !is_readable($contoller_file)) {
            sys_err('SYS_M_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . $contoller_file);
            return;
        }
        require_once $contoller_file;
        $class = $m . '_controller';
        if (!class_exists($class)) {
            sys_err('SYS_M_NOT_DEFINED', 'line:' . __LINE__);
            return;
        }

        $DD = new $class();

        if (!method_exists($DD, $a)) {
            sys_err('SYS_A_NOT_DEFINED', 'line:' . __LINE__ . ' action:' . $m . '_controller->' . $a);
            return;
        }

        $DD->_SIGN = md5($c . $m . $a . uniqid(microtime(TRUE)));
        $DD->_C = $c;
        $DD->_M = $m;
        $DD->_A = $a;
        $this->logging($DD, "start {$c}/{$m}->{$a}()");
        if (method_exists($DD, 'init')) {
            $DD->init();
            $this->logging($DD, 'init');
        }

        $this->logging($DD, "run {$a}");
        $DD->$a();
        $this->logging($DD, "end {$a}");

        //todo Hook P7

    }

    private function get_controller() {
        $c = _get('c', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $c ? $c : C('default_controller');
    }

    private function get_module() {
        $m = _get('m', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $m ? $m : C('default_module');
    }

    private function get_action() {
        $a = _get('a', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return $a ? $a : C('default_action');
    }

    private function load() {
        $_CFG = array();
        require_once __CORE__ . 'Config.php';
        require_once __CONFIG__ . 'config.php';
        require_once __CORE__ . 'Helper.php';
        require_once __CORE__ . 'Controller.php';
        if (DEBUG) {
            require __CONFIG__ . 'development/config.php';
        }
        self::$_CFG = $_CFG;
        if (C('load_db')) {
            $this->db = DB();
        }

        $init_set = C('INI_SET');
        if (is_array($init_set)) {
            foreach ($init_set as $varname => $newvalue) {
                @ini_set($varname, $newvalue);
            }
        }

        $charset = C('charset');
        if ($charset && function_exists('mb_internal_encoding')) {
            if (!mb_internal_encoding($charset)) {
                sys_err('SYS_SET_CHARSET_FAILED', 'line:' . __LINE__ . ',charset:' . $charset);
                return;
            }
        }

        define('__C__', __APP__ . C('controller'));
    }

    private function logging(&$DD, $value) {
        if ($DD->log === TRUE) {
            $DD->logging($value . ' use:' . $this->usetime() . 's');
        }
    }

    private function usetime() { #TODO test usetime
        if (is_null($this->_msec)) {
            $this->_msec = __MT__;
        } else {
            $this->_msec = microtime(TRUE);
        }
        return sprintf('%.6f', microtime(TRUE) - $this->_msec);
    }

}

define('__TIME__', time());
define('VERSION', '1.0');

$dd = new DD();
$dd->run();