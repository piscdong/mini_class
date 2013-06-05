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
$u='images/dphoto.jpg';
if(($config['open']==0 || chklog()) && chkre()){
	$i=(isset($_GET['id']) && intval($_GET['id'])>0)?intval($_GET['id']):1;
	$s_dbu=sprintf('select photo from %s where id=%s', $dbprefix.'member', $i);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		if(trim($r_dbu['photo'])!=''){
			$a_pho=explode('|', trim($r_dbu['photo']));
			$t_pho=count($a_pho);
			$k=($config['avator']==0 || $t_pho<2)?0:rand(0,(min($config['avator'], $t_pho)-1));
			$u=$a_pho[$k];
		}
	}
	mysql_free_result($q_dbu);
}
header('Location:'.$u);
