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
