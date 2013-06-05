<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
$pagesize=50;
if(isset($_GET['id']) && intval($_GET['id'])>0){
	$s_dbp=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.id=%s and a.aid=b.id and a.disp=0 limit 1', $dbprefix.'photo', $dbprefix.'member', intval($_GET['id']));
	$q_dbp=mysql_query($s_dbp) or die('');
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
			$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
			if($cont!=''){
				$i_db=sprintf('insert into %s (content, aid, pid, datetime) values (%s, %s, %s, %s)', $dbprefix.'pcomment', 
					SQLString($cont, 'text'),
					$_SESSION[$config['u_hash']],
					$r_dbp['id'],
					time());
				$result=mysql_query($i_db) or die('');
				$nid=mysql_insert_id();
				setsinfo($pn.' 发表评论', $r_dbp['aid'], $r_dbp['id'], 2);
			}
			header('Location:./?m=album&id='.$r_dbp['id'].(isset($nid)?'#topic-'.$nid:''));
			exit();
		}else{
			$t=$r_dbp['title']!=''?$r_dbp['title']:($r_dbp['vid']>0?'视频':'照片').' #'.$r_dbp['id'];
			$title.=$t;
			$u=$r_dbp['url'];
			if($r_dbp['upload']==0){
				$tb_i='';
				if(strstr($u, '[/]')){
					$a_u=explode('[/]', $u);
					$l_u=count($a_u)-1;
					$t_u=$a_u[$l_u];
					if(trim($t_u)!='' && strstr(trim($t_u), '://')){
						$tb_i=trim($t_u);
						unset($a_u[$l_u]);
					}
					$u=join('[/]', $a_u);
				}
			}
			$content.='<div class="title">'.$t.'</div><div class="lcontent"><a href="?m=user&amp;id='.$r_dbp['aid'].'">'.$r_dbp['name'].'</a>&nbsp;&nbsp;'.getldate($r_dbp['datetime']).'<br/>'.($r_dbp['vid']>0?$u:'<a href="'.(($config['slink']>0 || $r_dbp['upload']==0)?($r_dbp['upload']>0?'../file/':'').$u:'../img.php?id='.$r_dbp['id']).'"><img src="'.(($config['slink']>0 || $r_dbp['upload']==0)?($r_dbp['upload']>0?'../file/':'').$u:'../img.php?id='.$r_dbp['id']).'" alt="" title="'.$r_dbp['title'].'" class="photo" style="max-width: 250px;"/></a>');
			if($r_dbp['cid']>0){
				$s_dbc=sprintf('select id, title from %s where id=%s and disp=0 limit 1', $dbprefix.'camp', $r_dbp['cid']);
				$q_dbc=mysql_query($s_dbc) or die('');
				$r_dbc=mysql_fetch_assoc($q_dbc);
				if(mysql_num_rows($q_dbc)>0)$content.='<br/><br/>相关活动：<a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a>';
				mysql_free_result($q_dbc);
			}
			$content.='</div>';
			$s_a_dbr=sprintf('select a.id, a.aid, a.content, a.datetime, b.name from %s as a, %s as b where a.pid=%s and a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'pcomment', $dbprefix.'member', $r_dbp['id']);
			$q_a_dbr=mysql_query($s_a_dbr) or die('');
			$c_dbr=mysql_num_rows($q_a_dbr);
			if($c_dbr>0){
				$p_dbr=ceil($c_dbr/$config['pagesize']);
				if($page>$p_dbr)$page=$p_dbr;
				$s_dbr=sprintf('%s limit %d, %d', $s_a_dbr, ($page-1)*$config['pagesize'], $config['pagesize']);
				$q_dbr=mysql_query($s_dbr) or die('');
				$r_dbr=mysql_fetch_assoc($q_dbr);
				do{
					$content.='<div class="topic" id="topic-'.$r_dbr['id'].'"><a href="?m=user&amp;id='.$r_dbr['aid'].'">'.$r_dbr['name'].'</a>&nbsp;&nbsp;'.getldate($r_dbr['datetime']).'<div class="list_c">'.mbookencode($r_dbr['content']).'</div></div>';
				}while($r_dbr=mysql_fetch_assoc($q_dbr));
				mysql_free_result($q_dbr);
			}
			mysql_free_result($q_a_dbr);
			if(isset($p_dbr) && $p_dbr>1)$content.=getpage($page, $p_dbt);
			if($c_log)$content.='<div class="title">发表评论</div><div class="lcontent"><form method="post" action="" class="btform" id="lyform"><textarea name="rinfo" id="forminfor0" rows="4" style="width: 95%" class="bt_input" rel="内容"></textarea><br/><input type="submit" value="发表评论" /></form></div>';
		}
	}else{
		header('Location:./');
		exit();
	}
	mysql_free_result($q_dbp);
}else{
	$title.='照片视频';
	$s_a_dbp=sprintf('select a.id, a.upload, a.vid, a.url, a.title, b.name from %s as a, %s as b where a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'photo', $dbprefix.'member');
	$q_a_dbp=mysql_query($s_a_dbp) or die('');
	$c_dbp=mysql_num_rows($q_a_dbp);
	if($c_dbp>0){
		$p_dbp=ceil($c_dbp/$pagesize);
		if($page>$p_dbp)$page=$p_dbp;
		$s_dbp=sprintf('%s limit %d, %d', $s_a_dbp, ($page-1)*$pagesize, $pagesize);
		$q_dbp=mysql_query($s_dbp) or die('');
		$r_dbp=mysql_fetch_assoc($q_dbp);
		$content.='<center>';
		do{
			$content.='<a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getmthu($r_dbp).'" width="70" height="70" class="photo" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').($r_dbp['title']!=''?$r_dbp['title'].'，':'').'上传：'.$r_dbp['name'].'"/></a>';
		}while($r_dbp=mysql_fetch_assoc($q_dbp));
		$content.='</center>';
		mysql_free_result($q_dbp);
		if($p_dbp>1)$content.=getpage($page, $p_dbp);
	}else{
		$content.='<div class="title">照片视频</div><div class="lcontent">没有照片/视频</div>';
	}
	mysql_free_result($q_a_dbp);
}
