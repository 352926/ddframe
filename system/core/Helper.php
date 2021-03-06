<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:03
 */

function _get($name, $xss = TRUE) {
    return chk_val($_GET, $name, $xss);
}

function _post($name, $xss = TRUE) {
    return chk_val($_POST, $name, $xss);
}

function cookie($name, $xss = TRUE) {
    return chk_val($_COOKIE, $name, $xss);
}

function chk_val($array, $name, $xss = TRUE) {
    if ($xss) {
        $result = isset($array[$name]) ? Security::xss_clean($array[$name]) : NULL;
    } else {
        $result = isset($array[$name]) ? trim($array[$name]) : NULL;
    }
    return $result;
}

function is_post() {
    return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

function force_filter($buffer) {
    return C('force_filter') ? strtr($buffer, C('force_filter')) : $buffer;
}

function show_404() {
    not_found();
}

function not_found() {
    define('__404__', TRUE);
    if (__SAPI__ == 'CLI') {
        exit("not_found\n");
    }
    load_lib('Output');
    $site = C('site');
    $output = new Output();
    $output->code = 404;
    $output->set_header();
    $output->sitename = $site['name'];
    $output->delimiter = $site['delimiter'];
    $output->system = TRUE;
    $output->display('404', '', 'default');
    exit();
}

function show_error($msg) {
    define('__ERR__', TRUE);
    if (__SAPI__ == 'CLI') {
        echo $msg;
        exit("\n");
    }
    load_lib('Output');
    $site = C('site');
    $output = new Output();
    $output->code = 404;
    $output->set_header();
    $output->sitename = $site['name'];
    $output->delimiter = $site['delimiter'];
    $output->set_title('系统错误');
    $output->system = TRUE;
    $output->put('msg', $msg);
    $output->display('Error');
    exit();
}

function sys_err($name, $msg) {
    if (!class_exists('Error')) {
        require_once __CORE__ . 'Error.php';
    }
    //todo not debug mode log info P4
    Error::show($name, $msg, 500);
}

/**
 * 默认载入第一个配置好的DB
 * @param array $config
 */
function DB($config = array()) {
    $DD = &get_instance();
    if (!is_array($config) || empty($config)) {
        $config = C('database');
        if (!is_null($DD->DB)) {
            return $DD->DB;
        }
    }
    load_lib('Database');
    return new Database(array(
            'database_type' => $config['type'],
            'database_name' => $config['database'],
            'server' => $config['host'],
            'username' => $config['user'],
            'password' => $config['pass'],
            'port' => $config['port'],
            'charset' => $config['charset'],
        )
    );
}

function C($key = NULL) {
    $DD = &get_instance();
    if (is_null($key)) {
        return $DD->_CFG;
    }
    return isset($DD->_CFG[$key]) ? $DD->_CFG[$key] : NULL;
}

function load_model($model) {
    if (empty($model)) {
        return FALSE;
    }

    $model = strtolower($model);
    $file = __APP__ . 'models/' . $model . '.php';
    if (!class_exists($model) && check_file($file)) {
        require_once $file;
    } else { #todo system error
        return FALSE;
    }
    return TRUE;
}

function load_core($class, $init = FALSE) {
    $file = __CORE__ . $class . '.php';
    if (!class_exists($class) && check_file($file)) {
        require_once $file;
        if ($init) {
            return new $class();
        }
        return TRUE;
    }
    return NULL;
}

function load_lib($class, $init = FALSE) {
    $file = __APP__ . 'lib/' . $class . '.php'; #优先级先查找 APP 目录下
    if (!class_exists($class) && check_file($file)) {
        require_once $file;
        if ($init) {
            return new $class;
        }
        return TRUE;
    }
    $file = __SYSTEM__ . 'lib/' . $class . '.php'; #再查找 SYSTEM 目录
    if (!class_exists($class) && check_file($file)) {
        require_once $file;
        if ($init) {
            return new $class;
        }
        return TRUE;
    }
    return FALSE;
}

function load_helper($name) {
    $file = __APP__ . 'helper/' . $name . '.php';
    if (check_file($file)) {
        require_once $file;
        return TRUE;
    }
    return FALSE;
}

function Model($table = '') {
    $table = strtolower($table);

    if (empty($table)) {
        return new DD_Model();
    }
    if (class_exists($table)) {
        $table = ucfirst($table);
        return new $table();
    }

    return load_model($table);
}

function M($model = '') {
    return Model($model);
}

function load_config($name) {
    $file = __CONFIG__ . $name . '.php';
    if (DEBUG && file_exists(__CONFIG__ . 'development/' . $name . '.php')) {
        $file = __CONFIG__ . 'development/' . $name . '.php';
    }
    if (is_null(C($name)) && check_file($file)) {
        get_instance()->_CFG[$name] = require $file;
    }
    return C($name);
}

function set_cookie($name = '', $value = '', $expire = 86400, $domain = '', $path = '/', $prefix = '', $secure = FALSE) {
    $expire = time() + $expire;

    if (!$domain) {
        $domain = C('cookie_domain');
    }
    if (!$prefix) {
        $prefix = C('cookie_prefix');
    }
    if (!$path) {
        $path = C('cookie_path');
    }
    if (!$secure) {
        $secure = C('cookie_secure');
    }

    return setcookie($prefix . $name, $value, $expire, $path, $domain, $secure);
}

function check_file($file) {
    return file_exists($file);
//    return $file == realpath($file);
}

function encrypt($str) {
    $config = load_config('encrypt');
    if (!class_exists('Encrypt')) {
        load_lib('Encrypt');
    }

    return Encrypt::encrypt_string($str, $config['iv'], $config['key']);
}

function decrypt($str) {
    $encrypt = load_config('encrypt');
    load_lib('Encrypt');
    return Encrypt::decrypt_string($str, $encrypt['iv'], $encrypt['key']);
}

function get_random($size = 4, $type = 'all') {
    $type = strtolower($type);
    $allow = array(
        'num', #数字
        'char', #a-zA-Z
        'all',
    );
    if (!in_array($type, $allow)) {
        $type = 'all';
    }
    switch ($type) {
        case 'num':
            $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
            break;
        case 'char':
            $array = array_map('chr', array_merge(range(65, 90), range(97, 122)));
            break;
        case 'all':
        default:
            $array = array_map('chr', array_merge(range(48, 57), range(65, 90), range(97, 122)));
            break;
    }
    $i = 0;
    $count = count($array);
    $random_str = '';
    while ($i++ < $size) {
        $random_str .= $array[mt_rand(0, $count - 1)];
    }
    return $random_str;
}

/**
 * 转义
 * 直接调用避免以非变量形式，因为非变量不可传址，例： echo secure_value("'xx'");
 * 用以 array_walk_recursive 替换成 stripslashesForArray
 * @param string(var) $val
 * @return string
 */
function secure_value(&$val) {
    #return 返回为兼容一维数组时使用array_map，或常规使用
    return $val = addslashes($val);
}

/**
 * 打印数组，支持传入多个数组同时打印
 * @param $array
 */
function dump($array) {
    $args = func_get_args();
    $cli = FALSE;
    if ('cli' == strtolower(php_sapi_name())) {
        $cli = TRUE;
    }
    if (isset($args[1])) {
        echo $cli ? '' : '<pre>';
        foreach ($args as $arg) {
            echo print_r($arg, TRUE);
        }
        echo $cli ? '' : '</pre>';
    } else {
        echo $cli ? print_r($array, TRUE) : '<pre>' . print_r($array, TRUE) . '</pre>';
    }
    exit(PHP_EOL);
}

function redirect($url) {
    header("Location: " . $url, TRUE, 302);
    exit;
}

function set_color($str, $color = "red") {
    $c = array(
        'red' => '31',
        'green' => '32',
        'yellow' => '34',
        'blue' => '35',
    );
    if (!isset($c[$color])) {
        return $str;
    }
    return chr(27) . "[{$c[$color]};1m" . $str . chr(27) . "[0m";
}

function get_value($array, $key, $default = FALSE) {
    return isset($array[$key]) ? $array[$key] : $default;
}

function log_debug($msg) {
    if (__SAPI__ == 'CLI') {
        echo $msg . PHP_EOL;
    } else {
    }
}

//todo
function log_info($msg) {
}

function log_notice($msg) {
}

function log_error($msg) {
}

function &get_instance() {
    return DD::get_instance();
}