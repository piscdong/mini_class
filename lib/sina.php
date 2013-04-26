<?php
/**
 * PHP Library for weibo.com
 *
 * @author PiscDong (http://www.piscdong.com/)
 */
class sinaPHP
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
			'redirect_uri'=>$callback_url
		);
		return 'https://api.weibo.com/oauth2/authorize?'.http_build_query($params);
	}

	function access_token($callback_url, $code){
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
	function access_token_refresh($refresh_token){
	}
	**/

	function get_uid(){
		$params=array();
		$url='https://api.weibo.com/2/account/get_uid.json';
		return $this->api($url, $params);
	}

	function show_user_by_id($uid){
		$params=array(
			'uid'=>$uid
		);
		$url='https://api.weibo.com/2/users/show.json';
		return $this->api($url, $params);
	}

	function statuses_count($ids){
		$params=array(
			'ids'=>$ids
		);
		$url='https://api.weibo.com/2/statuses/count.json';
		return $this->api($url, $params);
	}

	function get_comments_by_sid($id, $count=10, $page=1){
		$params=array(
			'id'=>$id,
			'page'=>$page,
			'count'=>$count
		);
		$url='https://api.weibo.com/2/comments/show.json';
		return $this->api($url, $params);
	}

	function repost_timeline($id, $count=10, $page=1){
		$params=array(
			'id'=>$id,
			'page'=>$page,
			'count'=>$count
		);
		$url='https://api.weibo.com/2/statuses/repost_timeline.json';
		return $this->api($url, $params);
	}

	function update($content, $pic=''){
		$params=array(
			'status'=>$content
		);
		if($pic!='' && is_array($pic)){
			$url='https://api.weibo.com/2/statuses/upload.json';
			$params['pic']=$pic;
		}else{
			$url='https://api.weibo.com/2/statuses/update.json';
		}
		return $this->api($url, $params, 'POST');
	}

	function user_timeline($uid, $count=10, $page=1){
		$params=array(
			'uid'=>$uid,
			'page'=>$page,
			'count'=>$count
		);
		$url='https://api.weibo.com/2/statuses/user_timeline.json';
		return $this->api($url, $params);
	}

	function querymid($id, $type=1, $is_batch=0){
		$params=array(
			'id'=>$id,
			'type'=>$type,
			'is_batch'=>$is_batch
		);
		$url='https://api.weibo.com/2/statuses/querymid.json';
		return $this->api($url, $params);
	}

	function api($url, $params, $method='GET'){
		$params['access_token']=$this->access_token;
		if($method=='GET'){
			$result=$this->http($url.'?'.http_build_query($params));
		}else{
			if(isset($params['pic'])){
				uksort($params, 'strcmp');
				$pairs=array();
				$boundary=uniqid('------------------');
				$MPboundary='--'.$boundary;
				$endMPboundary=$MPboundary. '--';
				$body='';
				foreach($params as $k=>$v){
					if($k=='pic'){
						if(is_array($v)){
							$content=$v[2];
							$filename=$v[1];
						}elseif($v{0}=='@'){
							$url=ltrim($v, '@');
							$content=file_get_contents($url);
							$array=explode('?', basename($url));
							$filename=$array[0];
						}
						$body.=$MPboundary."\r\n";
						$body.='Content-Disposition: form-data; name="'.$k.'"; filename="'.$filename.'"'."\r\n";
						$body.="Content-Type: image/unknown\r\n\r\n";
						$body.=$content."\r\n";
					}else{
						$body.=$MPboundary."\r\n";
						$body.='content-disposition: form-data; name="'.$k."\"\r\n\r\n";
						$body.=$v."\r\n";
					}
				}
				$body.=$endMPboundary;
				$headers[]="Content-Type: multipart/form-data; boundary=".$boundary;
				$result=$this->http($url, $body, 'POST', $headers);
			}else{
				$result=$this->http($url, http_build_query($params), 'POST');
			}
		}
		return $result;
	}

	function http($url, $postfields='', $method='GET', $headers=array()){
		$json_r=array();
		$ci=curl_init();
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		$headers[]="User-Agent: sinaPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);
		curl_close($ci);
		if($response!='')$json_r=json_decode($response, true);
		return $json_r;
	}
}