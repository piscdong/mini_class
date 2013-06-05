<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$title.='班级留言';
$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
$vcolor=array(array('c18f5a', 'e7ab6d'), array('586e97', '6c81b6'), array('e37f24', 'ffc535'), array('67b410', 'c2f263'), array('bf2146', 'ee335f'));
$vcolor_c=count($vcolor);
$id=(isset($_GET['id']) && intval($_GET['id'])>0)?intval($_GET['id']):0;
if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
	$jid=0;
	if(isset($_POST['rinfo'])){
		$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
		if($cont!=''){
			$time=time();
			$rid=(isset($_POST['rid']) && intval($_POST['rid'])>0)?intval($_POST['rid']):0;
			$i_db=sprintf('insert into %s (content, aid, datetime, lasttime, rid) values (%s, %s, %s, %s, %s)', $dbprefix.'topic', 
				SQLString($cont, 'text'),
				$_SESSION[$config['u_hash']],
				$time,
				$time,
				$rid);
			$result=mysql_query($i_db) or die('');
			if($rid>0){
				$u_db=sprintf('update %s set lasttime=%s where id=%s', $dbprefix.'topic', $time, $rid);
				$result=mysql_query($u_db) or die('');
				$jid=$rid;
			}else{
				$jid=mysql_insert_id();
			}
		}
	}elseif(isset($_POST['vote']) && intval($_POST['vote'])>0){
		$s_dbt=sprintf('select id, content, datetime from %s where id=%s and tid=0 and mid=1 limit 1', $dbprefix.'topic', SQLString($_POST['vote'], 'int'));
		$q_dbt=mysql_query($s_dbt) or die('');
		$r_dbt=mysql_fetch_assoc($q_dbt);
		if(mysql_num_rows($q_dbt)>0){
			$cont=explode('[/]', $r_dbt['content']);
			if($cont[1]==0 || time()<($r_dbt['datetime']+86400*$cont[1])){
				if(($cont[2]>0 && count($_POST['vote'.$r_dbt['id']])>0) || $_POST['vote'.$r_dbt['id']]!=''){
					$s_dbv=sprintf('select id from %s where tid=%s and aid=%s limit 1', $dbprefix.'vote', $r_dbt['id'], $_SESSION[$config['u_hash']]);
					$q_dbv=mysql_query($s_dbv) or die('');
					if(mysql_num_rows($q_dbv)==0){
						$time=time();
						if($cont[2]>0){
							foreach($_POST['vote'.$r_dbt['id']] as $v){
								$i_db=sprintf('insert into %s (aid, tid, vid, datetime) values (%s, %s, %s, %s)', $dbprefix.'vote', 
									$_SESSION[$config['u_hash']],
									$r_dbt['id'],
									$v,
									$time);
								$result=mysql_query($i_db) or die('');
							}
						}else{
							$i_db=sprintf('insert into %s (aid, tid, vid, datetime) values (%s, %s, %s, %s)', $dbprefix.'vote', 
								$_SESSION[$config['u_hash']],
								$r_dbt['id'],
								$_POST['vote'.$r_dbt['id']],
								$time);
							$result=mysql_query($i_db) or die('');
						}
						$u_db=sprintf('update %s set lasttime=%s where id=%s', $dbprefix.'topic', $time, $r_dbt['id']);
						$result=mysql_query($u_db) or die('');
					}
					mysql_free_result($q_dbv);
				}
			}
			$jid=$r_dbt['id'];
		}
		mysql_free_result($q_dbt);
	}
	header('Location:?page='.$page.($id>0?'&id='.$id:'').($jid>0?'#topic-'.$jid:''));
	exit();
}else{
	if($page==1 && $id==0){
		if($config['content']!='')$content.='<div class="mcontent">'.mbookencode($config['content']).'</div>';
		$s_dbc=sprintf('select a.id, a.aid, a.title, a.datetime, b.name from %s as a, %s as b where a.sticky>0 and a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'camp', $dbprefix.'member');
		$q_dbc=mysql_query($s_dbc) or die('');
		$r_dbc=mysql_fetch_assoc($q_dbc);
		if(mysql_num_rows($q_dbc)>0){
			$content.='<div class="title"><a href="?m=camp">热门活动</a></div><div class="lcontent">';
			$i=0;
			do{
				if($i>0)$content.='<br/>';
				$content.='<a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a>&nbsp;&nbsp;<a href="?m=user&amp;id='.$r_dbc['aid'].'">'.$r_dbc['name'].'</a>, '.getldate($r_dbc['datetime']);
				$i++;
			}while($r_dbc=mysql_fetch_assoc($q_dbc));
			$content.='</div>';
		}
		mysql_free_result($q_dbc);
		$s_dbp=sprintf('select id, upload, vid, url, title from %s where disp=0 and datetime>%s order by datetime desc limit 3', $dbprefix.'photo', (time()-86400*30));
		$q_dbp=mysql_query($s_dbp) or die('');
		$r_dbp=mysql_fetch_assoc($q_dbp);
		if(mysql_num_rows($q_dbp)>0){
			$content.='<div class="title"><a href="?m=album">最新照片、视频</a></div><div class="lcontent">';
			do{
				$content.='<a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getmthu($r_dbp).'" width="70" height="70" class="photo" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').$r_dbp['title'].'"/></a>';
			}while($r_dbp=mysql_fetch_assoc($q_dbp));
			$content.='</div>';
		}
		mysql_free_result($q_dbp);
	}
	if($id>0){
		$s_dbt=sprintf('select a.*, b.name from %s as a, %s as b where a.disp=0 and a.aid=b.id and a.rid=0 and a.tid=0 and a.id=%s limit 1', $dbprefix.'topic', $dbprefix.'member', $id);
		$q_dbt=mysql_query($s_dbt) or die('');
		$c_dbt=mysql_num_rows($q_dbt);
	}else{
		$s_a_dbt=sprintf('select a.*, b.name from %s as a, %s as b where a.disp=0 and a.aid=b.id and a.rid=0 order by a.sticky desc, a.lasttime desc', $dbprefix.'topic', $dbprefix.'member');
		$q_a_dbt=mysql_query($s_a_dbt) or die('');
		$c_dbt=mysql_num_rows($q_a_dbt);
	}
	if($c_dbt>0){
		if($id==0){
			$p_dbt=ceil($c_dbt/$config['pagesize']);
			if($page>$p_dbt)$page=$p_dbt;
			$s_dbt=sprintf('%s limit %d, %d', $s_a_dbt, ($page-1)*$config['pagesize'], $config['pagesize']);
			$q_dbt=mysql_query($s_dbt) or die('');
		}
		$r_dbt=mysql_fetch_assoc($q_dbt);
		do{
			unset($cm);
			$content.='<div class="topic" id="topic-'.$r_dbt['id'].'"><a href="?m=user&amp;id='.$r_dbt['aid'].'">'.$r_dbt['name'].'</a>&nbsp;&nbsp;'.getldate($r_dbt['datetime']).'<div class="list_c">';
			switch($r_dbt['tid']){
				case 2:
					$pr=getpinfo($r_dbt['sid']);
					$content.='<a href="?m=album&amp;id='.$r_dbt['sid'].'"><img src="'.getmthu($pr).'" alt="" title="'.($pr['vid']>0?'[视频]':'').$pr['title'].'" width="70" height="70" class="photo"/></a>';
					break;
				case 3:
					$pr=getcinfo($r_dbt['sid'], 'content, closed, cdate, cloc, cpay, title');
					if($pr['content']!='')$ca[$r_dbt['id']][]=mbookencode($pr['content']);
					if($pr['closed']==0){
						if($pr['cdate']!='')$ca[$r_dbt['id']][]='<strong>活动时间：</strong>'.$pr['cdate'];
						if($pr['cloc']!='')$ca[$r_dbt['id']][]='<strong>活动地点：</strong>'.$pr['cloc'];
						if($pr['cpay']!='')$ca[$r_dbt['id']][]='<strong>活动费用：</strong>'.$pr['cpay'];
						$s_dbu=sprintf('select sum(tid) as ctid from %s where cid=%s', $dbprefix.'cuser', $r_dbt['sid']);
						$q_dbu=mysql_query($s_dbu) or die('');
						$r_dbu=mysql_fetch_assoc($q_dbu);
						if(mysql_num_rows($q_dbu)>0 && $r_dbu['ctid']>0)$ca[$r_dbt['id']][]='<strong>已报名人数：</strong> '.$r_dbu['ctid'].' 人';
						mysql_free_result($q_dbu);
					}
					$content.='<a href="?m=camp&amp;id='.$r_dbt['sid'].'">'.$pr['title'].'</a>'.(isset($ca[$r_dbt['id']])?'<br/><br/>'.join('<br/>', $ca[$r_dbt['id']]):'');
					break;
			}
			if($r_dbt['tid']==0 || $r_dbt['tid']==1){
				if($r_dbt['mid']==0){
					if($id>0){
						$content.=mbookencode($r_dbt['content']);
					}else{
						$content.=getmco($r_dbt['content'], $r_dbt['id']);
					}
				}else{
					unset($cont);
					unset($vop);
					unset($vta);
					unset($vtb);
					$maxv=0;
					$is_vote=0;
					if(!$c_log)$is_vote=1;
					$is_avote=0;
					$s_dbv=sprintf('select a.aid, a.vid, b.name from %s as a, %s as b where a.tid=%s and a.aid=b.id order by a.id', $dbprefix.'vote', $dbprefix.'member', $r_dbt['id']);
					$q_dbv=mysql_query($s_dbv) or die('');
					$r_dbv=mysql_fetch_assoc($q_dbv);
					if(mysql_num_rows($q_dbv)>0){
						do{
							if(!isset($vta[$r_dbv['vid']]))$vta[$r_dbv['vid']]=0;
							$vta[$r_dbv['vid']]+=1;
							$vtb[$r_dbv['vid']][]='<a href="?m=user&amp;id='.$r_dbv['aid'].'">'.$r_dbv['name'].'</a>';
							if($is_vote==0 && $r_dbv['aid']==$_SESSION[$config['u_hash']]){
								$is_vote=1;
								$is_avote=1;
							}
						}while($r_dbv=mysql_fetch_assoc($q_dbv));
						$maxv=max($vta);
					}
					mysql_free_result($q_dbv);
					$cont=explode('[/]', $r_dbt['content']);
					if($cont[1]>0 && time()>($r_dbt['datetime']+86400*$cont[1])){
						$is_vote=1;
						$is_avote=0;
					}
					if($cont[0]!='')$content.=$cont[0].($cont[1]>0?'（投票截止日期：'.date('Y年n月j日', $r_dbt['datetime']+86400*$cont[1]).'）':'').'<br/><br/>';
					$vty=$cont[2]>0?'checkbox':'radio';
					$vop=explode("\r", $cont[3]);
					if($is_vote==0)$content.='<form method="post" action="">';
					foreach($vop as $k=>$v){
						$cuv=isset($vta[$k])?$vta[$k]:0;
						$vcolor_k=$k%$vcolor_c;
						$content.=($is_vote>0?'':'<input type="'.$vty.'" name="vote'.$r_dbt['id'].($cont[2]>0?'[]':'').'" value="'.$k.'"/> ').$v.($maxv>0?'（'.$cuv.'）<br/><img src="images/v.gif" height="8" width="'.($cuv>0?max(1, round(100*$cuv/$maxv)).'%':'1').'" style="border: 1px solid #'.$vcolor[$vcolor_k][0].'; background: #'.$vcolor[$vcolor_k][1].';"/>':'').'<br/>';
					}
					if($is_avote>0){
						$content.='<a href="?page='.$page.'&vid='.$r_dbt['id'].($id>0?'&amp;id='.$id:'').'">重新投票</a>';
						if(isset($_GET['vid']) && $_GET['vid']==$r_dbt['id']){
							$d_db=sprintf('delete from %s where aid=%s and tid=%s', $dbprefix.'vote', $_SESSION[$config['u_hash']], $r_dbt['id']);
							$result=mysql_query($d_db) or die('');
							header('Location:?page='.$page.($id>0?'&id='.$id:'#topic-'.$r_dbt['id']));
							exit();
						}
					}else{
						$content.='<br/><input type="submit" value="投票" /><input type="hidden" name="vote" value="'.$r_dbt['id'].'"/></form>';
					}
				}
			}
			$reply_s=5;
			if($r_dbt['tid']>1){
				if($r_dbt['tid']>2){
					$idb='c';
					$imdb=' and a.sid=0';
				}else{
					$idb='p';
					$imdb='';
				}
				$s_dbr=sprintf('select a.*, b.name, b.power from %s as a, %s as b where a.%sid=%s and a.aid=b.id and a.disp=0%s order by a.datetime desc limit %d', $dbprefix.$idb.'comment', $dbprefix.'member', $idb, $r_dbt['sid'], $imdb, $reply_s);
				$q_dbr=mysql_query($s_dbr) or die('');
				$c_dbr=mysql_num_rows($q_dbr);
			}else{
				$s_dbr=sprintf('select a.*, b.name, b.power from %s as a, %s as b where a.disp=0 and a.rid=%s and a.aid=b.id order by a.datetime desc', $dbprefix.'topic', $dbprefix.'member', $r_dbt['id']);
				if($id>0){
					$q_dbr=mysql_query($s_dbr) or die('');
					$c_dbr=mysql_num_rows($q_dbr);
				}else{
					$s_a_dbr=$s_dbr;
					$q_a_dbr=mysql_query($s_dbr) or die('');
					$c_dbr=mysql_num_rows($q_a_dbr);
				}
			}
			$is_comment=0;
			if($r_dbt['tid']==0 && $c_log && $r_dbt['disp']==0 && $r_dbt['is_lock']==0)$is_comment=1;
			if(($id>0 && $is_comment>0) || $c_dbr>0)$content.='<div class="reply_d">';
			if($c_dbr>0){
				if($id==0 && $r_dbt['tid']<2){
					$s_dbr=sprintf('%s limit %d', $s_a_dbr, $reply_s);
					$q_dbr=mysql_query($s_dbr) or die('');
				}
				$r_dbr=mysql_fetch_assoc($q_dbr);
				$i=0;
				$fid=0;
				do{
					if($i==0)$fid=$r_dbr['id'];
					$content.='<div class="reply_v" id="reply-'.$r_dbr['id'].'"><a href="?m=user&amp;id='.$r_dbr['aid'].'">'.$r_dbr['name'].'</a>：'.($id>0?mbookencode($r_dbr['content']):getmco($r_dbr['content'], $r_dbt['id'], $r_dbr['id'], 1)).'<div class="reply_i">- '.getldate($r_dbr['datetime']).'</div></div>';
					$i++;
				}while($r_dbr=mysql_fetch_assoc($q_dbr));
				if($id==0){
					if($r_dbt['tid']>1){
						$content.='<div class="reply_v"><a href="?m='.($r_dbt['tid']>2?'camp':'album').'&amp;id='.$r_dbt['sid'].'#topic-'.$fid.'">更多评论</a></div>';
					}else{
						mysql_free_result($q_dbr);
						if($is_comment>0)$a_ci[$r_dbt['id']][]='<a href="?m=list&amp;id='.$r_dbt['id'].'#reply">发表回复</a>';
						if($c_dbr>$reply_s)$a_ci[$r_dbt['id']][]='<a href="?m=list&amp;id='.$r_dbt['id'].'">更多回复</a>';
						if(isset($a_ci[$r_dbt['id']]))$content.='<div class="reply_v">'.join(' | ', $a_ci[$r_dbt['id']]).'</div>';
					}
				}
			}elseif($id==0 && $is_comment>0){
				$content.='<br/><a href="?m=list&amp;id='.$r_dbt['id'].'#reply">发表回复</a>';
			}
			if($id>0 && $is_comment>0)$content.='<div class="reply_v" id="reply"><form method="post" action="" class="btform" id="lyform'.$r_dbt['id'].'"><textarea name="rinfo" id="forminfor'.$r_dbt['id'].'" rows="4" style="width: 95%" class="bt_input" rel="内容"></textarea><br/><input type="submit" value="发表回复" /><input type="hidden" name="rid" value="'.$r_dbt['id'].'" /></form></div>';
			if(($id>0 && $is_comment>0) || $c_dbr>0)$content.='</div>';
			if($id>0){
				mysql_free_result($q_dbr);
			}else{
				if($r_dbt['tid']>1){
					mysql_free_result($q_dbr);
				}else{
					mysql_free_result($q_a_dbr);
				}
			}
			$content.='</div></div>';
		}while($r_dbt=mysql_fetch_assoc($q_dbt));
		if($id==0){
			mysql_free_result($q_dbt);
			if($p_dbt>1)$content.=getpage($page, $p_dbt);
		}
	}else{
		if($id>0){
			header('Location:./');
			exit();
		}else{
			$content.='<div class="title">班级留言</div><div class="lcontent">没有留言</div>';
		}
	}
	if($id>0){
		mysql_free_result($q_dbt);
	}else{
		mysql_free_result($q_a_dbt);
	}
	if($c_log && $id==0)$content.='<div class="title">发表留言</div><div class="lcontent"><form method="post" action="" class="btform" id="lyform"><textarea name="rinfo" id="forminfor0" rows="4" style="width: 95%" class="bt_input" rel="内容"></textarea><br/><input type="submit" value="发表留言" /></form></div>';
}
