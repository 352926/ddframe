<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-5-4
 * Time: 14:17
 */


class Task {
    public $_SIGN; #本次请求的唯一标识
    public $db;
    public $start_log = TRUE; #默认关闭日志
    public $Output;

    public function __construct() {
        load_lib('Output');
        $this->Output = new Output();
    }

    public function loger($msg, $level = 'INFO') {
        $this->start_log = TRUE;
        //DD::log($msg, $level);
        if (DEBUG) echo $level . ' ' . $msg . PHP_EOL;
    }

}