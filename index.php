<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-22
 * Time: 18:58
 */

define('__MT__', microtime(TRUE)); #站点实际总目录
define('__ROOT__', dirname(__FILE__) . '/'); #站点实际总目录
define('__CURRENT__', __ROOT__); #站点入口文件所在目录
define('__SYSTEM__', __ROOT__ . 'system/');
define('__APP__', __ROOT__ . 'app/'); #您的APP应用目录
define('__CONFIG__', __APP__ . 'config/'); #同目录下不同的站点可以引用不同的配置目录
define('__CORE__', __SYSTEM__ . 'core/');
define('__LIB__', __SYSTEM__ . 'lib/');

require_once __SYSTEM__ . 'core/DDframe.php';

