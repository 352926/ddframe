<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:59
 */


class DD_Controller {
    public $_SIGN; #本次请求的唯一标识
    public $_C; #controller
    public $_M; #module
    public $_A; #action
    public $log = FALSE; #默认关闭请求日志

    public function logging($msg = '') {
        if (!$this->log) {
            return FALSE;
        }
//        echo $msg.'<hr>';
        #todo P4
    }
}