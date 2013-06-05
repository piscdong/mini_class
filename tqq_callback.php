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
	$u='./?m=profile&t=sync&n=tqq';
}else{
	$u='./?m=login&t=tqq';
}
if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
	if(isset($_GET['code']) && trim($_GET['code'])!=''){
		require_once('lib/tqq.php');
		$db_o=new tqqPHP($config['tqq_key'], $config['tqq_se']);
		$result=$db_o->access_token($config['site_url'].'tqq_callback.php', $_GET['code']);
	}
	if(isset($result['access_token']) && $result['access_token']!='' && isset($_GET['openid']) && $_GET['openid']!=''){
		$s_id=$_GET['openid'];
		$s_t=$result['access_token'];
		$s_r=$result['refresh_token'];
		$edate=time()+$result['expires_in'];
		if($c_log){
			$ar=getainfo($_SESSION[$config['u_hash']], 'id, name');
			$d_db=sprintf('delete from %s where s_id=%s and aid<>%s and name=%s', $dbprefix.'m_sync', SQLString($s_id, 'text'), $ar['id'], SQLString('tqq', 'text'));
			$result=mysql_query($d_db) or die('');
			$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('tqq', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				$u_db=sprintf('update %s set s_id=%s, s_t=%s, s_r=%s, edate=%s where id=%s', $dbprefix.'m_sync',
					SQLString($s_id, 'text'),
					SQLString($s_t, 'text'),
					SQLString($s_r, 'text'),
					SQLString($edate, 'int'),
					$r_dby['id']);
				$result=mysql_query($u_db) or die('');
			}else{
				$i_db=sprintf('insert into %s (aid, name, s_id, s_t, s_r, edate) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
					$ar['id'],
					SQLString('tqq', 'text'),
					SQLString($s_id, 'text'),
					SQLString($s_t, 'text'),
					SQLString($s_r, 'text'),
					SQLString($edate, 'int'));
				$result=mysql_query($i_db) or die('');
			}
			mysql_free_result($q_dby);
			setsinfo($ar['name'].' 绑定了腾讯微博', $ar['id']);
		}else{
			$_SESSION['tqq_login_u_id']=$s_id;
			$_SESSION['tqq_login_u_t']=$s_t;
			$_SESSION['tqq_login_u_r']=$s_r;
			$_SESSION['tqq_login_u_edate']=$edate;
		}
	}
}
header('Location:'.$u);
