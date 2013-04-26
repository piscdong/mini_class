<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
if(isset($_GET['id']) && intval($_GET['id'])>0){
	$s_dbc=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.id=%s and a.aid=b.id and a.disp=0 limit 1', $dbprefix.'camp', $dbprefix.'member', intval($_GET['id']));
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
			if(isset($_POST['jid']) && $_POST['jid']==1 && $r_dbc['closed']==0){
				$s_dbu=sprintf('select id from %s where cid=%s and aid=%s limit 1', $dbprefix.'cuser', $r_dbc['id'], $_SESSION[$config['u_hash']]);
				$q_dbu=mysql_query($s_dbu) or die('');
				$r_dbu=mysql_fetch_assoc($q_dbu);
				if(mysql_num_rows($q_dbu)>0){
					if(isset($_POST['delu']) && $_POST['delu']!=''){
						$d_db=sprintf('delete from %s where id=%s', $dbprefix.'cuser', $r_dbu['id']);
						$result=mysql_query($d_db) or die('');
						setsinfo($pn.' 退出活动', $r_dbc['aid'], $r_dbc['id'], 3);
					}else{
						$tid=(isset($_POST['tid']) && intval($_POST['tid'])>0)?intval($_POST['tid']):1;
						$u_db=sprintf('update %s set tid=%s, datetime=%s where id=%s', $dbprefix.'cuser', SQLString($tid, 'int'), time(), $r_dbu['id']);
						$result=mysql_query($u_db) or die('');
						setsinfo($pn.' 修改参加人数', $r_dbc['aid'], $r_dbc['id'], 3);
					}
				}else{
					$tid=(isset($_POST['tid']) && intval($_POST['tid'])>0)?intval($_POST['tid']):1;
					$i_db=sprintf('insert into %s (aid, cid, tid, datetime) values (%s, %s, %s, %s)', $dbprefix.'cuser', 
						$_SESSION[$config['u_hash']],
						$r_dbc['id'],
						SQLString($tid, 'int'),
						time());
					$result=mysql_query($i_db) or die('');
					setsinfo($pn.' 参加活动', $r_dbc['aid'], $r_dbc['id'], 3);
				}
				mysql_free_result($q_dbu);
			}else{
				$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
				if($cont!=''){
					$i_db=sprintf('insert into %s (content, aid, cid, datetime) values (%s, %s, %s, %s)', $dbprefix.'ccomment', 
						SQLString($cont, 'text'),
						$_SESSION[$config['u_hash']],
						$r_dbc['id'],
						time());
					$result=mysql_query($i_db) or die('');
					$nid=mysql_insert_id();
					setsinfo($pn.' 发表留言', $r_dbc['aid'], $r_dbc['id'], 3);
				}
			}
			header('Location:./?m=camp&id='.$r_dbc['id'].(isset($nid)?'#topic-'.$nid:''));
			exit();
		}else{
			$title.=$r_dbc['title'];
			$ca[]='发起人：<a href="?m=user&amp;id='.$r_dbc['aid'].'">'.$r_dbc['name'].'</a><br/>';
			if($r_dbc['content']!='')$ca[]=gbookencode($r_dbc['content']).'<br/>';
			if($r_dbc['cdate']!='')$ca[]='<strong>活动时间：</strong>'.$r_dbc['cdate'];
			if($r_dbc['cloc']!='')$ca[]='<strong>活动地点：</strong>'.$r_dbc['cloc'];
			if($r_dbc['cpay']!='')$ca[]='<strong>活动费用：</strong>'.$r_dbc['cpay'];
			$s_dbu=sprintf('select a.aid, a.tid, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id order by a.datetime desc', $dbprefix.'cuser', $dbprefix.'member', $r_dbc['id']);
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				$t_user=0;
				do{
					$cuser_c[]='<a href="?m=user&amp;id='.$r_dbu['aid'].'">'.$r_dbu['name'].'</a>'.($r_dbu['tid']>1?'带 '.($r_dbu['tid']-1).' 人':'');
					$t_user+=$r_dbu['tid'];
					if($c_log && $_SESSION[$config['u_hash']]==$r_dbu['aid'])$cjoin=$r_dbu['tid'];
				}while($r_dbu=mysql_fetch_assoc($q_dbu));
				$ca[]='<strong>已报名人数：</strong> '.$t_user.' 人<br/><strong>已报名：</strong> '.join('、',$cuser_c);
			}
			mysql_free_result($q_dbu);
			$content.='<div class="title">'.$r_dbc['title'].'</div><div class="lcontent">'.join('<br/>', $ca).(($c_log && $r_dbc['closed']==0)?'<br/><br/><form method="post" action="" class="btform_nv">参加人数：<br/><input name="tid" id="formtid" style="width: 90%;" value="'.((isset($cjoin) && $cjoin>0)?$cjoin:'1').'" /><br/><input type="submit" value="'.((isset($cjoin) && $cjoin>0)?'修改参加人数" /><br/><input type="submit" value="我要退出这个活动" name="delu" />':'我要参加这个活动" />').'<input type="hidden" name="jid" value="1"/></form>':'').'</div>';
			$s_a_dbr=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'ccomment', $dbprefix.'member', $r_dbc['id']);
			$q_a_dbr=mysql_query($s_a_dbr) or die('');
			$c_dbr=mysql_num_rows($q_a_dbr);
			if($c_dbr>0){
				$p_dbr=ceil($c_dbr/$config['pagesize']);
				if($page>$p_dbr)$page=$p_dbr;
				$s_dbr=sprintf('%s limit %d, %d', $s_a_dbr, ($page-1)*$config['pagesize'], $config['pagesize']);
				$q_dbr=mysql_query($s_dbr) or die('');
				$r_dbr=mysql_fetch_assoc($q_dbr);
				do{
					$content.='<div class="topic" id="topic-'.$r_dbr['id'].'"><a href="?m=user&amp;id='.$r_dbr['aid'].'">'.$r_dbr['name'].'</a>&nbsp;&nbsp;'.getldate($r_dbr['datetime']).'<div class="list_c">';
					if($r_dbr['sid']>0){
						$pr=getpinfo($r_dbr['sid']);
						$content.='<a href="?m=album&amp;id='.$r_dbr['sid'].'"><img src="'.getmthu($pr).'" alt="" title="'.($pr['vid']>0?'[视频]':'').$pr['title'].'" width="70" height="70" class="photo"/></a>';
						$reply_s=5;
						$s_dbb=sprintf('select a.id, a.aid, a.content, a.datetime, b.name from %s as a, %s as b where a.pid=%s and a.aid=b.id and a.disp=0 order by a.datetime desc limit %d', $dbprefix.'pcomment', $dbprefix.'member', $r_dbr['sid'], $reply_s);
						$q_dbb=mysql_query($s_dbb) or die('');
						$r_dbb=mysql_fetch_assoc($q_dbb);
						if(mysql_num_rows($q_dbb)>0){
							$content.='<div class="reply_d">';
							$i=0;
							$fid=0;
							do{
								if($i==0)$fid=$r_dbb['id'];
								$content.='<div class="reply_v"><a href="?m=user&amp;id='.$r_dbb['aid'].'">'.$r_dbb['name'].'</a>：'.mbookencode($r_dbb['content']).'<div class="reply_i">- '.getldate($r_dbb['datetime']).'</div></div>';
								$i++;
							}while($r_dbb=mysql_fetch_assoc($q_dbb));
							$content.='<div class="reply_v"><a href="?m=album&amp;id='.$r_dbr['sid'].'#topic-'.$fid.'">更多评论</a></div></div>';
						}
						mysql_free_result($q_dbb);
					}else{
						$content.=mbookencode($r_dbr['content']);
					}
					$content.='</div></div>';
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
	mysql_free_result($q_dbc);
}else{
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if($c_log){
			$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
			$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
			$cdate=htmlspecialchars(trim($_POST['cdate']),ENT_QUOTES);
			$cloc=htmlspecialchars(trim($_POST['cloc']),ENT_QUOTES);
			$cpay=htmlspecialchars(trim($_POST['cpay']),ENT_QUOTES);
			if($title!=''){
				$i_db=sprintf('insert into %s (title, content, cdate, cloc, cpay, aid, datetime) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'camp', 
					SQLString($title, 'text'),
					SQLString($cont, 'text'),
					SQLString($cdate, 'text'),
					SQLString($cloc, 'text'),
					SQLString($cpay, 'text'),
					$_SESSION[$config['u_hash']],
					time());
				$result=mysql_query($i_db) or die('');
				$nid=mysql_insert_id();
				setsinfo($pn.' 发起新活动', $_SESSION[$config['u_hash']], $nid, 3);
			}
		}
		header('Location:./?m=camp');
		exit();
	}else{
		$title.='班级活动';
		$s_a_dbc=sprintf('select a.*, b.name from %s as a, %s as b where a.aid=b.id and a.disp=0 order by a.closed, a.sticky desc, a.datetime desc', $dbprefix.'camp', $dbprefix.'member');
		$q_a_dbc=mysql_query($s_a_dbc) or die('');
		$c_dbc=mysql_num_rows($q_a_dbc);
		if($c_dbc>0){
			$p_dbc=ceil($c_dbc/$config['pagesize']);
			if($page>$p_dbc)$page=$p_dbc;
			$s_dbc=sprintf('%s limit %d, %d', $s_a_dbc, ($page-1)*$config['pagesize'], $config['pagesize']);
			$q_dbc=mysql_query($s_dbc) or die('');
			$r_dbc=mysql_fetch_assoc($q_dbc);
			do{
				$ca[$r_dbc['id']][]='发起人：<a href="?m=user&amp;id='.$r_dbc['aid'].'">'.$r_dbc['name'].'</a>, '.($r_dbc['closed']>0?'已结束':getldate($r_dbc['datetime']));
				if($r_dbc['content']!='')$ca[$r_dbc['id']][]=gbookencode($r_dbc['content']).'<br/>';
				if($r_dbc['closed']==0){
					if($r_dbc['cdate']!='')$ca[$r_dbc['id']][]='<strong>活动时间：</strong>'.$r_dbc['cdate'];
					if($r_dbc['cloc']!='')$ca[$r_dbc['id']][]='<strong>活动地点：</strong>'.$r_dbc['cloc'];
					if($r_dbc['cpay']!='')$ca[$r_dbc['id']][]='<strong>活动费用：</strong>'.$r_dbc['cpay'];
					$s_dbu=sprintf('select sum(tid) as ctid from %s where cid=%s', $dbprefix.'cuser', $r_dbc['id']);
					$q_dbu=mysql_query($s_dbu) or die('');
					$r_dbu=mysql_fetch_assoc($q_dbu);
					if(mysql_num_rows($q_dbu)>0 && $r_dbu['ctid']>0)$ca[$r_dbc['id']][]='<strong>已报名人数：</strong> '.$r_dbu['ctid'].' 人';
					mysql_free_result($q_dbu);
				}
				$content.='<div class="title"><a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a></div>'.(isset($ca[$r_dbc['id']])?'<div class="lcontent">'.join('<br/>', $ca[$r_dbc['id']]).'</div>':'');
			}while($r_dbc=mysql_fetch_assoc($q_dbc));
			mysql_free_result($q_dbc);
			if($p_dbc>1)$content.=getpage($page, $p_dbc);
		}else{
			$content.='<div class="title">班级活动</div><div class="lcontent">没有活动</div>';
		}
		mysql_free_result($q_a_dbc);
		if($c_log)$content.='<div class="title">发起活动</div><div class="lcontent"><form method="post" action="" class="btform" id="camform">活动名称：<br/><input name="title" id="formtitle" style="width: 90%" class="bt_input" rel="活动名称"/><br/>活动时间：<br/><input name="cdate" id="formcdate" style="width: 90%"/><br/>活动地点：<br/><input name="cloc" id="formcloc" style="width: 90%"/><br/>活动费用：<br/><input name="cpay" id="formcpay" style="width: 90%"/><br/><textarea name="rinfo" style="width: 95%" rows="5"></textarea><br/><input type="submit" value="发布"/></form></div>';
	}
}
$content.='</div>';
?>