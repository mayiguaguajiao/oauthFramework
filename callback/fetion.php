<?php
/*****************************

 oauthFramework - CMCC Fetion Callback
 Copyright (C) 1999 XING Bin

 This file is part of oauthFramework.

 oauthFramework is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 
 oauthFramework is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with oauthFramework. If not, see <http://www.gnu.org/licenses/>.

 Developer:
     XING Bin <web@xingbin.net>

 Homepage:
     http://www.xingbin.net
 
 *******************************/

include "../../common.php";
require_once("../oauth.php");

$oauthObj = new FetionOauth();

$ret = $oauthObj->callback();

if($ret['status'] === false)
{
	$oauthObj->logout();

	echo("Fail, reason = ".$ret['reason']);

	echo("You need redirect user to ".$ret['url']);

	die();
}
else
{
	header("location: ".$ret['url']);
}
?>