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
if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
	$c_log=chklog();
	if($c_log){
		$u='./?m=profile&t=sync&n=tsohu';
		$ar=getainfo($_SESSION[$config['u_hash']], 'id, name');
		$token=$_SESSION['tsohu_token'];
		$secret=$_SESSION['tsohu_secret'];
		$_SESSION['tsohu_token']='';
		unset($_SESSION['tsohu_token']);
		$_SESSION['tsohu_secret']='';
		unset($_SESSION['tsohu_secret']);
	}else{
		$u='./?m=login&t=tsohu';
		$token=$_SESSION['tsohu_login_token'];
		$secret=$_SESSION['tsohu_login_secret'];
		$_SESSION['tsohu_login_token']='';
		unset($_SESSION['tsohu_login_token']);
		$_SESSION['tsohu_login_secret']='';
		unset($_SESSION['tsohu_login_secret']);
	}
	if($token!='' && $secret!=''){
		require_once('lib/SohuOAuth.php');
		$connection=new SohuOAuth($config['tsohu_key'], $config['tsohu_se'], $token, $secret);
		$last_key=$connection->getAccessToken($_REQUEST['oauth_verifier']);
		if($last_key['oauth_token']!='' && $last_key['oauth_token_secret']!=''){
			if($c_log){
				$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('tsohu', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$u_db=sprintf('update %s set s_t=%s, s_s=%s where id=%s', $dbprefix.'m_sync',
						SQLString($last_key['oauth_token'], 'text'),
						SQLString($last_key['oauth_token_secret'], 'text'),
						$r_dby['id']);
					$result=mysql_query($u_db) or die('');
				}else{
					$i_db=sprintf('insert into %s (aid, name, s_t, s_s) values (%s, %s, %s, %s)', $dbprefix.'m_sync',
						$ar['id'],
						SQLString('tsohu', 'text'),
						SQLString($last_key['oauth_token'], 'text'),
						SQLString($last_key['oauth_token_secret'], 'text'));
					$result=mysql_query($i_db) or die('');
				}
				mysql_free_result($q_dby);
				setsinfo($ar['name'].' 绑定了搜狐微博', $ar['id']);
			}else{
				$_SESSION['tsohu_login_u_t']=$last_key['oauth_token'];
				$_SESSION['tsohu_login_u_s']=$last_key['oauth_token_secret'];
			}
		}
	}
}
header('Location:'.$u);
