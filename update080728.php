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
$lfile='update080728.lock';
if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once($c_file);
		echo '<div class="title">升级MySQL数据库</div><div class="gcontent"><ul>';
		$query=sprintf("alter table %s add ip varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("update %s set ip='%s'", $dbprefix.'main', '<a href="http://www.ip138.com/ips.asp?ip=[ip]" rel="external">[ip]</a>');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add vid int(5) NOT NULL default '0'", $dbprefix.'photo');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'photo：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);
		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($lfile,time());
	}else{
?>
	<div class="title">从 1.0.5 升级到 1.0.6</div>
	<div class="lcontent">
		<form method="post">
			此升级程序只能从 <strong>1.0.5</strong> 升级到 1.0.6，版本太低的用户请先升级到 1.0.5，再使用此升级程序！<br/><br/>
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