<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$ddb=($c_log && $pa==9)?'':' and a.disp=0';
$ucpid=(isset($_GET['ucid']) && intval($_GET['ucid'])>0)?intval($_GET['ucid']):0;
$campop=getcampo($ucpid);
$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
$pagesize=50;
$content.='<div class="tcontent">';
if(isset($_GET['id']) && intval($_GET['id'])>0){
	$s_dbp=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.id=%s and a.aid=b.id%s limit 1', $dbprefix.'photo', $dbprefix.'member', intval($_GET['id']), $ddb);
	$q_dbp=mysql_query($s_dbp) or die('');
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		if($_SERVER['REQUEST_METHOD']=='POST' && $c_log){
			if(isset($_POST['edit']) && $_POST['edit']==1 && ($pa>$r_dbp['power'] || $_SESSION[$config['u_hash']]==$r_dbp['aid'])){
				$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
				if($r_dbp['vid']>0){
					$url=strip_tags(trim($_POST['url']), '<object><param><embed>');
				}elseif($r_dbp['upload']==0){
					$url=getfurl(htmlspecialchars(trim($_POST['url']),ENT_QUOTES));
				}else{
					$url=$r_dbp['url'];
				}
				if($url!=''){
					$tbimg=(isset($_POST['tbimg']) && trim($_POST['tbimg'])!='')?getfurl(htmlspecialchars(trim($_POST['tbimg']),ENT_QUOTES)):'';
					if($tbimg!='' && $r_dbp['upload']==0)$url.='[/]'.$tbimg;
					$cid=isset($_POST['cid'])?$_POST['cid']:0;
					if($r_dbp['cid']>0 && $cid>0 && $cid!=$r_dbp['cid']){
						$u_db=sprintf('update %s set cid=%s where sid=%s', $dbprefix.'ccomment', SQLString($cid, 'int'), $r_dbp['id']);
						$result=mysql_query($u_db) or die('');
					}elseif($cid==0 && $r_dbp['cid']!=$cid){
						$d_db=sprintf('delete from %s where sid=%s', $dbprefix.'ccomment', $r_dbp['id']);
						$result=mysql_query($d_db) or die('');
					}elseif($r_dbp['cid']==0 && $cid>0){
						$ptitle=$title!=''?$title:'#'.$r_dbp['id'];
						$i_db=sprintf('insert into %s (content, aid, cid, sid, datetime, disp) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'ccomment', 
							SQLString($ptitle, 'text'),
							$r_dbp['aid'],
							SQLString($cid, 'int'),
							$r_dbp['id'],
							$r_dbp['datetime'],
							$r_dbp['disp']);
						$result=mysql_query($i_db) or die('');
					}
					$u_db=sprintf('update %s set url=%s, title=%s, cid=%s where id=%s', $dbprefix.'photo',
						SQLString($url, 'text'),
						SQLString($title, 'text'),
						SQLString($cid, 'int'),
						$r_dbp['id']);
					$result=mysql_query($u_db) or die('');
				}
			}elseif(isset($_POST['rinfo'])){
				$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
				if(isset($_POST['id']) && intval($_POST['id'])>0 && $cont!=''){
					$s_dbr=sprintf('select a.id, a.aid, b.power from %s as a, %s as b where a.id=%s and a.pid=%s and a.aid=b.id limit 1', $dbprefix.'pcomment', $dbprefix.'member', SQLString($_POST['id'], 'int'), $r_dbp['id']);
					$q_dbr=mysql_query($s_dbr) or die('');
					$r_dbr=mysql_fetch_assoc($q_dbr);
					if(mysql_num_rows($q_dbr)>0 && ($pa>$r_dbr['power'] || $_SESSION[$config['u_hash']]==$r_dbr['aid'])){
						$u_db=sprintf('update %s set content=%s where id=%s', $dbprefix.'pcomment', SQLString($cont, 'text'), $r_dbr['id']);
						$result=mysql_query($u_db) or die('');
					}
					mysql_free_result($q_dbr);
				}elseif($cont!='' && $r_dbp['disp']==0){
					if(isset($_POST['u_sina']) && $_POST['u_sina']==1)$a_syncp[]='sina';
					if(isset($_POST['u_tqq']) && $_POST['u_tqq']==1)$a_syncp[]='tqq';
					if(isset($_POST['u_renren']) && $_POST['u_renren']==1)$a_syncp[]='renren';
					if(isset($_POST['u_kx001']) && $_POST['u_kx001']==1)$a_syncp[]='kx001';
					if(isset($_POST['u_tsohu']) && $_POST['u_tsohu']==1)$a_syncp[]='tsohu';
					if(isset($_POST['u_t163']) && $_POST['u_t163']==1)$a_syncp[]='t163';
					if(isset($_POST['u_twitter']) && $_POST['u_twitter']==1)$a_syncp[]='twitter';
					if(isset($_POST['u_facebook']) && $_POST['u_facebook']==1)$a_syncp[]='facebook';
					$sync_p=isset($a_syncp)?join('|', $a_syncp):'';
					$i_db=sprintf('insert into %s (content, aid, pid, datetime, sync_p) values (%s, %s, %s, %s, %s)', $dbprefix.'pcomment', 
						SQLString($cont, 'text'),
						$_SESSION[$config['u_hash']],
						$r_dbp['id'],
						time(), 
						SQLString($sync_p, 'text'));
					$result=mysql_query($i_db) or die('');
					setsinfo($pn.' 发表评论', $r_dbp['aid'], $r_dbp['id'], 2);
				}
			}elseif(isset($_POST['did']) && $pa>0 && $pa<9 && trim($_POST['rtext'])!=''){
				$rtext=htmlspecialchars(trim($_POST['rtext']),ENT_QUOTES);
				if($_POST['did']==0){
					delphoto($r_dbp, $rtext);
					header('Location:./?m=album');
					exit();
				}else{
					$s_dbr=sprintf('select a.id, a.content, b.name from %s as a, %s as b where a.pid=%s and a.aid=b.id and a.id=%s limit 1', $dbprefix.'pcomment', $dbprefix.'member', $r_dbp['id'], SQLString($_POST['did'], 'int'));
					$q_dbr=mysql_query($s_dbr) or die('');
					$r_dbr=mysql_fetch_assoc($q_dbr);
					if(mysql_num_rows($q_dbr)>0){
						$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'pcomment', $r_dbr['id']);
						$result=mysql_query($u_db) or die('');
						$ac=$pn." 删除留言\r删除理由：".$rtext."\r\r".$r_dbr['name'].'：'.$r_dbr['content'];
						$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid) values (%s, %s, %s, %s, 1)', $dbprefix.'adminop', 
							SQLString($ac, 'text'),
							$_SESSION[$config['u_hash']],
							time(),
							$r_dbp['id']);
						$result=mysql_query($i_db) or die('');
					}
					mysql_free_result($q_dbr);
				}
			}
			header('Location:./?m=album&id='.$r_dbp['id']);
			exit();
		}else{
			$t=$r_dbp['title']!=''?$r_dbp['title']:($r_dbp['vid']>0?'视频':'照片').' #'.$r_dbp['id'];
			$title.=$t;
			if(isset($_GET['did']) && $c_log && $pa==9){
				if($_GET['did']==0){
					delphoto($r_dbp);
					header('Location:./?m=album');
					exit();
				}else{
					$d_db=sprintf('delete from %s where id=%s', $dbprefix.'pcomment', SQLString($_GET['did'], 'int'));
					$result=mysql_query($d_db) or die('');
					header('Location:./?m=album&id='.$r_dbp['id']);
					exit();
				}
			}
			if(isset($_GET['pid']) && $c_log && $pa==9){
				if($_GET['pid']==0){
					$u_db=sprintf('update %s set disp=0 where id=%s', $dbprefix.'photo', $r_dbp['id']);
					$result=mysql_query($u_db) or die('');
					$u_db=sprintf('update %s set disp=0 where sid=%s and tid=2', $dbprefix.'topic', $r_dbp['id']);
					$result=mysql_query($u_db) or die('');
					$u_db=sprintf('update %s set disp=0 where sid=%s', $dbprefix.'ccomment', $r_dbp['id']);
					$result=mysql_query($u_db) or die('');
				}else{
					$u_db=sprintf('update %s set disp=0 where id=%s and pid=%s', $dbprefix.'pcomment', SQLString($_GET['pid'], 'int'), $r_dbp['id']);
					$result=mysql_query($u_db) or die('');
				}
				header('Location:./?m=album&id='.$r_dbp['id']);
				exit();
			}
			$content.='<ul class="clist"><li><div class="title">';
			if($c_log){
				$c_sync=getsync_c($ar);
				if($pa>$r_dbp['power'] || $_SESSION[$config['u_hash']]==$r_dbp['aid'])$cm[]='&nbsp;<img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_0|h_0" class="f_link"/>';
				if($pa>0){
					$js_c.='
	$("img[name=\'del_list_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=album&id='.$r_dbp['id'].'&did=\'+$(this).data(\'id\');
	});';
					$cm[]='&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="'.($pa==9?'del_list_img':'hs_cbt').'" data-id="'.($pa==9?'0':'l_0|del_0').'" class="f_link"/>';
				}
				if($pa==9 && $r_dbp['disp']>0)$cm[]='&nbsp;<span class="del_n">已删除</span> <a href="?m=album&amp;id='.$r_dbp['id'].'&amp;pid=0"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
			}
			if(isset($cm))$content.='<span class="gmod">'.join('&nbsp; &nbsp;',$cm).'</span>';
			$content.=$t.'&nbsp;&nbsp;<span class="gdate">'.getalink($r_dbp['aid'], $r_dbp['name']).', '.getldate($r_dbp['datetime']).'</span></div>';
			$eb=3;
		$js_c.='
	var i0='.$eb.';
	var i1='.$eb.';
	$("#pr_b0").click(function(){
		if($("div[rel=\'pr_a\']:last img").attr(\'src\').match(\'images/r_al.gif\')){
		}else{
			if($("div[rel=\'pr_a1\']").length>0){
				$("div[rel=\'pr_a1\']:first").show(500);
				$("div[rel=\'pr_a1\']:first").attr(\'rel\', \'pr_a\');
			}else{
				$("div[rel=\'pr_a\']:last").after(\'<div class="pr_ld_img" rel="pr_a" style="display: none;background-image: url(images/loading.gif);"></div>\');
				$("div[rel=\'pr_a\']:last").show(500);
				$("div[rel=\'pr_a\']:last").load(\'j_photo.php?i='.$r_dbp['id'].'&s=\'+i0, function(){
					$("div[rel=\'pr_a\']:last").css(\'background-image\', \'\');
				});
				i0++;
			}
			if($("div[rel=\'pr_a\']").length>'.(1+$eb*2).'){
				$("div[rel=\'pr_a\']:first").hide(500);
				$("div[rel=\'pr_a\']:first").attr(\'rel\', \'pr_a0\');
			}
		}
	});
	$("#pr_b1").click(function(){
		if($("div[rel=\'pr_a\']:first img").attr(\'src\').match(\'images/l_al.gif\')){
		}else{
			if($("div[rel=\'pr_a0\']").length>0){
				$("div[rel=\'pr_a0\']:last").show(500);
				$("div[rel=\'pr_a0\']:last").attr(\'rel\', \'pr_a\');
			}else{
				$("div[rel=\'pr_a\']:first").before(\'<div class="pr_ld_img" rel="pr_a" style="display: none;background-image: url(images/loading.gif);"></div>\');
				$("div[rel=\'pr_a\']:first").show(500);
				$("div[rel=\'pr_a\']:first").load(\'j_photo.php?i='.$r_dbp['id'].'&t=1&s=\'+i1, function(){
					$("div[rel=\'pr_a\']:first").css(\'background-image\', \'\');
				});
				i1++;
			}
			if($("div[rel=\'pr_a\']").length>'.(1+$eb*2).'){
				$("div[rel=\'pr_a\']:last").hide(500);
				$("div[rel=\'pr_a\']:last").attr(\'rel\', \'pr_a1\');
			}
		}
	});
';
			$content.='<div class="gcontent" id="l_0"><div id="al_ajaxdiv"><img style="float: left;" id="pr_b0" src="images/le_al.gif" alt=""/>';
			$s_dbn=sprintf('select id, title, url, vid, upload from %s where id>%s%s order by datetime limit %d', $dbprefix.'photo', $r_dbp['id'], $ddb, $eb);
			$q_dbn=mysql_query($s_dbn) or die('');
			$r_dbn=mysql_fetch_assoc($q_dbn);
			$c_po=mysql_num_rows($q_dbn);
			if($c_po<$eb)$content.='<div class="pr_ld_img" rel="pr_a"><img src="images/l_al.gif" alt="" title="这是最后一张" class="pr_img" width="70" height="70" /></div>';
			if($c_po>0){
				$i=0;
				do{
					$poc[$i]='<div class="pr_ld_img" rel="pr_a"><a href="?m=album&amp;id='.$r_dbn['id'].'"><img src="'.getthu($r_dbn).'" width="70" height="70" alt="" title="'.$r_dbn['title'].'" class="pr_img" /></a></div>';
					$i++;
				}while($r_dbn=mysql_fetch_assoc($q_dbn));
				krsort($poc);
				$content.=join('', $poc);
			}
			mysql_free_result($q_dbn);
			$content.='<div class="pr_ld_img" rel="pr_a"><img src="'.getthu($r_dbp).'" alt="" title="'.$r_dbp['title'].'" width="70" height="70" /></div>';
			$s_dbn=sprintf('select id, title, url, vid, upload from %s where id<%s%s order by datetime desc limit %d', $dbprefix.'photo', $r_dbp['id'], $ddb, $eb);
			$q_dbn=mysql_query($s_dbn) or die('');
			$r_dbn=mysql_fetch_assoc($q_dbn);
			$c_po=mysql_num_rows($q_dbn);
			if($c_po>0){
				do{
					$content.='<div class="pr_ld_img" rel="pr_a"><a href="?m=album&amp;id='.$r_dbn['id'].'"><img src="'.getthu($r_dbn).'" width="70" height="70" alt="" title="'.$r_dbn['title'].'" class="pr_img" /></a></div>';
				}while($r_dbn=mysql_fetch_assoc($q_dbn));
			}
			mysql_free_result($q_dbn);
			if($c_po<$eb)$content.='<div class="pr_ld_img" rel="pr_a"><img src="images/r_al.gif" alt="" title="这是第一张" class="pr_img" width="70" height="70" /></div>';
			$content.='<img src="images/ri_al.gif" alt="" id="pr_b1"/></div>';

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
			$content.='<br/><br/>';
			if($r_dbp['vid']>0){
				$content.=$u;
			}else{
				$maxiw=650;
				$js_c.='
	$("#lightbox").load(function(){
		var w=$(this).width();
		var h=$(this).height();
		if($(this).width()>'.$maxiw.'){
			$(this).css(\'width\', \''.$maxiw.'px\');
			$(this).attr(\'title\', \'点击查看原图\');
			$(this).click(function(){
				if($("#lightbox_bg").length==0){
					$("body").append(\'<img src=\'+$(this).attr(\'src\')+\' id="lightbox_img" alt="" title="点击关闭"/><div id="lightbox_bg" title="点击关闭"></div>\');
				}
				w+=10;
				h+=10;
				var l=$(document).scrollLeft()+($(window).width()-w)/2;
				if(l<0)l=0;
				var t=$(document).scrollTop()+($(window).height()-h)/2;
				if(t<0)t=0;
				var vw=$(document).width();
				var vh=$(document).height();
				if(w>vw){
					vw=w;
					l=0;
				}
				if(h>vh){
					vh=h;
					t=0;
				}
				if($.browser.msie){
					$("#lightbox_img").show();
					$("#lightbox_bg").show();
					$("#lightbox_bg").fadeTo(50, 0.5);
				}else{
					$("#lightbox_img").fadeIn(500);
					$("#lightbox_bg").fadeIn(500);
				}
				$("#lightbox_img").css({\'top\':t+\'px\', \'left\':l+\'px\'});
				$("#lightbox_bg").css({\'width\':vw+\'px\', \'height\':vh+\'px\'});
				$("#lightbox_img, #lightbox_bg").click(function(){
					$("#lightbox_bg").fadeOut(500);
					$("#lightbox_img").fadeOut(500);
				});
			});
		}
	});';
				$content.='<img src="'.(($config['slink']>0 || $r_dbp['upload']==0)?($r_dbp['upload']>0?'file/':'').$u:'img.php?id='.$r_dbp['id']).'" alt="" title="'.$r_dbp['title'].'" id="lightbox" class="al_t"/>';
			}
			if($r_dbp['cid']>0){
				$s_dbc=sprintf('select id, title from %s where id=%s and disp=0 limit 1', $dbprefix.'camp', $r_dbp['cid']);
				$q_dbc=mysql_query($s_dbc) or die('');
				$r_dbc=mysql_fetch_assoc($q_dbc);
				if(mysql_num_rows($q_dbc)>0)$content.='<br/><br/>相关活动：<a href="?m=album&amp;camp='.$r_dbc['id'].'">'.$r_dbc['title'].'</a>';
				mysql_free_result($q_dbc);
			}
			$content.='</div>';
			if($c_log && $pa>0 && $pa<9)$content.='<div class="lcontent" id="del_0" style="display: none;"><form method="post" action="" class="btform" id="delform0"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" name="hs_cbt" data-id="del_0|l_0"/><input type="hidden" name="did" value="0" /></td></tr></table></form></div>';
			if($c_log && ($pa>$r_dbp['power'] || $_SESSION[$config['u_hash']]==$r_dbp['aid']))$content.='<div class="lcontent" id="h_0" style="display: none;"><form method="post" action=""'.($r_dbp['upload']>0?'':' name="btform" id="editform"').'><table>'.(($r_dbp['upload']==0 && $r_dbp['vid']==0)?'<tr><td>照片地址：</td><td><input name="url" size="32" value="'.$u.'" class="bt_input" rel="照片地址" /></td></tr>':'').($r_dbp['upload']>0?'':'<tr><td>缩略图：</td><td><input name="tbimg" size="32" value="'.$tb_i.'" />（可选项，70*70px）</td></tr>').'<tr><td>'.($r_dbp['vid']>0?'视频':'照片').'描述：</td><td><input name="title" size="32" value="'.$r_dbp['title'].'" /></td></tr>'.($campop!=''?'<tr><td>相关活动：</td><td><select name="cid">'.getcampo($r_dbp['cid']).'</select></td></tr>':'').($r_dbp['vid']>0?'<tr><td colspan="2">视频代码：<br/><textarea name="url" id="formurl1" cols="50" rows="5" class="bt_input" rel="视频代码">'.htmlspecialchars($u,ENT_QUOTES).'</textarea></td></tr>':'').'<tr><td colspan="2"><input type="submit" value="修改" class="button" /><input type="hidden" name="edit" value="1"/> <input type="button" value="取消" class="button" name="hs_cbt" data-id="h_0|l_0" /></td></tr></table></form></div>';
			$content.='</li>';
			$s_a_dbr=sprintf('select a.*, b.power, b.name from %s as a, %s as b where a.pid=%s and a.aid=b.id%s order by a.datetime desc', $dbprefix.'pcomment', $dbprefix.'member', $r_dbp['id'], $ddb);
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
			if(isset($p_dbr) && $p_dbr>1)$content.=getpage($page, $p_dbt);
			if($c_log && $r_dbp['disp']==0)$content.='<div class="title" id="postreply">发表评论</div><div class="lcontent">'.getcform().'</div>';
		}
	}else{
		header('Location:./');
		exit();
	}
	mysql_free_result($q_dbp);
}elseif(isset($_GET['camp']) && intval($_GET['camp'])>0){
	$s_dbc=sprintf('select id, title from %s where id=%s and disp=0 limit 1', $dbprefix.'camp', intval($_GET['camp']));
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		$s_a_dbp=sprintf('select a.id, a.title, a.url, a.vid, a.upload, a.disp, b.name from %s as a, %s as b where a.cid=%s and a.aid=b.id and a.disp=0 order by a.datetime desc', $dbprefix.'photo', $dbprefix.'member', $r_dbc['id']);
		$q_a_dbp=mysql_query($s_a_dbp) or die('');
		$c_dbp=mysql_num_rows($q_a_dbp);
		if($c_dbp>0){
			$content.='<div class="title">相关活动：<a href="?m=camp&amp;id='.$r_dbc['id'].'">'.$r_dbc['title'].'</a></div><div class="gcontent">';
			$p_dbp=ceil($c_dbp/$pagesize);
			if($page>$p_dbp)$page=$p_dbp;
			$s_dbp=sprintf('%s limit %d, %d', $s_a_dbp, ($page-1)*$pagesize, $pagesize);
			$q_dbp=mysql_query($s_dbp) or die('');
			$r_dbp=mysql_fetch_assoc($q_dbp);
			do{
				$content.='<div class="al_list"><a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="'.($r_dbp['disp']>0?'del_':'').'al_t" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').($r_dbp['title']!=''?$r_dbp['title'].'，':'').'上传：'.$r_dbp['name'].'"/></a></div>';
			}while($r_dbp=mysql_fetch_assoc($q_dbp));
			mysql_free_result($q_dbp);
			$content.='<div class="extr"></div>';
			if($p_dbp>1)$content.=getpage($page, $p_dbp);
			$content.='</div>';
		}else{
			header('Location:./?m=album');
			exit();
		}
		mysql_free_result($q_a_dbp);
	}else{
		header('Location:./?m=album');
		exit();
	}
	mysql_free_result($q_dbc);
}elseif(isset($_GET['user']) && intval($_GET['user'])>0){
	$vdb=$config['veri']>0?'':' and status=0';
	$s_dbu=sprintf('select id, name from %s where id=%s%s limit 1', $dbprefix.'member', intval($_GET['user']), $vdb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		$s_a_dbp=sprintf('select id, title, url, vid, upload, disp from %s where aid=%s and disp=0 order by datetime desc', $dbprefix.'photo', $r_dbu['id']);
		$q_a_dbp=mysql_query($s_a_dbp) or die('');
		$c_dbp=mysql_num_rows($q_a_dbp);
		if($c_dbp>0){
			$content.='<div class="title"><a href="?m=user&amp;id='.$r_dbu['id'].'">'.$r_dbu['name'].'</a>的照片视频</div><div class="gcontent">';
			$p_dbp=ceil($c_dbp/$pagesize);
			if($page>$p_dbp)$page=$p_dbp;
			$s_dbp=sprintf('%s limit %d, %d', $s_a_dbp, ($page-1)*$pagesize, $pagesize);
			$q_dbp=mysql_query($s_dbp) or die('');
			$r_dbp=mysql_fetch_assoc($q_dbp);
			do{
				$content.='<div class="al_list"><a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="'.($r_dbp['disp']>0?'del_':'').'al_t" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').($r_dbp['title']!=''?$r_dbp['title']:'').'"/></a></div>';
			}while($r_dbp=mysql_fetch_assoc($q_dbp));
			mysql_free_result($q_dbp);
			$content.='<div class="extr"></div>';
			if($p_dbp>1)$content.=getpage($page, $p_dbp);
			$content.='</div>';
		}else{
			header('Location:./?m=album');
			exit();
		}
		mysql_free_result($q_a_dbp);
	}else{
		header('Location:./?m=album');
		exit();
	}
	mysql_free_result($q_dbu);
}else{
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if($c_log){
			$url='';
			$up=(isset($_POST['upload']) && $_POST['upload']==1 && $config['upload']==0 && isset($_FILES['url']))?1:0;
			$vid=(isset($_POST['video']) && $_POST['video']==1)?1:0;
			if($vid>0){
				$url=strip_tags(trim($_POST['url']), '<object><param><embed>');
			}else{
				if($up>0){
					$u_s=$config['maxsize']*1024;
					$u_f='file/';
					if(!is_dir($u_f))mkdir($u_f);
					$datef=date('Ym').'/';
					$u_f.=$datef;
					if(!is_dir($u_f))mkdir($u_f);
					if(trim($config['filetype'])!=''){
						$u_e=explode(',',trim(strtolower($config['filetype'])));
						foreach($u_e as $v){
							if(trim($v)!='')$u_ea[]=trim($v);
						}
					}
					$e=0;
					$f_a=$_FILES['url'];
					if(is_uploaded_file($f_a['tmp_name']) && $f_a['error']==0){
						if($f_a['size']>$u_s && $u_s>0)$e=1;
						$f_e=explode('.', $f_a['name']);
						$f_e=strtolower($f_e[count($f_e)-1]);
						if(isset($u_ea) && !in_array($f_e, $u_ea))$e=2;
						if(!is_dir($u_f) && is_writeable($u_f))$e=3;
						if($e==0){
							$f_m=md5($_SESSION[$config['u_hash']].time().rand(0,1000));
							$f=$u_f.$f_m.'.'.$f_e;
							if(@copy($f_a['tmp_name'], $f)){
								$data=GetImageSize($f);
								if($data && $data[2]<=3){
									$url=$datef.$f_m.'.'.$f_e;
									if($config['thum']==0 && isset($_POST['thum']) && $_POST['thum']==1){
										$s=$u_f.'t_'.str_replace('.', '_', $f_m.'.'.$f_e).'.jpg';
										$w=70;
										$h=70;
										switch($data[2]){
											case 1:
												$im=@imagecreatefromgif($f);
												break;
											case 2:
												$im=@imagecreatefromjpeg($f);
												break;
											case 3:
												$im=@imagecreatefrompng($f);
												break;
										}
										$sw=$data[0];
										$sh=$data[1];
										if($sw>$w || $sh>$h){
											if($sw>$w && $sh>$h){
												if(($sh/$h)>($sw/$w)){
													$dw=$w;
													$dh=round(($sh*$dw)/$sw);
												}else{
													$dh=$h;
													$dw=round(($sw*$dh)/$sh);
												}
											}elseif($sw>$w){
												$dw=$w;
												$dh=round(($sh*$dw)/$sw);
											}else{
												$dh=$h;
												$dw=round(($sw*$dh)/$sh);
											}
											$ni=imagecreate($w,$h);
											imagecopyresized($ni,$im,($w-$dw)/2,($h-$dh)/2,0,0,$dw,$dh,$sw,$sh);
											imagejpeg($ni,$s);
											imagedestroy($im);
											imagedestroy($ni);
										}
									}
								}else{
									$e=4;
									unlink($f);
								}
							}else{
								$e=5;
							}
						}
					}else{
						$e=5;
					}
				}else{
					$url=getfurl(htmlspecialchars(trim($_POST['url']),ENT_QUOTES));
				}
			}
			$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
			if($url!=''){
				$tbimg=(isset($_POST['tbimg']) && trim($_POST['tbimg'])!='')?getfurl(htmlspecialchars(trim($_POST['tbimg']),ENT_QUOTES)):'';
				if($tbimg!='' && $up==0)$url.='[/]'.$tbimg;
				$cid=isset($_POST['cid'])?$_POST['cid']:0;
				$i_db=sprintf('insert into %s (title, url, aid, cid, datetime, upload, vid) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'photo', 
					SQLString($title, 'text'),
					SQLString($url, 'text'),
					$_SESSION[$config['u_hash']],
					SQLString($cid, 'int'),
					time(),
					SQLString($up, 'int'),
					SQLString($vid, 'int'));
				$result=mysql_query($i_db) or die('');
				$nid=mysql_insert_id();
				if($cid>0){
					$ptitle=$title!=''?$title:'#'.$nid;
					$i_db=sprintf('insert into %s (content, aid, cid, sid, datetime) values (%s, %s, %s, %s, %s)', $dbprefix.'ccomment', 
						SQLString($ptitle, 'text'),
						$_SESSION[$config['u_hash']],
						SQLString($cid, 'int'),
						SQLString($nid, 'int'),
						time());
					$result=mysql_query($i_db) or die('');
				}
				setsinfo($pn.' 添加新'.($vid>0?'视频':'照片'), $_SESSION[$config['u_hash']], $nid, 2);
			}
		}
		header('Location:./?m=album'.((isset($e) && $e>0)?'&e='.$e.'#msg':''));
		exit();
	}else{
		$title.='照片视频';
		$a_msg=array(1=>'文件太大！', '文件类型不可用！', '上传路径不可用！', '上传的不是图片文件！', '上传出错！');
		$content.='<div class="title">照片视频</div><div class="';
		$s_a_dbp=sprintf('select a.id, a.title, a.url, a.vid, a.upload, a.disp, b.name from %s as a, %s as b where a.aid=b.id%s order by a.datetime desc', $dbprefix.'photo', $dbprefix.'member', $ddb);
		$q_a_dbp=mysql_query($s_a_dbp) or die('');
		$c_dbp=mysql_num_rows($q_a_dbp);
		if($c_dbp>0){
			$p_dbp=ceil($c_dbp/$pagesize);
			if($page>$p_dbp)$page=$p_dbp;
			$s_dbp=sprintf('%s limit %d, %d', $s_a_dbp, ($page-1)*$pagesize, $pagesize);
			$q_dbp=mysql_query($s_dbp) or die('');
			$r_dbp=mysql_fetch_assoc($q_dbp);
			$content.='gcontent">';
			do{
				$content.='<div class="al_list"><a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="'.($r_dbp['disp']>0?'del_':'').'al_t" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').($r_dbp['title']!=''?$r_dbp['title'].'，':'').'上传：'.$r_dbp['name'].'"/></a></div>';
			}while($r_dbp=mysql_fetch_assoc($q_dbp));
			mysql_free_result($q_dbp);
			$content.='<div class="extr"></div>';
			if($p_dbp>1)$content.=getpage($page, $p_dbp);
		}else{
			$content.='lcontent">没有照片/视频';
		}
		mysql_free_result($q_a_dbp);
		$content.='</div>';
		if($c_dbp>0 && $page==1){
			$s_dbc=sprintf('select id, title from %s where disp=0', $dbprefix.'camp');
			$q_dbc=mysql_query($s_dbc) or die('');
			$r_dbc=mysql_fetch_assoc($q_dbc);
			if(mysql_num_rows($q_dbc)>0){
				do{
					$s_dbp=sprintf('select id, title, url, vid, upload, datetime from %s where cid=%s and disp=0 order by datetime desc limit 1', $dbprefix.'photo', $r_dbc['id']);
					$q_dbp=mysql_query($s_dbp) or die('');
					$r_dbp=mysql_fetch_assoc($q_dbp);
					if(mysql_num_rows($q_dbp)>0){
						$cp_list[]=array($r_dbp['datetime'], '<div class="al_list"><a href="?m=album&amp;camp='.$r_dbc['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="cp_t" alt="" title="'.$r_dbc['title'].'"/></a></div>');
					}
					mysql_free_result($q_dbp);
				}while($r_dbc=mysql_fetch_assoc($q_dbc));
				if(isset($cp_list) && count($cp_list)>0){
					rsort($cp_list);
					$content.='<br/><div class="title">相关活动</div><div class="gcontent">';
					foreach($cp_list as $v)$content.=$v[1];
					$content.='<div class="extr"></div></div>';
				}
			}
			mysql_free_result($q_dbc);
			$vdb=$config['veri']>0?'':' where status=0';
			$s_dbu=sprintf('select id, name from %s%s', $dbprefix.'member', $vdb);
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				do{
					$s_dbp=sprintf('select id, title, url, vid, upload, datetime from %s where aid=%s and disp=0 order by datetime desc limit 1', $dbprefix.'photo', $r_dbu['id']);
					$q_dbp=mysql_query($s_dbp) or die('');
					$r_dbp=mysql_fetch_assoc($q_dbp);
					if(mysql_num_rows($q_dbp)>0){
						$cu_list[]=array($r_dbp['datetime'], '<div class="al_list"><a href="?m=album&amp;user='.$r_dbu['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="cp_t" alt="" title="'.$r_dbu['name'].'"/></a></div>');
					}
					mysql_free_result($q_dbp);
				}while($r_dbu=mysql_fetch_assoc($q_dbu));
				if(isset($cu_list) && count($cu_list)>0){
					rsort($cu_list);
					$content.='<br/><div class="title">班级成员</div><div class="gcontent">';
					foreach($cu_list as $v)$content.=$v[1];
					$content.='<div class="extr"></div></div>';
				}
			}
			mysql_free_result($q_dbu);
		}
		$t_form=$config['upload']>0?2:3;
		if($c_log){
			if($config['is_instagram']>0 && $config['instagram_key']!='' && $config['instagram_se']!=''){
				$a_synl[]=array('instagram', 'Instagram');
				$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('instagram', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$is_syn['instagram']=mysql_num_rows($q_dby)>0?1:0;
				mysql_free_result($q_dby);
			}
			if($config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
				$a_synl[]=array('babab', '巴巴变');
				$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('babab', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$is_syn['babab']=mysql_num_rows($q_dby)>0?1:0;
				mysql_free_result($q_dby);
			}
			if($config['is_flickr']>0 && ($config['is_uflickr']>0 || $config['flickr_key']!='')){
				$a_synl[]=array('flickr', 'Flickr');
				$s_dby=sprintf('select s_id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('flickr', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				$is_syn['flickr']=mysql_num_rows($q_dby)>0?1:0;
				if($is_syn['flickr']>0)$content.='<input type="hidden" id="flickr_key" value="'.$config['flickr_key'].'"/><input type="hidden" id="flickr_id" value="'.$r_dby['s_id'].'"/>';
				mysql_free_result($q_dby);
			}
			if(isset($a_synl)){
				foreach($a_synl as $v){
					$js_c.='
	$("#getimg_'.$v[0].'").click(function(){';
					foreach($a_synl as $vv)$js_c.='
		$("#'.$vv[0].'_sdiv").'.($vv[0]==$v[0]?'show':'hide').'();';
					$js_c.='
		$("#album_sync_div").slideDown(500);';
					if($is_syn[$v[0]]>0){
						$js_c.='
		if($("#'.$v[0].'_isload").length==0 || $("#'.$v[0].'_isload").val()!=\'1\')';
						if($v[0]=='flickr'){
							$js_c.='getflickr(\'1\');';
						}else{
							$js_c.='$("#'.$v[0].'_sdiv").load(\'j_sync.php?t='.$v[0].'\');';
						}
					}
					$js_c.='
	});';
					$a_syn_i[]='<img src="images/i-'.$v[0].'.gif" alt="" title="选取'.$v[1].'图片" id="getimg_'.$v[0].'" class="f_link"/>';
					$a_syn_c[]='<div id="'.$v[0].'_sdiv">'.($is_syn[$v[0]]>0?'<img src="images/v.gif" alt="" title="载入中……" class="loading_va"/>':'您还没有绑定'.$v[1].'帐号，<a href="?m=profile&amp;t=sync&amp;n='.$v[0].'">点击绑定</a> | <span name="h_cbt" data-id="album_sync_div" class="mlink f_link">取消</span>').'</div>';
				}
			}
			$js_c.='
	$("span[name=\'uimg_m\']").click(function(){
		dhdivf(\'topicform\', $(this).data(\'id\'), '.$t_form.');
	});';
			$content.='<br/>'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v" id="msg">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title" id="uploadimg">添加照片/视频&nbsp;&nbsp;<span class="gdate"><span name="uimg_m" data-id="0" class="mlink f_link">转贴照片</span>'.($config['upload']==0?' | <span name="uimg_m" data-id="2" class="mlink f_link">上传照片</span>':'').' | <span name="uimg_m" data-id="1" class="mlink f_link">添加视频</span></span></div>'.($config['upload']==0?'<div class="lcontent" id="topicform2" style="display: none;"><form method="post" action="" enctype="multipart/form-data"><table><tr><td>照片文件：</td><td><input type="file" name="url" size="32" /> '.($config['maxsize']>0?'最大上传：'.$config['maxsize'].'K、':'').'允许类型：'.$config['filetype'].'</td></tr>'.($config['thum']==0?'<tr><td colspan="2"><input type="checkbox" name="thum" value="1" checked="checked" />生成缩略图</td></tr>':'').'<tr><td>照片描述：</td><td><input name="title" size="32" /></td></tr>'.($campop!=''?'<tr><td>相关活动：</td><td><select name="cid">'.$campop.'</select></td></tr>':'').'<tr><td colspan="2"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /><input type="hidden" name="upload" value="1"/></td></tr></table></form></div>':'').'<div class="lcontent" id="topicform1" style="display: none;"><form method="post" action="" class="btform" id="pform_v"><table><tr><td>视频描述：</td><td><input name="title" size="32" /></td></tr><tr><td>缩略图：</td><td><input name="tbimg" size="32" />（可选项，70*70px）</td></tr>'.($campop!=''?'<tr><td>相关活动：</td><td><select name="cid">'.$campop.'</select></td></tr>':'').'<tr><td colspan="2">视频代码：<br/><textarea name="url" id="formurl1" cols="50" rows="5" class="bt_input" rel="视频代码"></textarea></td></tr><tr><td colspan="2"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /><input type="hidden" name="video" value="1"/></td></tr></table></form></div><div class="lcontent" id="topicform0"><form method="post" action="" class="btform" id="pform_u"><table><tr><td>照片地址：</td><td><input name="url" id="formurl0" size="32" class="bt_input" rel="照片地址" />'.(isset($a_syn_i)?' '.join(' ', $a_syn_i).'</td></tr><tr id="album_sync_div" style="display: none;"><td></td><td>'.join('', $a_syn_c):'').'</td></tr><tr><td>缩略图：</td><td><input name="tbimg" id="formtitle1" size="32" />（可选项，70*70px）</td></tr><tr><td>照片描述：</td><td><input name="title" id="formtitle0" size="32" /></td></tr>'.($campop!=''?'<tr><td>相关活动：</td><td><select name="cid">'.$campop.'</select></td></tr>':'').'<tr><td colspan="2"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /></td></tr></table></form></div>';
		}
	}
}
$content.='</div>';
