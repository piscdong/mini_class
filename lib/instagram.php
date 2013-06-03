<?php
/**
 * Instagram API client for PHP
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class instagramPHP
{
	public $api_url='https://api.instagram.com/v1/';

	public function __construct($client_id, $client_secret, $access_token=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
	}

	//生成授权网址
	public function login_url($callback_url){
		$params=array(
			'response_type'=>'code',
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url
		);
		return 'https://api.instagram.com/oauth/authorize/?'.http_build_query($params);
	}

	//获取access token
	public function access_token($callback_url, $code){
		$params=array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret,
			'redirect_uri'=>$callback_url
		);
		$url='https://api.instagram.com/oauth/access_token';
		return $this->http($url, http_build_query($params), 'POST');
	}

	/**
	//使用refresh token获取新的access token，Instagram暂时不支持
	public function access_token_refresh($refresh_token){
	}
	**/

	//根据id获取用户信息
	public function user($id){
		$params=array();
		return $this->api('users/'.$id.'/', $params);
	}

	//根据id获取用户图片列表
	public function user_media($id, $count=10, $max_id=''){
		$params=array(
			'count'=>$count
		);
		if($max_id!='')$params['max_id']=$max_id;
		return $this->api('users/'.$id.'/media/recent/', $params);
	}

	//调用接口
	/**
	//示例：根据id获取用户信息
	$result=$instagram->api('users/'.$id.'/', array(), 'GET');
	**/
	public function api($url, $params=array(), $method='GET'){
		$url=$this->api_url.$url;
		$params['access_token']=$this->access_token;
		if($method=='GET'){
			$result=$this->http($url.'?'.http_build_query($params));
		}else{
			$result=$this->http($url, http_build_query($params), 'POST');
		}
		return $result;
	}

	//提交请求
	private function http($url, $postfields='', $method='GET', $headers=array()){
		$ci=curl_init();
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		$headers[]='User-Agent: Instagram.PHP(piscdong.com)';
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		$json_r=array();
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}