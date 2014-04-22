<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 19:35
 */


class ddframe {
    public $_CFG = array();

    public function run() {
        $this->load();
        global $_CFG;
        print_r($_CFG);
    }

    private function get_module() {
        $m = strtr($_GET['m'], array(
                '.' => '',
                '/' => '',
                '\\' => '',
                ' ' => '',
            )
        );
        return trim($m);
    }

    private function load() {
        $_SYS_CFG = array();
        $_CFG = array();
        require_once __SYSTEM__ . 'core/config.php';
        require_once __ROOT__ . 'config/config.php';
        require_once __SYSTEM__ . 'core/helper.php';
        $this->_CFG = array_merge($_SYS_CFG, $_CFG);
    }

    public function config($name) {
        return isset($this->_CFG[$name]) ? $this->_CFG[$name] : '';
    }
}

$dd = new ddframe();
$dd->run();