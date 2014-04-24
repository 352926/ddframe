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

function show_error($name, $msg) {
    if (!class_exists('Error')) {
        require_once __CORE__ . 'Error.php';
    }
    Error::show($name, $msg);
}

/**
 * 默认载入第一个配置好的DB
 * @param array $db
 */
function DB($db = array()) {
    //todo P2
    return TRUE;
}

/**
 * @param string $table
 */
function load_model($table) {
    //todo P2
}

function C($key = NULL, $value = NULL) {
    if (is_null($key)) {
        return DD::$_CFG;
    }
    if (is_null($value)) {
        return isset(DD::$_CFG[$key]) ? DD::$_CFG[$key] : NULL;
    } else {
        return DD::$_CFG[$key] = $value;
    }
}

function load_core($class) {
    $file = __CORE__ . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        new $class();
    }
}