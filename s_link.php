<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='链接管理';
	$s_dbl=sprintf('select id, url, title from %s order by thread', $dbprefix.'link');
	$q_dbl=mysql_query($s_dbl) or die('');
	$r_dbl=mysql_fetch_assoc($q_dbl);
	$c_dbl=mysql_num_rows($q_dbl);
	if($c_dbl>0){
		$js_c.='
		$("img[name=\'mu_img\']").click(function(){
			$("#linklist").load(\'j_link.php?i=\'+$(this).data(\'id\'));
		});
		$("img[name=\'md_img\']").click(function(){
			$("#linklist").load(\'j_link.php?e=1&i=\'+$(this).data(\'id\'));
		});
		$("img[name=\'del_img\']").click(function(){
			if(confirm(\'确认要删除？\'))location.href=\'?m=setting&t=link&did=\'+$(this).data(\'id\');
		});';
		$i=0;
		do{
			$lp[]='<tr><td>'.substrs($r_dbl['title'], 25).'</td><td>'.substrs($r_dbl['url'], 20).'</td><td>'.($i>0?'<img src="images/o_0.gif" alt="" title="上移" name="mu_img" data-id="'.$r_dbl['id'].'" class="f_link"/> ':'').($i<($c_dbl-1)?'<img src="images/o_1.gif" alt="" title="下移" name="md_img" data-id="'.$r_dbl['id'].'" class="f_link"/> ':'').'<a href="?m=setting&amp;t=link&amp;eid='.$r_dbl['id'].'"><img src="images/o_3.gif" alt="" title="编辑"/></a> &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$r_dbl['id'].'" class="f_link"/></td></tr>';
			if(isset($_GET['eid']) && $_GET['eid']==$r_dbl['id'])$edb=$r_dbl;
			if(isset($_GET['did']) && $_GET['did']==$r_dbl['id']){
				$d_db=sprintf('delete from %s where id=%s', $dbprefix.'link', $r_dbl['id']);
				$result=mysql_query($d_db) or die('');
				header('Location:./?m=setting&t=link');
				exit();
			}
			$tid=$r_dbl['thread'];
			$i++;
		}while($r_dbl=mysql_fetch_assoc($q_dbl));
	}
	mysql_free_result($q_dbl);
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['title']) && trim($_POST['title'])!='' && isset($_POST['url']) && trim($_POST['url'])!=''){
			$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
			$url=getfurl(htmlspecialchars(trim($_POST['url']),ENT_QUOTES));
			if(isset($edb)){
				$u_db=sprintf('update %s set title=%s, url=%s where id=%s', $dbprefix.'link',
					SQLString($title, 'text'),
					SQLString($url, 'text'),
					$edb['id']);
				$result=mysql_query($u_db) or die('');
				$e=1;
			}else{
				$thread=isset($tid)?($tid+1):0;
				$i_db=sprintf('insert into %s (title, url, thread) values (%s, %s, %s)', $dbprefix.'link',
					SQLString($title, 'text'),
					SQLString($url, 'text'),
					$thread);
				$result=mysql_query($i_db) or die('');
				$e=2;
			}
		}
		header('Location:./?m=setting&t=link'.(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'链接已修改。', '新链接已添加。');
		if(isset($edb))$js_c.='
		$("#link_cbt").click(function(){
			location.href=\'?m=setting&t=link\';
		});';
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').(isset($lp)?'<div class="title">链接管理</div><div class="lcontent" id="linklist"><table>'.join('', $lp).'</table></div><br/>':'').'<div class="title">'.(isset($edb)?'编辑':'添加').'链接</div><div class="lcontent"><form method="post" action="" class="btform" id="linkform"><table><tr><td>标题：</td><td><input name="title" size="32"'.(isset($edb)?' value="'.$edb['title'].'"':'').' class="bt_input" rel="标题" /></td></tr><tr><td>网址：</td><td><input name="url" size="32"'.(isset($edb)?' value="'.$edb['url'].'"':'').' class="bt_input" rel="网址" /></td></tr><tr><td colspan="2"><input type="submit" value="'.(isset($edb)?'编辑':'添加').'" class="button" />'.(isset($edb)?' <input type="button" value="取消" id="link_cbt" class="button" />':'').'</td></tr></table></form></div>';
	}
}
