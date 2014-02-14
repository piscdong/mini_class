<?php
/////////////////////////////////////////////////////////////////////////////
// 迷你同学录 (http://mini_class.piscdong.com/)
//
// (c)PiscDong studio (http://www.piscdong.com/)
//
// 程序完全免费，请保留这段代码。
// 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
/////////////////////////////////////////////////////////////////////////////

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

function SQLString($c, $t){
	$c=(!get_magic_quotes_gpc())?addslashes($c):$c;
	switch($t){
		case 'text':
			$c=($c!='')?"'".$c."'":'NULL';
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

function imString($c, $t){
	$c=str_replace(array('<&#039;>','<r>','<n>'), array("'","\r","\n"), $c);
	$c=SQLString($c, $t);
	return $c;
}
$app_n='迷你同学录';

$lname='update100901';
$lfile=$lname.'.lock';
$sql_f='sql-107.php';
$b_file='config.php';
$c_file='../'.$b_file;

if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent"><div class="title">从 1.0.7 升级到 1.1.0</div>';
	if(file_exists($sql_f)){
		if(isset($_GET['n']) && $_GET['n']==md5('2')){
			require_once($c_file);
			echo '<div class="gcontent"><strong>更新数据库</strong><ul><li>更新'.$dbprefix.'main</li>';
			require_once($sql_f);
			$sql=sprintf('truncate table %s', $dbprefix.'main');
			$result=mysql_query($sql) or die('');
			if(isset($data['main']) && count($data['main'])>0){
				foreach($data['main'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, title, school, classname, open, openreg, gid, content, email, pagesize, upload, maxsize, filetype, thum, slink, veri, icp, skin, timefix, ip, avator) values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'main', $k, imString($v[0], 'text'), imString($v[1], 'text'), imString($v[2], 'text'), imString($v[3], 'int'), imString($v[4], 'int'), imString($v[5], 'int'), imString($v[6], 'text'), imString($v[7], 'text'), imString($v[8], 'int'), imString($v[9], 'int'), imString($v[10], 'int'), imString($v[11], 'text'), imString($v[12], 'int'), imString($v[13], 'int'), imString($v[14], 'int'), imString($v[15], 'text'), imString($v[16], 'int'), imString($v[17], 'int'), imString($v[18], 'text'), imString($v[19], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'member</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'member');
			$result=mysql_query($sql) or die('');
			if(isset($data['member']) && count($data['member'])>0){
				foreach($data['member'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, username, password, name, status, power, regdate, visit, visitdate, question, answer, email, gender, bir_y, bir_m, bir_d, address, location, url, work, phone, tel, qq, msn, gtalk, gid, rela, photo) values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'member', $k, imString($v[0], 'text'), imString($v[1], 'text'), imString($v[2], 'text'), imString($v[3], 'int'), imString($v[4], 'int'), imString($v[5], 'int'), imString($v[6], 'int'), imString($v[7], 'int'), imString($v[8], 'text'), imString($v[9], 'text'), imString($v[10], 'text'), imString($v[11], 'text'), imString($v[12], 'int'), imString($v[13], 'int'), imString($v[14], 'int'), imString($v[15], 'text'), imString($v[16], 'text'), imString($v[17], 'text'), imString($v[18], 'text'), imString($v[19], 'text'), imString($v[20], 'text'), imString($v[21], 'text'), imString($v[22], 'text'), imString($v[23], 'text'), imString($v[24], 'int'), imString($v[25], 'text'), imString($v[26], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'topic</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'topic');
			$result=mysql_query($sql) or die('');
			if(isset($data['topic']) && count($data['topic'])>0){
				foreach($data['topic'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, content, aid, datetime, sticky, sid, tid, mid, disp, `lock`, rid, lasttime) values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'topic', $k, imString($v[0], 'text'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'int'), imString($v[5], 'int'), imString($v[6], 'int'), imString($v[7], 'int'), imString($v[8], 'int'), imString($v[9], 'int'), imString($v[10], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'vote</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'vote');
			$result=mysql_query($sql) or die('');
			if(isset($data['vote']) && count($data['vote'])>0){
				foreach($data['vote'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, tid, vid, datetime) values (%s, %s, %s, %s, %s)', $dbprefix.'vote', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'photo</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'photo');
			$result=mysql_query($sql) or die('');
			if(isset($data['photo']) && count($data['photo'])>0){
				foreach($data['photo'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, url, title, aid, cid, datetime, upload, disp, vid) values (%s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'photo', $k, imString($v[0], 'text'), imString($v[1], 'text'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'int'), imString($v[5], 'int'), imString($v[6], 'int'), imString($v[7], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'pcomment</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'pcomment');
			$result=mysql_query($sql) or die('');
			if(isset($data['pcomment']) && count($data['pcomment'])>0){
				foreach($data['pcomment'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, pid, disp, datetime, content) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'pcomment', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'campc</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'camp');
			$result=mysql_query($sql) or die('');
			if(isset($data['camp']) && count($data['camp'])>0){
				foreach($data['camp'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, title, aid, sticky, closed, disp, datetime, content, cdate, cloc, cpay) values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'camp', $k, imString($v[0], 'text'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'int'), imString($v[5], 'int'), imString($v[6], 'text'), imString($v[7], 'text'), imString($v[8], 'text'), imString($v[9], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'ccomment</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'ccomment');
			$result=mysql_query($sql) or die('');
			if(isset($data['ccomment']) && count($data['ccomment'])>0){
				foreach($data['ccomment'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, cid, sid, disp, datetime, content) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'ccomment', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'int'), imString(($v[5]!=''?$v[5]:' '), 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'cuser</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'cuser');
			$result=mysql_query($sql) or die('');
			if(isset($data['cuser']) && count($data['cuser'])>0){
				foreach($data['cuser'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, cid, tid, datetime) values (%s, %s, %s, %s, %s)', $dbprefix.'cuser', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'message</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'message');
			$result=mysql_query($sql) or die('');
			if(isset($data['message']) && count($data['message'])>0){
				foreach($data['message'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, tid, datetime, readed, content) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'message', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'adminop</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'adminop');
			$result=mysql_query($sql) or die('');
			if(isset($data['adminop']) && count($data['adminop'])>0){
				foreach($data['adminop'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, aid, sid, tid, datetime, content) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'adminop', $k, imString($v[0], 'int'), imString($v[1], 'int'), imString($v[2], 'int'), imString($v[3], 'int'), imString($v[4], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'link</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'link');
			$result=mysql_query($sql) or die('');
			if(isset($data['link']) && count($data['link'])>0){
				foreach($data['link'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, title, url, thread) values (%s, %s, %s, %s)', $dbprefix.'link', $k, imString($v[0], 'text'), imString($v[1], 'text'), imString($v[2], 'int'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '<li>更新'.$dbprefix.'skin</li>';
			$sql=sprintf('truncate table %s', $dbprefix.'skin');
			$result=mysql_query($sql) or die('');
			if(isset($data['skin']) && count($data['skin'])>0){
				foreach($data['skin'] as $k=>$v){
					$iSQL=sprintf('insert into %s (id, path, title, sfile) values (%s, %s, %s, %s)', $dbprefix.'skin', $k, imString($v[0], 'text'), imString($v[1], 'text'), imString($v[2], 'text'));
					$result=mysql_query($iSQL) or die('');
				}
			}
			echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/>';
			writeText($lfile,time());
		}else{
			echo '<script type="text/JavaScript">location.href=\''.$lname.'.php\';</script>';
		}
	}else{
		echo '<script type="text/JavaScript">alert(\''.$sql_f.'不存在\');location.href=\''.$lname.'.php\';</script>';
	}
	echo '</div></div></div><div id="foot">&copy; '.date('Y').' '.$app_n.'<br/><a href="http://mini_class.piscdong.com/" rel="external"><img src="../images/powered.gif" alt="Powered by '.$app_n.'"/></a></div></div></body></html>';
}else{
	header('Location:../');
}
