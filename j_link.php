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
		$odb=(isset($_GET['e']) && $_GET['e']==1)?' desc':'';
		$s_dbl=sprintf('select id, thread from %s order by thread%s', $dbprefix.'link', $odb);
		$q_dbl=mysql_query($s_dbl) or die('');
		$r_dbl=mysql_fetch_assoc($q_dbl);
		if(mysql_num_rows($q_dbl)>0){
			do{
				if(isset($_GET['i']) && $_GET['i']==$r_dbl['id'] && isset($tid)){
					$u_db=sprintf('update %s set thread=%s where thread=%s', $dbprefix.'link', $r_dbl['thread'], $tid);
					$result=mysql_query($u_db) or die('');
					$u_db=sprintf('update %s set thread=%s where id=%s', $dbprefix.'link', $tid, $r_dbl['id']);
					$result=mysql_query($u_db) or die('');
				}
				$tid=$r_dbl['thread'];
			}while($r_dbl=mysql_fetch_assoc($q_dbl));
		}
		mysql_free_result($q_dbl);
	}
	$s_dbl=sprintf('select id, title, url from %s order by thread', $dbprefix.'link');
	$q_dbl=mysql_query($s_dbl) or die('');
	$r_dbl=mysql_fetch_assoc($q_dbl);
	$c_dbl=mysql_num_rows($q_dbl);
	if($c_dbl>0){
		echo '<table>';
		$i=0;
		do{
			echo '<tr><td>'.substrs($r_dbl['title'], 25).'</td><td>'.substrs($r_dbl['url'], 20).'</td><td>'.($i>0?'<img src="images/o_0.gif" alt="" title="上移" onclick="$(\'#linklist\').load(\'j_link.php?i='.$r_dbl['id'].'\');" class="f_link"/> ':'').($i<($c_dbl-1)?'<img src="images/o_1.gif" alt="" title="下移" onclick="$(\'#linklist\').load(\'j_link.php?i='.$r_dbl['id'].'&e=1\');" class="f_link"/> ':'').'<a href="?m=setting&amp;t=link&amp;eid='.$r_dbl['id'].'"><img src="images/o_3.gif" alt="" title="编辑"/></a> &nbsp; <img src="images/o_2.gif" alt="" title="删除" onclick="if(confirm(\'确认要删除？\'))location.href=\'?m=setting&t=link&did='.$r_dbl['id'].'\';" class="f_link"/></td></tr>';
			$i++;
		}while($r_dbl=mysql_fetch_assoc($q_dbl));
		echo '</table>';
	}
	mysql_free_result($q_dbl);
}
?>