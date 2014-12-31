<?
/*****************************

 oauthFramework - Microsoft Live Class
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

class LiveOauth extends OauthBase {

	protected $appkey = "";
	protected $secretkey = "";
	protected $callback = "";
	protected $scope = "";
	
	const CALLBACK = "live.php";
	const SCOPE = "wl.basic,wl.emails";
	const GET_AUTH_CODE_URL = "https://login.live.com/oauth20_authorize.srf";
    const GET_ACCESS_TOKEN_URL = "https://login.live.com/oauth20_token.srf";
	const GET_USER_INFO_URL = "https://apis.live.net/v5.0/me";

	public function __construct($url = "", $specialSet = "")
	{
		parent::__construct("live", $url, $specialSet);
		
		$this->appkey = LIVE_APP_KEY;
		$this->secretkey = LIVE_SECRET_KEY;
		$this->callback = DEFAULT_CALLBACKPATH . self::CALLBACK;
		$this->scope = self::SCOPE;
	}

	public function getAuthorizeURL()
	{
		$state = $this->createState();

		$params = array(
            "client_id" => $this->appkey,
			"scope" => $this->scope,
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
            "client_id" => $this->appkey,
            "redirect_uri" => $this->callback,
            "client_secret" => $this->secretkey,
            "code" => $_GET["code"],
            "grant_type" => "authorization_code"
        );

		$response = $this->post(self::GET_ACCESS_TOKEN_URL, http_build_query($params, null, '&'));

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

		if(isset($msg['error']))
		{
			$ret["reason"] = $msg["error"];
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
		$this->writeData("avatar", "");
		$this->writeData("email", $msg['emails']['preferred']);
		$this->writeData("url", $msg['link']);

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