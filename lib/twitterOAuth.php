<?php
require_once('OAuth.php');

class TwitterOAuth {
	private $http_status;
	private $last_api_call;
	public static $TO_API_ROOT="https://twitter.com";
	function requestTokenURL(){ return self::$TO_API_ROOT.'/oauth/request_token'; }
	function authorizeURL(){ return self::$TO_API_ROOT.'/oauth/authorize'; }
	function accessTokenURL(){ return self::$TO_API_ROOT.'/oauth/access_token'; }
	function lastStatusCode(){ return $this->http_status; }
	function lastAPICall(){ return $this->last_api_call; }

	function __construct($consumer_key, $consumer_secret, $oauth_token=NULL, $oauth_token_secret=NULL){
		$this->sha1_method=new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer=new OAuthConsumer($consumer_key, $consumer_secret);
		if(!empty($oauth_token) && !empty($oauth_token_secret)){
			$this->token=new OAuthConsumer($oauth_token, $oauth_token_secret);
		}else{
			$this->token=NULL;
		}
	}

	function getRequestToken(){
		$r=$this->oAuthRequest($this->requestTokenURL());
		$token=$this->oAuthParseResponse($r);
		$this->token=new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	function oAuthParseResponse($responseString){
		$r=array();
		foreach(explode('&', $responseString) as $param){
			$pair=explode('=', $param, 2);
			if(count($pair)!=2)continue;
			$r[urldecode($pair[0])]=urldecode($pair[1]);
		}
		return $r;
	}

	function getAuthorizeURL($token){
		if(is_array($token)) $token=$token['oauth_token'];
		return $this->authorizeURL().'?oauth_token='.$token;
	}

	function getAccessToken($token=NULL){
		$r=$this->oAuthRequest($this->accessTokenURL());
		$token=$this->oAuthParseResponse($r);
		$this->token=new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	function oAuthRequest($url, $args=array(), $method=NULL){
		if(empty($method)) $method=empty($args)?"GET":"POST";
		$req=OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $args);
		$req->sign_request($this->sha1_method, $this->consumer, $this->token);
		switch($method){
			case 'GET': return $this->http($req->to_url());
			case 'POST': return $this->http($req->get_normalized_http_url(), $req->to_postdata());
		}
	}

	function http($url, $post_data=null){
		$ch=curl_init();
		if(defined("CURL_CA_BUNDLE_PATH"))curl_setopt($ch, CURLOPT_CAINFO, CURL_CA_BUNDLE_PATH);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if(isset($post_data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		$response=curl_exec($ch);
		$this->http_status=curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->last_api_call=$url;
		curl_close($ch);
		return $response;
	}
}