<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

session_start();
require_once('config.php');
require_once('function.php');
$u='./';
if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
	$c_log=chklog();
	if($c_log){
		$u='./?m=profile&t=sync&n=tw';
		$ar=getainfo($_SESSION[$config['u_hash']], 'id, name');
		$token=$_SESSION['tw_token'];
		$secret=$_SESSION['tw_secret'];
		$_SESSION['tw_token']='';
		unset($_SESSION['tw_token']);
		$_SESSION['tw_secret']='';
		unset($_SESSION['tw_secret']);
	}else{
		$u='./?m=login&t=twitter';
		$token=$_SESSION['tw_login_token'];
		$secret=$_SESSION['tw_login_secret'];
		$_SESSION['tw_login_token']='';
		unset($_SESSION['tw_login_token']);
		$_SESSION['tw_login_secret']='';
		unset($_SESSION['tw_login_secret']);
	}
	if($token!='' && $secret!=''){
		require_once('lib/twitterOAuth.php');
		$to=new TwitterOAuth($config['tw_key'], $config['tw_se'], $token, $secret);
		$tok=$to->getAccessToken();
		if($tok['oauth_token']!='' && $tok['oauth_token_secret']!=''){
			if($c_log){
				$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('twitter', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$u_db=sprintf('update %s set s_t=%s, s_s=%s where id=%s', $dbprefix.'m_sync',
						SQLString($tok['oauth_token'], 'text'),
						SQLString($tok['oauth_token_secret'], 'text'),
						$r_dby['id']);
					$result=mysql_query($u_db) or die('');
				}else{
					$i_db=sprintf('insert into %s (aid, name, s_t, s_s) values (%s, %s, %s, %s)', $dbprefix.'m_sync',
						$ar['id'],
						SQLString('twitter', 'text'),
						SQLString($tok['oauth_token'], 'text'),
						SQLString($tok['oauth_token_secret'], 'text'));
					$result=mysql_query($i_db) or die('');
				}
				mysql_free_result($q_dby);
				setsinfo($ar['name'].' 绑定了Twitter', $ar['id']);
			}else{
				$_SESSION['tw_login_u_t']=$tok['oauth_token'];
				$_SESSION['tw_login_u_s']=$tok['oauth_token_secret'];
			}
		}
	}
}
header('Location:'.$u);
?>