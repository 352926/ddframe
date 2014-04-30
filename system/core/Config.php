<?php
/**
 * 系统默认配置文件
 * 注：请勿修改此框架默认配置文件!
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 20:07
 */

$_CFG['controller'] = 'controller/';

$_CFG['view']['path'] = 'view/';
$_CFG['view']['auto'] = TRUE; #自动显示视图 todo P6
$_CFG['view']['layout'] = TRUE; #开启layout

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

$_CFG['log']['start'] = array('WEB'); #CLI:脚本模式开启日志,WEB:web模式开启日志
$_CFG['log']['level'] = array('SYS', 'INFO', 'NOTICE', 'ERROR'); #日志存储级别 DEBUG|INFO|NOTICE|ERROR
$_CFG['log']['type'] = 'file'; #日志存储类型 error_log() 第二个参数
$_CFG['log']['path'] = __APP__ . 'data/'; #存储日志路径
$_CFG['log']['time'] = 'H:i:s';
$_CFG['log']['post'] = FALSE; #默认关闭记录$_POST 开启将会记录一维数组日志（多维数组将过滤），最多记录前20个key，value长度大于20的只截取20字符

$_CFG['force_filter'] = array(); #过滤非法字符串，如：array('iphone'=>'android'),则框架会将输出结果里的iphone字符串强制替换成android

$_CFG['csrf_protection'] = FALSE;
$_CFG['csrf_name'] = 'csrf_token';
$_CFG['csrf_expire'] = '3600';

$_CFG['cookie_prefix'] = '';
$_CFG['cookie_domain'] = ''; #  .xxx.com
$_CFG['cookie_path'] = '/';
$_CFG['cookie_secure'] = FALSE;

$_CFG['INI_SET']['precision'] = 15; #统一设置精度(不同系统PHP默认设置不同精度,如:win:12,linux:14)

$_CFG['INI_SET']['session.cookie_domain'] = ''; //跨域访问Session
$_CFG['INI_SET']['session.gc_maxlifetime'] = '1440'; //默认Session有效时间 单位秒
$_CFG['INI_SET']['date.timezone'] = 'PRC'; //设置时间
