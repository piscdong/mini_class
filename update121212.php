<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

require_once('inc.php');
$lfile='update121212.lock';
if(!file_exists($lfile.'1')){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once($c_file);
		echo '<div class="title">升级MySQL数据库</div><div class="gcontent"><ul><li>数据库结构有较大变化，建议升级后重新备份数据库</li>';

		$query=sprintf("alter table %s add smtp_server varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add smtp_port varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add smtp_email varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add smtp_isa int(5) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add smtp_user varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add smtp_pwd varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add is_google int(5) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add google_key varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add google_se varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add is_live int(5) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add live_key varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add live_se varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop is_ut163", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop is_udouban", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s change s_t s_t text", $dbprefix.'m_sync');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add s_r text", $dbprefix.'m_sync');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add edate int(15) NOT NULL default '0'", $dbprefix.'m_sync');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add mdate int(15) NOT NULL default '0'", $dbprefix.'m_sync');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($lfile,time());
	}else{
?>
	<div class="title">从 1.1.6 升级到 1.1.7</div>
	<div class="lcontent">
		<form method="post">
			此升级程序只能从 <strong>1.1.6</strong> 升级到 1.1.7，版本太低的用户请先升级到 1.1.6，再使用此升级程序！<br/><br/>
			<div class="formline"><input type="submit" value="下一步" class="button" /></div>
		</form>
	</div>
<?php
	}
	echo getsfoot();
}else{
	header('Location:../');
}
?>