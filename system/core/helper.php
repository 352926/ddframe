<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:03
 */

function _get($name) {
    return isset($_GET[$name]) ? trim($_GET[$name]) : NULL;
}

function _post($name) {
    return isset($_POST[$name]) ? trim($_POST[$name]) : NULL;
}