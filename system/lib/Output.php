<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-30
 * Time: 10:51
 */

class Output {
    public $fomat_arr = array(
        'xml' => 'application/xml',
        'rawxml' => 'application/xml',
        'json' => 'application/json',
        'jsonp' => 'application/javascript',
        'serialize' => 'application/vnd.php.serialized',
        'php' => 'text/plain',
        'html' => 'text/html',
        'csv' => 'application/csv'
    );

    public function set_format($name) {
        if (function_exists('header_remove')) {
            ob_clean();
            header_remove();
        }
    }
}