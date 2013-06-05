<?php
/**
 * 腾讯微博 API client for PHP
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class tqqPHP
{
	public $api_url='https://open.t.qq.com/api/';

	public function __construct($client_id, $client_secret, $access_token=NULL, $openid=NULL){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->access_token=$access_token;
		$this->openid=$openid;
	}

	//生成授权网址
	public function login_url($callback_url){
		$params=array(
			'response_type'=>'code',
			'client_id'=>$this->client_id,
			'redirect_uri'=>$callback_url
		);
		return 'https://open.t.qq.com/cgi-bin/oauth2/authorize?'.http_build_query($params);
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
		$url='https://open.t.qq.com/cgi-bin/oauth2/access_token?'.http_build_query($params);
		$result_str=$this->http($url);
		$json_r=array();
		if($result_str!='')parse_str($result_str, $json_r);
		return $json_r;
	}

	//使用refresh token获取新的access token
	public function access_token_refresh($refresh_token){
		$params=array(
			'grant_type'=>'refresh_token',
			'refresh_token'=>$refresh_token,
			'client_id'=>$this->client_id
		);
		$url='https://open.t.qq.com/cgi-bin/oauth2/access_token?'.http_build_query($params);
		$result_str=$this->http($url);
		$json_r=array();
		if($result_str!='')parse_str($result_str, $json_r);
		return $json_r;
	}

	//获取登录用户信息
	public function me(){
		$params=array();
		return $this->api('user/info', $params);
	}

	//获取登录用户微博列表
	public function getMyTweet($reqnum=10, $pageflag=0){
		$params=array(
			'pageflag'=>$pageflag,
			'reqnum'=>$reqnum
		);
		return $this->api('statuses/broadcast_timeline', $params);
	}

	//发布微博
	public function postOne($img_c, $pic=''){
		$params=array(
			'content'=>$img_c
		);
		if($pic!='' && is_array($pic)){
			$url='t/add_pic';
			$params['pic']=$pic;
		}else{
			$url='t/add';
		}
		return $this->api($url, $params, 'POST');
	}

	//调用接口
	/**
	//示例：获取登录用户信息
	$result=$tqq->api('user/info', array(), 'GET');
	**/
	public function api($url, $params=array(), $method='GET'){
		$url=$this->api_url.$url;
		$params['oauth_consumer_key']=$this->client_id;
		$params['access_token']=$this->access_token;
		$params['openid']=$this->openid;
		$params['clientip']=$this->getIP();
		$params['oauth_version']='2.a';
		$params['format']='json';
		$params['scope']='all';
		if($method=='GET'){
			$result_str=$this->http($url.'?'.http_build_query($params));
		}else{
			if(isset($params['pic'])){
				uksort($params, 'strcmp');
				$str_b=uniqid('------------------');
				$str_m='--'.$str_b;
				$str_e=$str_m. '--';
				$body='';
				foreach($params as $k=>$v){
					if($k=='pic'){
						if(is_array($v)){
							$img_c=$v[2];
							$img_n=$v[1];
						}elseif($v{0}=='@'){
							$url=ltrim($v, '@');
							$img_c=file_get_contents($url);
							$url_a=explode('?', basename($url));
							$img_n=$url_a[0];
						}
						$body.=$str_m."\r\n";
						$body.='Content-Disposition: form-data; name="'.$k.'"; filename="'.$img_n.'"'."\r\n";
						$body.="Content-Type: image/unknown\r\n\r\n";
						$body.=$img_c."\r\n";
					}else{
						$body.=$str_m."\r\n";
						$body.='Content-Disposition: form-data; name="'.$k.'"'."\r\n\r\n";
						$body.=$v."\r\n";
					}
				}
				$body.=$str_e;
				$headers[]='Content-Type: multipart/form-data; boundary='.$str_b;
				$result_str=$this->http($url, $body, 'POST', $headers);
			}else{
				$result_str=$this->http($url, http_build_query($params), 'POST');
			}
		}
		$json_r=array();
		if($result_str!='')$json_r=json_decode($result_str, true);
		return $json_r;
	}

	//获取IP地址
	private function getIP(){
		if(isset($_ENV['HTTP_CLIENT_IP'])){
			$ip=$_ENV['HTTP_CLIENT_IP'];
		}elseif(isset($_ENV['HTTP_X_FORWARDED_FOR'])){
			$ip=$_ENV['HTTP_X_FORWARDED_FOR'];
		}elseif(isset($_ENV['REMOTE_ADDR'])){
			$ip=$_ENV['REMOTE_ADDR'];
		}else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		if(strstr($ip, ':')){
			$ipa=explode(':', $ip);
			foreach($ipa as $v){
				if(strlen($v)>7)$ip=$v;
			}
		}
		if(strlen($ip)<7)$ip='0.0.0.0';
		return $ip;
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
		$headers[]='User-Agent: tQQ.PHP(piscdong.com)';
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		return $response;
	}
}
