# open api demo (php ver.)

## Awesome project

### Getting started
1. set up your own corp id and corp secret in env.php
2. launch your server in your prayers

### Jsapi authorization
see jsapi.php and public/javascripts/demo.js

### Get user infomation via auth code
see demo.js

## ISV Callback API
<http://open.dingtalk.com/#4-回调接口（分为三个回调类型）>
* suite ticket push
* temporary auth code push
* auth change event

see receive.php

* create suite callback

see create_suite_receive.php

## ISV Service API
<http://open.dingtalk.com/#5-获取套件访问token（suite_access_token）>

see isv.php

### FAQ
* Q: {"message" : "权限校验失败", "errorCode" : 3}
* A: Make sure getCurrentUrl() in Auth.php returns the correct url

部署：
1.下载代码
2.修改env.php里面的配置
如果是做微应用，必填：CORPID，SECRET，其他的可不填写
如果是做isv应用，必填：CORPID，SECRET，CREATE_SUITE_KEY，SUITE_KEY，SUITE_SECRET，TOKEN，ENCODING_AES_KEY
其中CORPID，SECRET在微应用设置的地方即可获取
CREATE_SUITE_KEY：自己设置
SUITE_KEY，SUITE_SECRET，TOKEN，ENCODING_AES_KEY等在阿里云开发者平台注册企业并获取，具体请详细阅读开发者平台api文档

将项目部署在apache或者nginx下面即可访问
