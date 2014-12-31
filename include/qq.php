<?
/*****************************

 oauthFramework - Tencent QQ Connect Class
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

class QQOauth extends OauthBase {

	protected $appkey = "";
	protected $secretkey = "";
	protected $callback = "";
	protected $scope = "";

	const CALLBACK = "qq.php";
	const SCOPE = "get_user_info,add_t";
	const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
	const GET_USER_INFO_URL = "https://graph.qq.com/user/get_user_info";

	public function __construct($url = "", $specialSet = "")
	{
		parent::__construct("qq", $url, $specialSet);
		
		$this->appkey = QQ_APP_ID;
		$this->secretkey = QQ_APP_KEY;
		$this->callback = DEFAULT_CALLBACKPATH . self::CALLBACK;
		$this->scope = self::SCOPE;
	}

	public function getAuthorizeURL()
	{
		$state = $this->createState();

		$params = array(
			"response_type" => "code",
            "client_id" => $this->appkey,
            "redirect_uri" => $this->callback,
            "state" => $state,
            "scope" => $this->scope
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
            "client_id" => $this->appkey,
            "client_secret" => $this->secretkey,
            "code" => $_GET["code"],
            "redirect_uri" => $this->callback
        );

		$response = $this->post(self::GET_ACCESS_TOKEN_URL, http_build_query($params, null, '&'));

		if(false === $response)
		{
			$ret["reason"] = "请求ACCESS TOKEN出错";
			return $ret;
		}

		//qq返回的信息特殊，
		//如果有错，返回信息类似于
		//callback( {"error":100004,"error_description":"param grant_type is wrong or lost "} );
		//需先去掉“callback( ”和“ )”部分
		//如果正确，返回信息类似于
		//access_token=8B00B4200611F599D930098FF57E4259&expires_in=7776000&refresh_token=4377D68E3AE7521FA12B8B263A63AE72

		//如果出现callback，很可能出错了，获取出错信息
		if(false !== strpos($response, "callback"))
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response = substr($response, $lpos + 1, $rpos - $lpos -1);

			$msg = json_decode($response, true);

			if((null === $msg) || (false === $msg))
			{
				$ret["reason"] = "解析错误数据时出错";
				return $ret;
			}

			if(isset($msg['error']))
			{
				$ret["reason"] = $msg["error_description"];
				return $ret;
			}
        }

		//否则，信息应该正常，使用数组解析它
		$msg = array();
        parse_str($response, $msg);
		
		$this->writeData("access_token", $msg['access_token']);
		$this->writeData("expires", $msg['expires_in']);

		$openid = $this->get_openid();

		if(false === $openid)
		{
			$ret["reason"] = "获取用户openid出错";
			return $ret;
		}

		$this->writeData("uid", $openid);

		$msg = $this->get_userinfo();

		if(false === $msg)
		{
			$ret["reason"] = "获取用户信息出错";
			return $ret;
		}
		
		$this->writeData("name", $msg['nickname']);
		$this->writeData("avatar", $msg['figureurl_qq_1']);
		$this->writeData("email", "");
		$this->writeData("url", $msg['url']);

		$ret["status"] = $this->finishLogin();

        return $ret;
	}

	public function get_openid()
	{
        $params = array(
            "access_token" => $this->readData("access_token")
        );

        $graph_url = self::GET_OPENID_URL . "?" . http_build_query ( $params );
        $response = $this->get($graph_url);

		//返回信息类似于 callback( {"client_id":"YOUR_appkey","openid":"YOUR_OPENID"} );
        if(false !== strpos($response, "callback"))
		{
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
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

		if(empty($msg['openid']))
		{
			return false;
		}

		return $msg['openid'];
	}

	public function get_userinfo()
	{
		$params = array(
			"oauth_consumer_key" => (int)$this->appkey,
			"access_token" => $this->readData("access_token"),
			"openid" => $this->readData("uid"),
			"format" => "json"
        );

		$api_url = self::GET_USER_INFO_URL . "?" . http_build_query ( $params );
        $response = $this->get($api_url);

		//出错信息依然可能含有callback，处理一下。
        if(strpos($response, "callback") !== false)
		{
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
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