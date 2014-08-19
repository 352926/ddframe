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
                $trace = file_get_contents($file);
                if (strtoupper(php_sapi_name()) == 'CLI') {
                    echo PHP_EOL;
                    return;
                }
                print_r("<hr><font color=red>filename:{$file}</font><br>" . str_replace("\n", "<BR>", $trace));
            }
        );
    }
}

if (!defined('DEBUG')) {
    define('DEBUG', FALSE);
}

class DD {
    public $_CFG = array();
    public $_C, $_M, $_A, $DB, $logs, $app, $_msec, $csrf_hash;
    private static $instance;

    public function __construct() {
        self::$instance = & $this;
    }

    public static function & get_instance() {
        return self::$instance;
    }

    public function run() {
        $this->load();
        $hooks = new Hooks();
        $hooks->load('start');

        $ss = load_lib('Security', TRUE);
        if (!$ss) {
            if (DEBUG) {
                exit('SYS_SECURITY_NOT_LOAD:' . 'line:' . __LINE__ . ',file:' . __C__ . $this->_C);
            } else {
                exit('notfind');
            }
        }

        $this->csrf_hash = $ss->get_csrf_hash();

        $this->_C = $this->get_controller();
        $this->_M = $this->get_module();
        $this->_A = $this->get_action();
        $argv = array();
        if (__SAPI__ == 'CLI') {
            global $argv;
            $this->_C = 'task';
            if (isset($argv[1])) {
                substr_count($argv[1], '/');
                list($this->_M, $this->_A, $param) = explode('/', $argv[1] . '//', 3);
                $this->_M = empty($this->_M) ? 'index' : $this->_M;
                $this->_A = empty($this->_A) ? 'index' : $this->_A;

                $param = trim($param, '/');
                parse_str($param, $_GET);
            }
        }

        $URI = isset($_SERVER['REQUEST_URI']) ? pathinfo($_SERVER['REQUEST_URI']) : FALSE;
        if ($this->_CFG['URI']['hide_php'] && isset($URI['extension']) && substr($URI['extension'], 0, 3) == 'php') {
            not_found();
        }

        if (!is_dir(__C__ . $this->_C)) {
            if (DEBUG) {
                show_error('SYS_C_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . __C__ . $this->_C);
            } else {
                not_found();
            }
            return;
        }

        $contoller_file = __C__ . $this->_C . '/' . $this->_M . '.class.php';
        if (!file_exists($contoller_file) || !is_readable($contoller_file)) {
            if (DEBUG) {
                show_error('SYS_M_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . $contoller_file);
            } else {
                not_found();
            }
            return;
        }

        require_once $contoller_file;
        $class = $this->_M . '_controller';
        if (!class_exists($class)) {
            if (DEBUG) {
                show_error('SYS_M_NOT_DEFINED', 'line:' . __LINE__);
            } else {
                not_found();
            }
            return;
        }

        $app = new $class();
        $this->app = $app;

        $this->_msec = microtime(TRUE);

        if (!method_exists($app, $this->_A)) {
            if (DEBUG) {
                show_error('SYS_A_NOT_DEFINED', 'line:' . __LINE__ . ' action:' . $this->_M . '_controller->' . $this->_A);
            } else {
                not_found();
            }
            return;
        }

        $app->_SIGN = md5($this->_C . $this->_M . $this->_A . uniqid(microtime(TRUE)));

        $method = isset($_SERVER['REQUEST_METHOD']) ? ' ' . $_SERVER['REQUEST_METHOD'] : '';
        $host = isset($_SERVER['HTTP_HOST']) ? ' ' . $_SERVER['HTTP_HOST'] : '';
        if (__SAPI__ == 'CLI') {
            $this->logger('start', __SAPI__ . ' ' . implode(" ", $argv));
        } else {
            $query_string = isset($_SERVER['QUERY_STRING']) ? ' ' . $_SERVER['QUERY_STRING'] : '';
            $this->logger('start', __SAPI__ . "{$method}{$host} {$query_string}");
        }

        if (method_exists($app, 'init')) {
            $hooks->load('init');
            $this->logger('init', $this->_M . '->init()');
            $app->init();
        }

        $hooks->load('begin');
        $this->logger('begin', $this->_A . '()');

        $app->{$this->_A}();

        $hooks->load('end');
        $this->logger('end', $this->_A . '()');

        if (__SAPI__ == 'CLI') {
            return;
        }

        $format = $app->Output->get_format();
        $app->Output->set_header();
        if ($format == 'json') {
            ob_clean();
            echo json_encode($app->Output->get_content());
        } elseif ($format == 'html') {
            $view = C('views');
            if ($view['auto']) {
                $app->display($this->_A);
            }
        }

        $hooks->load('done');
        $this->logger('done', 'SYS');
    }

    private function load() {
        define('__SAPI__', strtoupper(php_sapi_name()));
        $_CFG = array();
        require_once __CORE__ . 'Config.php';
        require_once __CONFIG__ . 'config.php';
        require_once __CORE__ . 'Helper.php';
        require_once __CORE__ . 'Controller.php';
        require_once __CORE__ . 'Model.php';
        require_once __CORE__ . 'Error.php';
        require_once __CORE__ . 'Hooks.php';
        if (__SAPI__ == 'CLI') {
            require_once __CORE__ . 'Task.php';
        }
        if (DEBUG) {
            require __CONFIG__ . 'development/config.php';
        }
        $this->_CFG = $_CFG;

        $init_set = C('INI_SET');
        if (is_array($init_set)) {
            foreach ($init_set as $varname => $newvalue) {
                @ini_set($varname, $newvalue);
            }
        }

        $charset = C('charset');
        if ($charset && function_exists('mb_internal_encoding')) {
            if (!mb_internal_encoding($charset)) {
                if (DEBUG) {
                    show_error('SYS_SET_CHARSET_FAILED', 'line:' . __LINE__ . ',charset:' . $charset);
                } else {
                    not_found();
                }
                return;
            }
        }
        if (__SAPI__ != 'CLI') {
            ob_start('force_filter');
        }
        define('__C__', __APP__ . C('controller'));

        if (!empty($this->_CFG['load_helper'])) {
            $load_rs = array_map('load_helper', $this->_CFG['load_helper']);
            if (count($this->_CFG['load_helper']) != array_sum($load_rs)) {
                if (DEBUG) {
                    show_error('NOT_LOAD_HELPER', 'line:' . __LINE__ . ' action:' . $this->_M . '_controller->' . $this->_A);
                } else {
                    not_found();
                }
                return;
            }
        }
        if (!empty($this->_CFG['load_lib'])) {
            $load_rs = array_map('load_lib', $this->_CFG['load_lib']);
            if (count($this->_CFG['load_lib']) != array_sum($load_rs)) {
                if (DEBUG) {
                    show_error('NOT_LOAD_LIB', 'line:' . __LINE__ . ' action:' . $this->_M . '_controller->' . $this->_A);
                } else {
                    not_found();
                }
                return;
            }
        }
    }

    private function get_controller() {
        $c = _get('c', array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        $_GET['c'] = $c;
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
        $_GET['m'] = $m;
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
        $_GET['a'] = $a;
        return $a ? $a : C('default_action');
    }

    /**
     * @param $status = start|init|begin|end|done
     * @param $msg
     */
    public function logger($status, $msg) {
        $this->logs[$status] = $msg;
    }

}

define('__TIME__', time());
define('VERSION', '1.1');

$dd = new DD();
$dd->run();
