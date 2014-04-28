<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 11:58
 */

class home_controller extends DD_Controller {
    public $log = TRUE;
    public $model = array(
        'index' => array('test')
    );

    public function index() {
        session_start();
//        $_SESSION['aaa']='bbb';
//        unset($_SESSION['aaa']);
        $test = new Model('test');
        echo "<pre>";
        print_r($test->find());
        exit;
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