<?php

/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:59
 */

class DD_Controller {
    public $_SIGN; #本次请求的唯一标识
    public $Output;
    private $layout = '';
    public $DD;

    public function __construct() {
        $this->DD = &get_instance();
        load_lib('Output');
        $site = C('site');
        $this->Output = new Output();
        $this->Output->sitename = $site['name'];
        $this->Output->delimiter = $site['delimiter'];
    }

    public function set_format($name) {
        $this->Output->set_format($name);
    }

    public function set_layout($layout) {
        $this->layout = $layout;
    }

    public function put($key, $value) {
        $this->Output->put($key, $value);
    }

    public function get($key) {
        $this->Output->get($key);
    }

    public function display($action = '') {
        $this->Output->display($action, '', $this->layout);
    }

    //close auto display
    public function close_display() {
        $this->Output->display_lock = TRUE;
    }

}