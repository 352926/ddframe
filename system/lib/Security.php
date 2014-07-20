<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 16:46
 */

class Security {

    private $csrf_hash = '';

    public function __construct() {
        $this->magic_quotes_check();
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

    public function get_csrf_hash() {
        if (!$this->csrf_hash) {
            $this->csrf_hash = cookie(C('csrf_name')) ? cookie(C('csrf_name')) : md5(uniqid(microtime(TRUE) . __TIME__));
        }
        return $this->csrf_hash;
    }

    public static function xss_clean($data) {
        if (is_array($data)) {
            while (list($key) = each($data)) {
                $str[$key] = self::xss_clean($data[$key]);
            }

            return $data;
        }
        // Fix &entity\n;
        $data = rawurldecode($data);
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return trim($data);
    }

    private function set_csrf() {
        $csrf_expire = time() + C('csrf_expire');

        $csrf_hash = $this->get_csrf_hash();
        return setcookie(C('csrf_name'), $csrf_hash, $csrf_expire, C('cookie_path'), C('cookie_domain'), C('cookie_secure'));
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