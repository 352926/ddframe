ddframe
=======

php framework

为了DDframe更好的控制您写的代码，请最好不要在控制器里写exit()退出，您可以使用return;来控制退出。

PLAN:

1、基础框架实现(controller、module、action、config、output)

2、安全模块(security)

3、数据库实现、model模块()

4、日志模块(log)

5、脚本模块(cli)

6、view模块(layout、变量)  ---- now

7、钩子模块(hook)

8、扩展模块(plugin)

9、语言模块(lang)

index.php 用法：


define('__MT__', microtime(TRUE)); #站点实际总目录
define('__ROOT__', dirname(__FILE__) . '/'); #站点实际总目录
define('__CURRENT__', __ROOT__); #站点入口文件所在目录
define('__SYSTEM__', __ROOT__ . 'system/');
define('__APP__', __ROOT__ . 'app/'); #您的APP应用目录
define('__CONFIG__', __APP__ . 'config/'); #同目录下不同的站点可以引用不同的配置目录
define('__CORE__', __SYSTEM__ . 'core/');

require_once __SYSTEM__ . 'core/DDframe.php';
