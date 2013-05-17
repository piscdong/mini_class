<?php
/**
 * PHP Library for kaixin001.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class kaixinPHP
{
	public function __construct($client_id, $client_secret, $access_token=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
	}

	//生成授权网址
	public function login_url($callback_url, $scope=''){
		$params=array(
			'response_type'=>'code',
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url,
			'scope'=>$scope
		);
		return 'http://api.kaixin001.com/oauth2/authorize?'.http_build_query($params);
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
		$url='https://api.kaixin001.com/oauth2/access_token';
		return $this->http($url, http_build_query($params), 'POST');
	}

	//使用refresh token获取新的access token
	public function access_token_refresh($refresh_token){
		$params=array(
			'grant_type'=>'refresh_token',
			'refresh_token'=>$refresh_token,
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret
		);
		$url='https://api.kaixin001.com/oauth2/access_token';
		return $this->http($url, http_build_query($params), 'POST');
	}

	//获取登录用户信息
	public function me(){
		$params=array();
		$url='https://api.kaixin001.com/users/me.json';
		return $this->api($url, $params);
	}

	//发表记录
	public function records_add($content, $picurl=''){
		$params=array(
			'content'=>$content
		);
		if($picurl!='')$params['picurl']=$picurl;
		$url='https://api.kaixin001.com/records/add.json';
		return $this->api($url, $params, 'POST');
	}

	//获取登录用户的记录
	public function records_me($num=10, $start=0){
		$params=array(
			'start'=>$start,
			'num'=>$num
		);
		$url='https://api.kaixin001.com/records/me.json';
		return $this->api($url, $params);
	}

	//获取记录的所有评论
	public function comment_list($id, $uid, $num=10, $start=0){
		$params=array(
			'objtype'=>'records',
			'objid'=>$id,
			'ouid'=>$uid,
			'start'=>$start,
			'num'=>$num
		);
		$url='https://api.kaixin001.com/comment/list.json';
		return $this->api($url, $params);
	}

	//获取记录的所有转发
	public function forward_list($id, $uid, $num=10, $start=0){
		$params=array(
			'objtype'=>'records',
			'objid'=>$id,
			'ouid'=>$uid,
			'start'=>$start,
			'num'=>$num
		);
		$url='https://api.kaixin001.com/forward/list.json';
		return $this->api($url, $params);
	}

	//获取对记录赞过的用户列表
	public function like_show($id, $uid, $num=10, $start=0){
		$params=array(
			'objtype'=>'records',
			'objid'=>$id,
			'ouid'=>$uid,
			'start'=>$start,
			'num'=>$num
		);
		$url='https://api.kaixin001.com/like/show.json';
		return $this->api($url, $params);
	}

	//调用接口
	public function api($url, $params, $method='GET'){
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
		$headers[]="User-Agent: kaixinPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		$json_r=array();
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}