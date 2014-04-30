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

class DD {
    public static $_CFG = array();
    public static $_C = NULL;
    public static $_M = NULL;
    public static $_A = NULL;
    public static $DB = NULL;
    public static $log = NULL;
    public static $logged = array();
    public static $DD = NULL;
    public static $_ACTION = NULL;
    public static $_msec = NULL;

    public function run() {
        $this->load();
        load_lib('Security', TRUE);

        self::$_C = $this->get_controller();;
        self::$_M = $this->get_module();
        self::$_A = $this->get_action();

        if (!is_dir(__C__ . self::$_C)) {
            sys_err('SYS_C_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . __C__ . self::$_C);
            return;
        }

        $contoller_file = __C__ . self::$_C . '/' . self::$_M . '.class.php';
        if (!file_exists($contoller_file) || !is_readable($contoller_file)) {
            sys_err('SYS_M_NOT_EXISTS', 'line:' . __LINE__ . ',file:' . $contoller_file);
            return;
        }

        require_once $contoller_file;
        $class = self::$_M . '_controller';
        if (!class_exists($class)) {
            sys_err('SYS_M_NOT_DEFINED', 'line:' . __LINE__);
            return;
        }

        $DD = new $class();
        self::$DD = $DD;

        self::$_msec = microtime(TRUE);

        if (!method_exists($DD, self::$_A)) {
            sys_err('SYS_A_NOT_DEFINED', 'line:' . __LINE__ . ' action:' . self::$_M . '_controller->' . self::$_A);
            return;
        }

        $DD->_SIGN = md5(self::$_C . self::$_M . self::$_A . uniqid(microtime(TRUE)));

        self::log("start " . __SAPI__ . " {$_SERVER['REQUEST_METHOD']} {$_SERVER['HTTP_HOST']} url:{$_SERVER['QUERY_STRING']}", 'SYS');

        if (method_exists($DD, 'init')) {
            self::log('doing ' . self::$_M . '->init()', 'SYS');
            $DD->init();
        }

        self::log('doing ' . self::$_A, 'SYS');
        $DD->{self::$_A}();

        if ($DD->Output->get_format() == 'json') {
            echo json_encode($DD->Output->get_content());
            return;
        }

        //todo layout P6

        //todo Hook P7
        self::log('done', 'SYS');
    }

    private function load() {
        $_CFG = array();
        require_once __CORE__ . 'Config.php';
        require_once __CONFIG__ . 'config.php';
        require_once __CORE__ . 'Helper.php';
        require_once __CORE__ . 'Controller.php';
        require_once __CORE__ . 'Model.php';
        if (DEBUG) {
            require __CONFIG__ . 'development/config.php';
        }
        self::$_CFG = $_CFG;

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
        ob_start('force_filter');
        define('__SAPI__', strtoupper(php_sapi_name()));
        define('__C__', __APP__ . C('controller'));
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

    /**
     * @param $msg
     * @param string $level = 'INFO' array('DEBUG','INFO','NOTICE','ERROR')
     */
    public static function log($msg, $level = 'INFO') {
        $name = DD::$_M . '.' . DD::$_A;
        $config = C('log');
        $sapi = __SAPI__ == 'CLI' ? 'CLI' : 'WEB';

        if (!in_array(strtoupper($level), $config['level'])) {
            return;
        }

        if (!is_object(DD::$log)) {
            load_lib('Log');
            DD::$log = new Log($config['path'] . date('Ymd') . '/' . DD::$_C . '/', $config['type'], $config['time']);
        }

        if (!in_array($sapi, $config['start']) || !property_exists(self::$DD, 'start_log') || !self::$DD->start_log) {
            DD::$logged[] = DD::$log->loger($msg, $name, $level, TRUE);
            return;
        }

        if (!empty(DD::$logged)) {
            DD::$log->err_log(implode(PHP_EOL, DD::$logged), $name);
            DD::$logged = array();
        }
        DD::$log->loger($msg, $name, $level);
    }

    private function usetime() {
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