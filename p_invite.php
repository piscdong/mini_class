<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && isset($r_dbu) && $config['invnreg']==0){
	$title.='邀请朋友';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['invite_link']) && $_POST['invite_link']==1){
			$code=md5(time().$r_dbu['id'].'|'.rand(1,1000));
			$i_db=sprintf('insert into %s (aid, datetime, code) values (%s, %s, %s)', $dbprefix.'invite',
				$r_dbu['id'],
				time(),
				SQLString($code, 'text'));
			$result=mysql_query($i_db) or die('');
		}
		header('Location:./?m=profile&t=invite');
		exit();
	}else{
		$content.='<div class="title">邀请朋友</div><div class="lcontent">';
		$s_dbi=sprintf('select id, code from %s where aid=%s and jid=0 order by datetime desc', $dbprefix.'invite', $r_dbu['id']);
		$q_dbi=mysql_query($s_dbi) or die('');
		$r_dbi=mysql_fetch_assoc($q_dbi);
		if(mysql_num_rows($q_dbi)>0){
			$js_c.='
	$("img[name=\'del_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=profile&t=invite&did=\'+$(this).data(\'id\');
	});
	$(".invcode").mouseover(function(){
		$(this).select();
	});';
			do{
				$content.='<input value="'.$config['site_url'].'?m=reg&amp;c='.$r_dbi['code'].'" size="60" class="invcode"/> <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$r_dbi['id'].'" class="f_link"/><br/>';
				if(isset($_GET['did']) && $_GET['did']==$r_dbi['id']){
					$d_db=sprintf('delete from %s where id=%s', $dbprefix.'invite', $r_dbi['id']);
					$result=mysql_query($d_db) or die('');
					header('Location:./?m=profile&t=invite');
					exit();
				}
			}while($r_dbi=mysql_fetch_assoc($q_dbi));
			$content.='<br/>同一邀请链接只可以使用一次，你可以通过QQ、MSN和邮件发给你的朋友，邀请他注册';
		}else{
			$content.='没有邀请链接或者所有邀请链接都已经被使用了';
		}
		mysql_free_result($q_dbi);
		$content.='<br/><br/><form method="post" action=""><input type="submit" value="生成新邀请链接" class="button" /><input type="hidden" name="invite_link" value="1"/></form></div>';
		$s_dbe=sprintf('select id, name from %s where jaid=%s order by id desc', $dbprefix.'member', $r_dbu['id']);
		$q_dbe=mysql_query($s_dbe) or die('');
		$r_dbe=mysql_fetch_assoc($q_dbe);
		if(mysql_num_rows($q_dbe)>0){
			$content.='<br/><div class="title">已邀请朋友</div><div class="lcontent">';
			do{
				$content.='<div class="al_list"><a href="?m=user&amp;id='.$r_dbe['id'].'"><img src="avator.php?id='.$r_dbe['id'].'" alt="" title="'.$r_dbe['name'].'" class="photo" width="55" height="55"/></a></div>';
			}while($r_dbe=mysql_fetch_assoc($q_dbe));
			$content.='<div class="extr"></div></div>';
		}
		mysql_free_result($q_dbe);
	}
}
?>