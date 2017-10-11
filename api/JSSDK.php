<?php
class JSSDK
{
	private $appId;
	private $appSecret;
	private $appDir;
	private $nonce;
	private $timestamp;

	public function __construct($appId, $appSecret)
	{
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->appDir = __DIR__."/";
		$this->appDirKeys = __DIR__."/../keys/";
		$this->timestamp = time();
		$this->nonce = $this->createNonceStr();
	}

	public function getAppUrl()
	{
		return $this->appDir;
	}

	public function signUrls($appUrl='', $queryStrings=[], $queryString='')
	{
		$data['current'] = $this->getSignPackage($appUrl, (!empty($queryString))?'?'.$queryString:'' );
		foreach($queryStrings as $qs):
			$data[$qs] = $this->getSignPackage($appUrl, ($qs!='main')?'?p='.$qs:'' );
		endforeach;
		return $data;
	}

	public function getSignPackage($appUrl='', $signUri='')
	{
		$jsapiTicket = $this->getJsApiTicket();
		$url = "$appUrl$signUri";
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$this->nonce&timestamp=$this->timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
				"appId"     => $this->appId,
				"nonceStr"  => $this->nonce,
				"timestamp" => $this->timestamp,
				"url"       => $url,
				"signature" => $signature,
				"rawString" => $string,
				"URI" => $signUri
				);
		return $signPackage;
	}

	private function createNonceStr($length = 16)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	private function getJsApiTicket()
	{

		$data = json_decode(file_get_contents($this->appDirKeys .$this->appId."_jsapi_ticket.json"));
		//print_r($data); print " getJsApiTicket - json data<br>";
		if (!isset($data->expire_time) || $data->expire_time < time()) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode($this->httpGet($url));
			//print_r($res); print " getJsApiTicket - call res<br>";
			$ticket = $res->ticket;

			if ($ticket) {
				$data->expire_time = time() + 7000;
				$data->jsapi_ticket = $ticket;
				$fp = fopen($this->appDirKeys .$this->appId."_jsapi_ticket.json", "w+");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}


		} else {
			$ticket = $data->jsapi_ticket;
			//print_r($ticket); print "ticket already exist, take it - getJsApiTicket<br>";
		}

		return $ticket;
	}

	private function getAccessToken()
	{
		$data = json_decode(file_get_contents($this->appDirKeys . $this->appId."_access_token.json"));
		//print_r($data); print " getAccessToken - json data<br>";

		if (!isset($data->expire_time) || $data->expire_time < time()) {

			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			//print_r($res); print " getAccessToken - call res<br>";

			if ($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				ini_set('track_errors', 1);
				$fp = fopen($this->appDirKeys . $this->appId."_access_token.json", "w+");
				if ( !$fp ) {
					echo 'fopen failed. reason: ', $php_errormsg;
				}
				//print_r($this->appDir . "access_token.json"); print " trying printing access token<br>";
				fwrite($fp, json_encode($data));
				fclose($fp);
			}


		} else {
			$access_token = $data->access_token;
			//print_r($access_token); print "access already exist, take it - access_token<br>";
		}

		return $access_token;
	}

	private function httpGet($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}
