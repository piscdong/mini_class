<?php
/**
 * PHP Library for renren.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class renrenPHP
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
		return 'https://graph.renren.com/oauth/authorize?'.http_build_query($params);
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
		$url='https://graph.renren.com/oauth/token';
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
		$url='https://graph.renren.com/oauth/token';
		return $this->http($url, http_build_query($params), 'POST');
	}

	//获取登录用户信息
	public function me(){
		$params=array();
		return $this->api('users.getInfo', $params, 'POST');
	}

	//更新状态
	public function setStatus($status){
		$params=array(
			'status'=>$status
		);
		return $this->api('status.set', $params, 'POST');
	}

	//获取用户的状态列表
	public function getStatus($uid, $count=10, $page=1){
		$params=array(
			'uid'=>$uid,
			'page'=>$page,
			'count'=>$count
		);
		return $this->api('status.gets', $params, 'POST');
	}

	//发表一篇日志
	public function addBlog($title, $content){
		$params=array(
			'title'=>$title,
			'content'=>$content
		);
		return $this->api('blog.addBlog', $params, 'POST');
	}

	//获取日志的全部信息
	public function getBlog($id, $uid){
		$params=array(
			'id'=>$id,
			'uid'=>$uid
		);
		return $this->api('blog.get', $params, 'POST');
	}

	//获取一篇日志的评论
	public function getComments($id, $uid, $count=10, $page=1){
		$params=array(
			'id'=>$id,
			'uid'=>$uid,
			'page'=>$page,
			'count'=>$count
		);
		return $this->api('blog.getComments', $params, 'POST');
	}

	//调用接口
	public function api($method_name, $params, $method='GET'){
		$params['method']=$method_name;
		$params['v']='1.0';
		$params['access_token']=$this->access_token;
		$params['format']='json';
		ksort($params);
		$sig_str='';
		foreach($params as $k=>$v)$sig_str.=$k.'='.$v;
		$sig_str.=$this->client_secret;
		$sig=md5($sig_str);
		$params['sig']=$sig;
		$url='http://api.renren.com/restserver.do';
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
		$headers[]="User-Agent: renrenPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		$json_r=array();
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}