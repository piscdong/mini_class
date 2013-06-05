<?php
/**
 * 新浪微博 API client for PHP
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class sinaPHP
{
	public $api_url='https://api.weibo.com/2/';
	public $format='json';

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
		return 'https://api.weibo.com/oauth2/authorize?'.http_build_query($params);
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
		$url='https://api.weibo.com/oauth2/access_token';
		return $this->http($url, http_build_query($params), 'POST');
	}

	/**
	//使用refresh token获取新的access token，新浪微博暂时不支持
	public function access_token_refresh($refresh_token){
	}
	**/

	//获取登录用户的uid
	public function get_uid(){
		$params=array();
		return $this->api('account/get_uid', $params);
	}

	//根据uid获取用户信息
	public function show_user_by_id($uid){
		$params=array(
			'uid'=>$uid
		);
		return $this->api('users/show', $params);
	}

	//发布微博
	public function update($img_c, $pic=''){
		$params=array(
			'status'=>$img_c
		);
		if($pic!='' && is_array($pic)){
			$url='statuses/upload';
			$params['pic']=$pic;
		}else{
			$url='statuses/update';
		}
		return $this->api($url, $params, 'POST');
	}

	//根据uid获取用户微博列表
	public function user_timeline($uid, $count=10, $page=1){
		$params=array(
			'uid'=>$uid,
			'page'=>$page,
			'count'=>$count
		);
		return $this->api('statuses/user_timeline', $params);
	}

	//调用接口
	/**
	//示例：根据uid获取用户信息
	$result=$sina->api('users/show', array('uid'=>$uid), 'GET');
	**/
	public function api($url, $params=array(), $method='GET'){
		$url=$this->api_url.$url.'.'.$this->format;
		$params['access_token']=$this->access_token;
		if($method=='GET'){
			$result=$this->http($url.'?'.http_build_query($params));
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
				$result=$this->http($url, $body, 'POST', $headers);
			}else{
				$result=$this->http($url, http_build_query($params), 'POST');
			}
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
		$headers[]='User-Agent: weibo.PHP(piscdong.com)';
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		$json_r=array();
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}
