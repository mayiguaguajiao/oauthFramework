oauthFramework (for PHP)
========================
Copyright (C) 2014 XING Bin

oauthFramework is an OAuth 2.0 Client Framework written by PHP.

With this framework, website managers can easily to let SNS users logon their sites.

oauthFramework support SNSes including Weibo, Tencent QQ, Netease, Renren, Baidu, Taobao, CMCC Fetion, 
Douban, 360safe, Kaixin, CSDN, and Microsoft Live. oauthFramework will support more SNSes in the future.


INSTALL
=======

PUT files and directories whereever you want, and make them can be visited from the explorer.
For example, if your homepage is http://www.***.com/, then you can put the files and directories
(including oauth.php, config.php, include(DIR), callback(DIR)) in http://www.***.com/, or 
http://www.***.com/open/, or some place else.


CONFIG
======

Open file config.php and edit it.

* DATASTORNAME
DATASTORNAME is the parameter name used in SESSION storage, which is $_SESSION[DATASTORNAME].
Default value is "user".
You can modify the following line of config.php by replacing the string "user" to what you want:
```
define("DATASTORNAME", "user");
```

* DEFAULT_HOMEURL
DEFAULT_HOMEURL is the default go back url.
After user logon, the program will send the user back to DEFAULT_HOMEURL after oauth logon, if the referer is missing.
You can modify this value to your homepage, just by edit the following line.
```
define("DEFAULT_HOMEURL", "http://www.***.com/");
```

* DEFAULT_CALLBACKPATH
DEFAULT_CALLBACKPATH is the path where the callback program stored.
E.g., if your DEFAULT_CALLBACKPATH is "http://www.***.com/open/callback/", 
then your callback program of weibo should be "http://www.***.com/open/callback/weibo.php". 
You must modify this value to the real path, just by edit the following line.
```
define("DEFAULT_CALLBACKPATH", "http://www.***.com/open/callback/");
```
Note: Some SNS open platform will need you to provide callback url to enhance security. You must make them the same.

* SNS CONFIG
If you want to let Weibo users to logon your website, visit http://open.weibo.com to new an web app.
After that, you will get a pair of APP key and Secret key.
So, just write down them in to the config.php, and make sure WEIBO_ENABLE is true.
```
define("WEIBO_ENABLE", true);
define("WEIBO_APP_KEY", "");
define("WEIBO_SECRET_KEY", "");
```
And then, do the similar thing again on other SNS open platfrom.


USING FRAMEWORK
===============

* Get the logon url
To get the logon url for Weibo user, just using the following code.
```
<?php
   $oauthObj = new WeiboOauth($referer);
   $logonUrl = $oauthObj->getAuthorizeURL();
?>
```

In the codes above, $referer is a URL, which the user will be sent back to once logon.
If $referer is missing, the user will be send back to DEFAULT_HOMEURL.

$logonUrl is the logon URL, please redirect user to this URL to logon.

* Finish logon code
After user logon, the framework will send you the following value:
$provider: oauth service provider, such as "sina", "qq", "netease", etc.
$uid: user's openid, provided by oauth service.
$uname: user's name or nickname, provided by oauth service.
$email: user's email address, provided by oauth service.
$avatar: URL of user's avatar, provided by oauth service.
$url: URL of user's homepage, provided by oauth service.
$access_token: oauth access token.
$timelimit: the deadtime of access token.

Add your code to do with these parameters in donext.php, such as:
* Do a SQL query to find whether the user had logoned before or not;
* Get user's level on your website;
* Anything else you want.


DEMO
====

Demo will be available soon...


COPYRIGHT
=========

Plese check the copyright statement of each file.
