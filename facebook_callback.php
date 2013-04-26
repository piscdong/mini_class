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
$c_log=chklog();
if($c_log){
	$u='./?m=profile&t=sync&n=facebook';
}else{
	$u='./?m=login&t=facebook';
}
if($config['is_fb']>0 && $config['fb_app_id']!='' && $config['fb_se']!=''){
	if(isset($_GET['code']) && trim($_GET['code'])!=''){
		require_once('lib/facebook.php');
		$o=new facebookPHP($config['fb_app_id'], $config['fb_se']);
		$result=$o->access_token($config['site_url'].'facebook_callback.php', $_GET['code']);
	}
	if(isset($result['access_token']) && $result['access_token']!=''){
		$s_t=$result['access_token'];
		if($c_log){
			$ar=getainfo($_SESSION[$config['u_hash']], 'id, name');
			$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('facebook', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				$u_db=sprintf('update %s set s_t=%s where id=%s', $dbprefix.'m_sync',
					SQLString($s_t, 'text'),
					$r_dby['id']);
				$result=mysql_query($u_db) or die('');
			}else{
				$i_db=sprintf('insert into %s (aid, name, s_t) values (%s, %s, %s)', $dbprefix.'m_sync',
					$ar['id'],
					SQLString('facebook', 'text'),
					SQLString($s_t, 'text'));
				$result=mysql_query($i_db) or die('');
			}
			mysql_free_result($q_dby);
			setsinfo($ar['name'].' 绑定了Facebook', $ar['id']);
		}else{
			$_SESSION['facebook_login_u_t']=$s_t;
		}
	}
}
header('Location:'.$u);
?>