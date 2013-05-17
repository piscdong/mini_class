<?php
/////////////////////////////////////////////////////////////////////////////
// 迷你同学录 (http://www.piscdong.com/?m=mini_class)
//
// (c)PiscDong studio (http://www.piscdong.com/)
//
// 程序完全免费，请保留这段代码。
// 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
/////////////////////////////////////////////////////////////////////////////

require_once('inc.php');
$lfile='update071125.lock';
if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once($c_file);
		echo '<div class="title">升级MySQL数据库</div><div class="gcontent"><ul>';
		$query=sprintf("alter table %s add rid int(10) NOT NULL default '0'", $dbprefix.'topic');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add lasttime int(15) NOT NULL default '0'", $dbprefix.'topic');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("update %s set lasttime=datetime", $dbprefix.'topic');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query="create table {$dbprefix}skin (
		id int(10) NOT NULL auto_increment,
		path varchar(255) NOT NULL,
		title varchar(255) default NULL,
		sfile varchar(255) NOT NULL,
		UNIQUE KEY id (id)
		) ".((isset($charset_conn) && $charset_conn==1)?'ENGINE=MyISAM DEFAULT CHARSET=gb2312':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf('insert into %s (path, title, sfile) values (%s, %s, %s)', $dbprefix.'skin',
			SQLString('blue', 'text'),
			SQLString('蓝色梦想', 'text'),
			SQLString('styles.css', 'text'));
		$result=mysql_query($query);
		echo '<li>写入新数据 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add skin int(10) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add photo varchar(255) default NULL", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add pupload int(5) NOT NULL default '0'", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);
		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($lfile,time());
	}else{
?>
	<div class="title">从 1.0.1 升级到 1.0.2</div>
	<div class="lcontent">
		<form method="post">
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