ddframe<br>
=======<br>

php framework<br>

为了DDframe更好的控制您写的代码，请最好不要在控制器里写exit()退出，您可以使用return;来控制退出。<br>

PLAN:<br>

1、基础框架实现(controller、module、action、config、output)<br>

2、安全模块(security)<br>

3、数据库实现、model模块()<br>

4、日志模块(log)<br>

5、脚本模块(cli)<br>
php index.php "module/action/aa=bb&cc=dd"<br>
例：php index.php "test"  =>  php index.php "test/index"  =>  DD::$_C = 'task' | DD::$_M = 'test' | DD::$_A = 'index'<br>
第三项为参数，可以通过_get('aa') 获取<br>

6、view模块(layout、变量)<br>

7、钩子模块(hook)<br>

8、扩展模块(plugin) todo<br>

9、语言模块(lang) todo<br>

2013-05-13起，ddframe的新功能(7:done、8、9)开发将暂停一段时间，并不是不维护，<br>
而是近期要将ddframe应用至一项目，如项目中发现框架bug或者框架其它需求会更新至此。<br>

index.php 用法：<br>

define('__MT__', microtime(TRUE)); #站点实际总目录<br>
define('__ROOT__', dirname(__FILE__) . '/'); #站点实际总目录<br>
define('__CURRENT__', __ROOT__); #站点入口文件所在目录<br>
define('__SYSTEM__', __ROOT__ . 'system/');<br>
define('__APP__', __ROOT__ . 'app/'); #您的APP应用目录<br>
define('__CONFIG__', __APP__ . 'config/'); #同目录下不同的站点可以引用不同的配置目录<br>
define('__CORE__', __SYSTEM__ . 'core/');<br>
define('__LIB__', __SYSTEM__ . 'lib/');<br>

require_once __SYSTEM__ . 'core/DDframe.php';<br>

Time:2014-08-23<br>
DDframe v1.1.2 change log:<br>
修复 hooks start的时候，尚未初始化 _C、_M、_A问题，导致start的hook无法对此进行判断<br>

Time:2014-08-20<br>
DDframe v1.1.1 change log:<br>
1、完善hooks，增加同一钩子支持调用多个钩子模块<br>

Time:2014-08-19<br>

DDframe v1.1 change log:<br>

1、精简代码，去掉DD::相关静态属性及静态方法，改用 get_instance()-> /$this->DD-> 来调用。<br>

2、去掉框架自动日志功能，改为系统日志，不写入文件，可自由通过get_instance()->logs 或 $this->DD->logs 来调用，返回数组<br>

3、调整内核命名，之前命名的变量有点难以阅读代码<br>

4、新增hooks功能，用法请看config/hooks.php和app/hooks/test.php<br>
