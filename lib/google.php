<?php
/**
 * PHP Library for google.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class googlePHP
{
	function __construct($client_id, $client_secret, $access_token=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
	}

	function login_url($callback_url){
		$params=array(
			'response_type'=>'code',
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url,
			'scope'=>'https://www.googleapis.com/auth/userinfo.profile',
			'state'=>'profile',
			'access_type'=>'offline'
		);
		return 'https://accounts.google.com/o/oauth2/auth?'.http_build_query($params);
	}

	function access_token($callback_url, $code){
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret,
			'redirect_uri'=>$callback_url
		);
		$url='https://accounts.google.com/o/oauth2/token';
		$result=$this->http($url, http_build_query($params), 'POST');
		return $result;
	}

	function access_token_refresh($refresh_token){
		$params=array(
			'grant_type'=>'refresh_token',
			'refresh_token'=>$refresh_token,
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret
		);
		$url='https://accounts.google.com/o/oauth2/token';
		$result=$this->http($url, http_build_query($params), 'POST');
		return $result;
	}

	function me(){
		$url='https://www.googleapis.com/oauth2/v1/userinfo';
		return $this->http($url, '', 'AUTH');
	}

	function http($url, $postfields='', $method='GET', $headers=array()){
		$json_r=array();
		$ci=curl_init();
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		if($method=='AUTH')$headers[]="Authorization: Bearer ".$this->access_token;
		$headers[]="User-Agent: google(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}