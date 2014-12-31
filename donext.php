<?php
/*****************************

 oauthFramework - donext
 Copyright (C) 1999 XING Bin

 Developer:
     XING Bin <web@xingbin.net>

 Homepage:
     http://www.xingbin.net
 
 *******************************/

if(!defined("IN_OAUTH"))
	die();

// till now, user had finished his/her logon. the available data here are:
// $provider: oauth service provider
// $uid: user's openid, provided by oauth service
// $uname: user's name or nickname, provided by oauth service
// $email: user's email address, provided by oauth service
// $avatar: URL of user's avatar, provided by oauth service
// $url: URL of user's homepage, provided by oauth service
// $access_token: oauth access token.
// $timelimit: the deadtime of access token.
//
// now, please use the data listed above to check the user's information is in your database (with $provider and $uid):
// 1. if true, let the user logon.
// 2. if not, register the user before let he/she logon.

//
// add your code here.
//

// write the related data to $_SESSION, eg: 
// $this->writeData("uid", $uid);
// $this->writeData("from", $provider);
// $this->writeData("access_token", $access_token);
// $this->writeData("timelimit", $timelimit);

       
?>