<?
/*****************************

 oauthFramework - 360 Class
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

if(!defined("IN_OAUTH"))
	die();

class S60Oauth extends OauthBase {

	protected $appkey = "";
	protected $secretkey = "";
	protected $callback = "";
	protected $scope = "";
	
	const CALLBACK = "s60.php";
	const SCOPE = "";
	const GET_AUTH_CODE_URL = "https://openapi.360.cn/oauth2/authorize";
    const GET_ACCESS_TOKEN_URL = "https://openapi.360.cn/oauth2/access_token";
	const GET_USER_INFO_URL = "https://openapi.360.cn/user/me.json";

	public function __construct($url = "", $specialSet = "")
	{
		parent::__construct("s60", $url, $specialSet);
		
		$this->appkey = S60_APP_KEY;
		$this->secretkey = S60_SECRET_KEY;
		$this->callback = DEFAULT_CALLBACKPATH . self::CALLBACK;
		$this->scope = self::SCOPE;
	}

	public function getAuthorizeURL()
	{
		$state = $this->createState();

		$params = array(
			"client_id" => $this->appkey,
            "response_type" => "code",
			"redirect_uri" => $this->callback,
            "state" => $state
        );

		return self::GET_AUTH_CODE_URL . "?" . http_build_query ( $params );
	}

	public function callback()
	{
		$ret = array(
            "status" => false,
			"reason" => "",
            "url" => $this->refererUrl
        );

		$ret["url"] = empty($ret["url"]) ? DEFAULT_HOMEURL : $ret["url"];

		$state = urldecode($_GET["state"]);

		$rc = $this->verifyState($state);

		if(false === $rc)
		{
			$ret["reason"] = "完整性状态验证出错";
			return $ret;
		}

		if(!isset($_GET['code']) || empty($_GET['code']))
		{
			$ret["reason"] = "获得服务器返回信息出错";
			return $ret;
		}
		
		$params = array(
            "grant_type" => "authorization_code",
            "code" => $_GET['code'],
            "client_id" => $this->appkey,
            "client_secret" => $this->secretkey,
            "redirect_uri" => $this->callback
        );

		$token_url = self::GET_ACCESS_TOKEN_URL . "?" . http_build_query ( $params );
		$response = $this->get($token_url);

		if(false === $response)
		{
			$ret["reason"] = "请求ACCESS TOKEN出错";
			return $ret;
		}

		$msg = json_decode($response, true);

		if((null === $msg) || (false === $msg))
		{
			$ret["reason"] = "解析ACCESS TOKEN数据出错";
			return $ret;
		}

		if(isset($msg['error_code']))
		{
			$ret["reason"] = $msg["error_description"];
			return $ret;
        }
		
		$this->writeData("access_token", $msg['access_token']);
		$this->writeData("expires", $msg['expires_in']);

		$msg = $this->get_userinfo();

		if(false === $msg)
		{
			$ret["reason"] = "获取用户信息出错";
			return $ret;
		}
		
		$this->writeData("uid", $msg['id']);
		$this->writeData("name", $msg['name']);
		$this->writeData("avatar", $msg['avatar']);
		$this->writeData("email", "");
		$this->writeData("url", "");

		$ret["status"] = $this->finishLogin();

        return $ret;
	}

	public function get_userinfo()
	{
		$params = array(
			"access_token" => $this->readData("access_token")
        );

		$api_url = self::GET_USER_INFO_URL . "?" . http_build_query ( $params );
        $response = $this->get($api_url);

		if(false === $response)
		{
			return false;
		}

        $msg = json_decode($response, true);

		if((null === $msg) || (false === $msg))
		{
			return false;
		}

        if(isset($msg['error']))
		{
			return false;
        }

		return $msg;
	}
}

?>