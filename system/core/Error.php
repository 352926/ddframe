<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:25
 */

class Error {
    public static $ERROR = array(
        'SYS_C_NOT_EXISTS' => 'controller dir is not exists!',
        'SYS_M_NOT_EXISTS' => 'module file is not exists!',
        'SYS_M_NOT_DEFINED' => 'module class is not defined!',
        'SYS_A_NOT_DEFINED' => 'module action is not defined!',
    );

    public static function show($name, $msg = '', $code = 200) {
        if (defined('DEBUG')) {
            exit($msg . ' ' . (isset(self::$ERROR[$name]) ? self::$ERROR[$name] : $name));
        } else {
            not_found();
        }
    }
}

