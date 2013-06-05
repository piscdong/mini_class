<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$ddb=($c_log && $pa==9)?'':' and a.disp=0';
$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
$content.='<div class="tcontent">';
if(isset($_GET['id']) && intval($_GET['id'])>0){
	$s_dbc=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.id=%s and a.aid=b.id%s limit 1', $dbprefix.'camp', $dbprefix.'member', intval($_GET['id']), $ddb);
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
			if(isset($_POST['title']) && ($pa>$r_dbc['power'] || $_SESSION[$config['u_hash']]==$r_dbc['aid'])){
				$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
				$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
				$cdate=htmlspecialchars(trim($_POST['cdate']),ENT_QUOTES);
				$cloc=htmlspecialchars(trim($_POST['cloc']),ENT_QUOTES);
				$cpay=htmlspecialchars(trim($_POST['cpay']),ENT_QUOTES);
				$u_db=sprintf('update %s set title=%s, content=%s, cdate=%s, cloc=%s, cpay=%s where id=%s', $dbprefix.'camp',
					SQLString($title, 'text'),
					SQLString($cont, 'text'),
					SQLString($cdate, 'text'),
					SQLString($cloc, 'text'),
					SQLString($cpay, 'text'),
					$r_dbc['id']);
				$result=mysql_query($u_db) or die('');
				if($r_dbc['disp']==0 && $r_dbc['closed']==0)setsinfo($pn.' 修改活动信息', $r_dbc['aid'], $r_dbc['id'], 3);
			}elseif(isset($_POST['rinfo'])){
				$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
				if(isset($_POST['id']) && intval($_POST['id'])>0 && $cont!=''){
					$s_dbr=sprintf('select a.id, a.aid, b.power from %s as a, %s as b where a.id=%s and a.cid=%s and a.aid=b.id limit 1', $dbprefix.'ccomment', $dbprefix.'member', SQLString($_POST['id'], 'int'), $r_dbc['id']);
					$q_dbr=mysql_query($s_dbr) or die('');
					$r_dbr=mysql_fetch_assoc($q_dbr);
					if(mysql_num_rows($q_dbr)>0 && ($pa>$r_dbr['power'] || $_SESSION[$config['u_hash']]==$r_dbr['aid'])){
						$u_db=sprintf('update %s set content=%s where id=%s', $dbprefix.'ccomment', SQLString($cont, 'text'), $r_dbr['id']);
						$result=mysql_query($u_db) or die('');
					}
					mysql_free_result($q_dbr);
				}elseif($cont!='' && $r_dbc['disp']==0){
					if(isset($_POST['u_sina']) && $_POST['u_sina']==1)$a_syncp[]='sina';
					if(isset($_POST['u_tqq']) && $_POST['u_tqq']==1)$a_syncp[]='tqq';
					if(isset($_POST['u_renren']) && $_POST['u_renren']==1)$a_syncp[]='renren';
					if(isset($_POST['u_kx001']) && $_POST['u_kx001']==1)$a_syncp[]='kx001';
					if(isset($_POST['u_tsohu']) && $_POST['u_tsohu']==1)$a_syncp[]='tsohu';
					if(isset($_POST['u_t163']) && $_POST['u_t163']==1)$a_syncp[]='t163';
					if(isset($_POST['u_twitter']) && $_POST['u_twitter']==1)$a_syncp[]='twitter';
					if(isset($_POST['u_facebook']) && $_POST['u_facebook']==1)$a_syncp[]='facebook';
					$sync_p=isset($a_syncp)?join('|', $a_syncp):'';
					$i_db=sprintf('insert into %s (content, aid, cid, datetime, sync_p) values (%s, %s, %s, %s, %s)', $dbprefix.'ccomment', 
						SQLString($cont, 'text'),
						$_SESSION[$config['u_hash']],
						$r_dbc['id'],
						time(), 
						SQLString($sync_p, 'text'));
					$result=mysql_query($i_db) or die('');
					setsinfo($pn.' 发表留言', $r_dbc['aid'], $r_dbc['id'], 3);
				}
			}elseif(isset($_POST['did']) && $pa>0 && $pa<9 && trim($_POST['rtext'])!=''){
				$rtext=htmlspecialchars(trim($_POST['rtext']),ENT_QUOTES);
				if($_POST['did']==0){
					delcamp($r_dbc, $rtext);
					header('Location:./?m=camp');
					exit();
				}else{
					$s_dbr=sprintf('select a.id, a.content, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id and a.id=%s limit 1', $dbprefix.'ccomment', $dbprefix.'member', $r_dbc['id'], SQLString($_POST['did'], 'int'));
					$q_dbr=mysql_query($s_dbr) or die('');
					$r_dbr=mysql_fetch_assoc($q_dbr);
					if(mysql_num_rows($q_dbr)>0){
						$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'ccomment', $r_dbr['id']);
						$result=mysql_query($u_db) or die('');
						$ac=$pn." 删除留言\r删除理由：".$rtext."\r\r".$r_dbr['name'].'：'.$r_dbr['content'];
						$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid) values (%s, %s, %s, %s, 2)', $dbprefix.'adminop', 
							SQLString($ac, 'text'),
							$_SESSION[$config['u_hash']],
							time(),
							$r_dbc['id']);
						$result=mysql_query($i_db) or die('');
					}
					mysql_free_result($q_dbr);
				}
			}elseif(isset($_POST['jid']) && $_POST['jid']==1 && $r_dbc['closed']==0){
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
			}
			header('Location:./?m=camp&id='.$r_dbc['id']);
			exit();
		}else{
			$title.=$r_dbc['title'];
			if(isset($_GET['sid'])){
				if($c_log && $pa>0 && $r_dbc['closed']==0){
					$sticky=(isset($_GET['sid']) && $_GET['sid']==1)?1:0;
					$u_db=sprintf('update %s set sticky=%s where id=%s', $dbprefix.'camp', SQLString($sticky, 'int'), $r_dbc['id']);
					$result=mysql_query($u_db) or die('');
				}
				header('Location:./?m=camp&id='.$r_dbc['id']);
				exit();
			}
			if(isset($_GET['cid'])){
				if($c_log && $pa>0){
					$closed=(isset($_GET['cid']) && $_GET['cid']==1)?1:0;
					$sticky=$closed>0?0:$r_dbc['sticky'];
					$u_db=sprintf('update %s set closed=%s, sticky=%s where id=%s', $dbprefix.'camp', SQLString($closed, 'int'), SQLString($sticky, 'int'), $r_dbc['id']);
					$result=mysql_query($u_db) or die('');
				}
				header('Location:./?m=camp&id='.$r_dbc['id']);
				exit();
			}
			if(isset($_GET['did']) && $c_log && $pa==9){
				if($_GET['did']==0){
					delcamp($r_dbc);
					header('Location:./?m=camp');
					exit();
				}else{
					$d_db=sprintf('delete from %s where id=%s', $dbprefix.'ccomment', SQLString($_GET['did'], 'int'));
					$result=mysql_query($d_db) or die('');
					header('Location:./?m=camp&id='.$r_dbc['id']);
					exit();
				}
			}
			if(isset($_GET['pid']) && $c_log && $pa==9){
				if($_GET['pid']==0){
					$u_db=sprintf('update %s set disp=0 where id=%s', $dbprefix.'camp', $r_dbc['id']);
					$result=mysql_query($u_db) or die('');
					$u_db=sprintf('update %s set disp=0 where sid=%s and tid=3', $dbprefix.'topic', $r_dbc['id']);
					$result=mysql_query($u_db) or die('');
				}else{
					$u_db=sprintf('update %s set disp=0 where id=%s and cid=%s', $dbprefix.'ccomment', SQLString($_GET['pid'], 'int'), $r_dbc['id']);
					$result=mysql_query($u_db) or die('');
				}
				header('Location:./?m=camp&id='.$r_dbc['id']);
				exit();
			}
			$content.='<ul class="clist"><li>';
			if($c_log){
				$c_sync=getsync_c($ar);
				if($pa>$r_dbc['power'] || $_SESSION[$config['u_hash']]==$r_dbc['aid'])$cm[]='&nbsp; &nbsp; <img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_0|h_0" class="f_link"/>';
				if($pa>0 && $r_dbc['closed']==0)$cm[]='&nbsp;<a href="?m=camp&amp;id='.$r_dbc['id'].'&amp;sid='.($r_dbc['sticky']>0?'0"><img src="images/o_1.gif" alt="" title="取消':'1"><img src="images/o_0.gif" alt="" title="').'设置为热门活动"/></a>';
				if($pa>$r_dbc['power'] || ($pa>0 && $_SESSION[$config['u_hash']]==$r_dbc['aid']))$cm[]='&nbsp;<a href="?m=camp&amp;id='.$r_dbc['id'].'&amp;cid='.($r_dbc['closed']>0?'0"><img src="images/o_4.gif" alt="" title="开启':'1"><img src="images/o_8.gif" alt="" title="关闭').'"/></a>';
				if($pa>0){
					$js_c.='
	$("img[name=\'del_list_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=camp&id='.$r_dbc['id'].'&did=\'+$(this).data(\'id\');
	});';
					$cm[]='&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="'.($pa==9?'del_list_img':'hs_cbt').'" data-id="'.($pa==9?'0':'l_0|del_0').'" class="f_link"/>';
				}
				if($pa==9 && $r_dbc['disp']>0)$cm[]='&nbsp; &nbsp; <span class="del_n">已删除</span> <a href="?m=camp&amp;id='.$r_dbc['id'].'&amp;pid=0"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
			}
			if($r_dbc['content']!='')$ca[]=gbookencode($r_dbc['content']).'<br/>';
			if($r_dbc['cdate']!='')$ca[]='<strong>活动时间：</strong>'.$r_dbc['cdate'];
			if($r_dbc['cloc']!='')$ca[]='<strong>活动地点：</strong>'.$r_dbc['cloc'].' <span name="s_cbt" data-id="map_v_div" class="mlink f_link">查看地图</span><div id="map_v_div" style="padding: 5px; display: none;"><img src="http://api.map.baidu.com/staticimage?center='.urlencode($r_dbc['cloc']).'&amp;zoom=16&amp;markers='.urlencode($r_dbc['cloc']).'" style="border: 1px solid #999;" alt=""/><br/><a href="http://map.baidu.com/?s=s%26wd%3D'.urlencode($r_dbc['cloc']).'" rel="external">查看大图</a></div>';
			if($r_dbc['cpay']!='')$ca[]='<strong>活动费用：</strong>'.$r_dbc['cpay'];
			$s_dbu=sprintf('select a.aid, a.tid, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id order by a.datetime desc', $dbprefix.'cuser', $dbprefix.'member', $r_dbc['id']);
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				$t_user=0;
				do{
					$cuser_c[]='<div class="al_list"><a href="?m=user&amp;id='.$r_dbu['aid'].'"><img src="avator.php?id='.$r_dbu['aid'].'" alt="" title="'.$r_dbu['name'].($r_dbu['tid']>1?'，带 '.($r_dbu['tid']-1).' 人':'').'" class="photo" width="55" height="55"/></a></div>';
					$t_user+=$r_dbu['tid'];
					if($c_log && $_SESSION[$config['u_hash']]==$r_dbu['aid'])$cjoin=$r_dbu['tid'];
				}while($r_dbu=mysql_fetch_assoc($q_dbu));
				$ca[]='<strong>已报名人数：</strong> '.$t_user.' 人<div class="extr"></div>'.join('',$cuser_c).'<div class="extr"></div>';
			}
			mysql_free_result($q_dbu);
			$content.='<div class="title">'.(isset($cm)?'<span class="gmod">'.join('&nbsp; &nbsp;',$cm):'').'</span>'.$r_dbc['title'].'&nbsp;&nbsp;<span class="gdate">发起人：<a href="?m=user&amp;id='.$r_dbc['aid'].'">'.$r_dbc['name'].'</a></span></div><div class="lcontent"><div id="l_0">'.(isset($ca)?join('<br/>', $ca):'没有公告！').(($c_log && $r_dbc['closed']==0)?'<br/><br/><form method="post" action="" class="btform_nv"><table><tr><td>参加人数：</td><td><input name="tid" size="32" value="'.((isset($cjoin) && $cjoin>0)?$cjoin:'1').'" /></td></tr><tr><td colspan="2"><input type="submit" value="'.((isset($cjoin) && $cjoin>0)?'修改参加人数" class="button" /> <input type="submit" value="我要退出这个活动" name="delu" class="button" />':'我要参加这个活动" id="formjoin" class="button" />').'<input type="hidden" name="jid" value="1"/></td></tr></table></form>':'').'</div>';
			if($c_log && $pa>0 && $pa<9)$content.='<div id="del_0" style="display: none;"><form method="post" action="" class="btform" id="delform0"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" name="hs_cbt" data-id="del_0|l_0"/><input type="hidden" name="did" value="0" /></td></tr></table></form></div>';
			if($c_log && ($pa>$r_dbc['power'] || $_SESSION[$config['u_hash']]==$r_dbc['aid'])){
				$content.='<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script><div id="h_0" style="display: none;"><form method="post" action="" class="btform" id="camform"><table><tr><td>活动名称：</td><td><input name="title" size="32" value="'.$r_dbc['title'].'" class="bt_input" rel="活动名称" /></td></tr><tr><td>活动时间：</td><td><input name="cdate" size="32" value="'.$r_dbc['cdate'].'" /></td></tr><tr><td>活动地点：</td><td><input name="cloc" title="准确填写详细住址后可以在地图上显示" id="formloc" size="32" value="'.$r_dbc['cloc'].'" /><span name="s_cbt" data-id="map_tr" class="mlink f_link">从地图上选取</span></td></tr><tr id="map_tr" style="display: none;"><td></td><td><input type="hidden" id="cmid" value="0"/>操作方法：左键按住移动，滚轮放大缩小，左键单击选取地点 <span name="h_cbt" data-id="map_tr" class="mlink f_link">关闭地图</span><div style="width: 400px;height: 300px;border:1px solid #999;" id="map_container"></div></td></tr><tr><td>活动费用：</td><td><input name="cpay" size="32" value="'.$r_dbc['cpay'].'" /></td></tr></table><div class="formline"><textarea name="rinfo" cols="50" rows="5">'.$r_dbc['content'].'</textarea></div><div class="formline"><input type="submit" value="修改" class="button" /> <input type="button" value="取消" class="button" name="hs_cbt" data-id="h_0|l_0" /></div></form></div>';
				$js_c.='
	var map=new BMap.Map(\'map_container\');
	var opts={type: BMAP_NAVIGATION_CONTROL_SMALL}
	map.addControl(new BMap.NavigationControl(opts));
	map.enableScrollWheelZoom();
	var contextMenu=new BMap.ContextMenu();
	var txtMenuItem=[
		{text:\'放大\', callback:function(){map.zoomIn()}},
		{text:\'缩小\', callback:function(){map.zoomOut()}},
		{text:\'放置到最大级\', callback:function(){map.setZoom(18)}},
		{text:\'查看全国\', callback:function(){map.setZoom(4)}}
	];
	for(var i=0;i<txtMenuItem.length;i++){
		contextMenu.addItem(new BMap.MenuItem(txtMenuItem[i].text,txtMenuItem[i].callback,100));
		if(i==1)contextMenu.addSeparator();
	}
	map.addContextMenu(contextMenu);';
				if($r_dbc['cloc']!='')$js_c.='
	var myGeo=new BMap.Geocoder();
	myGeo.getPoint(\''.$r_dbc['cloc'].'\', function(point){
		if(point){
			map.centerAndZoom(point, 16);
			$(\'#cmid\').val(\'1\');
		}
	}, \'\');';
				$js_c.='
	var myCity=new BMap.LocalCity();
	myCity.get(function(result){
		var cityName=result.name;
		if($(\'#cmid\').val()==\'0\'){
			map.centerAndZoom(cityName, 16);
			$(\'#cmid\').val(\'1\');
		}
	});

	var gc=new BMap.Geocoder();    
	map.addEventListener(\'click\', function(e){        
		var pt=e.point;
		gc.getLocation(pt, function(rs){
			var addComp=rs.addressComponents;
			var addr=addComp.city;
			if(addComp.province!=addComp.city)addr+=addComp.district;
			addr+=addComp.street+addComp.streetNumber;
			$(\'#formloc\').val(addr);
		});        
	});';
			}
			$content.='</div></li>';
			$ddb=($c_log && $pa==9)?' and (a.sid=0 or a.disp=0)':' and a.disp=0';
			$s_a_dbr=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id%s order by a.datetime desc', $dbprefix.'ccomment', $dbprefix.'member', $r_dbc['id'], $ddb);
			$q_a_dbr=mysql_query($s_a_dbr) or die('');
			$c_dbr=mysql_num_rows($q_a_dbr);
			if($c_dbr>0){
				$p_dbr=ceil($c_dbr/$config['pagesize']);
				if($page>$p_dbr)$page=$p_dbr;
				$s_dbr=sprintf('%s limit %d, %d', $s_a_dbr, ($page-1)*$config['pagesize'], $config['pagesize']);
				$q_dbr=mysql_query($s_dbr) or die('');
				$r_dbr=mysql_fetch_assoc($q_dbr);
				do{
					$content.=getpcinfo($r_dbr);
				}while($r_dbr=mysql_fetch_assoc($q_dbr));
				mysql_free_result($q_dbr);
			}
			mysql_free_result($q_a_dbr);
			$content.='</ul>';
			if(isset($p_dbr) && $p_dbr>1)$content.=getpage($page, $p_dbr);
			if($c_log && $r_dbc['disp']==0)$content.='<div class="title" id="postreply">发表留言&nbsp;&nbsp;<span class="gdate"><a href="?m=album&amp;ucid='.$r_dbc['id'].'#uploadimg">添加照片/视频</a></span></div><div class="lcontent">'.getcform().'</div>';
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
		$content.='<ul class="clist">';
		$s_a_dbc=sprintf('select a.*, b.name from %s as a, %s as b where a.aid=b.id%s order by a.closed, a.sticky desc, a.datetime desc', $dbprefix.'camp', $dbprefix.'member', $ddb);
		$q_a_dbc=mysql_query($s_a_dbc) or die('');
		$c_dbc=mysql_num_rows($q_a_dbc);
		if($c_dbc>0){
			$p_dbc=ceil($c_dbc/$config['pagesize']);
			if($page>$p_dbc)$page=$p_dbc;
			$s_dbc=sprintf('%s limit %d, %d', $s_a_dbc, ($page-1)*$config['pagesize'], $config['pagesize']);
			$q_dbc=mysql_query($s_dbc) or die('');
			$r_dbc=mysql_fetch_assoc($q_dbc);
			do{
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
				$content.='<li><div class="title"><a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a>&nbsp;&nbsp;<span class="gdate">'.($r_dbc['closed']>0?'已结束':'发起人：'.getalink($r_dbc['aid'], $r_dbc['name']).', '.getldate($r_dbc['datetime'])).($r_dbc['disp']>0?' <span class="del_n">已删除</span>':'').'</span></div>'.(isset($ca[$r_dbc['id']])?'<div class="lcontent">'.join('<br/>', $ca[$r_dbc['id']]).'</div>':'').'</li>';
			}while($r_dbc=mysql_fetch_assoc($q_dbc));
			mysql_free_result($q_dbc);
			$content.='</ul>';
			if($p_dbc>1)$content.=getpage($page, $p_dbc);
		}else{
			$content.='<li><div class="title">班级活动</div><div class="lcontent">没有活动</div></li></ul>';
		}
		mysql_free_result($q_a_dbc);
		if($c_log){
			$content.='<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script><div class="title">发起活动</div><div class="lcontent"><form method="post" action="" class="btform" id="camform"><table><tr><td>活动名称：</td><td><input name="title" size="32" class="bt_input" rel="活动名称" /></td></tr><tr><td>活动时间：</td><td><input name="cdate" size="32" /></td></tr><tr><td>活动地点：</td><td><input name="cloc" title="准确填写详细住址后可以在地图上显示" id="formloc" size="32" /><span name="s_cbt" data-id="map_tr" class="mlink f_link">从地图上选取</span></td></tr><tr id="map_tr" style="display: none;"><td></td><td>操作方法：左键按住移动，滚轮放大缩小，左键单击选取地点 <span name="h_cbt" data-id="map_tr" class="mlink f_link">关闭地图</span><div style="width: 400px;height: 300px;border:1px solid #999;" id="map_container"></div></td></tr><tr><td>活动费用：</td><td><input name="cpay" size="32" /></td></tr></table><div class="formline"><textarea name="rinfo" cols="50" rows="5"></textarea></div><div class="formline"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /></div></form></div>';
			$js_c.='
	var map=new BMap.Map(\'map_container\');
	var opts={type: BMAP_NAVIGATION_CONTROL_SMALL}
	map.addControl(new BMap.NavigationControl(opts));
	map.enableScrollWheelZoom();
	var contextMenu=new BMap.ContextMenu();
	var txtMenuItem=[
		{text:\'放大\', callback:function(){map.zoomIn()}},
		{text:\'缩小\', callback:function(){map.zoomOut()}},
		{text:\'放置到最大级\', callback:function(){map.setZoom(18)}},
		{text:\'查看全国\', callback:function(){map.setZoom(4)}}
	];
	for(var i=0;i<txtMenuItem.length;i++){
		contextMenu.addItem(new BMap.MenuItem(txtMenuItem[i].text,txtMenuItem[i].callback,100));
		if(i==1)contextMenu.addSeparator();
	}
	map.addContextMenu(contextMenu);

	var myCity=new BMap.LocalCity();
	myCity.get(function(result){
		var cityName=result.name;
		map.centerAndZoom(cityName, 16);
	});

	var gc=new BMap.Geocoder();    
	map.addEventListener(\'click\', function(e){        
		var pt=e.point;
		gc.getLocation(pt, function(rs){
			var addComp=rs.addressComponents;
			var addr=addComp.city;
			if(addComp.province!=addComp.city)addr+=addComp.district;
			addr+=addComp.street+addComp.streetNumber;
			$(\'#formloc\').val(addr);
		});        
	});';
		}
	}
}
$content.='</div>';
