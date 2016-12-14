
ISV应用和企业应用php demo
   注意！注意！注意！demo中的数据库存储一定要修改为mysql等持久化存储。
目录结构：


isv目录：isv应用php demo


corp目录：企业应用php demo

运行前先看开发文档：http://ddtalk.github.io/dingTalkDoc/?spm=a3140.7785475.0.0.Q5c5r7

## Getting Started

ISV应用注册开发流程
###创建套件前
登录到 http://console.d.aliyun.com/#/dingding/suite 创建套件（需要先注册开发者账号和钉钉企业才能创建套件）
###创建套件
3.填写套件信息
其中：

- Token:  可以随意填写，填写完之后，打开工程的isv/config.php文件，把Token的值复制给TOKEN
- 数据加密密钥：点击自动生成，然后打开工程的isv/config.php文件，把值复制给给ENCODING_AES_KEY
- 应用ID:把应用ID的值复制给APPID
- IP白名单:  调用钉钉API的合法IP列表(例如，工程部署在ip地址为123.56.71.118的主机上，那就填写"123.56.71.118")
- 回调URL:   url为`工程地址/receive.php`(例如，工程将部署在ip地址为123.56.71.118的主机上，端口为8080，那么我的回调URL即为：`http://123.56.71.118:8080/receive.php`，假如你有域名的话，也可以把IP地址换成域名)

4.配置PHP服务器环境（php+apache/nginx），安装mcrypt扩展（注意，一定要安装mcrypt扩展），保证apache服务根目录与可写权限（存储json数据）

5.将demo工程（isv）部署到服务器上

6.部署成功之后，点击『创建套件』弹窗中的『验证有效性』。

  具体是如何验证回调URL有效性的，请查看(isv/receive.php)

7.创建套件成功之后，将得到的SuiteKey和SuiteSecret填写到工程的config.php中。

8.点击['测试企业和文档']，注册测试企业，注册完成后，点击『登录管理』到```oa.dingtalk.com```完成测试企业的激活

9.测试企业激活完成后，进入套件『管理』，在页面底部选择要授权的测试企业进行授权

10.修改微应用主页地址和PC主页地址

  点击应用最右侧的`编辑`，编辑微应用信息，例如，工程部署在ip地址为123.56.71.118的主机上，端口为8080，那么微应用首页地址即为：`http://123.56.71.118:8080/index.php?corpid=$CORPID$`，PC版首页地址为：`http://123.56.71.118:8080/indexpc.php?corpid=$CORPID$`，点击保存。

11.打开钉钉，进入对应企业，即可看到微应用，点击进入

注意：Ticket推送状态成功之后，再授权企业

###创建企业应用
1.进入`https://oa.dingtalk.com/#/microApp/microAppList`,点击『新建应用』

2.配置PHP服务器环境（php+apache/nginx），安装mcrypt扩展（注意，一定要安装mcrypt扩展）,保证apache服务根目录与可写权限（存储json数据）

3.微应用主页地址填写。地址为`根目录/index.php`，(例如，工程部署在ip地址为123.56.71.118的主机上，端口为8080，那么微应用首页地址即为：`http://123.56.71.118:8080/index.php`，PC版首页地址为：`http://123.56.71.118:8080/indexpc.php`，假如你有域名的话，也可以把IP地址换成域名)
  修改config.php中的CORPID，SECRET，AGENTID，其中CORPID，SECRET在微应用设置页面`https://oa.dingtalk.com/#/microApp/microAppSet`获取，AGENTID在创建微应用的时候可以获取

4.微应用创建成功后，需要把微应用首页地址改为'根目录/index.php'

5.打开钉钉，进入对应企业，即可看到微应用，点击进入


###本DEMO具体实现

1.URL回调流程

请查看[文档](http://ddtalk.github.io/dingTalkDoc/#2-回调接口（分为五个回调类型）)

2.jsapi权限验证配置流程

请查看[文档](http://ddtalk.github.io/dingTalkDoc/#页面引入js文件)

3.免登流程

请查看[文档](http://ddtalk.github.io/dingTalkDoc/#手机客户端微应用中调用免登)
