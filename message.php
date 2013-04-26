<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log){
	$title.='短消息';
	$content.='<div class="tcontent">';
	$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
	if(isset($_GET['id']) && intval($_GET['id'])>0 && intval($_GET['id'])!=$_SESSION[$config['u_hash']] && getainfo(intval($_GET['id']), 'id')){
		$tid=intval($_GET['id']);
		$tn=getainfo($tid, 'name');
		$title.=' - '.$tn['name'];
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
			if($cont!=''){
				$i_db=sprintf('insert into %s (content, aid, tid, datetime, readed) values (%s, %s, %s, %s, 1)', $dbprefix.'message', 
					SQLString($cont, 'text'),
					$_SESSION[$config['u_hash']],
					$tid,
					time());
				$result=mysql_query($i_db) or die('');
			}
			header('Location:./?m=message&id='.$tid);
			exit();
		}else{
			if(isset($_GET['did'])){
				$d_db=sprintf('delete from %s where id=%s and aid=%s and tid=%s', $dbprefix.'message', intval($_GET['did']), $tid, $_SESSION[$config['u_hash']]);
				$result=mysql_query($d_db) or die('');
				header('Location:./?m=message&id='.$tid);
				exit();
			}
			$content.='<div class="title" id="send">发消息 - 收件人：'.$tn['name'].'</div><div class="lcontent">'.getcform().'</div>';
			$s_a_dbg=sprintf('select * from %s where (aid=%s and tid=%s) or (tid=%s and aid=%s) order by datetime desc', $dbprefix.'message', $tid, $_SESSION[$config['u_hash']], $tid, $_SESSION[$config['u_hash']]);
			$q_a_dbg=mysql_query($s_a_dbg) or die('');
			$c_dbg=mysql_num_rows($q_a_dbg);
			if($c_dbg>0){
				$content.='<div class="title" id="history">聊天记录</div>';
				$p_dbg=ceil($c_dbg/$config['pagesize']);
				if($page>$p_dbg)$page=$p_dbg;
				$s_dbg=sprintf('%s limit %d, %d', $s_a_dbg, ($page-1)*$config['pagesize'], $config['pagesize']);
				$q_dbg=mysql_query($s_dbg) or die('');
				$r_dbg=mysql_fetch_assoc($q_dbg);
				$js_c.='
	$("img[name=\'del_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=message&id='.$tid.'&did=\'+$(this).data(\'id\');
	});';
				do{
					$content.='<div class="msg_t_'.($r_dbg['aid']==$_SESSION[$config['u_hash']]?'0':'1').'" id="m'.$r_dbg['id'].'">'.($r_dbg['aid']==$_SESSION[$config['u_hash']]?'我':'<a href="?m=user&amp;id='.$tid.'">'.$tn['name'].'</a>').'：'.gbookencode($r_dbg['content']).($r_dbg['readed']>0?'<img src="images/new.gif" alt="" title="'.($r_dbg['aid']==$_SESSION[$config['u_hash']]?'对方未读':'新消息').'"/>':'').'<div class="msg_d">'.getldate($r_dbg['datetime']).($r_dbg['aid']==$_SESSION[$config['u_hash']]?' <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$r_dbg['id'].'" class="f_link"/>':'').'</div></div><div class="msg_b_'.($r_dbg['aid']==$_SESSION[$config['u_hash']]?'0':'1').'"></div>';
					if($r_dbg['readed']>0 && $r_dbg['tid']==$_SESSION[$config['u_hash']]){
						$u_db=sprintf('update %s set readed=0 where id=%s', $dbprefix.'message', $r_dbg['id']);
						$result=mysql_query($u_db) or die('');
					}
				}while($r_dbg=mysql_fetch_assoc($q_dbg));
				mysql_free_result($q_dbg);
				if($p_dbg>1)$content.=getpage($page, $p_dbg);
			}
			mysql_free_result($q_a_dbg);
		}
	}else{
		$title.=' - 收件箱';
		if(isset($_GET['did'])){
			$d_db=sprintf('delete from %s where id=%s and tid=%s', $dbprefix.'message', intval($_GET['did']), $_SESSION[$config['u_hash']]);
			$result=mysql_query($d_db) or die('');
			header('Location:./?m=message');
			exit();
		}
		$content.='<ul class="clist"><li><div class="title" id="history">收件箱</div></li>';
		$s_a_dbg=sprintf('select a.*, b.name from %s as a, %s as b where a.tid=%s and a.aid=b.id order by a.datetime desc', $dbprefix.'message', $dbprefix.'member', $_SESSION[$config['u_hash']]);
		$q_a_dbg=mysql_query($s_a_dbg) or die('');
		$c_dbg=mysql_num_rows($q_a_dbg);
		if($c_dbg>0){
			$p_dbg=ceil($c_dbg/$config['pagesize']);
			if($page>$p_dbg)$page=$p_dbg;
			$s_dbg=sprintf('%s limit %d, %d', $s_a_dbg, ($page-1)*$config['pagesize'], $config['pagesize']);
			$q_dbg=mysql_query($s_dbg) or die('');
			$r_dbg=mysql_fetch_assoc($q_dbg);
			$js_c.='
	$("img[name=\'del_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=message&did=\'+$(this).data(\'id\');
	});';
			do{
				$content.='<li class="l_list"><a href="?m=user&amp;id='.$r_dbg['aid'].'"><img src="avator.php?id='.$r_dbg['aid'].'" alt="" title="'.$r_dbg['name'].'" class="photo" width="55" height="55"/></a><div class="list_r"><div class="list_title"><span class="gmod"><img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$r_dbg['id'].'" class="f_link"/></span><a href="?m=message&amp;id='.$r_dbg['aid'].'#m'.$r_dbg['id'].'">'.$r_dbg['name'].' 致 我</a>'.($r_dbg['readed']>0?' <img src="images/new.gif" alt="" title="新消息"/>':'').'&nbsp;&nbsp;<span class="gdate">'.getldate($r_dbg['datetime']).'</span></div><div class="list_c">'.gbookencode($r_dbg['content']).'</div></div></li>';
				if($r_dbg['readed']>0){
					$u_db=sprintf('update %s set readed=0 where id=%s', $dbprefix.'message', $r_dbg['id']);
					$result=mysql_query($u_db) or die('');
				}
			}while($r_dbg=mysql_fetch_assoc($q_dbg));
			mysql_free_result($q_dbg);
			$content.='</ul>';
			if($p_dbg>1)$content.=getpage($page, $p_dbg);
		}else{
			$content.='<li><div class="gcontent">没有短消息</div></li></ul>';
		}
		mysql_free_result($q_a_dbg);
	}
	$content.='</div>';
}else{
	header('Location:./');
	exit();
}
?>