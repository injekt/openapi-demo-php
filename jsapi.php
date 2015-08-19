<!DOCTYPE html>
<?php
require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Http.php");
require_once(__DIR__ . "/api/Auth.php");
?>
<html>
    <head>
        <title>jsapi demo</title>
        <link rel="stylesheet" href="/public/stylesheets/style.css" type="text/css" />
        <script type="text/javascript">var _config = <?php echo \api\Auth::getConfig();?>;</script>
        <script type="text/javascript" src="/public/javascripts/zepto.min.js"></script>
        <script type="text/javascript" src="http://g.alicdn.com/ilw/ding/0.3.8/scripts/dingtalk.js"></script>
    </head>
    <body>
        <script type="text/javascript" src="/public/javascripts/logger.js"></script>
        <script type="text/javascript" src="/public/javascripts/demo.js"></script>
    </body>
</html>