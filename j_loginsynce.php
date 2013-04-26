<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

session_start();
if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!=''){
	$_SESSION['login_sync_tn']='';
	$_SESSION['login_sync_id']='';
	$_SESSION['login_sync_t']='';
	$_SESSION['login_sync_r']='';
	$_SESSION['login_sync_s']='';
	$_SESSION['login_sync_u']='';
	$_SESSION['login_sync_edate']=0;
}
?>