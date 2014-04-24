<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 19:38
 */

$_CFG['db'] = array();

$_CFG['memcached'] = array();

$_CFG['controller'] = 'app1/controller/';

$_CFG['csrf_protection'] = TRUE;

$_CFG['cookie_prefix'] = '';
$_CFG['cookie_domain'] = '.ddframe.com'; #  .xxx.com
$_CFG['cookie_path'] = '/';
$_CFG['cookie_secure'] = FALSE;
ini_set('session.cookie_domain', '.ddframe.com');//跨域访问Session