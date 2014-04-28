<?php
/**
 * 系统默认配置文件
 * 注：请勿修改此框架默认配置文件!
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:07
 */

$_CFG['controller'] = 'controller/';
$_CFG['view'] = 'view/';

$_CFG['default_controller'] = 'home';
$_CFG['default_module'] = 'home';
$_CFG['default_action'] = 'index';

$_CFG['charset'] = 'UTF-8';

$_CFG['load_db'] = FALSE;

$_CFG['database'] = array();
$_CFG['database']['type'] = 'mysql';
$_CFG['database']['host'] = 'localhost';
$_CFG['database']['port'] = '3306';
$_CFG['database']['user'] = 'root';
$_CFG['database']['pass'] = '';
$_CFG['database']['charset'] = 'utf8';


$_CFG['csrf_protection'] = FALSE;
$_CFG['csrf_name'] = 'csrf_token';
$_CFG['csrf_expire'] = '3600';

$_CFG['cookie_prefix'] = '';
$_CFG['cookie_domain'] = ''; #  .xxx.com
$_CFG['cookie_path'] = '/';
$_CFG['cookie_secure'] = FALSE;

$_CFG['log_path'] = 'log/'; #存储日志路径
$_CFG['log_date_format'] = '%Y%m%d'; #按日期分目录

$_CFG['auto_show_view'] = TRUE; #自动显示视图 todo P6

$_CFG['INI_SET']['precision'] = 15; #统一设置精度(不同系统PHP默认设置不同精度,如:win:12,linux:14)

$_CFG['INI_SET']['session.cookie_domain'] = ''; //跨域访问Session
$_CFG['INI_SET']['session.gc_maxlifetime'] = '1440'; //默认Session有效时间 单位秒
$_CFG['INI_SET']['date.timezone'] = 'PRC'; //设置时间
