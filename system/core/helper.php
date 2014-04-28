<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:03
 */

function _get($name, $strtr = array()) {
    if (isset($_GET[$name]) && is_array($strtr) && !empty($strtr)) {
        $_GET[$name] = strtr($_GET[$name], $strtr);
    }
    return isset($_GET[$name]) ? trim($_GET[$name]) : NULL;
}

function _post($name, $strtr = array()) {
    if (isset($_POST[$name]) && is_array($strtr) && !empty($strtr)) {
        $_POST[$name] = strtr($_POST[$name], $strtr);
    }
    return isset($_POST[$name]) ? trim($_POST[$name]) : NULL;
}

function not_found() {
    exit('404');
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
    if (!is_array($config) || empty($config)) {
        $config = C('database');
        if (!is_null(DD::$DB)) {
            return DD::$DB;
        }
    }
    load_lib('Database');
    return new Database([
        'database_type' => $config['type'],
        'database_name' => $config['database'],
        'server' => $config['host'],
        'username' => $config['user'],
        'password' => $config['pass'],
        'port' => $config['port'],
        'charset' => $config['charset'],
    ]);
}

function C($key = NULL) {
    if (is_null($key)) {
        return DD::$_CFG;
    }
    return isset(DD::$_CFG[$key]) ? DD::$_CFG[$key] : NULL;
}

function load_model($model = array()) {
    if (empty($model)) {
        return FALSE;
    }
    if (is_string($model)) {
        $model = array($model);
    }
    foreach ($model as $m) {
        $m = strtolower($m);
        $file = __APP__ . 'model/' . $m . '.php'; #exit($file);
        if (!class_exists($m) && check_file($file)) {
            require_once $file;
        } else {
            return FALSE;
        }
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
    $file = __SYSTEM__ . 'lib/' . $class . '.php';
    if (!class_exists($class) && check_file($file)) {
        require_once $file;
        if ($init) {
            return new $class;
        }
        return TRUE;
    }
    return NULL;
}

function load_config($name) {
    $file = __CONFIG__ . $name . '.php';
    if (is_null(C($name)) && check_file($file)) {
        DD::$_CFG[$name] = require $file;
    }
    return C($name);
}

function check_file($file) {
    return $file == realpath($file);
}

function encrypt($str) {
    $config = load_config('encrypt');
    if (!class_exists('Encrypt')) {
        load_lib('Encrypt');
    }

    return Encrypt::encrypt_string($str, $config['iv'], $config['key']);
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

function setColor($str, $color = "red") {
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