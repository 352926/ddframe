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
 * @param array $db
 */
function DB($db = array()) {
    //todo P3
    return TRUE;
}

/**
 * @param string $table
 */
function load_model($table) {
    //todo P3
}

function C($key = NULL) {
    if (is_null($key)) {
        return DD::$_CFG;
    }
    return isset(DD::$_CFG[$key]) ? DD::$_CFG[$key] : NULL;
}

function load_core($class) {
    $file = __CORE__ . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        new $class();
    }
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