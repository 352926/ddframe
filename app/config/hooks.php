<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-8-19
 * Time: 16:43
 */

defined('VERSION') or exit('Forbidden');

$hooks = array();

#start|init|begin|end|done

$hooks['start'] = array(
    'file' => 'test',
    'class' => 'abcd',
    'function' => 'aaa',
    'path' => '',
);


return $hooks;