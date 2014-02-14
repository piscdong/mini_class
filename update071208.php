<?php
/////////////////////////////////////////////////////////////////////////////
// 迷你同学录 (http://mini_class.piscdong.com/)
//
// (c)PiscDong studio (http://www.piscdong.com/)
//
// 程序完全免费，请保留这段代码。
// 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
/////////////////////////////////////////////////////////////////////////////

require_once('inc.php');
$lfile='update071208.lock';
if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once($c_file);
		echo '<div class="title">升级MySQL数据库</div><div class="gcontent"><ul>';
		$query=sprintf("alter table %s add timefix varchar(255) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$q_camp=sprintf('select * from %s', $dbprefix.'camp');
		$camp=mysql_query($q_camp) or die('');
		$r_camp=mysql_fetch_assoc($camp);
		if(mysql_num_rows($camp)>0){
			do{
				$q_comment=sprintf('select * from %s where cid=%s and disp=0 order by datetime desc limit 1', $dbprefix.'ccomment', $r_camp['id']);
				$comment=mysql_query($q_comment) or die('');
				$r_comment=mysql_fetch_assoc($comment);
				$dtime=mysql_num_rows($comment)!=0?$r_comment['datetime']:$r_camp['datetime'];
				mysql_free_result($comment);
				$uSQL=sprintf('update %s set datetime=%s, lasttime=%s where sid=%s and tid=3', $dbprefix.'topic',
					$r_camp['datetime'],
					$dtime,
					$r_camp['id']);
				$result=mysql_query($uSQL) or die('');
			}while($r_camp=mysql_fetch_assoc($camp));
			echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#036;">成功</span></li>';
		}
		mysql_free_result($camp);

		$q_photo=sprintf('select * from %s', $dbprefix.'photo');
		$photo=mysql_query($q_photo) or die('');
		$r_photo=mysql_fetch_assoc($photo);
		if(mysql_num_rows($photo)>0){
			do{
				$q_comment=sprintf('select * from %s where pid=%s and disp=0 order by datetime desc limit 1', $dbprefix.'pcomment', $r_photo['id']);
				$comment=mysql_query($q_comment) or die('');
				$r_comment=mysql_fetch_assoc($comment);
				$dtime=mysql_num_rows($comment)!=0?$r_comment['datetime']:$r_photo['datetime'];
				mysql_free_result($comment);
				$uSQL=sprintf('update %s set datetime=%s, lasttime=%s where sid=%s and tid=2', $dbprefix.'topic',
					$r_photo['datetime'],
					$dtime,
					$r_photo['id']);
				$result=mysql_query($uSQL) or die('');
			}while($r_photo=mysql_fetch_assoc($photo));
			echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#036;">成功</span></li>';
		}
		mysql_free_result($photo);
		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($lfile,time());
	}else{
?>
	<div class="title">从 1.0.2 升级到 1.0.3</div>
	<div class="lcontent">
		<form method="post">
			此升级程序只能从 <strong>1.0.2</strong> 升级到 1.0.3，版本太低的用户请先升级到 1.0.2，再使用此升级程序！<br/><br/>
			<div class="formline"><input type="submit" value="下一步" id="formsubmit" class="button" /></div>
		</form>
	</div>
<?php
	}
	echo getsfoot();
}else{
	header('Location:../');
}
?>