<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-28
 * Time: 21:55
 */


class Log {
    private $type = NULL;
    private $path = NULL;
    private $time = NULL;
    private $uniqid = NULL;
    private $log_name = NULL;

    function __construct($path, $type = 'file', $time = 'H:i:s') {
        $this->uniqid = substr(md5(uniqid(time())), 8, 16);
        $this->path = $path;
        $this->time = $time;
    }

    /**
     * 将在msg前后封装日志格式
     * @param $msg
     * @param $name
     * @param string $level
     * @return bool
     */
    public function loger($msg, $name, $level = 'INFO') {
        if (!file_exists($this->path) || !is_dir($this->path)) {
            mkdir($this->path, 0777, TRUE);
        }
        $hash = " " . $this->uniqid;
        $msg = date($this->time) . $hash . ' ' . strtoupper($level) . ' ' . $msg;

        return $this->err_log($msg, $name);
    }

    /**
     * 直接写入日志
     * @param $value
     * @param $name
     * @return bool
     */
    public function err_log($value, $name) {
        return error_log($value . PHP_EOL, 3, $this->path . $name . '.log');
    }
}