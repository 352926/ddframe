<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:07
 */


$_SYS_CFG['controller'] = 'controller/';
$_SYS_CFG['view'] = 'view/';

$_SYS_CFG['default_controller'] = 'home';
$_SYS_CFG['default_module'] = 'home';
$_SYS_CFG['default_action'] = 'index';

$_SYS_CFG['load_db'] = FALSE;


$_SYS_CFG['csrf_protection'] = FALSE;
$_SYS_CFG['csrf_name'] = 'csrf_token';
$_SYS_CFG['csrf_expire'] = '3600';

$_SYS_CFG['cookie_prefix'] = '';
$_SYS_CFG['cookie_domain'] = ''; #  .xxx.com
$_SYS_CFG['cookie_path'] = '/';
$_SYS_CFG['cookie_secure'] = FALSE;
//ini_set('session.cookie_domain', '');//跨域访问Session

$_SYS_CFG['log_path'] = 'log/'; #存储日志路径
$_SYS_CFG['log_date_format'] = '%Y%m%d'; #按日期分目录

$_SYS_CFG['auto_show_view'] = TRUE; #自动显示视图 todo P6