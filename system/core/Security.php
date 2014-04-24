<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 16:46
 */

class Security {

    public function __construct() {
        #csrf检查
        if (C('csrf_protection')) {
            $this->csrf_check();
        }
    }

    private function csrf_check() {
        $csrf_token = C('csrf_token');
        if (count($_POST) == 0) {
            $this->set_csrf();
            return;
        }
        if (!isset($_POST[$csrf_token]) || !$_POST[$csrf_token]) {
            Error::show('', '', 500);
        }
        if (!isset($_COOKIE[$csrf_token]) || !isset($_COOKIE[$csrf_token])) {
            Error::show('', '', 500);
        }
        if ($_COOKIE[$csrf_token] != $_POST[$csrf_token]) {
            Error::show('', '', 500);
        }
    }

    private function set_csrf() {
        $csrf_expire = time() + C('csrf_expire');
        $csrf_token = C('csrf_token');
        $csrf_value = md5(uniqid(microtime(TRUE) . __TIME__));
        return setcookie($csrf_token, $csrf_value, $csrf_expire, C('cookie_path'), C('cookie_domain'), C('cookie_secure'));
    }
}

$security = new Security();