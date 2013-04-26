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
	if($c_log){
		$ar=getainfo($_SESSION[$config['u_hash']]);
		$pa=$ar['power'];
	}
	$r=(isset($_GET['i']) && intval($_GET['i'])>0)?intval($_GET['i']):1;
	$ddb=($c_log && $pa==9)?'':' and a.disp=0';
	$dpage=(isset($_GET['e']) && intval($_GET['e'])>0)?intval($_GET['e']):1;
	$page=(isset($_GET['p']) && intval($_GET['p'])>0)?intval($_GET['p']):1;
	$reply_s=5;
	$s_a_dbt=sprintf('select a.id, a.aid, a.content, a.disp, b.name, b.power from %s as a, %s as b where a.rid=%s and a.aid=b.id%s order by a.datetime desc', $dbprefix.'topic', $dbprefix.'member', $r, $ddb);
	$q_a_dbt=mysql_query($s_a_dbt) or die('');
	$c_dbt=mysql_num_rows($q_a_dbt);
	if($c_dbt>0){
		$p_dbt=ceil($c_dbt/$reply_s);
		if($page>$p_dbt)$page=$p_dbt;
		$s_dbt=sprintf('%s limit %d, %d', $s_a_dbt, ($page-1)*$reply_s, $reply_s);
		$q_dbt=mysql_query($s_dbt) or die('');
		$r_dbt=mysql_fetch_assoc($q_dbt);
		do{
			$ei=($c_log && ($pa>$r_dbt['power'] || $_SESSION[$config['u_hash']]==$r_dbt['aid']))?'&nbsp; <img src="images/o_3.gif" alt="" title="编辑" onclick="$(\'#l_'.$r_dbt['id'].'\').slideUp(500);$(\'#h_'.$r_dbt['id'].'\').slideDown(500);" class="f_link"/>':'';
			echo '<div class="reply_v"><div id="l_'.$r_dbt['id'].'">'.getalink($r_dbt['aid'], $r_dbt['name'], 1).'：'.getaco($r_dbt['content'], $r_dbt['id'], 1).'</div>'.($ei!=''?'<form method="post" action="" id="h_'.$r_dbt['id'].'" style="display: none;" onsubmit="return chkform(\'h_'.$r_dbt['id'].'\');"><div class="formline"><textarea name="rinfo" id="forminfo'.$r_dbt['id'].'" class="bt_input" rel="留言内容" rows="4">'.$r_dbt['content'].'</textarea>'.getsync_c($ar).'</div><div class="formline"><input type="submit" value="修改" class="button" /> <input value="取消" class="button" type="button" onclick="$(\'#h_'.$r_dbt['id'].'\').slideUp(500);$(\'#l_'.$r_dbt['id'].'\').slideDown(500);"/><input type="hidden" name="id" value="'.$r_dbt['id'].'" /></div></form>':'');
			if($c_log && $pa>0 && $pa<9)echo '<form method="post" action="" onsubmit="return chkform(\'del_'.$r_dbt['id'].'\');" id="del_'.$r_dbt['id'].'" style="display: none;"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" onclick="$(\'#del_'.$r_dbt['id'].'\').slideUp(500);"/><input type="hidden" name="did" value="'.$r_dbt['id'].'" /></td></tr></table></form>';
			echo '<div class="reply_i">- '.getldate($r_dbt['datetime']).$ei;
			if($c_log){
				if($pa>0)echo '&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" onclick="'.($pa==9?'if(confirm(\'确认要删除？\'))location.href=\'?page='.$dpage.'&did='.$r_dbt['id'].'\';':'$(\'#del_'.$r_dbt['id'].'\').slideDown(500);').'" class="f_link"/>';
				if($pa==9 && $r_dbt['disp']>0)echo '&nbsp; &nbsp; <span class="del_n">已删除</span> <a href="?page='.$dpage.'&amp;pid='.$r_dbt['id'].'"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
			}
			echo '</div></div>';
		}while($r_dbt=mysql_fetch_assoc($q_dbt));
		mysql_free_result($q_dbt);
		if($p_dbt>1){
			for($i=1;$i<=$p_dbt;$i++)echo ($i!=$page?'<span onclick="ld('.$r.', '.$i.', \'j_topic.php?i='.$r.'&p='.$i.'&e='.$dpage.'\');" class="mlink f_link">'.$i.'</span>':$i).' ';
		}
	}
	mysql_free_result($q_a_dbt);
}
?>