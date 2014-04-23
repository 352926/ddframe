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