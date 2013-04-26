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
$c_log=chklog();
if($c_log){
	$ar=getainfo($_SESSION[$config['u_hash']], 'power');
	$pa=$ar['power'];
	if($pa==9){
		$i=(isset($_GET['i']) && intval($_GET['i'])>0)?intval($_GET['i']):0;
		$s_dbk=sprintf('select id, title from %s where id=%s limit 1', $dbprefix.'skin', $i);
		$q_dbk=mysql_query($s_dbk) or die('');
		$r_dbk=mysql_fetch_assoc($q_dbk);
		if(mysql_num_rows($q_dbk)>0){
			$u_db=sprintf('update %s set skin=%s', $dbprefix.'main', $r_dbk['id']);
			$result=mysql_query($u_db) or die('');
			echo ($r_dbk['title']!=''?$r_dbk['title']:'样式#'.$r_dbk['id']).'已被设置为默认样式！';
		}else{
			$u_db=sprintf('update %s set skin=0', $dbprefix.'main');
			$result=mysql_query($u_db) or die('');
			echo '青青校园已被设置为默认样式！';
		}
		mysql_free_result($q_dbk);
	}
}
?>