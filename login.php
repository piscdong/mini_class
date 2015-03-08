<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(!$c_log){
	$title.='登录';
	if($config['is_qq']>0 && $config['qq_app_id']!='' && $config['qq_app_key']!='')$a_sync['qq']='QQ';
	if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!='')$a_sync['sina']='新浪微博';
	if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!='')))$a_sync['tqq']='腾讯微博';
	if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!='')$a_sync['renren']='人人网';
	if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!='')$a_sync['kx001']='开心网';
	if($config['is_baidu']>0 && $config['baidu_key']!='' && $config['baidu_se']!='')$a_sync['baidu']='百度';
	if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!='')$a_sync['douban']='豆瓣';
	if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!='')$a_sync['google']='Google';
	if($config['is_live']>0 && $config['live_key']!='' && $config['live_se']!='')$a_sync['live']='Microsoft';
	if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!='')$a_sync['t163']='网易微博';
	if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!='')))$a_sync['tsohu']='搜狐微博';
	if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!='')$a_sync['twitter']='Twitter';
	if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!='')$a_sync['facebook']='Facebook';
	$nct=(isset($_GET['t']) && isset($a_sync[$_GET['t']]))?$_GET['t']:'';
	$vdb=$config['veri']>0?'':' and a.status=0';
	$goto='./';
	if($nct!=''){
		if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
			$_SESSION['login_sync_tn']='';
			$_SESSION['login_sync_id']='';
			$_SESSION['login_sync_t']='';
			$_SESSION['login_sync_r']='';
			$_SESSION['login_sync_s']='';
			$_SESSION['login_sync_u']='';
			$_SESSION['login_sync_edate']=0;
		}
	}
	switch($nct){
		case 'facebook':
			require_once('lib/facebook.php');
			$fb=new facebookPHP($config['fb_app_id'], $config['fb_se'], $_SESSION['facebook_login_u_t']);
			$fb_me=$fb->me();
			if(isset($fb_me['id']) && $fb_me['id']!=''){
				if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
					$goto='m/';
					$_SESSION[$config['u_hash'].'_m']='';
					unset($_SESSION[$config['u_hash'].'_m']);
				}
				$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($fb_me['id'], 'text'), SQLString($nct, 'text'), $vdb);
				$q_dbu=mysql_query($s_dbu) or die('');
				$r_dbu=mysql_fetch_assoc($q_dbu);
				if(mysql_num_rows($q_dbu)>0){
					if($r_dbu['s_t']!=$_SESSION['facebook_login_u_t']){
						$u_db=sprintf('update %s set s_t=%s where id=%s', $dbprefix.'m_sync',
							SQLString($_SESSION['facebook_login_u_t'], 'text'),
							$r_dbu['bid']);
						$result=mysql_query($u_db) or die('');
					}
					$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
					$result=mysql_query($u_db) or die('');
					$_SESSION[$config['u_hash']]=$r_dbu['id'];
					if($r_dbu['sylorm']=='1'){
						setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
						setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
					}else{
						setcookie($config['u_hash'].'_u','',time());
						setcookie($config['u_hash'].'_p','',time());
					}
				}else{
					$goto.='?m=login';
					$_SESSION['login_sync_tn']=$nct;
					$_SESSION['login_sync_id']=$fb_me['id'];
					$_SESSION['login_sync_t']=$_SESSION['facebook_login_u_t'];
					$_SESSION['login_sync_r']='';
					$_SESSION['login_sync_s']='';
					$_SESSION['login_sync_u']='<a href="'.$fb_me['link'].'" target="_blank">'.$fb_me['name'].'</a>';
					$_SESSION['login_sync_edate']=0;
				}
				mysql_free_result($q_dbu);
				$_SESSION['facebook_login_u_t']='';
				header('Location:'.$goto);
				exit();
			}else{
				$_SESSION['facebook_login_u_t']='';
			}
			if(!isset($_SESSION['facebook_login_u_t']) || $_SESSION['facebook_login_u_t']==''){
				$fb=new facebookPHP($config['fb_app_id'], $config['fb_se']);
				$aurl=$fb->login_url($config['site_url'].'facebook_callback.php', 'user_about_me');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'twitter':
			require_once('lib/twitterOAuth.php');
			if(isset($_SESSION['tw_login_u_t']) && $_SESSION['tw_login_u_t']!='' && isset($_SESSION['tw_login_u_s']) && $_SESSION['tw_login_u_s']!=''){
				$to=new TwitterOAuth($config['tw_key'], $config['tw_se'], $_SESSION['tw_login_u_t'], $_SESSION['tw_login_u_s']);
				$ma=$to->OAuthRequest('https://twitter.com/account/verify_credentials.json', array(), 'GET');
				if(isset($ma['id']) && $ma['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($ma['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['tw_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_s=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['tw_login_u_t'], 'text'),
								SQLString($_SESSION['tw_login_u_s'], 'text'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$ma['id'];
						$_SESSION['login_sync_t']=$_SESSION['tw_login_u_t'];
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']=$_SESSION['tw_login_u_s'];
						$_SESSION['login_sync_u']='<a href="http://twitter.com/'.$ma['screen_name'].'" target="_blank">'.$ma['name'].'</a>';
						$_SESSION['login_sync_edate']=0;
					}
					$_SESSION['tw_login_u_t']='';
					$_SESSION['tw_login_u_s']='';
					mysql_free_result($q_dbu);
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['tw_login_u_t']='';
					$_SESSION['tw_login_u_s']='';
				}
			}
			if(!isset($_SESSION['tw_login_u_t']) || $_SESSION['tw_login_u_t']=='' || !isset($_SESSION['tw_login_u_s']) || $_SESSION['tw_login_u_s']==''){
				$to=new TwitterOAuth($config['tw_key'], $config['tw_se']);
				$tok=$to->getRequestToken();
				$_SESSION['tw_login_token']=$tok['oauth_token'];
				$_SESSION['tw_login_secret']=$tok['oauth_token_secret'];
				$aurl=$to->getAuthorizeURL($tok['oauth_token']);
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'tqq':
			require_once('lib/tqq.php');
			if(isset($_SESSION['tqq_login_u_id']) && $_SESSION['tqq_login_u_id']!='' && isset($_SESSION['tqq_login_u_t']) && $_SESSION['tqq_login_u_t']!=''){
				$o=new tqqPHP($config['tqq_key'], $config['tqq_se'], $_SESSION['tqq_login_u_t'], $_SESSION['tqq_login_u_id']);
				$ma=$o->me();
				if(isset($ma['ret']) && $ma['ret']==0 && isset($ma['data']) && is_array($ma['data'])){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($ma['data']['openid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['tqq_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['tqq_login_u_t'], 'text'),
								SQLString($_SESSION['tqq_login_u_r'], 'text'),
								SQLString($_SESSION['tqq_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$ma['data']['openid'];
						$_SESSION['login_sync_t']=$_SESSION['tqq_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['tqq_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="http://t.qq.com/'.$ma['data']['name'].'" target="_blank">'.$ma['data']['nick'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['tqq_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['tqq_login_u_id']='';
					$_SESSION['tqq_login_u_t']='';
					$_SESSION['tqq_login_u_r']='';
					$_SESSION['tqq_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['tqq_login_u_id']='';
					$_SESSION['tqq_login_u_t']='';
					$_SESSION['tqq_login_u_r']='';
					$_SESSION['tqq_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['tqq_login_u_id']) || $_SESSION['tqq_login_u_id']=='' || !isset($_SESSION['tqq_login_u_t']) || $_SESSION['tqq_login_u_t']==''){
				$o=new tqqPHP($config['tqq_key'], $config['tqq_se']);
				$aurl=$o->login_url($config['site_url'].'tqq_callback.php');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'sina':
			require_once('lib/sina.php');
			if(isset($_SESSION['sina_login_u_t']) && $_SESSION['sina_login_u_t']!=''){
				$so=new sinaPHP($config['sina_key'], $config['sina_se'], $_SESSION['sina_login_u_t']);
				$ma=$so->get_uid();
				if(isset($ma['uid']) && !isset($ma['error'])){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($ma['uid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['sina_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['sina_login_u_t'], 'text'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$sina_u=$so->show_user_by_id($ma['uid']);
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$ma['uid'];
						$_SESSION['login_sync_t']=$_SESSION['sina_login_u_t'];
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="http://weibo.com/'.((isset($sina_u['domain']) && $sina_u['domain']!='')?$sina_u['domain']:$ma['uid']).'" target="_blank">'.$sina_u['name'].'</a>';
						$_SESSION['login_sync_edate']=0;
					}
					mysql_free_result($q_dbu);
					$_SESSION['sina_login_u_t']='';
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['sina_login_u_t']='';
				}
			}
			if(!isset($_SESSION['sina_login_u_t']) || $_SESSION['sina_login_u_t']==''){
				$so=new sinaPHP($config['sina_key'], $config['sina_se']);
				$aurl=$so->login_url($config['site_url'].'sina_callback.php');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'qq':
			require_once('lib/qq.php');
			if(isset($_SESSION['qq_login_u_t']) && $_SESSION['qq_login_u_t']!=''){
				$qq=qqPHP($config['qq_app_id'], $config['qq_app_key'], $_SESSION['qq_login_u_t']);
				$q_a=$qq->get_openid();
				if(isset($q_a['openid']) && $q_a['openid']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($q_a['openid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['qq_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['qq_login_u_t'], 'text'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$q_u=$qq->get_user_info($q_a['openid']);
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$q_a['openid'];
						$_SESSION['login_sync_t']=$_SESSION['qq_login_u_t'];
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']=$q_u['nickname'];
						$_SESSION['login_sync_edate']=0;
					}
					mysql_free_result($q_dbu);
					$_SESSION['qq_login_u_t']='';
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['qq_login_u_t']='';
				}
			}
			if(!isset($_SESSION['qq_login_u_t']) || $_SESSION['qq_login_u_t']==''){
				$qq=new qqPHP($config['qq_app_id'], $config['qq_app_key']);
				$qurl=$qq->login_url($config['site_url'].'qq_callback.php');
				header('Location:'.$qurl);
				exit();
			}
			break;
		case 'kx001':
			require_once('lib/kaixin.php');
			if(isset($_SESSION['kx001_login_u_t']) && $_SESSION['kx001_login_u_t']!=''){
				$kx_co=new kaixinPHP($config['kx001_key'], $config['kx001_se'], $_SESSION['kx001_login_u_t']);
				$kx_re=$kx_co->me();
				if(isset($kx_re['uid']) && $kx_re['uid']!='' && !isset($kx_re['error_code'])){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($kx_re['uid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['kx001_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['kx001_login_u_t'], 'text'),
								SQLString($_SESSION['kx001_login_u_r'], 'text'),
								SQLString($_SESSION['kx001_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$kx_re['uid'];
						$_SESSION['login_sync_t']=$_SESSION['kx001_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['kx001_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="http://www.kaixin001.com/home/?uid='.$kx_re['uid'].'" target="_blank">'.$kx_re['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['kx001_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['kx001_login_u_t']='';
					$_SESSION['kx001_login_u_r']='';
					$_SESSION['kx001_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['kx001_login_u_t']='';
					$_SESSION['kx001_login_u_r']='';
					$_SESSION['kx001_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['kx001_login_u_t']) || $_SESSION['kx001_login_u_t']==''){
				$kx_uco=new kaixinPHP($config['kx001_key'], $config['kx001_se']);
				$aurl=$kx_uco->login_url($config['site_url'].'kx001_callback.php', 'user_records create_records');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'renren':
			require_once('lib/renren.php');
			if(isset($_SESSION['renren_login_u_t']) && $_SESSION['renren_login_u_t']!=''){
				$rr_c=new renrenPHP($config['renren_key'], $config['renren_se'], $_SESSION['renren_login_u_t']);
				$rr_me=$rr_c->me();
				if(isset($rr_me[0]['uid']) && $rr_me[0]['uid']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($rr_me[0]['uid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['renren_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['renren_login_u_t'], 'text'),
								SQLString($_SESSION['renren_login_u_r'], 'text'),
								SQLString($_SESSION['renren_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$rr_me[0]['uid'];
						$_SESSION['login_sync_t']=$_SESSION['renren_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['renren_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="http://www.renren.com/'.$rr_me[0]['uid'].'" target="_blank">'.$rr_me[0]['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['renren_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['renren_login_u_t']='';
					$_SESSION['renren_login_u_r']='';
					$_SESSION['renren_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['renren_login_u_t']='';
					$_SESSION['renren_login_u_r']='';
					$_SESSION['renren_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['renren_login_u_t']) || $_SESSION['renren_login_u_t']==''){
				$rr_co=new renrenPHP($config['renren_key'], $config['renren_se']);
				$aurl=$rr_co->login_url($config['site_url'].'renren_callback.php', 'status_update read_user_status');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'douban':
			require_once('lib/douban.php');
			if(isset($_SESSION['douban_login_u_t']) && $_SESSION['douban_login_u_t']!=''){
				$db_o=new doubanPHP($config['douban_key'], $config['douban_se'], $_SESSION['douban_login_u_t']);
				$me=$db_o->me();
				if(isset($me['id']) && $me['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($me['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['douban_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['douban_login_u_t'], 'text'),
								SQLString($_SESSION['douban_login_u_r'], 'text'),
								SQLString($_SESSION['douban_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$me['id'];
						$_SESSION['login_sync_t']=$_SESSION['douban_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['douban_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="'.$me['alt'].'" target="_blank">'.$me['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['douban_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['douban_login_u_t']='';
					$_SESSION['douban_login_u_r']='';
					$_SESSION['douban_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['douban_login_u_t']='';
					$_SESSION['douban_login_u_r']='';
					$_SESSION['douban_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['douban_login_u_t']) || $_SESSION['douban_login_u_t']==''){
				$db_o=new doubanPHP($config['douban_key'], $config['douban_se']);
				$aurl=$db_o->login_url($config['site_url'].'douban_callback.php', 'douban_basic_common');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'google':
			require_once('lib/google.php');
			if(isset($_SESSION['google_login_u_t']) && $_SESSION['google_login_u_t']!=''){
				$gg_o=new googlePHP($config['google_key'], $config['google_se'], $_SESSION['google_login_u_t']);
				$me=$gg_o->me();
				if(isset($me['id']) && $me['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($me['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['google_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['google_login_u_t'], 'text'),
								SQLString($_SESSION['google_login_u_r'], 'text'),
								SQLString($_SESSION['google_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$me['id'];
						$_SESSION['login_sync_t']=$_SESSION['google_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['google_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="'.$me['link'].'" target="_blank">'.$me['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['google_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['google_login_u_t']='';
					$_SESSION['google_login_u_r']='';
					$_SESSION['google_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['google_login_u_t']='';
					$_SESSION['google_login_u_r']='';
					$_SESSION['google_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['google_login_u_t']) || $_SESSION['google_login_u_t']==''){
				$gg_o=new googlePHP($config['google_key'], $config['google_se']);
				$aurl=$gg_o->login_url($config['site_url'].'google_callback.php', 'https://www.googleapis.com/auth/userinfo.profile');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'live':
			require_once('lib/live.php');
			if(isset($_SESSION['live_login_u_t']) && $_SESSION['live_login_u_t']!=''){
				$ms_o=new livePHP($config['live_key'], $config['live_se'], $_SESSION['live_login_u_t']);
				$me=$ms_o->me();
				if(isset($me['id']) && $me['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($me['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['live_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['live_login_u_t'], 'text'),
								SQLString($_SESSION['live_login_u_r'], 'text'),
								SQLString($_SESSION['live_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$me['id'];
						$_SESSION['login_sync_t']=$_SESSION['live_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['live_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="'.$me['link'].'" target="_blank">'.$me['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['live_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['live_login_u_t']='';
					$_SESSION['live_login_u_r']='';
					$_SESSION['live_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['live_login_u_t']='';
					$_SESSION['live_login_u_r']='';
					$_SESSION['live_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['live_login_u_t']) || $_SESSION['live_login_u_t']==''){
				$ms_o=new livePHP($config['live_key'], $config['live_se']);
				$aurl=$ms_o->login_url($config['site_url'].'live_callback.php', 'wl.basic,wl.offline_access');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'tsohu':
			require_once('lib/SohuOAuth.php');
			if(isset($_SESSION['tsohu_login_u_t']) && $_SESSION['tsohu_login_u_t']!='' && isset($_SESSION['tsohu_login_u_s']) && $_SESSION['tsohu_login_u_s']!=''){
				$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se'], $_SESSION['tsohu_login_u_t'], $_SESSION['tsohu_login_u_s']);
				$url='http://api.t.sohu.com/users/show.json';
				$ma=$oauth->get($url);
				if(isset($ma['id']) && $ma['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($ma['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['tsohu_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_s=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['tsohu_login_u_t'], 'text'),
								SQLString($_SESSION['tsohu_login_u_s'], 'text'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$ma['id'];
						$_SESSION['login_sync_t']=$_SESSION['tsohu_login_u_t'];
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']=$_SESSION['tsohu_login_u_s'];
						$_SESSION['login_sync_u']='<a href="http://t.sohu.com/u/'.$ma['id'].'" target="_blank">'.$ma['screen_name'].'</a>';
						$_SESSION['login_sync_edate']=0;
					}
					mysql_free_result($q_dbu);
					$_SESSION['tsohu_login_u_t']='';
					$_SESSION['tsohu_login_u_s']='';
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['tsohu_login_u_t']='';
					$_SESSION['tsohu_login_u_s']='';
				}
			}
			if(!isset($_SESSION['tsohu_login_u_t']) || $_SESSION['tsohu_login_u_t']=='' || !isset($_SESSION['tsohu_login_u_s']) || $_SESSION['tsohu_login_u_s']==''){
				$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se']);
				$request_token=$oauth->getRequestToken($config['site_url'].'tsohu_callback.php');
				$_SESSION['tsohu_login_token']=$request_token['oauth_token'];
				$_SESSION['tsohu_login_secret']=$request_token['oauth_token_secret'];
				switch($oauth->http_code){
					case 200:
						$aurl=$oauth->getAuthorizeUrl1($request_token['oauth_token'], $config['site_url'].'tsohu_callback.php');
						break;
					default:
						$aurl='./?m=login';
						break;
				}
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 't163':
			require_once('lib/t163.php');
			if(isset($_SESSION['t163_login_u_t']) && $_SESSION['t163_login_u_t']!=''){
				$tblog=new t163PHP($config['t163_key'], $config['t163_se'], $_SESSION['t163_login_u_t']);
				$me=$tblog->me();
				if(isset($me['id']) && $me['id']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($me['id'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['t163_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['t163_login_u_t'], 'text'),
								SQLString($_SESSION['t163_login_u_r'], 'text'),
								SQLString($_SESSION['t163_login_u_edate'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$me['id'];
						$_SESSION['login_sync_t']=$_SESSION['t163_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['t163_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='<a href="http://t.163.com/'.$me['screen_name'].'" target="_blank">'.$me['name'].'</a>';
						$_SESSION['login_sync_edate']=$_SESSION['t163_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['t163_login_u_t']='';
					$_SESSION['t163_login_u_r']='';
					$_SESSION['t163_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['t163_login_u_t']='';
					$_SESSION['t163_login_u_r']='';
					$_SESSION['t163_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['t163_login_u_t']) || $_SESSION['t163_login_u_t']==''){
				$oauth=new t163PHP($config['t163_key'], $config['t163_se']);
				$aurl=$oauth->login_url($config['site_url'].'t163_callback.php');
				header('Location:'.$aurl);
				exit();
			}
			break;
		case 'baidu':
			require_once('lib/baidu.php');
			if(isset($_SESSION['baidu_login_u_t']) && $_SESSION['baidu_login_u_t']!=''){
				$bo=new baiduPHP($config['baidu_key'], $config['baidu_se'], $_SESSION['baidu_login_u_t']);
				$ba=$bo->me();
				if(!isset($ba['error_code']) && isset($ba['uid']) && $ba['uid']!=''){
					if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
						$goto='m/';
						$_SESSION[$config['u_hash'].'_m']='';
						unset($_SESSION[$config['u_hash'].'_m']);
					}
					$s_dbu=sprintf('select a.id, a.username, a.password, a.sylorm, b.s_t, b.id as bid from %s as a, %s as b where a.id=b.aid and b.s_id=%s and b.name=%s%s limit 1', $dbprefix.'member', $dbprefix.'m_sync', SQLString($ba['uid'], 'text'), SQLString($nct, 'text'), $vdb);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['s_t']!=$_SESSION['baidu_login_u_t']){
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['baidu_login_u_t'], 'text'),
								SQLString($_SESSION['baidu_login_u_r'], 'text'),
								SQLString($_SESSION['baidu_login_u_date'], 'int'),
								$r_dbu['bid']);
							$result=mysql_query($u_db) or die('');
						}
						$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						$_SESSION[$config['u_hash']]=$r_dbu['id'];
						if($r_dbu['sylorm']=='1'){
							setcookie($config['u_hash'].'_u', $r_dbu['username'], time()+86400*30);
							setcookie($config['u_hash'].'_p', $r_dbu['password'], time()+86400*30);
						}else{
							setcookie($config['u_hash'].'_u','',time());
							setcookie($config['u_hash'].'_p','',time());
						}
					}else{
						$goto.='?m=login';
						$_SESSION['login_sync_tn']=$nct;
						$_SESSION['login_sync_id']=$ba['uid'];
						$_SESSION['login_sync_t']=$_SESSION['baidu_login_u_t'];
						$_SESSION['login_sync_r']=$_SESSION['baidu_login_u_r'];
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']=$ba['uname'];
						$_SESSION['login_sync_edate']=$_SESSION['baidu_login_u_edate'];
					}
					mysql_free_result($q_dbu);
					$_SESSION['baidu_login_u_t']='';
					$_SESSION['baidu_login_u_r']='';
					$_SESSION['baidu_login_u_edate']=0;
					header('Location:'.$goto);
					exit();
				}else{
					$_SESSION['baidu_login_u_t']='';
					$_SESSION['baidu_login_u_r']='';
					$_SESSION['baidu_login_u_edate']=0;
				}
			}
			if(!isset($_SESSION['baidu_login_u_t']) || $_SESSION['baidu_login_u_t']==''){
				$bo=new baiduPHP($config['baidu_key'], $config['baidu_se']);
				$aurl=$bo->login_url($config['site_url'].'baidu_callback.php');
				header('Location:'.$aurl);
				exit();
			}
			break;
		default:
			if(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
				$_SESSION[$config['u_hash'].'_m']='';
				unset($_SESSION[$config['u_hash'].'_m']);
			}
			if($_SERVER['REQUEST_METHOD']=='POST'){
				if(isset($_POST['username']) && trim($_POST['username'])!='' && isset($_POST['password']) && trim($_POST['password'])!=''){
					$username=trim($_POST['username']);
					$password=enc_p(trim($_POST['password']));
					$s_dbu=sprintf('select id, status, name from %s where username=%s and password=%s limit 1', $dbprefix.'member', SQLString($username, 'text'), SQLString($password, 'text'));
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0){
						if($r_dbu['status']==0 || $config['veri']>0){
							$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
							$result=mysql_query($u_db) or die('');
							$_SESSION['guest_n_'.$config['u_hash']]='';
							unset($_SESSION['guest_n_'.$config['u_hash']]);
							$_SESSION['guest_p_'.$config['u_hash']]='';
							unset($_SESSION['guest_p_'.$config['u_hash']]);
							$_SESSION[$config['u_hash']]=$r_dbu['id'];
							if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
								$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($_SESSION['login_sync_tn'], 'text'));
								$q_dby=mysql_query($s_dby) or die('');
								$r_dby=mysql_fetch_assoc($q_dby);
								if(mysql_num_rows($q_dby)>0){
									$u_db=sprintf('update %s set s_id=%s, s_t=%s, s_r=%s, s_s=%s, edate=%s where id=%s', $dbprefix.'m_sync',
										SQLString($_SESSION['login_sync_id'], 'text'),
										SQLString($_SESSION['login_sync_t'], 'text'),
										SQLString($_SESSION['login_sync_r'], 'text'),
										SQLString($_SESSION['login_sync_s'], 'text'),
										SQLString($_SESSION['login_sync_edate'], 'int'),
										$r_dby['id']);
									$result=mysql_query($u_db) or die('');
								}else{
									$i_db=sprintf('insert into %s (aid, name, s_id, s_t, s_r, s_s, edate) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
										$r_dbu['id'],
										SQLString($_SESSION['login_sync_tn'], 'text'),
										SQLString($_SESSION['login_sync_id'], 'text'),
										SQLString($_SESSION['login_sync_t'], 'text'),
										SQLString($_SESSION['login_sync_r'], 'text'),
										SQLString($_SESSION['login_sync_s'], 'text'),
										SQLString($_SESSION['login_sync_edate'], 'int'));
									$result=mysql_query($i_db) or die('');
								}
								mysql_free_result($q_dby);
								setsinfo($r_dbu['name'].' 绑定了'.$a_sync[$_SESSION['login_sync_tn']], $r_dbu['id']);
								$_SESSION['login_sync_tn']='';
								$_SESSION['login_sync_id']='';
								$_SESSION['login_sync_t']='';
								$_SESSION['login_sync_r']='';
								$_SESSION['login_sync_s']='';
								$_SESSION['login_sync_u']='';
								$_SESSION['login_sync_edate']=0;
							}
							if(isset($_POST['remember']) && $_POST['remember']=='1'){
								setcookie($config['u_hash'].'_u', $username, time()+86400*30);
								setcookie($config['u_hash'].'_p', $password, time()+86400*30);
							}else{
								setcookie($config['u_hash'].'_u','',time());
								setcookie($config['u_hash'].'_p','',time());
							}
						}else{
							$e=1;
						}
					}else{
						$e=2;
					}
					mysql_free_result($q_dbu);
					if(isset($e) && $e==2 && $config['open']>0 && $config['g_open']>0 && $config['g_name']!='' && $config['g_pwd']!=''){
						$g_name=htmlspecialchars(trim($_POST['username']),ENT_QUOTES);
						$g_pwd=enc_p(htmlspecialchars(trim($_POST['password']),ENT_QUOTES));
						if($g_name==$config['g_name'] && $g_pwd==enc_p($config['g_pwd'])){
							$_SESSION['guest_n_'.$config['u_hash']]=$g_name;
							$_SESSION['guest_p_'.$config['u_hash']]=$g_pwd;
							$u_db=sprintf('update %s set g_vc=g_vc+1, g_vdate=%s, g_ip_i=inet_aton(%s)', $dbprefix.'main', time(), SQLString(getIP(), 'text'));
							$result=mysql_query($u_db) or die('');
						}
					}
				}
				if(isset($_GET['m']))$u[]='m='.$_GET['m'];
				if(isset($_GET['t']))$u[]='t='.$_GET['t'];
				if(isset($_GET['page']))$u[]='page='.$_GET['page'];
				if(isset($e))$u[]='e='.$e;
				header('Location:'.(isset($u)?'?'.join('&', $u):'./'));
				exit();
			}else{
				$a_msg=array(1=>'您的帐号还没有通过审核，请稍候再试。', '用户名/密码错误！', '注册成功！请'.($config['veri']>0?'登录':'等待管理员审核').'。');
				$content.='<div class="tcontent">'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">登录</div><div class="lcontent"><form method="post" action="" class="btform" id="loginform">';
				if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
					$js_c.='
	$(\'#login_sync_clink\').click(function(){
		$.get(\'j_loginsynce.php\');
		$(\'#login_sync_v\').slideUp(500);
	});';
					$content.='<div id="login_sync_v" class="sync_list" style="background-image: url(images/i-'.$_SESSION['login_sync_tn'].'.gif);margin-bottom: 10px;">'.($_SESSION['login_sync_u']!=''?$_SESSION['login_sync_u'].' ':'').'目前没有账号绑定此'.$a_sync[$_SESSION['login_sync_tn']].'账号，登录后绑定 <span class="mlink f_link" id="login_sync_clink">取消</span></div>';
				}
				$content.='<table><tr><td>用户名：</td><td><input name="username" size="32" maxlength="20" class="bt_input" rel="用户名" /></td></tr><tr><td>密码：</td><td><input name="password" size="32" maxlength="20" type="password" class="bt_input" rel="密码" /></td></tr><tr><td colspan="2"><input name="remember" value="1" type="checkbox" title="为了确保信息安全，请不要在网吧或者公共机房选择此项！如果今后要取消此选项，只需点击“退出登录”即可。'.(($config['open']>0 && $config['g_open']>0)?'此功能对访客账号无效。':'').'" />记住我</td></tr><tr><td colspan="2"><input type="submit" value="登录" class="button" /> <input type="reset" value="取消" class="button" />';
				$content.='<br/>'.($config['openreg']==0?'<a href="?m=reg">加入本班</a> | ':'').'<a href="?m=lostpw">忘记密码</a></td></tr></table></form></div>';
				if(isset($a_sync) && count($a_sync)>0){
					$content.='<br/><div class="title">其他账号登录</div><div class="lcontent">';
					foreach($a_sync as $k=>$v)$content.='<a href="?m=login&amp;t='.$k.'" title="'.$v.'登录"><img src="images/i-'.$k.'-l.gif" alt=""/></a> ';
					$content.='</div>';
				}
				$content.='</div>';
			}
			break;
	}
}else{
	header('Location:./');
	exit();
}
