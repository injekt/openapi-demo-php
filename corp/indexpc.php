<!DOCTYPE html>
<?php
require_once(__DIR__ . "/api/Auth.php");
?>
<html>
    <head>
        <title>jsapi demo</title>
        <link rel="stylesheet" href="/public/stylesheets/style.css" type="text/css" />
        <!-- config中signature由jsticket产生，若jsticket失效，则signature失效，表现为dd.error()返回“权限校验失败”之错误。 -->
        <!-- 在请求新的jsticket之后，旧的ticket会失效，导致旧ticket产生的signature失效。 -->
        <link rel="stylesheet" href="http://g.alicdn.com/ilw/ding/0.2.3/styles/main.css?v=262192719_110744" type="text/css"/>
        <script type="text/javascript">var _config = <?php echo Auth::getConfig();?></script>
        <script type="text/javascript" src="/public/javascripts/zepto.min.js"></script>
        <script type="text/javascript" src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.3.1/index.js"></script>
    </head>
    <body>
    <button class="btn btn-block btn-default chooseonebtn">给朋友发ding</button>
        <script type="text/javascript" src="/public/javascripts/logger.js"></script>
        <script type="text/javascript" src="/public/javascripts/demopc.js"></script>
    </body>
</html>