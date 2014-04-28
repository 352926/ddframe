<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 16:46
 */

class Security {

    public function __construct() {
        $this->magic_quotes_check();
        echo "******";
        #csrf检查
        if (C('csrf_protection')) {
            $this->csrf_check();
        }
    }

    private function csrf_check() {
        $csrf_name = C('csrf_name');
        if (count($_POST) == 0) {
            if (!isset($_COOKIE[$csrf_name]) || !$_COOKIE[$csrf_name]) {
                $this->set_csrf();
            }
            return;
        }
        if (!isset($_POST[$csrf_name]) || !$_POST[$csrf_name]) {
            Error::show('', '', 500);
        }
        if (!isset($_COOKIE[$csrf_name]) || !isset($_COOKIE[$csrf_name])) {
            Error::show('', '', 500);
        }
        if ($_COOKIE[$csrf_name] != $_POST[$csrf_name]) {
            Error::show('', '', 500);
        }
    }

    private function set_csrf() {
        $csrf_expire = time() + C('csrf_expire');
        $csrf_name = C('csrf_name');
        $csrf_value = md5(uniqid(microtime(TRUE) . __TIME__));
        return setcookie($csrf_name, $csrf_value, $csrf_expire, C('cookie_path'), C('cookie_domain'), C('cookie_secure'));
    }

    private function magic_quotes_check() {
        if (get_magic_quotes_gpc()) {
            array_walk_recursive($_COOKIE, 'secure_value');
            array_walk_recursive($_POST, 'secure_value');
            array_walk_recursive($_GET, 'secure_value');
            array_walk_recursive($_REQUEST, 'secure_value');

            #以下均为过时，所以UNSET掉，在4.1之前用这个
            if (isset($HTTP_GET_VARS))
                unset($HTTP_GET_VARS);
            if (isset($HTTP_POST_VARS))
                unset($HTTP_POST_VARS);
            if (isset($HTTP_COOKIE_VARS))
                unset($HTTP_COOKIE_VARS);
        }
    }
}

$security = new Security();