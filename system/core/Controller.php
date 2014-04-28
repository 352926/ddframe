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
    public $db;
    private $log_obj;

    public function logging($msg = '') {
        if (!$this->log) {
            return FALSE;
        }
        $name = $this->_C . '.' . $this->_M . '.' . $this->_A . '.log';
        if (!is_object($this->log_obj)) {
            $this->log_obj = new Log();
            $config = C('log');
            $this->log_obj->type = $config['type'];
            $this->log_obj->path = $config['path'];
        }
        return $this->log_obj->loger($msg, $name);

    }
}