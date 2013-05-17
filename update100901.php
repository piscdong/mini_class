<?php
/////////////////////////////////////////////////////////////////////////////
// 迷你同学录 (http://www.piscdong.com/?m=mini_class)
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

function chksqlv(){
	return version_compare(mysql_get_server_info(), '4.1.0', '>=');
}
$app_n='迷你同学录';

$lname='update100901';
$lfile=$lname.'.lock';
$sql_f='sql-107.php';
$b_file='config.php';
$c_file='../'.$b_file;

if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent"><div class="title">从 1.0.7 升级到 1.1.0</div>';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(file_exists($sql_f)){
			require_once($c_file);
			$c_content="<?php\n\$hostname_conn='".$hostname_conn."';\n\$database_conn='".$database_conn."';\n\$username_conn='".$username_conn."';\n\$password_conn='".$password_conn."';\n\$dbprefix='".$dbprefix.'utf8_'."';\n\$conn=mysql_connect(\$hostname_conn, \$username_conn, \$password_conn) or die('');\nmysql_select_db(\$database_conn, \$conn);".(chksqlv()?"\nmysql_query(\"SET NAMES 'utf8'\", \$conn);\n\$charset_conn=1;":'')."\n?>";
			rename($c_file, '../gb2312-config-'.time().'.php');
			writeText($c_file,$c_content);
			echo '<script type="text/JavaScript">location.href=\''.$lname.'_m.php?n='.md5('1').'\';</script>';
		}else{
			echo '<script type="text/JavaScript">alert(\''.$sql_f.'不存在\');location.href=\''.$lname.'.php\';</script>';
		}
	}else{
?>
	<div class="lcontent">
		<form method="post">
			此升级程序只能从 <strong>1.0.7</strong> 升级到 1.1.0，版本太低的用户请先升级到 1.0.7，再使用此升级程序！<br/><br/>
			请严格按照下面的步骤进行升级：
			<ol>
				<li><a href="../?m=setting&amp;t=sql" target="_blank">备份数据库</a>，通过ftp下载备份好的文件</li>
				<li style="font-weight: bold;color: #f00;">使用EditPlus等文本工具将备份好的文件另存为utf-8格式，并重命名为“<?php echo $sql_f; ?>”</li>
				<li>将“<?php echo $sql_f; ?>”上传到“setup/”</li>
				<li>样式中如果有中文名称，请<a href="http://www.piscdong.com/?m=mini_class&amp;i=skin">下载</a>新的样式文件进行替换</li>
			</ol>
			完成以上步骤后点击“下一步”，升级过程中有任何问题都可以通过<a href="http://groups.google.com/group/mini_class/">论坛</a>，<a href="mailto:piscdong@gmail.com">Email</a>、QQ（58197283）等方式进行提问。
			<div class="formline"><input type="submit" value="下一步" id="formsubmit" class="button" /></div>
		</form>
	
<?php
	}
	echo '</div></div></div><div id="foot">&copy; '.date('Y').' '.$app_n.'<br/><a href="http://www.piscdong.com/?m=mini_class" rel="external"><img src="../images/powered.gif" alt="Powered by '.$app_n.'"/></a></div></div></body></html>';
}else{
	header('Location:../');
}
?>