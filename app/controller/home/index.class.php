<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:58
 */

class index_controller extends DD_Controller {
    public $log = TRUE;

    public function index() {show_error(date('Y-m-d H:i:s'));
        $this->put('time', date('Y-m-d H:i:s'));
        return;
        //echo "HelloWorld";
//        header("Location: http://www.baidu.com", TRUE, 505);
//        header("Content-type: text/html; charset=utf-8");
//        exit;
        //$this->start_log = TRUE; #开启日志
        //log_info('asdfl');
//        not_found();
        //
        session_start();
//        $_SESSION['aaa']='bbb';
//        unset($_SESSION['aaa']);
        $test = Model('test');
//        $test->abc();exit;
        echo "<pre>";
        print_r($test->find());
//        exit;
//        echo encrypt('abc');
        echo "<hr><PRE>";
//        print_r($_SESSION);exit;
//        print_r(C());
//        $model = new DD_Model('test');
//        $model = new test();
//        $rs = $model->quoto("'or'='or'");
        /*        $rs = $model->insert(array(
                        array('title' => 'adsfasd'),
                        array('title' => 'bbbbbbb')
                    )
                );*/
//        print_r($rs);
//        echo $this->sign;
//        sleep(1);
    }
}