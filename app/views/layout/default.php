<?php
/**
 * default layout
 * User: 352926 <352926@qq.com>
 * Date: 14-5-10
 * Time: 21:41
 */
defined('VERSION') or exit('Forbidden');
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->get_title(); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="http://g.tbcdn.cn/kissy/k/1.4.2/??css/dpl/base-min.css,css/dpl/forms-min.css,button/assets/dpl-min.css"
          rel="stylesheet"/>
    <style>
        .site-nav {
            z-index: 10000;
            width: 100%;
            background: #f5f5f5;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="site_nav"></div>
<div class="content">
    <?php if (!$this->load_view()) echo "Page not find!"; ?>
</div>
</body>
</html>