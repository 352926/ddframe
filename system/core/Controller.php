<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:59
 */


class DD_Controller {
    public $_SIGN; #本次请求的唯一标识
    public $db;

    public function start_log() {
        DD::$log = TRUE;
    }

}