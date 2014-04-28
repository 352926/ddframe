<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-28
 * Time: 21:55
 */


class Log {
    public $type = NULL;
    public $path = NULL;
    public $uniqid = NULL;

    function loger($msg, $name) {
        $dir = $this->path . date("Ymd");
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir, 0777, TRUE);
        }
        $this->uniqid = md5(uniqid(time()));
        $msg = date('H:i:s') . " {$this->uniqid} " . $msg . "\n";
        return error_log($msg, 3, "{$dir}/{$name}");
    }
}