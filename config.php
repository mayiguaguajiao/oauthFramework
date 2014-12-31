<?php
/*****************************

 oauthFramework - configure file
 Copyright (C) 1999 XING Bin

 Developer:
     XING Bin <web@xingbin.net>

 Homepage:
     http://www.xingbin.net
 
 *******************************/

//DATASTORNAME, the parameter name used in SESSION storage: $_SESSION[DATASTORNAME].
define("DATASTORNAME", "user");

//DEFAULT_HOMEURL, the default go back url.
//We will take user back to DEFAULT_HOMEURL after oauth logon, if the referer is missing.
define("DEFAULT_HOMEURL", "http://www.***.com/");

//DEFAULT_CALLBACKPATH, the path where the callback program is.
//e.g. if your DEFAULT_CALLBACKPATH is "http://www.***.com/open/callback/", then your callback program of weibo is "http://www.***.com/open/callback/weibo.php". 
define("DEFAULT_CALLBACKPATH", "http://www.***.com/open/callback/");


//weibo
//WEIBO_APP_KEY, WEIBO_SECRET_KEY are the App Key and App Secret of your Weibo Web App.
define("WEIBO_ENABLE", false);
define("WEIBO_APP_KEY", "");
define("WEIBO_SECRET_KEY", "");


//qq
//QQ_APP_ID, QQ_APP_KEY are the APP ID and APP KEY of your QQ Connect App.
define("QQ_ENABLE", false);
define("QQ_APP_ID", "");
define("QQ_APP_KEY", "");

//netease
//NETEASE_APP_KEY, NETEASE_SECRET_KEY are the APP ID and APP KEY of your Netease Web App.
define("NETEASE_ENABLE", false);
define("NETEASE_APP_KEY", "");
define("NETEASE_SECRET_KEY", "");

//renren
//RENREN_APP_KEY, RENREN_SECRET_KEY are the APP ID and APP KEY of your Renren Web App.
define("RENREN_ENABLE", false);
define("RENREN_APP_KEY", "");
define("RENREN_SECRET_KEY", "");

//baidu
//BAIDU_APP_KEY, BAIDU_SECRET_KEY are the APP ID and APP KEY of your Baidu Web App.
define("BAIDU_ENABLE", false);
define("BAIDU_APP_KEY", "");
define("BAIDU_SECRET_KEY", "");

//taobao
//TAOBAO_APP_KEY, TAOBAO_SECRET_KEY are the APP ID and APP KEY of your Taobao Web App.
define("TAOBAO_ENABLE", false);
define("TAOBAO_APP_KEY", "");
define("TAOBAO_SECRET_KEY", "");

//fetion
//FETION_APP_KEY, FETION_SECRET_KEY are the APP ID and APP KEY of your Fetion Web App.
define("FETION_ENABLE", false);
define("FETION_APP_KEY", "");
define("FETION_SECRET_KEY", "");

//douban
//DOUBAN_APP_KEY, DOUBAN_SECRET_KEY are the APP ID and APP KEY of your Douban Web App.
define("DOUBAN_ENABLE", false);
define("DOUBAN_APP_KEY", "");
define("DOUBAN_SECRET_KEY", "");

//360
//S60_APP_KEY, S60_SECRET_KEY are the APP ID and APP KEY of your 360 Web App.
//numeric symbol is not adviced to be the start of the parameter name. here we use S60 instead of 360.
define("S60_ENABLE", false);
define("S60_APP_KEY", "");
define("S60_SECRET_KEY", "");

//Kaixin001
//KAIXIN_APP_KEY, KAIXIN_SECRET_KEY are the APP ID and APP KEY of your Kaixin001 Web App.
define("KAIXIN_ENABLE", false);
define("KAIXIN_APP_KEY", "");
define("KAIXIN_SECRET_KEY", "");

//CSDN
//CSDN_APP_KEY, CSDN_SECRET_KEY are the APP ID and APP KEY of your CSDN Web App.
define("CSDN_ENABLE", false);
define("CSDN_APP_KEY", "");
define("CSDN_SECRET_KEY", "");

//Microsoft LIVE
//LIVE_APP_KEY, LIVE_SECRET_KEY are the APP ID and APP KEY of your LIVE Web App.
define("LIVE_ENABLE", false);
define("LIVE_APP_KEY", "");
define("LIVE_SECRET_KEY", "");

?>
