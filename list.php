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
if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
	if(isset($_POST['rinfo'])){
		$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
		if(isset($_POST['tid']) && $_POST['tid']=='t' && $pa>0){
			$u_db=sprintf('update %s set content=%s', $dbprefix.'main', SQLString($cont, 'text'));
			$result=mysql_query($u_db) or die('');
		}elseif(isset($_POST['id']) && intval($_POST['id'])>0 && $cont!=''){
			$s_dbt=sprintf('select a.id, a.aid, b.power from %s as a, %s as b where a.id=%s and a.aid=b.id and a.tid=0 and a.mid=0 limit 1', $dbprefix.'topic', $dbprefix.'member', SQLString($_POST['id'], 'int'));
			$q_dbt=mysql_query($s_dbt) or die('');
			$r_dbt=mysql_fetch_assoc($q_dbt);
			if(mysql_num_rows($q_dbt)>0 && ($pa>$r_dbt['power'] || $_SESSION[$config['u_hash']]==$r_dbt['aid'])){
				$u_db=sprintf('update %s set content=%s where id=%s', $dbprefix.'topic', SQLString($cont, 'text'), $r_dbt['id']);
				$result=mysql_query($u_db) or die('');
			}
			mysql_free_result($q_dbt);
		}elseif($cont!=''){
			$time=time();
			$rid=(isset($_POST['rid']) && intval($_POST['rid'])>0)?intval($_POST['rid']):0;
			if(isset($_POST['u_sina']) && $_POST['u_sina']==1)$a_syncp[]='sina';
			if(isset($_POST['u_tqq']) && $_POST['u_tqq']==1)$a_syncp[]='tqq';
			if(isset($_POST['u_renren']) && $_POST['u_renren']==1)$a_syncp[]='renren';
			if(isset($_POST['u_kx001']) && $_POST['u_kx001']==1)$a_syncp[]='kx001';
			if(isset($_POST['u_tsohu']) && $_POST['u_tsohu']==1)$a_syncp[]='tsohu';
			if(isset($_POST['u_t163']) && $_POST['u_t163']==1)$a_syncp[]='t163';
			if(isset($_POST['u_twitter']) && $_POST['u_twitter']==1)$a_syncp[]='twitter';
			if(isset($_POST['u_facebook']) && $_POST['u_facebook']==1)$a_syncp[]='facebook';
			$sync_p=isset($a_syncp)?join('|', $a_syncp):'';
			$i_db=sprintf('insert into %s (content, aid, datetime, lasttime, rid, sync_p) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'topic', 
				SQLString($cont, 'text'),
				$_SESSION[$config['u_hash']],
				$time,
				$time,
				$rid, 
				SQLString($sync_p, 'text'));
			$result=mysql_query($i_db) or die('');
			if($rid>0){
				$u_db=sprintf('update %s set lasttime=%s where id=%s', $dbprefix.'topic', $time, $rid);
				$result=mysql_query($u_db) or die('');
			}
		}
	}elseif(isset($_POST['mid']) && isset($_POST['vtitle'])){
		$cont[]=htmlspecialchars(trim($_POST['vtitle']),ENT_QUOTES);
		$cont[]=(isset($_POST['vday']) && intval($_POST['vday'])>0)?$_POST['vday']:0;
		if(isset($_POST['id']) && intval($_POST['id'])>0){
			$s_dbt=sprintf('select a.id, a.aid, a.content, b.power from %s as a, %s as b where a.id=%s and a.aid=b.id and a.tid=0 and a.mid=%s limit 1', $dbprefix.'topic', $dbprefix.'member', SQLString($_POST['id'], 'int'), SQLString($_POST['mid'], 'int'));
			$q_dbt=mysql_query($s_dbt) or die('');
			$r_dbt=mysql_fetch_assoc($q_dbt);
			if(mysql_num_rows($q_dbt)>0 && ($pa>$r_dbt['power'] || $_SESSION[$config['u_hash']]==$r_dbt['aid'])){
				$vopti=explode('[/]', $r_dbt['content']);
				$cont[]=$vopti[2];
				$cont[]=$vopti[3];
				$u_db=sprintf('update %s set content=%s where id=%s', $dbprefix.'topic', SQLString(join('[/]', $cont), 'text'), $r_dbt['id']);
				$result=mysql_query($u_db) or die('');
			}
			mysql_free_result($q_dbt);
		}else{
			$cont[]=$_POST['vtype'];
			$vopta=explode("\r", $_POST['voption']);
			foreach($vopta as $v){
				if(trim($v)!='')$vopti[]=htmlspecialchars(trim($v),ENT_QUOTES);
			}
			if(isset($vopti) && count($vopti)>0){
				$cont[]=join("\r", $vopti);
				$time=time();
				$i_db=sprintf('insert into %s (content, aid, datetime, lasttime, mid) values (%s, %s, %s, %s, %s)', $dbprefix.'topic', 
					SQLString(join('[/]', $cont), 'text'),
					$_SESSION[$config['u_hash']],
					$time,
					$time,
					SQLString($_POST['mid'], 'int'));
				$result=mysql_query($i_db) or die('');
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
		}
		mysql_free_result($q_dbt);
	}elseif(isset($_POST['did']) && intval($_POST['did'])>0 && $pa>0 && $pa<9 && trim($_POST['rtext'])!=''){
		$rtext=htmlspecialchars(trim($_POST['rtext']),ENT_QUOTES);
		$s_dbt=sprintf('select a.id, a.mid, a.content, b.name from %s as a, %s as b where a.id=%s and a.aid=b.id and a.tid=0 limit 1', $dbprefix.'topic', $dbprefix.'member', SQLString($_POST['did'], 'int'));
		$q_dbt=mysql_query($s_dbt) or die('');
		$r_dbt=mysql_fetch_assoc($q_dbt);
		if(mysql_num_rows($q_dbt)>0){
			$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'topic', $r_dbt['id']);
			$result=mysql_query($u_db) or die('');
			if($r_dbt['mid']>0){
				$coa=explode('[/]', $r_dbt['content']);
				$msg=$coa[0];
			}else{
				$msg=$r_dbt['content'];
			}
			$ac=$pn." 删除留言\r删除理由：".$rtext."\r\r".$r_dbt['name'].'：'.$msg;
			setoinfo($ac, $r_dbt['id']);
		}
		mysql_free_result($q_dbt);
	}
	header('Location:?page='.$page);
	exit();
}else{
	if($c_log){
		if($pa==9){
			if(isset($_GET['pid']) && intval($_GET['pid'])>0){
				undellist($_GET['pid']);
				header('Location:?page='.$page);
				exit();
			}
			if(isset($_GET['did']) && intval($_GET['did'])>0){
				$s_dbt=sprintf('select id, rid, mid, datetime from %s where id=%s and tid=0 limit 1', $dbprefix.'topic', SQLString($_GET['did'], 'int'));
				$q_dbt=mysql_query($s_dbt) or die('');
				$r_dbt=mysql_fetch_assoc($q_dbt);
				if(mysql_num_rows($q_dbt)>0){
					dellist($r_dbt);
					header('Location:?page='.$page);
					exit();
				}
				mysql_free_result($q_dbt);
			}
		}
		$c_sync=getsync_c($ar);
	}
	$content.='<div class="rcontent"><div class="content"><ul class="clist">';
	if($page==1){
		$content.='<li><div class="mcontent"><div id="l_0">'.($config['content']!=''?gbookencode($config['content']):'没有公告！').(($c_log && $pa>0)?'&nbsp;<img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_0|h_0" class="f_link"/>':'').'</div>';
		if($c_log && $pa>0)$content.='<form method="post" action="" id="h_0" style="display: none;"><div class="formline"><textarea name="rinfo" cols="50" rows="4">'.$config['content'].'</textarea></div><div class="formline"><input type="submit" value="修改" class="button" /> <input type="button" value="取消" class="button" name="hs_cbt" data-id="h_0|l_0" /><input type="hidden" name="tid" value="t"/></div></form>';
		$content.='</div></li>';
		$s_dbc=sprintf('select a.id, a.aid, a.title, a.datetime, b.name from %s as a, %s as b where a.sticky>0 and a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'camp', $dbprefix.'member');
		$q_dbc=mysql_query($s_dbc) or die('');
		$r_dbc=mysql_fetch_assoc($q_dbc);
		if(mysql_num_rows($q_dbc)>0){
			$content.='<li><div class="title"><a href="?m=camp">热门活动</a></div><div class="lcontent"><ul>';
			do{
				$content.='<li><a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a>&nbsp;&nbsp;<span class="gdate">'.getalink($r_dbc['aid'], $r_dbc['name']).', '.getldate($r_dbc['datetime']).'</span></li>';
			}while($r_dbc=mysql_fetch_assoc($q_dbc));
			$content.='</ul></div></li>';
		}
		mysql_free_result($q_dbc);
	}
	$ddb=($c_log && $pa==9)?'(a.disp=0 || a.tid=0)':'a.disp=0';
	$s_a_dbt=sprintf('select a.*, b.power, b.name from %s as a, %s as b where %s and a.aid=b.id and a.rid=0 order by a.sticky desc, a.lasttime desc', $dbprefix.'topic', $dbprefix.'member', $ddb);
	$q_a_dbt=mysql_query($s_a_dbt) or die('');
	$c_dbt=mysql_num_rows($q_a_dbt);
	if($c_dbt>0){
		if($c_log){
			$js_c.='
	$("#forminfok").keydown(function(event){
		if(event.ctrlKey && event.keyCode==13)$("#qsform").submit();
	});';
			$content.='<li><div class="title">快速留言&nbsp;&nbsp;<span class="gdate">按 Ctrl+Enter 发布留言</span></div><div class="lcontent"><form method="post" action="" class="btform" id="qsform"><div class="formline"><textarea name="rinfo" id="forminfok" rows="2" title="按 Ctrl+Enter 发布留言" class="bt_input" rel="留言内容"></textarea>'.$c_sync.'</div></form></div></li>';
		}
		$p_dbt=ceil($c_dbt/$config['pagesize']);
		if($page>$p_dbt)$page=$p_dbt;
		$s_dbt=sprintf('%s limit %d, %d', $s_a_dbt, ($page-1)*$config['pagesize'], $config['pagesize']);
		$q_dbl=mysql_query($s_dbt) or die('');
		$r_dbl=mysql_fetch_assoc($q_dbl);
		if($c_log && $pa>0)$js_c.='
	$("img[name=\'del_list_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?page='.$page.'&did=\'+$(this).data(\'id\');
	});';
		if($c_log)$js_c.='
	$("span[name=\'del_vlink\']").click(function(){
		if(confirm(\'确认要删除你的现有投票，重新投票？\'))location.href=\'?page='.$page.'&vid=\'+$(this).data(\'id\');
	});';
		do{
			unset($cm);
			$content.='<li class="l_list"><a href="?m=user&amp;id='.$r_dbl['aid'].'"><img src="avator.php?id='.$r_dbl['aid'].'" alt="" title="'.$r_dbl['name'].'" class="photo" width="55" height="55"/></a><div class="list_r"><div class="list_title">';
			if($r_dbl['tid']>0){
				if($c_log){
					if($r_dbl['tid']>1)$cm[]='&nbsp;<a href="?m='.($r_dbl['tid']>2?'camp':'album').'&amp;id='.$r_dbl['sid'].'#postreply"><img src="images/o_9.gif" alt="" title="回复"/></a>';
					if($pa>0 && $r_dbl['tid']==1){
						$cm[]='&nbsp;<img src="images/o_2.gif" alt="" title="删除" name="del_list_img" data-id="'.$r_dbl['id'].'" class="f_link"/>';
						if(isset($_GET['did']) && $_GET['did']==$r_dbl['id']){
							$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'topic', $r_dbl['id']);
							$result=mysql_query($u_db) or die('');
							header('Location:?page='.$page);
							exit();
						}
					}
				}
			}else{
				if($c_log){
					if($r_dbl['disp']==0 && $r_dbl['is_lock']==0)$cm[]='&nbsp;<img src="images/o_9.gif" alt="" title="回复" name="s_cbt" data-id="r_'.$r_dbl['id'].'" class="f_link"/>';
					if($pa>$r_dbl['power'] || $_SESSION[$config['u_hash']]==$r_dbl['aid'])$cm[]='&nbsp;<img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_'.$r_dbl['id'].'|h_'.$r_dbl['id'].'" class="f_link"/>';
					if($pa>0){
						$cm[]='&nbsp;<a href="?page='.$page.'&amp;sid='.$r_dbl['id'].'"><img src="images/o_'.($r_dbl['sticky']>0?'1.gif" alt="" title="取消':'0.gif" alt="" title="').'置顶"/></a>&nbsp; &nbsp;&nbsp;<a href="?page='.$page.'&amp;lid='.$r_dbl['id'].'"><img src="images/o_'.($r_dbl['is_lock']>0?'4.gif" alt="" title="取消':'8.gif" alt="" title="').'锁定"/></a>&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="'.($pa==9?'del_list_img':'s_cbt').'" data-id="'.($pa==9?'':'del_').$r_dbl['id'].'" class="f_link"/>';
						if(isset($_GET['sid']) && $_GET['sid']==$r_dbl['id']){
							$sticky=$r_dbl['sticky']>0?0:1;
							$u_db=sprintf('update %s set sticky=%s where id=%s', $dbprefix.'topic', SQLString($sticky, 'int'), $r_dbl['id']);
							$result=mysql_query($u_db) or die('');
							if($r_dbl['mid']>0){
								$coa=explode('[/]', $r_dbl['content']);
								$msg=$coa[0];
							}else{
								$msg=$r_dbl['content'];
							}
							$ac=$pn.' '.($sticky>0?'':'取消')."置顶留言\r\r".$r_dbl['name'].'：'.$msg;
							setoinfo($ac, $r_dbl['id']);
							header('Location:?page='.$page);
							exit();
						}
						if(isset($_GET['lid']) && $_GET['lid']==$r_dbl['id']){
							$lock=$r_dbl['is_lock']>0?0:1;
							$u_db=sprintf('update %s set is_lock=%s where id=%s', $dbprefix.'topic', SQLString($lock, 'int'), $r_dbl['id']);
							$result=mysql_query($u_db) or die('');
							if($r_dbl['mid']>0){
								$coa=explode('[/]', $r_dbl['content']);
								$msg=$coa[0];
							}else{
								$msg=$r_dbl['content'];
							}
							$ac=$pn.' '.($lock>0?'':'取消')."锁定留言\r\r".$r_dbl['name'].'：'.$msg;
							setoinfo($ac, $r_dbl['id']);
							header('Location:?page='.$page);
							exit();
						}
					}
					if($pa==9 && $r_dbl['disp']>0)$cm[]='&nbsp;<span class="del_n">已删除</span> <a href="?page='.$page.'&amp;pid='.$r_dbl['id'].'"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
				}
			}
			if(isset($cm))$content.='<span class="gmod">'.join('&nbsp; &nbsp;',$cm).'</span>';
			$content.=($r_dbl['sticky']>0?'<img src="images/o_0.gif" alt="" title="置顶留言"/> ':'').getalink($r_dbl['aid'], $r_dbl['name']).'&nbsp;&nbsp;<span class="gdate">'.getldate($r_dbl['datetime']).'</span></div><div class="list_c">';
			switch($r_dbl['tid']){
				case 2:
					$pr=getpinfo($r_dbl['sid']);
					$u=$pr['url'];
					if($pr['upload']==0){
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
					if($pr['vid']>0){
						$content.='<img src="'.getthu($pr).'" width="70" height="70" class="f_link video_slink al_t" alt="" title="观看视频" data-id="'.$r_dbl['id'].'"/><div id="video_div_'.$r_dbl['id'].'"></div><textarea id="video_text_'.$r_dbl['id'].'" style="display: none;">'.htmlspecialchars($u,ENT_QUOTES).'</textarea><a href="?m=album&amp;id='.$r_dbl['sid'].'">'.($pr['title']!=''?$pr['title']:'视频 #'.$pr['id']).'</a>';
					}else{
						$content.='<img src="'.getthu($pr).'" alt="" title="点击查看原图" width="70" height="70" class="f_link img_lb al_t" data-img="'.(($config['slink']>0 || $pr['upload']==0)?($pr['upload']>0?'file/':'').$u:'img.php?id='.$pr['id']).'"/><br/><a href="?m=album&amp;id='.$r_dbl['sid'].'">'.($pr['title']!=''?$pr['title']:'照片 #'.$pr['id']).'</a>';
					}
					break;
				case 3:
					$pr=getcinfo($r_dbl['sid'], 'content, closed, cdate, cloc, cpay, title');
					if($pr['content']!='')$ca[$r_dbl['id']][]=gbookencode($pr['content']);
					if($pr['closed']==0){
						if($pr['cdate']!='')$ca[$r_dbl['id']][]='<strong>活动时间：</strong>'.$pr['cdate'];
						if($pr['cloc']!='')$ca[$r_dbl['id']][]='<strong>活动地点：</strong>'.$pr['cloc'].' <span class="mlink f_link img_lb" data-img="http://api.map.baidu.com/staticimage?center='.urlencode($pr['cloc']).'&amp;zoom=16&amp;markers='.urlencode($pr['cloc']).'">查看地图</span>';
						if($pr['cpay']!='')$ca[$r_dbl['id']][]='<strong>活动费用：</strong>'.$pr['cpay'];
						$s_dbu=sprintf('select sum(tid) as ctid from %s where cid=%s', $dbprefix.'cuser', $r_dbl['sid']);
						$q_dbu=mysql_query($s_dbu) or die('');
						$r_dbu=mysql_fetch_assoc($q_dbu);
						if(mysql_num_rows($q_dbu)>0 && $r_dbu['ctid']>0)$ca[$r_dbl['id']][]='<strong>已报名人数：</strong> '.$r_dbu['ctid'].' 人';
						mysql_free_result($q_dbu);
					}
					$content.='<a href="?m=camp&amp;id='.$r_dbl['sid'].'">'.$pr['title'].'</a>'.(isset($ca[$r_dbl['id']])?'<br/><br/>'.join('<br/>', $ca[$r_dbl['id']]):'');
					break;
			}
			if($r_dbl['tid']==0 && $c_log && $pa>0 && $pa<9)$content.='<form method="post" action="" class="btform" id="del_'.$r_dbl['id'].'" style="display: none;"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" name="h_cbt" data-id="del_'.$r_dbl['id'].'"/><input type="hidden" name="did" value="'.$r_dbl['id'].'" /></td></tr></table></form>';
			if($r_dbl['tid']==0 || $r_dbl['tid']==1){
				$content.='<div id="l_'.$r_dbl['id'].'">';
				if($r_dbl['mid']==0){
					$content.=getaco($r_dbl['content'], $r_dbl['id']);
				}else{
					unset($cont);
					unset($vop);
					unset($vta);
					unset($vtb);
					$maxv=0;
					$is_vote=0;
					if(!$c_log)$is_vote=1;
					$is_avote=0;
					$s_dbv=sprintf('select a.aid, a.vid, b.name from %s as a, %s as b where a.tid=%s and a.aid=b.id order by a.id', $dbprefix.'vote', $dbprefix.'member', $r_dbl['id']);
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
					$cont=explode('[/]', $r_dbl['content']);
					if($cont[1]>0 && time()>($r_dbl['datetime']+86400*$cont[1])){
						$is_vote=1;
						$is_avote=0;
					}
					if($cont[0]!='')$content.=$cont[0].($cont[1]>0?'（投票截止日期：'.date('Y年n月j日', $r_dbl['datetime']+86400*$cont[1]).'）':'').'<br/><br/>';
					$vty=$cont[2]>0?'checkbox':'radio';
					$vop=explode("\r", $cont[3]);
					if($pa>0 && $maxv>0)$content.='<div id="vdiv_v_'.$r_dbl['id'].'">';
					if($is_vote==0)$content.='<form method="post" action="">';
					$vrs_c='';
					foreach($vop as $k=>$v){
						$cuv=isset($vta[$k])?$vta[$k]:0;
						$vcolor_k=$k%$vcolor_c;
						$content.=($is_vote>0?'':'<input type="'.$vty.'" name="vote'.$r_dbl['id'].($cont[2]>0?'[]':'').'" value="'.$k.'"/> ').$v.($maxv>0?'（'.$cuv.'）<br/><img src="images/v.gif" height="8" width="'.max(1, round(400*$cuv/$maxv)).'" style="border: 1px solid #'.$vcolor[$vcolor_k][0].'; background: #'.$vcolor[$vcolor_k][1].';"/>':'').'<br/>';
						$vrs_c.=$v.'（'.$cuv.'）'.(isset($vtb[$k])?'<br/>'.join('、', $vtb[$k]):'').'<br/><br/>';
					}
					if($is_vote==0)$content.='<br/><input type="submit" value="投票" class="button" /><input type="hidden" name="vote" value="'.$r_dbl['id'].'"/> ';
					if($is_avote>0){
						$content.='<span name="del_vlink" data-id="'.$r_dbl['id'].'" class="mlink f_link">重新投票</span> ';
						if(isset($_GET['vid']) && $_GET['vid']==$r_dbl['id']){
							$d_db=sprintf('delete from %s where aid=%s and tid=%s', $dbprefix.'vote', $_SESSION[$config['u_hash']], $r_dbl['id']);
							$result=mysql_query($d_db) or die('');
							header('Location:?page='.$page);
							exit();
						}
					}
					if($pa>0 && $maxv>0)$content.='<span name="hs_cbt" data-id="vdiv_v_'.$r_dbl['id'].'|vdiv_r_'.$r_dbl['id'].'" class="mlink f_link">查看详情</span></div><div id="vdiv_r_'.$r_dbl['id'].'" style="display: none;">'.$vrs_c.'<span name="hs_cbt" data-id="vdiv_r_'.$r_dbl['id'].'|vdiv_v_'.$r_dbl['id'].'" class="mlink f_link">返回</span></div>';
					if($is_vote==0)$content.='</form>';
				}
				$content.='</div>';
			}
			if($r_dbl['tid']==0 && $c_log && ($pa>$r_dbl['power'] || $_SESSION[$config['u_hash']]==$r_dbl['aid'])){
				if($r_dbl['mid']>0){
					$content.='<form method="post" action="" class="btform" id="h_'.$r_dbl['id'].'" style="display: none;"><table><tr><td>投票标题：</td><td><input name="vtitle" class="bt_input" rel="投票标题" size="32" value="'.$cont[0].'" /></td></tr><tr><td>有效天数：</td><td><input name="vday" size="32" value="'.$cont[1].'" />天（0或空为不限制）</td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /> <input value="取消" class="button" type="button" name="hs_cbt" data-id="h_'.$r_dbl['id'].'|l_'.$r_dbl['id'].'"/><input type="hidden" name="id" value="'.$r_dbl['id'].'" /><input type="hidden" name="mid" value="'.$r_dbl['mid'].'" /></td></tr></table></form>';
				}else{
					$content.=getcform($r_dbl['id'], $r_dbl['content']);
				}
			}
			if($r_dbl['tid']==0 && $c_log && $r_dbl['disp']==0 && $r_dbl['is_lock']==0)$content.='<form method="post" action="" class="btform" id="r_'.$r_dbl['id'].'" style="display: none;"><div class="formline"><textarea name="rinfo" id="forminfor'.$r_dbl['id'].'" rows="4" class="bt_input" rel="内容"></textarea>'.$c_sync.'</div><div class="formline"><input type="submit" value="回复" class="button" /> <input value="取消" class="button" type="button" name="h_cbt" data-id="r_'.$r_dbl['id'].'"/><input type="hidden" name="rid" value="'.$r_dbl['id'].'" /></div></form>';
			$reply_s=5;
			if($r_dbl['tid']>1){
				if($r_dbl['tid']>2){
					$idb='c';
					$imdb=' and a.sid=0';
				}else{
					$idb='p';
					$imdb='';
				}
				$s_dbr=sprintf('select a.*, b.name, b.power from %s as a, %s as b where a.%sid=%s and a.aid=b.id and a.disp=0%s order by a.datetime desc limit %d', $dbprefix.$idb.'comment', $dbprefix.'member', $idb, $r_dbl['sid'], $imdb, $reply_s);
				$q_dbr=mysql_query($s_dbr) or die('');
				$c_dbr=mysql_num_rows($q_dbr);
			}else{
				$s_a_dbr=sprintf('select a.*, b.name, b.power from %s as a, %s as b where %s and a.rid=%s and a.aid=b.id order by a.datetime desc', $dbprefix.'topic', $dbprefix.'member', $ddb, $r_dbl['id']);
				$q_a_dbr=mysql_query($s_a_dbr) or die('');
				$c_dbr=mysql_num_rows($q_a_dbr);
			}
			if($c_dbr>0){
				$content.='<div id="reply_v_'.$r_dbl['id'].'" class="reply_d">';
				if($r_dbl['tid']<2){
					$p_dbr=ceil($c_dbr/$reply_s);
					$s_dbr=sprintf('%s limit %d', $s_a_dbr, $reply_s);
					$q_dbr=mysql_query($s_dbr) or die('');
					if($r_dbl['tid']==0 && $p_dbr>1)$content.='<span class="plist_'.$r_dbl['id'].'" id="plist_'.$r_dbl['id'].'_1">';
				}
				$r_dbr=mysql_fetch_assoc($q_dbr);
				do{
					$ei=($r_dbl['tid']==0 && $c_log && ($pa>$r_dbr['power'] || $_SESSION[$config['u_hash']]==$r_dbr['aid']))?'&nbsp; <img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_'.$r_dbr['id'].'|h_'.$r_dbr['id'].'" class="f_link"/>':'';
					$content.='<div class="reply_v"><div id="l_'.$r_dbr['id'].'">'.getalink($r_dbr['aid'], $r_dbr['name'], 1).'：'.getaco($r_dbr['content'], $r_dbr['id'], 1).'</div>';
					if($ei!='')$content.=getcform($r_dbr['id'], $r_dbr['content']);
					if($r_dbl['tid']==0 && $c_log && $pa>0 && $pa<9)$content.='<form method="post" action="" class="btform" id="del_'.$r_dbr['id'].'" style="display: none;"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" name="h_cbt" data-id="del_'.$r_dbr['id'].'"/><input type="hidden" name="did" value="'.$r_dbr['id'].'" /></td></tr></table></form>';
					$content.='<div class="reply_i">- '.getldate($r_dbr['datetime']).$ei;
					if($r_dbl['tid']==0 && $c_log){
						if($pa>0)$content.='&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="'.($pa==9?'del_list_img':'s_cbt').'" data-id="'.($pa==9?'':'del_').$r_dbr['id'].'" class="f_link"/>';
						if($pa==9 && $r_dbr['disp']>0)$content.='&nbsp; &nbsp; <span class="del_n">已删除</span> <a href="?page='.$page.'&amp;pid='.$r_dbr['id'].'"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
					}
					$content.='</div></div>';
				}while($r_dbr=mysql_fetch_assoc($q_dbr));
				if($r_dbl['tid']>1){
					$content.='<a href="?m='.($r_dbl['tid']>2?'camp':'album').'&amp;id='.$r_dbl['sid'].'">更多留言</a>';
				}else{
					mysql_free_result($q_dbr);
					if($p_dbr>1){
						for($i=1;$i<=$p_dbr;$i++)$content.=($i>1?'<span name="reply_link" data-pid="'.$r_dbl['id'].'|'.$i.'" class="mlink f_link">'.$i.'</span>':$i).' ';
						$content.='</span><span id="p_'.$r_dbl['id'].'" style="display: none;"><img src="images/v.gif" alt="" title="载入中……" class="loading_va"/></span>';
					}
				}
				$content.='</div>';
			}
			if($r_dbl['tid']>1){
				mysql_free_result($q_dbr);
			}else{
				mysql_free_result($q_a_dbr);
			}
			$content.='</div></div></li>';
		}while($r_dbl=mysql_fetch_assoc($q_dbl));
		mysql_free_result($q_dbl);
		$js_c.='
	$("span[name=\'reply_link\']").click(function(){
		var f=$(this).data(\'pid\').split(\'|\');
		ld(f[0], f[1], \'j_topic.php?i=\'+f[0]+\'&p=\'+f[1]+\'&e='.$page.'\');
	});';
		$content.='</ul>';
		if($p_dbt>1)$content.=getpage($page, $p_dbt);
	}else{
		$content.='<li><div class="title">班级留言</div><div class="lcontent">没有留言</div></li></ul>';
	}
	mysql_free_result($q_a_dbt);
	$t_topicf=2;
	if($c_log){
		$js_c.='
	$("span[name=\'post_link\']").click(function(){
		dhdivf(\'topicform\', $(this).data(\'id\'), '.$t_topicf.');
	});';
		$content.='<div class="title">发表留言&nbsp;&nbsp;<span class="gdate"><span name="post_link" data-id="0" class="mlink f_link">留言</span> | <span name="post_link" data-id="1" class="mlink f_link">投票</span></span></div><div class="lcontent">'.getcform().'<form method="post" action="" class="btform" id="topicform1" style="display: none;"><table><tr><td>投票标题：</td><td><input name="vtitle" size="32" class="bt_input" rel="投票标题" /></td></tr><tr><td>有效天数：</td><td><input name="vday" size="32" value="0" />天（0或空为不限制）</td></tr><tr><td>投票类型：</td><td><input name="vtype" value="0" type="radio" checked="checked" />单选 <input name="vtype" value="1" type="radio" />多选（发布后不可编辑）</td></tr><tr><td colspan="2">投票选项：每一行为一个选项，发布后不可编辑<br/><textarea name="voption" rows="8" class="bt_input" rel="投票选项"></textarea></td></tr><tr><td colspan="2"><input type="submit" value="发布" class="button" /> <input value="取消" class="button" type="reset" /><input type="hidden" name="mid" value="1"/></td></tr></table></form></div>';
	}
	$content.='</div></div><div class="lmenu"><ul><li>欢迎您';
	if($c_log){
		$s_dbg=sprintf('select id from %s where tid=%s and readed=1', $dbprefix.'message', $_SESSION[$config['u_hash']]);
		$q_dbg=mysql_query($s_dbg) or die('');
		$c_dbg=mysql_num_rows($q_dbg);
		mysql_free_result($q_dbg);
		$content.='，'.$pn.' <a href="?m=logout">退出</a><ol><li><a href="?m=message">短消息</a>'.($c_dbg>0?'（<span class="message_n">'.$c_dbg.'</span>）':'').'</li><li><a href="?m=profile">个人资料</a></li>'.($pa==9?'<li><a href="?m=setting">班级设置</a></li>':'').'</ol>';
	}else{
		$content.='<ol><li><a href="?m=login">登录留言</a></li></ol>';
	}
	$content.='</li>';
	require_once('lib/lunar.php');
	$lunar=new Lunar();
	for($i=0;$i<5;$i++){
		$ct=getftime(time()+86400*$i);
		$bdb[]='(bir_m='.date('n', $ct).' and bir_d='.date('j', $ct).' and isnl=0)';
		$nl=$lunar->S2L($ct);
		$bdb[]='(bir_m='.$nl[0].' and bir_d='.$nl[1].' and isnl=1)';
	}
	$nl_t=$lunar->S2L(getftime());
	$s_dbu=sprintf('select id, name, isnl, bir_m, bir_d from %s where %s', $dbprefix.'member', join(' or ', $bdb));
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		$content.='<li>生日榜<ol>';
		do{
			$sr_c=$r_dbu['isnl']>0?$lunar->LMonName($r_dbu['bir_m']).'月'.$lunar->LDayName($r_dbu['bir_d']).'日':''.$r_dbu['bir_m'].'月'.$r_dbu['bir_d'].'日';
			$content.='<li>'.((($r_dbu['isnl']==1 && $r_dbu['bir_m']==$nl_t[0] && $r_dbu['bir_d']==$nl_t[1]) || ($r_dbu['isnl']==0 && $r_dbu['bir_m']==date('n', getftime()) && $r_dbu['bir_d']==date('j', getftime())))?'<img src="images/cake.gif" alt="" title="生日快乐！" /> ':'').'<a href="?m=user&amp;id='.$r_dbu['id'].'">'.$r_dbu['name'].'</a> (<span title="生日：'.($r_dbu['isnl']>0?'农历':'').$sr_c.'">'.$sr_c.'</span>)</li>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
		$content.='</ol></li>';
	}
	mysql_free_result($q_dbu);
	$s_dbc=sprintf('select id, title from %s where closed=0 and disp=0 order by datetime desc limit 3', $dbprefix.'camp');
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		$content.='<li>最新活动<ol>';
		do{
			$content.='<li><a href="?m=camp&amp;id='.$r_dbc['id'].'" title="'.$r_dbc['title'].'">'.substrs($r_dbc['title']).'</a></li>';
		}while($r_dbc=mysql_fetch_assoc($q_dbc));
		$content.='<li><a href="?m=camp">更多……</a></li></ol></li>';
	}
	mysql_free_result($q_dbc);
	$s_dbp=sprintf('select id, title, url, vid, upload from %s where disp=0 order by datetime desc limit 3', $dbprefix.'photo');
	$q_dbp=mysql_query($s_dbp) or die('');
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		$content.='<li>最新照片、视频<ol>';
		do{
			$content.='<li><a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="al_t" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').$r_dbp['title'].'"/></a></li>';
		}while($r_dbp=mysql_fetch_assoc($q_dbp));
		$content.='<li><a href="?m=album">更多……</a></li></ol></li>';
	}
	mysql_free_result($q_dbp);
	$s_dbo=sprintf('select a.aid, b.name from %s as a, %s as b where a.online=1 and a.aid=b.id order by a.datetime desc limit 10', $dbprefix.'online', $dbprefix.'member');
	$q_dbo=mysql_query($s_dbo) or die('');
	$r_dbo=mysql_fetch_assoc($q_dbo);
	if(mysql_num_rows($q_dbo)>0){
		$content.='<li>当前在线<ol>';
		do{
			$content.='<li>'.getalink($r_dbo['aid'], $r_dbo['name'], 2).'</li>';
		}while($r_dbo=mysql_fetch_assoc($q_dbo));
		$content.='</ol></li>';
	}
	mysql_free_result($q_dbo);
	$s_dbu=sprintf('select id, name from %s where visitdate>0 order by visitdate desc limit 5', $dbprefix.'member');
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		$content.='<li>最近访问<ol>';
		do{
			$content.='<li>'.getalink($r_dbu['id'], $r_dbu['name']).'</li>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
		$content.='<li><a href="?m=user&amp;v=1">更多……</a></li></ol></li>';
	}
	mysql_free_result($q_dbu);
	$s_dbl=sprintf('select title, url from %s order by thread', $dbprefix.'link');
	$q_dbl=mysql_query($s_dbl) or die('');
	$r_dbl=mysql_fetch_assoc($q_dbl);
	if(mysql_num_rows($q_dbl)>0){
		$content.='<li>网站链接<ol>';
		do{
			$content.='<li><a href="'.$r_dbl['url'].'" title="'.$r_dbl['title'].'" rel="external">'.substrs($r_dbl['title']).'</a></li>';
		}while($r_dbl=mysql_fetch_assoc($q_dbl));
		$content.='</ol></li>';
	}
	mysql_free_result($q_dbl);
	if(count($skin_a)>0){
		$js_c.='
	$(".skin_sdiv img").click(function(){
		var f=$(this).attr(\'rel\').split(\'|\');
		setStyle(f[1], f[0]);
	});';
		$content.='<li>页面样式</li><div class="skin_sdiv">';
		foreach($skin_a as $k=>$v){
			if($k>0){
				$simg='skin/'.$v[1]['path'].'/skin_s.gif';
				$content.='<img src="'.(file_exists($simg)?$simg:'images/skin_s0.gif').'" alt="" title="'.($v[1]['title']!=''?$v[1]['title']:'样式#'.$k).'"';
			}else{
				$content.='<img src="images/skin_s.gif" alt="" title="青青校园"';
			}
			$content.=' width="20" height="20" rel="'.$k.'|'.$v[0].'" class="f_link"/>';
		}
		$content.='</div>';
	}
	$content.='</ul></div>';
}
