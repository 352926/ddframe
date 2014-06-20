<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-5-4
 * Time: 14:16
 */

class test_controller extends Task {

    /**
     * php index.php "test"
     * php index.php "test/index"
     * php index.php "test/index/aa=bb&cc=dd"
     */
    public function index() {
        $db = DB();
        log_debug('asdf');
        dump($db->select('test','*'));
//        $test = Model('test');
//        dump($test->find());
    }
}