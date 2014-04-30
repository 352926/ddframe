<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:59
 */


class DD_Controller {
    public $_SIGN; #本次请求的唯一标识
    public $db;
    public $start_log = FALSE; #默认关闭日志
    public $Output;

    public function __construct() {
        load_lib('Output');
        $this->Output = new Output();
    }

    public function loger($msg, $level = 'INFO') {
        $this->start_log = TRUE;
        DD::log($msg, $level);
    }

    public function set_format($name) {
        $this->Output->set_format($name);
    }

    public function put($key, $value) {
        $this->Output->put($key, $value);
    }

    public function get($key) {
        $this->Output->get($key);
    }

}