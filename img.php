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
if(($config['open']==0 || chklog()) && $config['slink']==0 && chkre()){
	$i=(isset($_GET['id']) && intval($_GET['id'])>0)?intval($_GET['id']):1;
	$s_dbp=sprintf('select id, url from %s where id=%s and upload>0', $dbprefix.'photo', $i);
	$q_dbp=mysql_query($s_dbp);
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		if(isset($_GET['t']) && $_GET['t']==1)$u='file/'.getthi($r_dbp['url']);
		if(!isset($u) || !file_exists($u))$u='file/'.$r_dbp['url'];
		$ta=explode('.', $u);
		$t=$ta[count($ta)-1];
		if(chkuag())header('Content-Disposition:image/'.$t.'; filename='.$r_dbp['id'].'.'.$t);	
		header('Content-type: image/'.$t);
		echo join('', file($u));
	}else{
		header('Location:images/error.gif');
	}
	mysql_free_result($q_dbp);
}else{
	header('Location:images/error.gif');
}
?>