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
if($config['open']==0 || $c_log){
	$i=(isset($_GET['i']) && intval($_GET['i'])>0)?intval($_GET['i']):1;
	$t=(isset($_GET['t']) && intval($_GET['t'])>0)?intval($_GET['t']):0;
	$s=(isset($_GET['s']) && intval($_GET['s'])>0)?intval($_GET['s']):0;
	$s_dbp=sprintf('select id from %s where id=%s limit 1', $dbprefix.'photo', $i);
	$q_dbp=mysql_query($s_dbp) or die('');
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		if($t>0){
			$qdb='>'.$r_dbp['id'].' order by datetime';
		}else{
			$qdb='<'.$r_dbp['id'].' order by datetime desc';
		}
		$s_dbn=sprintf('select id, title, url, vid, upload from %s where %sid%s limit %d, 1', $dbprefix.'photo', $cdb, $qdb, $s);
		$q_dbn=mysql_query($s_dbn) or die('');
		$r_dbn=mysql_fetch_assoc($q_dbn);
		if(mysql_num_rows($q_dbn)>0){
			echo '<a href="?m=album&amp;id='.$r_dbn['id'].$u_l.'"><img src="'.getthu($r_dbn).'" alt="" title="'.$r_dbn['title'].'" class="pr_img" width="70" height="70" /></a>';
		}else{
			echo '<img src="images/'.($t>0?'l_al.gif" alt="" title="这是最后一张':'r_al.gif" alt="" title="这是第一张').'" class="pr_img" width="70" height="70" />';
		}
		mysql_free_result($q_dbn);
	}
	mysql_free_result($q_dbp);
}
