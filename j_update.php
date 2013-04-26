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
require_once('version.php');
$c_log=chklog();
if($c_log){
	$ar=getainfo($_SESSION[$config['u_hash']], 'power');
	$pa=$ar['power'];
	if($pa==9){
		$uf='http://www.piscdong.com/mini_class/new_chkversion.php?v='.$v_date;
		$c=@file_get_contents($uf);
		if($c=='')$c='服务器连接失败，请稍后重试';
		echo $c;
	}
}
?>