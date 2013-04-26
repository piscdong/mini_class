<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='管理记录';
	$content.='<ul class="clist">';
	$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
	$s_a_dba=sprintf('select a.*, b.name from %s as a, %s as b where a.aid=b.id order by a.datetime desc', $dbprefix.'adminop', $dbprefix.'member');
	$q_a_dba=mysql_query($s_a_dba) or die('');
	$c_dba=mysql_num_rows($q_a_dba);
	if($c_dba>0){
		$p_dba=ceil($c_dba/$config['pagesize']);
		if($page>$p_dba)$page=$p_dba;
		$s_dba=sprintf('%s limit %d, %d', $s_a_dba, ($page-1)*$config['pagesize'], $config['pagesize']);
		$q_dba=mysql_query($s_dba) or die('');
		$r_dba=mysql_fetch_assoc($q_dba);
		$js_c.='
		$("img[name=\'del\']").click(function(){
			if(confirm(\'确认要删除？\'))location.href=\'?m=setting&t=adminop&did=\'+$(this).data(\'id\');
		});';
		do{
			if(isset($_GET['did']) && $_GET['did']==$r_dba['id']){
				$d_db=sprintf('delete from %s where id=%s', $dbprefix.'adminop', $r_dba['id']);
				$result=mysql_query($d_db) or die('');
				header('Location:./?m=setting&t=adminop');
				exit();
			}else{
				$content.='<li class="l_list"><a href="?m=user&amp;id='.$r_dba['aid'].'"><img src="avator.php?id='.$r_dba['aid'].'" alt="" title="'.$r_dba['name'].'" class="photo" width="55" height="55"/></a><div class="list_r"><div class="list_title"><span class="gmod"><img src="images/o_2.gif" alt="" title="删除" name="del" data-id="'.$r_dba['id'].'" class="f_link"/></span>'.getalink($r_dba['aid'], $r_dba['name']).'&nbsp;&nbsp;<span class="gdate">'.getldate($r_dba['datetime']).'</span></div><div class="list_c">';
				switch($r_dba['tid']){
					case 1:
						$pr=getpinfo($r_dba['sid']);
						$content.='<a href="?m=album&amp;id='.$r_dba['sid'].'"><img src="'.getthu($pr).'" alt="" title="'.$pr['title'].'" width="70" height="70" class="al_t"/></a><br/>';
						break;
					case 2:
						$pr=getcinfo($r_dba['sid'], 'title');
						$content.='<a href="?m=camp&amp;id='.$r_dba['sid'].'">'.$pr['title'].'</a><br/><br/>';
						break;
				}
				$content.=gbookencode($r_dba['content']).'</div></div></li>';
			}
		}while($r_dba=mysql_fetch_assoc($q_dba));
		mysql_free_result($q_dba);
		$content.='</ul>';
		if($p_dba>1)$content.=getpage($page, $p_dba);
	}else{
		$content.='<li><div class="title">管理记录</div><div class="lcontent">没有记录</div></li></ul>';
	}
	mysql_free_result($q_a_dba);
}
?>