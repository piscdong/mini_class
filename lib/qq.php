<?php
/**
 * PHP Library for qq.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class qqPHP
{
	function __construct($client_id, $client_secret, $access_token=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
	}

	function login_url($callback_url){
		$url='https://graph.qq.com/oauth2.0/authorize';
		$params=array(
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url,
			'response_type'=>'code',
			'scope'=>''
		);
		$p_str=http_build_query($params);
		return $url.'?'.$p_str;
	}

	function access_token($callback_url, $code){
		$url='https://graph.qq.com/oauth2.0/token';
		$params=array(
			'grant_type'=>'authorization_code',
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret,
			'code'=>$code,
			'state'=>'',
			'redirect_uri'=>$callback_url
		);
		$result_str=$this->http($url, $params);
		$result=array();
		if($result_str!='')parse_str($result_str, $result);
		return $result;
	}

	function get_openid(){
		$url='https://graph.qq.com/oauth2.0/me';
		$params=array(
			'access_token'=>$this->access_token
		);
		$result_str=$this->http($url, $params);
		$result=array();
		if($result_str!=''){
			preg_match('/callback\(\s+(.*?)\s+\)/i', $result_str, $result_a);
			$result=json_decode($result_a[1], true);
		}
		return $result;
	}

	function get_user_info($openid){
		$url='https://graph.qq.com/user/get_user_info';
		$params=array(
			'access_token'=>$this->access_token,
			'oauth_consumer_key'=>$this->client_id,
			'openid'=>$openid,
			'format'=>'json'
		);
		$result_str=$this->http($url, $params);
		$result=array();
		if($result_str!='')$result=json_decode($result_str, true);
		return $result;
	}

	function http($url, $params, $method='GET', $headers=array()){
		$ci=curl_init();
		if(stripos($url, 'https://')!==FALSE){
			curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		$url.='?'.http_build_query($params);
		$headers[]="User-Agent: qqPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		$response=curl_exec($ci);
		curl_close($ci);
		return $response;
	}
}