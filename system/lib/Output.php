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
    private $title = '';
    public $sitename = '';
    public $delimiter = ' - ';
    public $system = FALSE;
    private $layout = '';
    private $view = '';
    private $views = array();
    private $_path = '';

    public function set_format($name) {
        $this->format = $name;
    }

    public function get_format() {
        return $this->format;
    }

    public function set_header() {
        ob_clean();
        /*if (function_exists('header_remove')) {
            header_remove();
        }*/
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

    public function set_title($title) {
        $this->title = $title . ($this->sitename ? $this->delimiter . $this->sitename : '');
    }

    public function get_title() {
        return $this->title ? $this->title : $this->sitename;
    }

    public function display($action = '', $module = '') {
        $views = C('views');
        $action = $action ? $action : DD::$_A;
        $module = $module ? $module : DD::$_M;
        $path = __APP__ . $views['path'];
        $this->_path = $path;

        $site = $this->system ? 'system' : 'site';
        $this->views = array(
            $path . $site . '/' . DD::$_C . '/' . $module . '/' . $action . '.php',
            $path . $site . '/' . DD::$_C . '/' . $module . '/default.php',
            $path . $site . '/' . DD::$_C . '/' . $action . '.php',
            $path . $site . '/' . DD::$_C . '/default.php',
        );

        if ($this->system) {
            $this->views[] = $path . $site . '/' . $action . '.php';
            $this->views[] = $path . $site . '/default.php';
        }

        $layouts = array(
            $path . 'layout/' . DD::$_C . '/' . $module . '/' . $action . '.php',
            $path . 'layout/' . DD::$_C . '/' . $module . '/default.php',
            $path . 'layout/' . DD::$_C . '/default.php',
            $path . 'layout/default.php'
        );

        foreach ($layouts as $layout) {
            $this->layout = $layout;
            if ($this->load($layout)) {
                return;
            }
        }

        if (!$this->load_view()) {
            $_404 = $path . 'system/404.php';
            if (!$this->load($_404)) {
                exit('404');
            }
        }
    }

    private function load_view() {
        foreach ($this->views as $view) {
            $this->view = $view;
            if ($this->load($view)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param $name
     * @param string $path null不指定目录，默认空使用controller
     * @return bool
     */
    private function load_common($name, $path = '') {
        if (is_null($path)) {
            $path = '';
        } elseif (empty($path)) {
            $path = DD::$_C . '/';
        } else {
            $path .= '/';
        }
        $path = empty($path) ? DD::$_C : $path;
        $file = $this->_path . 'common/' . $path . $name . '.php';

        return $this->load($file);
    }

    private function load($file) {
        if (file_exists($file)) {
            extract($this->content);
            require_once $file;
            return TRUE;
        } else {
            return FALSE;
        }
    }
}