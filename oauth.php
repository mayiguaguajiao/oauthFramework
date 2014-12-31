<?php
/*****************************

 oauthFramework - main frame
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

define("IN_OAUTH", true);

class OauthBase {
	protected $provider = "";
	protected $randomCode = "";
	protected $refererUrl = "";
	protected $specialSet = "";

	public function __construct($provider, $refererUrl = "", $specialSet = "")
	{
		$this->provider = $provider;
		
		$randomCode = $this->readData("randomCode");
		if(empty($randomCode))
		{
			$this->randomCode = md5(rand());
			$this->writeData("randomCode", $this->randomCode);
		}
		else
		{
			$this->randomCode = $randomCode;
		}
		
		if(empty($refererUrl))
		{
			$this->refererUrl = $this->readData("refererUrl");
		}
		else
		{
			$this->refererUrl = $refererUrl;
			$this->writeData("refererUrl", $refererUrl);
		}

		if(empty($specialSet))
		{
			$this->specialSet = $this->readData("specialSet");
		}
		else
		{
			$this->specialSet = $specialSet;
			$this->writeData("specialSet", $specialSet);
		}
    }

	function createState()
	{
		$stateString = $this->randomCode;
		return base64_encode($stateString);
	}

	function verifyState($state)
	{
		$stateString = base64_decode($state);

		if(false === $stateString)
		{
			return false;
		}

		if(0 != strcasecmp($stateString, $this->randomCode))
		{
			return false;
		}

		return true;
	}

	function writeData($name, $value)
	{
		$_SESSION[DATASTORNAME][$name] = $value;
	}

	function readData($name)
	{
		return $_SESSION[DATASTORNAME][$name];
	}

	function logout()
	{
		global $_USER;

		unset($_SESSION[DATASTORNAME]);
		unset($_USER);

		@session_destroy();
	}

	function finishLogin()
	{
		global $openSites, $mysql, $ip, $wwwrootpath;
		
		$provider = $this->provider;
		$uid = $this->readData("uid");
		$uname = $this->readData("name");
		$email = $this->readData("email");
		$avatar = $this->readData("avatar");
		$url = $this->readData("url");
		$access_token = $this->readData("access_token");
		$timelimit = time() + $this->readData("expires_in");

		unset($_SESSION[DATASTORNAME]);

		$this->writeData("uid", "");
		$this->writeData("from", "");
		$this->writeData("access_token", "");
		$this->writeData("timelimit", "");

		if(empty($uid))
		{
			return false;
		}

		//do next program.
		require_once("donext.php");

        return true;
	}

	function post($url, $data, $header = "", $verifySSL = false)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

		if(!empty($header))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

        if(!$flag)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
		
		$ret = curl_exec($ch);
		
		curl_close($ch);
		
		return $ret;
	}

	function get($url, $header = "", $verifySSL = false)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

		if(!empty($header))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

        if(!$flag)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
		
		$ret = curl_exec($ch);
		
		curl_close($ch);
		
		return $ret;
	}
}


if(defined(WEIBO_ENABLE) && (true === WEIBO_ENABLE))
{
	include "include/weibo.php";
}

if(defined(QQ_ENABLE) && (true === QQ_ENABLE))
{
	include "include/qq.php";
}

if(defined(NETEASE_ENABLE) && (true === NETEASE_ENABLE))
{
	include "include/netease.php";
}

if(defined(RENREN_ENABLE) && (true === RENREN_ENABLE))
{
	include "include/renren.php";
}

if(defined(BAIDU_ENABLE) && (true === BAIDU_ENABLE))
{
	include "include/baidu.php";
}

if(defined(TAOBAO_ENABLE) && (true === TAOBAO_ENABLE))
{
	include "include/taobao.php";
}

if(defined(FETION_ENABLE) && (true === FETION_ENABLE))
{
	include "include/fetion.php";
}

if(defined(DOUBAN_ENABLE) && (true === DOUBAN_ENABLE))
{
	include "include/douban.php";
}

if(defined(S60_ENABLE) && (true === S60_ENABLE))
{
	include "include/s60.php";
}

if(defined(KAIXIN_ENABLE) && (true === KAIXIN_ENABLE))
{
	include "include/kaixin001.php";
}

if(defined(CSDN_ENABLE) && (true === CSDN_ENABLE))
{
	include "include/csdn.php";
}

if(defined(LIVE_ENABLE) && (true === LIVE_ENABLE))
{
	include "include/live.php";
}

?>