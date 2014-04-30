<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-30
 * Time: 10:51
 */

class Output {
    protected $format_map = array(
        'xml' => 'application/xml',
        'rawxml' => 'application/xml',
        'json' => 'application/json',
        'jsonp' => 'application/javascript',
        'serialize' => 'application/vnd.php.serialized',
        'php' => 'text/plain',
        'html' => 'text/html',
        'csv' => 'application/csv'
    );
    public $charset = 'utf-8';
    private $format = 'html';
    public $code = 200;
    public $location = '';
    public $filename = '';
    private $content = array();

    public function set_format($name) {
        $this->format = $name;
    }

    public function get_format() {
        return $this->format;
    }

    public function set_header() {
        ob_clean();
        if (function_exists('header_remove')) {
            header_remove();
        }
        $format = isset($this->format_map[$this->format]) ? $this->format_map[$this->format] : $this->format_map['html'];
        header("Content-type: {$format}; charset={$this->charset}", TRUE, $this->code);
        if ($this->filename) {
            header("Cache-Control: public");
            header("Pragma: public");
            header("Content-Disposition:attachment;filename={$this->filename}");
            header('Content-Type:APPLICATION/OCTET-STREAM');
        }
        if ($this->location) {
            header("Location: {$this->location}");
        }
    }

    public function put($key, $value) {
        $this->content[$key] = $value;
    }

    public function get($key) {
        return isset($this->content[$key]) ? $this->content[$key] : FALSE;
    }

    public function reset() {
        $this->content = array();
    }

    public function get_content() {
        return $this->content;
    }
}