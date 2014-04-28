<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 19:38
 */

#请不要直接使用 $var = array(key=>value) 的方式，会导致直接覆盖默认配置

$_CFG['database']['type'] = 'mysql';
$_CFG['database']['database'] = 'dd';
$_CFG['database']['host'] = 'localhost';
$_CFG['database']['port'] = '3306';
$_CFG['database']['user'] = 'root';
$_CFG['database']['pass'] = 'root';
$_CFG['database']['charset'] = 'utf8';

$_CFG['memcached'] = array();

$_CFG['controller'] = 'controller/';

$_CFG['log']['type'] = 'file';
$_CFG['log']['path'] = __APP__ . 'data/log/';

$_CFG['csrf_protection'] = TRUE;
$_CFG['load_db'] = TRUE;

$_CFG['cookie_prefix'] = '';
$_CFG['cookie_domain'] = '.dd.com'; #  .xxx.com
$_CFG['cookie_path'] = '/';
$_CFG['cookie_secure'] = FALSE;

$_CFG['INI_SET']['session.cookie_domain'] = '.dd.com';
$_CFG['INI_SET']['session.save_handler'] = 'memcached';
$_CFG['INI_SET']['session.save_path'] = 'localhost:11211';
