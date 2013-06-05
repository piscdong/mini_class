<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

function getMicrotime(){
	$mt=explode(' ',microtime());
	return((float)$mt[0]+(float)$mt[1]);
}
$mt=getMicrotime();

$s_dbm=sprintf('select * from %s limit 1', $dbprefix.'main');
$q_dbm=mysql_query($s_dbm) or die('');
$r_dbm=mysql_fetch_assoc($q_dbm);
$config=$r_dbm;
mysql_free_result($q_dbm);
$config['u_hash']=md5($dbprefix);

$root_p=pathinfo($_SERVER['PHP_SELF']);
$root_url='http://'.$_SERVER['HTTP_HOST'].str_replace('\\', '', $root_p['dirname']);
if(substr($root_url, -1)!='/')$root_url.='/';
$config['site_url']=$root_url;


if($config['open']>0 && $config['g_open']>0 && $config['g_name']!='' && $config['g_pwd']!='' && (!isset($_SESSION[$config['u_hash']]) || $_SESSION[$config['u_hash']]=='') && isset($_SESSION['guest_n_'.$config['u_hash']]) && $_SESSION['guest_n_'.$config['u_hash']]==$config['g_name'] && isset($_SESSION['guest_p_'.$config['u_hash']]) && $_SESSION['guest_p_'.$config['u_hash']]==enc_p($config['g_pwd']))$config['open']=0;

$g_a=array('班级成员', '家属', '班级友人', '老师');
$em_a=array(1=>'嘻嘻', '亲亲', '难过', '天使', '哈哈', '恶魔', '眼镜', '无语', '呵呵', '惊讶', '泪', '眨眼');
$a_d_sync=array('9904af8956646323962cc7e3139ac7d3', '9D8903FDDC5E9B0DB284F6879F2712EEAK', '67f7f7ab16734416a82a94be786d6876', '3b81f8e398bf6e40443a224dcf246b9a', 'NZVBEIcC5QxgsY34BhNX', 'lWete(vVG1D2H)-OfFZXNRAY2JviKe$T=(m#VGra');
if($config['is_flickr']>0 && $config['is_uflickr']>0)$config['flickr_key']=$a_d_sync[0];
if($config['is_babab']>0 && $config['is_ubabab']>0)$config['babab_key']=$a_d_sync[1];
if($config['is_tqq']>0 && $config['is_utqq']>0){
	$config['tqq_key']=$a_d_sync[2];
	$config['tqq_se']=$a_d_sync[3];
}
if($config['is_tsohu']>0 && $config['is_utsohu']>0){
	$config['tsohu_key']=$a_d_sync[4];
	$config['tsohu_se']=$a_d_sync[5];
}
$mgc_file='mgc_file.php';

function SQLString($c, $t){
	$c=(!get_magic_quotes_gpc())?addslashes($c):$c;
	switch($t){
		case 'text':
			$c=($c!='')?"'".str_replace("'", '&#039;', $c)."'":'NULL';
			break;
		case 'search':
			$c="'%%".$c."%%'";
			break;
		case 'int':
			$c=($c!='')?intval($c):'0';
			break;
	}
	return $c;
}

function imString($c){
	$c=str_replace(array('<&#039;>','<r>','<n>'), array("'","\r","\n"), $c);
	$c=SQLString($c, 'text');
	return $c;
}

function writeText($f,$c){
	if(is_writable($f) || !file_exists($f)){
		if(!$h=fopen($f,'w'))return false;
		if(!fwrite($h,$c))return false;
		fclose($h);
	}else{
		return false;
	}
	return true;
}

function getIP(){
	if(isset($_ENV['HTTP_CLIENT_IP'])){
		$ip=$_ENV['HTTP_CLIENT_IP'];
	}elseif(isset($_ENV['HTTP_X_FORWARDED_FOR'])){
		$ip=$_ENV['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_ENV['REMOTE_ADDR'])){
		$ip=$_ENV['REMOTE_ADDR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	if(strstr($ip, ':')){
		$ipa=explode(':', $ip);
		foreach($ipa as $v){
			if(strlen($v)>7)$ip=$v;
		}
	}
	if(strstr($ip, ':'))$ip='1.0.0.0';
	return $ip;
}

function chklog(){
	global $config;
	if(isset($_SESSION[$config['u_hash']]) && getainfo($_SESSION[$config['u_hash']], 'id')){
		return true;
	}else{
		return false;
	}
}

function enc_p($c){
	return md5(md5($c));
}

function getqs($page='page'){
	$qs='';
	if(!empty($_SERVER['QUERY_STRING'])){
		$p=explode('&', $_SERVER['QUERY_STRING']);
		foreach($p as $v){
			if(substr($v, 0, strlen($page)+1)!=$page.'=')$np[]='&amp;'.$v;
		}
		if(isset($np))$qs=join('', $np);
	}
	return $qs;
}

function getpage($p, $t){
	$c='<div class="navdiv">'.($p!=1?'<a href="?page=1'.getqs().'">首页</a>':'首页').' |';
	for($i=0;$i<$t;$i++)$c.=' '.($p!=($i+1)?'<a href="?page='.($i+1).getqs().'">':'[').($i+1).($p!=($i+1)?'</a>':']');
	return $c.' | '.($p!=$t?'<a href="?page='.$t.getqs().'">尾页</a>':'尾页').'</div>';
}

function gbookencode($c, $t=0){
	global $em_a;
	$c=preg_replace("/\[url\](.*?)\[\/url\]/i",'<a href="$1" rel="external">$1</a>',$c);
	$c=preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/is",'<a href="$1" rel="external">$2</a>',$c);
	foreach($em_a as $k=>$v){
		$ei=str_pad($k, 2, '0', STR_PAD_LEFT);  
		if($t>0){
			$c=str_replace('[em'.$ei.']', '['.$v.']', $c);
		}else{
			$c=str_replace('[em'.$ei.']', '<img src="images/em'.$ei.'.gif" alt="" title="'.$v.'" />', $c);
		}
	}
	$c=str_replace("\r",'<br />',$c);
	return $c;
}

function getainfo($i, $s=''){
	global $dbprefix, $config;
	$vdb=$config['veri']>0?'':' and status=0';
	if($s=='')$s='*';
	$s_dbu=sprintf('select %s from %s where id=%s%s limit 1', $s, $dbprefix.'member', $i, $vdb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		return $r_dbu;
	}else{
		return false;
	}
	mysql_free_result($q_dbu);
}

function getpinfo($i){
	global $dbprefix;
	$s_dbp=sprintf('select id, upload, vid, url, title from %s where id=%s limit 1', $dbprefix.'photo', $i);
	$q_dbp=mysql_query($s_dbp) or die('');
	$r_dbp=mysql_fetch_assoc($q_dbp);
	if(mysql_num_rows($q_dbp)>0){
		return $r_dbp;
	}else{
		return false;
	}
	mysql_free_result($q_dbp);
}

function getcinfo($i, $s=''){
	global $dbprefix;
	if($s=='')$s='*';
	$s_dbc=sprintf('select %s from %s where id=%s limit 1', $s, $dbprefix.'camp', $i);
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		return $r_dbc;
	}else{
		return false;
	}
	mysql_free_result($q_dbc);
}

function getpcinfo($r){
	global $pa, $c_log, $dbprefix, $config;
	$u='?m='.(isset($r['pid'])?'album&amp;id='.$r['pid']:'camp&amp;id='.$r['cid']);
	$c='<li class="l_list"><a href="?m=user&amp;id='.$r['aid'].'"><img src="avator.php?id='.$r['aid'].'" alt="" title="'.$r['name'].'" class="photo" width="55" height="55"/></a><div class="list_r"><div class="list_title">';
	if($c_log && (!isset($r['sid']) || $r['sid']==0)){
		if($pa>$r['power'] || $_SESSION[$config['u_hash']]==$r['aid'])$cm[]='&nbsp;<img src="images/o_3.gif" alt="" title="编辑" name="hs_cbt" data-id="l_'.$r['id'].'|h_'.$r['id'].'" class="f_link"/>';
		if($pa>0)$cm[]='&nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="'.($pa==9?'del_list_img':'s_cbt').'" data-id="'.($pa==9?'':'del_').$r['id'].'" class="f_link"/>';
		if($pa==9 && $r['disp']>0)$cm[]='&nbsp;<span class="del_n">已删除</span> <a href="'.$u.'&amp;pid='.$r['id'].'"><img src="images/o_4.gif" alt="" title="恢复"/></a>';
	}
	if(isset($cm))$c.='<span class="gmod">'.join('&nbsp; &nbsp;',$cm).'</span>';
	$c.=getalink($r['aid'], $r['name']).'&nbsp;&nbsp;<span class="gdate">'.getldate($r['datetime']).'</span></div><div class="list_c">';
	if($c_log && $pa>0 && $pa<9 && (!isset($r['sid']) || $r['sid']==0))$c.='<form method="post" action="" class="btform" id="del_'.$r['id'].'" style="display: none;"><table><tr><td>删除理由：</td><td><input name="rtext" size="32" class="bt_input" rel="删除理由" /></td></tr><tr><td colspan="2"><input type="submit" value="删除" class="button" /> <input value="取消" class="button" type="button" name="h_cbt" data-id="del_'.$r['id'].'"/><input type="hidden" name="did" value="'.$r['id'].'" /></td></tr></table></form>';
	$c.='<div id="l_'.$r['id'].'">';
	if(isset($r['sid']) && $r['sid']>0){
		$pr=getpinfo($r['sid']);
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
			$c.='<img src="'.getthu($pr).'" width="70" height="70" class="f_link video_slink al_t" alt="" title="观看视频" data-id="'.$r['id'].'"/><div id="video_div_'.$r['id'].'"></div><textarea id="video_text_'.$r['id'].'" style="display: none;">'.htmlspecialchars($u,ENT_QUOTES).'</textarea><a href="?m=album&amp;id='.$r['sid'].'">'.($pr['title']!=''?$pr['title']:'视频 #'.$pr['id']).'</a>';
		}else{
			$c.='<img src="'.getthu($pr).'" alt="" title="点击查看原图" width="70" height="70" class="f_link img_lb al_t" data-img="'.(($config['slink']>0 || $pr['upload']==0)?($pr['upload']>0?'file/':'').$u:'img.php?id='.$pr['id']).'"/><br/><a href="?m=album&amp;id='.$r['sid'].'">'.($pr['title']!=''?$pr['title']:'照片 #'.$pr['id']).'</a>';
		}
		$reply_s=5;
		$s_dbr=sprintf('select a.id, a.aid, a.content, a.datetime, b.name from %s as a, %s as b where a.pid=%s and a.aid=b.id and a.disp=0 order by a.datetime desc limit %d', $dbprefix.'pcomment', $dbprefix.'member', $r['sid'], $reply_s);
		$q_dbr=mysql_query($s_dbr) or die('');
		$r_dbr=mysql_fetch_assoc($q_dbr);
		if(mysql_num_rows($q_dbr)>0){
			$c.='<div id="reply_v_'.$r['id'].'" class="reply_d">';
			do{
				$c.='<div class="reply_v"><div id="l_'.$r_dbr['id'].'">'.getalink($r_dbr['aid'], $r_dbr['name'], 1).'：'.getaco($r_dbr['content'], $r_dbr['id'], 1).'</div><div class="reply_i">- '.getldate($r_dbr['datetime']).'</div></div>';
			}while($r_dbr=mysql_fetch_assoc($q_dbr));
			$c.='<a href="?m=album&amp;id='.$r['sid'].'">更多留言</a></div>';
		}
		mysql_free_result($q_dbr);
	}else{
		$c.=getaco($r['content'], $r['id']);
	}
	$c.='</div>';
	if($c_log && ($pa>$r['power'] || $_SESSION[$config['u_hash']]==$r['aid']) && (!isset($r['sid']) || $r['sid']==0))$c.=getcform($r['id'], $r['content']);
	$c.='</div></div></li>';
	return $c;
}

function setsinfo($c, $a, $s=0, $t=1){
	global $dbprefix;
	if($s==0)$s=$a;
	$time=time();
	$s_dbt=sprintf('select id, datetime from %s where sid=%s and tid=%s limit 1', $dbprefix.'topic', $s, $t);
	$q_dbt=mysql_query($s_dbt) or die('');
	$r_dbt=mysql_fetch_assoc($q_dbt);
	if(mysql_num_rows($q_dbt)>0){
		$dtime=$t>1?$r_dbt['datetime']:$time;
		$u_db=sprintf('update %s set content=%s, datetime=%s, aid=%s, disp=0, lasttime=%s where id=%s', $dbprefix.'topic',
			SQLString($c, 'text'),
			$dtime,
			$a,
			$time,
			$r_dbt['id']);
		$result=mysql_query($u_db) or die('');
	}else{
		$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid, lasttime) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'topic', 
			SQLString($c, 'text'),
			$a,
			$time,
			$s,
			$t,
			$time);
		$result=mysql_query($i_db) or die('');
	}
	mysql_free_result($q_dbt);
}

function setoinfo($c, $s, $t=0){
	global $dbprefix, $config;
	$time=time();
	$s_dba=sprintf('select id from %s where sid=%s and tid=%s limit 1', $dbprefix.'adminop', $s, $t);
	$q_dba=mysql_query($s_dba) or die('');
	$r_dba=mysql_fetch_assoc($q_dba);
	if(mysql_num_rows($q_dba)>0){
		$u_db=sprintf('update %s set content=%s, aid=%s, datetime=%s where id=%s', $dbprefix.'adminop',
			SQLString($c, 'text'),
			$_SESSION[$config['u_hash']],
			$time,
			$r_dba['id']);
		$result=mysql_query($u_db) or die('');
	}else{
		$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid) values (%s, %s, %s, %s, %s)', $dbprefix.'adminop', 
			SQLString($c, 'text'),
			$_SESSION[$config['u_hash']],
			$time,
			$s,
			$t);
		$result=mysql_query($i_db) or die('');
	}
	mysql_free_result($q_dba);
}

function getfurl($u){
	$u=trim($u);
	if($u!='')return (!strstr($u, '://')?'http://':'').$u;
}

function getuinfo($r, $i=0){
	global $g_a, $config, $lunar;
	if($i==0)$a[]='';
	if(trim($r['photo'])!='' && $i>0){
		$a_pho=explode('|', trim($r['photo']));
		$m_pho=$config['avator']>0?$config['avator']:1;
		foreach($a_pho as $k=>$v){
			if($k<$m_pho)$c_pho[]='<img src="'.$v.'" alt="" width="55" height="55"/>';
		}
		$a[]=join(' ', $c_pho).'<br/>';
	}
	if($r['rela']!='')$a[]=$r['rela'].'<br/>';
	if($r['gender']>0)$a[]='性别：'.($r['gender']==1?'帅哥':'美女');
	if($i>0){
		if($r['bir_y']>0){
			$bir_y=$r['isnl']>0?$lunar->LYearName($r['bir_y']):$r['bir_y'];
			$a_b[$r['id']][]=$bir_y.'年';
		}
		if($r['bir_m']>0){
			$bir_y=$r['isnl']>0?$lunar->LMonName($r['bir_m']):$r['bir_m'];
			$a_b[$r['id']][]=$bir_y.'月';
			if($r['bir_d']>0){
				$bir_y=$r['isnl']>0?$lunar->LDayName($r['bir_d']):$r['bir_d'];
				$a_b[$r['id']][]=$bir_y.'日';
			}
		}
		if(isset($a_b[$r['id']]))$a[]='生日：'.($r['isnl']>0?'农历':'').join('', $a_b[$r['id']]);
	}
	if($r['phone']!='')$a[]='手机：<span class="tel">'.$r['phone'].'</span>';
	if($r['tel']!='' && $i>0)$a[]='联系电话：<span class="tel home">'.$r['tel'].'</span>';
	if($r['email']!='' && $i>0)$a[]='电子邮件：<a href="mailto:'.$r['email'].'" class="email">'.$r['email'].'</a>';
	if($r['url']!='' && $i>0)$a[]='主页：<a href="'.getfurl($r['url']).'" class="url">'.$r['url'].'</a>';
	if($r['qq']!='' && $i>0)$a[]='QQ：'.$r['qq'];
	if($r['msn']!='' && $i>0)$a[]='MSN：'.$r['msn'];
	if($r['gtalk']!='' && $i>0)$a[]='GTalk：'.$r['gtalk'];
	if($r['address']!='' && $i>0)$a[]='住址：<span class="adr">'.$r['address'].'</span> <a href="?m=user&amp;t=map&amp;uid='.$r['id'].'">查看地图</a>';
	if($r['location']!='' && $i>0)$a[]='籍贯：'.$r['location'];
	if($r['work']!='' && $i>0)$a[]='工作单位：'.$r['work'];
	if(isset($g_a[$r['gid']]))$a[]='身份：'.$g_a[$r['gid']];
	$a[]='注册日期：'.date('Y-n-j H:i', getftime($r['regdate']));
	return join('<br/>', $a);
}

function delphoto($r, $c=''){
	global $dbprefix, $pa, $pn, $config;
	if($pa==9){
		$d_db=sprintf('delete from %s where sid=%s and tid=2', $dbprefix.'topic', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where sid=%s', $dbprefix.'ccomment', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where pid=%s', $dbprefix.'pcomment', $r['id']);
		$result=mysql_query($d_db) or die('');
		if($r['upload']>0){
			if(file_exists('file/'.$r['url']))unlink('file/'.$r['url']);
			if(file_exists('file/'.getthi($r['url'])))unlink('file/'.getthi($r['url']));
		}
		$d_db=sprintf('delete from %s where id=%s', $dbprefix.'photo', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where sid=%s and tid=1', $dbprefix.'adminop', $r['id']);
		$result=mysql_query($d_db) or die('');
	}else{
		$u_db=sprintf('update %s set disp=1 where sid=%s and tid=2', $dbprefix.'topic', $r['id']);
		$result=mysql_query($u_db) or die('');
		$u_db=sprintf('update %s set disp=1 where sid=%s', $dbprefix.'ccomment', $r['id']);
		$result=mysql_query($u_db) or die('');
		$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'photo', $r['id']);
		$result=mysql_query($u_db) or die('');
		$ac=$pn." 删除照片\r删除理由：".$c;
		$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid) values (%s, %s, %s, %s, 1)', $dbprefix.'adminop', 
			SQLString($ac, 'text'),
			$_SESSION[$config['u_hash']],
			time(),
			$r['id']);
		$result=mysql_query($i_db) or die('');
	}
}

function dellist($r){
	global $dbprefix;
	$d_db=sprintf('delete from %s where sid=%s and tid=0', $dbprefix.'adminop', $r['id']);
	$result=mysql_query($d_db) or die('');
	if($r['rid']==0){
		$s_dbt=sprintf('select id from %s where rid=%s order by datetime limit 1', $dbprefix.'topic', $r['id']);
		$q_dbt=mysql_query($s_dbt) or die('');
		$r_dbt=mysql_fetch_assoc($q_dbt);
		if(mysql_num_rows($q_dbt)>0){
			$u_db=sprintf('update %s set rid=%s where rid=%s', $dbprefix.'topic', $r_dbt['id'], $r['id']);
			$result=mysql_query($u_db) or die('');
			$u_db=sprintf('update %s set rid=0, lasttime=%s where id=%s', $dbprefix.'topic', $r['lasttime'], $r_dbt['id']);
			$result=mysql_query($u_db) or die('');
		}
		mysql_free_result($q_dbt);
	}
	if($r['mid']==1){
		$d_db=sprintf('delete from %s where tid=%s', $dbprefix.'vote', $r['id']);
		$result=mysql_query($d_db) or die('');
	}
	$d_db=sprintf('delete from %s where id=%s', $dbprefix.'topic', $r['id']);
	$result=mysql_query($d_db) or die('');
}

function undellist($i){
	global $dbprefix;
	$u_db=sprintf('update %s set disp=0 where id=%s', $dbprefix.'topic', $i);
	$result=mysql_query($u_db) or die('');
	$d_db=sprintf('delete from %s where sid=%s and tid=0', $dbprefix.'adminop', $i);
	$result=mysql_query($d_db) or die('');
}

function delcamp($r, $c=''){
	global $dbprefix, $pa, $pn, $config;
	if($pa==9){
		$u_db=sprintf('update %s set cid=0 where cid=%s', $dbprefix.'photo', $r['id']);
		$result=mysql_query($u_db) or die('');
		$d_db=sprintf('delete from %s where sid=%s and tid=3', $dbprefix.'topic', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where cid=%s', $dbprefix.'ccomment', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where cid=%s', $dbprefix.'cuser', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where id=%s', $dbprefix.'camp', $r['id']);
		$result=mysql_query($d_db) or die('');
		$d_db=sprintf('delete from %s where sid=%s and tid=2', $dbprefix.'adminop', $r['id']);
		$result=mysql_query($d_db) or die('');
	}else{
		$u_db=sprintf('update %s set disp=1 where sid=%s and tid=3', $dbprefix.'topic', $r['id']);
		$result=mysql_query($u_db) or die('');
		$u_db=sprintf('update %s set disp=1 where id=%s', $dbprefix.'camp', $r['id']);
		$result=mysql_query($u_db) or die('');
		$ac=$pn." 删除活动\r删除理由：".$c;
		$i_db=sprintf('insert into %s (content, aid, datetime, sid, tid) values (%s, %s, %s, %s, 2)', $dbprefix.'adminop', 
			SQLString($ac, 'text'),
			$_SESSION[$config['u_hash']],
			time(),
			$r['id']);
		$result=mysql_query($i_db) or die('');
	}
}

function getcampo($i=0){
	global $dbprefix;
	$c='';
	$s_dbc=sprintf('select id, title from %s where disp=0 order by closed, sticky desc, datetime desc', $dbprefix.'camp');
	$q_dbc=mysql_query($s_dbc) or die('');
	$r_dbc=mysql_fetch_assoc($q_dbc);
	if(mysql_num_rows($q_dbc)>0){
		$c.='<option value="0"'.($i==0?' selected="selected"':'').'>无相关活动</option>';
		do{
			$c.='<option value="'.$r_dbc['id'].'" title="'.$r_dbc['title'].'"'.($i==$r_dbc['id']?' selected="selected"':'').'>'.substrs($r_dbc['title'], 25).'</option>';
		}while($r_dbc=mysql_fetch_assoc($q_dbc));
	}
	mysql_free_result($q_dbc);
	return $c;
}

function getthu($r){
	global $config;
	if($r['upload']>0){
		if($config['slink']>0){
			$ct='file/'.getthi($r['url']);
			if(file_exists($ct)){
				$t=$ct;
			}else{
				$t='file/'.$r['url'];
			}
		}else{
			$t='img.php?t=1&amp;id='.$r['id'];
		}
	}else{
		$t=$r['vid']>0?'images/video.jpg':$r['url'];
		if(strstr($r['url'], '[/]')){
			$a_u=explode('[/]', $r['url']);
			$t_u=$a_u[count($a_u)-1];
			if(trim($t_u)!='' && strstr(trim($t_u), '://'))$t=trim($t_u);
		}
	}
	return $t;
}

function getthi($c){
	if(strstr($c, '/')){
		$a=explode('/', $c);
		$ca=count($a);
		$a[($ca-1)]='t_'.str_replace('.', '_', $a[($ca-1)]).'.jpg';
		return join('/', $a);
	}else{
		return 't_'.str_replace('.', '_', $c).'.jpg';
	}
}

function getcform($i=0, $t=''){
	global $c_sync, $em_a;
	$c='<form method="post" action="" id="'.($i>0?'h_'.$i.'" style="display: none;"':'topicform0"').' class="btform"><div class="formline"><textarea name="rinfo" id="forminfo'.$i.'" class="bt_input" rel="留言内容" rows="'.($i>0?'4">'.$t:'8">').'</textarea>';
	if($i==0){
		$c.='<br/>';
		foreach($em_a as $k=>$v){
			$ei=str_pad($k, 2, '0', STR_PAD_LEFT);
			$c.='<img src="images/em'.$ei.'.gif" class="em_img" data-id="'.$ei.'" alt="" title="插入表情：'.$v.'" /> ';
		}
		$c.='<img src="images/link.gif" class="url_img" alt="" title="插入链接" />';
	}
	$c.=$c_sync.'</div><div class="formline"><input type="submit" value="'.($i>0?'修改':'发布').'" class="button" /> <input value="取消" class="button" '.($i>0?'type="button" name="hs_cbt" data-id="h_'.$i.'|l_'.$i.'"/><input type="hidden" name="id" value="'.$i.'"':'type="reset"').' /></div></form>';
	return $c;
}

function chkre(){
	global $config;
	if(isset($_SERVER['HTTP_REFERER'])){
		$u=parse_url($_SERVER['HTTP_REFERER']);
		if($u['host']!=$_SERVER['HTTP_HOST'])return false;
	}
	return true;
}

function chkuag($a='MSIE'){
	if(isset($_SERVER['HTTP_USER_AGENT'])){
		$age=strtoupper($_SERVER['HTTP_USER_AGENT']);
		if(strstr($age, $a))return true;
	}
	return false;
}

function substrs($c,$l=16){
	if(strlen($c)>$l){
		$n=0;
		for($i=0;$i<$l;$i++){
			if(ord($c[$i])>127)$n++;
		}
		if($n%3>0)$l+=3-$n%3;
		$c=substr($c,0,$l).'…';
	}
	return $c;
}

function getftime($t=''){
	global $config;
	if($t=='')$t=time();
	return $t+$config['timefix'];
}

function getldate($t){
	$a=time()-$t;
	if($a<60){
		$c='刚刚';
	}elseif($a<3600){
		$c=floor($a/60).'分钟前';
	}elseif($a<86400){
		$c=floor($a/3600).'小时前';
	}
	return (isset($c)?$c.' ':'').date('Y-n-j H:i', getftime($t));
}

function getalink($i, $n, $t=0){
	global $config, $c_log;
	$c='<a href="?m=user&amp;id='.$i.'">'.$n.'</a>';
	if($c_log && $_SESSION[$config['u_hash']]!=$i && ($t==0 || $t==2)){
		$c.=' ';
		if($t==0){
			$c.='<a href="?m=message&amp;id='.$i.'#send"><img src="images/o_7.gif" alt="" title="发消息"/></a>';
		}else{
			$c.='<img src="images/chat.gif" alt="" title="开始聊天" name="chat_img" data-id="'.$i.'|'.$n.'"  class="f_link"/></a>';
		}
	}
	return $c;
}

function getaco($c, $i, $r=0){
	$m=200;
	return (($r>0 && strstr($c, "\r"))?'<br/>':'').(strlen($c)>$m?'<span id="k_'.$i.'">'.gbookencode(substrs($c, ($m-5))).'<br/><span name="alllink" data-id="'.$i.'" class="mlink f_link">查看全部 &gt;&gt;</span></span><span id="s_'.$i.'" style="display: none;">':'').gbookencode($c).(strlen($c)>$m?'</span>':'');
}

function getsync_c($ar){
	global $config, $dbprefix;
	if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!=''){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('sina', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_sina" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-sina.gif" alt="" title="新浪微博"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定新浪微博账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=sina"><img src="images/i-sina.gif" alt="" title="新浪微博" title="您还没有绑定新浪微博账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('tqq', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_tqq" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tqq.gif" alt="" title="腾讯微博"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定腾讯微博账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=tqq"><img src="images/i-tqq.gif" alt="" title="腾讯微博" title="您还没有绑定腾讯微博账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('renren', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_renren" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-renren.gif" alt="" title="人人网"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定人人网账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=renren"><img src="images/i-renren.gif" alt="" title="人人网" title="您还没有绑定人人网账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('kx001', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_kx001" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-kx001.gif" alt="" title="开心网"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定开心网账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=kx001"><img src="images/i-kx001.gif" alt="" title="开心网" title="您还没有绑定开心网账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('tsohu', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_tsohu" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tsohu.gif" alt="" title="搜狐微博"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定搜狐微博账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=tsohu"><img src="images/i-tsohu.gif" alt="" title="搜狐微博" title="您还没有绑定搜狐微博账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('t163', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_t163" value="1"/><a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-t163.gif" alt="" title="网易微博"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定网易微博账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=t163"><img src="images/i-t163.gif" alt="" title="网易微博" title="您还没有绑定网易微博账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
		$s_dby=sprintf('select s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('twitter', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_twitter" value="1"/><a href="http://twitter.com/'.$r_dby['s_n'].'" rel="external"><img src="images/i-twitter.gif" alt="" title="Twitter"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定Twitter账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=twitter"><img src="images/i-twitter.gif" alt="" title="Twitter" title="您还没有绑定Twitter账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!=''){
		$s_dby=sprintf('select s_id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $ar['id'], SQLString('facebook', 'text'));
		$q_dby=mysql_query($s_dby) or die('');
		$r_dby=mysql_fetch_assoc($q_dby);
		if(mysql_num_rows($q_dby)>0){
			$a_sync_c[]='<input type="checkbox" name="u_facebook" value="1"/><a href="http://www.facebook.com/profile.php?id='.$r_dby['s_id'].'" rel="external"><img src="images/i-facebook.gif" alt="" title="Facebook"/></a>';
		}else{
			$a_sync_c[]='<input type="checkbox" disabled="disabled" title="您还没有绑定Facebook账号，点击图标设置"/><a href="?m=profile&amp;t=sync&amp;n=facebook"><img src="images/i-facebook.gif" alt="" title="Facebook" title="您还没有绑定Facebook账号，点击设置"/></a>';
		}
		mysql_free_result($q_dby);
	}
	if(isset($a_sync_c))return '<br/>发布到：'.join(' ', $a_sync_c);
}

function smail($email, $title, $content){
	global $config;
	if($config['email']==0){
		mail($email, $title, $content);
	}elseif($config['email']==2 && $config['smtp_server']!='' && $config['smtp_port']!='' && $config['smtp_email']!='' && ($config['smtp_isa']==0 || ($config['smtp_user']!='' && $config['smtp_pwd']!=''))){
		if($config['smtp_isa']>0){
			$smtp=new smtp($config['smtp_server'], $config['smtp_port'], true, $config['smtp_user'], $config['smtp_pwd']);
		}else{
			$smtp=new smtp($config['smtp_server'], $config['smtp_port']);
		}
		$smtp->debug=false;
		$smtp->sendmail($email, $config['smtp_email'], $title, $content, 'TXT');
	}
}
