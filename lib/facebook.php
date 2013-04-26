<?php
/**
 * PHP Library for facebook.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class facebookPHP
{
	function __construct($client_id, $client_secret, $access_token=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
	}

	function login_url($callback_url, $scope){
		$params=array(
			'response_type'=>'code',
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url,
			'scope'=>$scope
		);
		return 'https://graph.facebook.com/oauth/authorize?'.http_build_query($params);
	}

	function access_token($callback_url, $code){
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret,
			'redirect_uri'=>$callback_url
		);
		$url='https://graph.facebook.com/oauth/access_token';
		$result_str=$this->http($url, http_build_query($params), 'POST');
		$json_r=array();
		if($result_str!='')parse_str($result_str, $json_r);
		return $json_r;
	}

	/**
	function access_token_refresh($refresh_token){
	}
	**/

	function me(){
		$params=array();
		$url='https://graph.facebook.com/me';
		return $this->api($url, $params);
	}

	function my_feed($count=10, $page=1){
		$params=array(
			'limit'=>$count,
			'offset'=>($page-1)*$count
		);
		$url='https://graph.facebook.com/me/feed';
		return $this->api($url, $params);
	}

	function update($content){
		$params=array(
			'message'=>$content
		);
		$url='https://graph.facebook.com/me/feed/';
		return $this->api($url, $params, 'POST');
	}

	function api($url, $params, $method='GET'){
		$params['access_token']=$this->access_token;
		if($method=='GET'){
			$result_str=$this->http($url.'?'.http_build_query($params));
		}else{
			$result_str=$this->http($url, http_build_query($params), 'POST');
		}
		$json_r=array();
		if($result_str!='')$json_r=json_decode($result_str, true);
		return $json_r;
	}

	function http($url, $postfields='', $method='GET', $headers=array()){
		$ci=curl_init();
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		$headers[]="User-Agent: facebookPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		return $response;
	}
}