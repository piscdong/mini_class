<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

require_once('inc.php');
if(!file_exists($l_file)){
	echo getstop();
	if(!file_exists($c_file)){
		if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['hostname']!='' && $_POST['database']!='' && $_POST['username']!=''){
			if(@mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password'])){
				if(mysql_select_db($_POST['database']) || mysql_query('CREATE DATABASE '.$_POST['database'])){
					$c_content="<?php\n\$hostname_conn='".$_POST['hostname']."';\n\$database_conn='".$_POST['database']."';\n\$username_conn='".$_POST['username']."';\n\$password_conn='".$_POST['password']."';\n\$dbprefix='".$_POST['dbprefix']."';\n\$conn=mysql_connect(\$hostname_conn, \$username_conn, \$password_conn) or die('');\nmysql_select_db(\$database_conn, \$conn);".(chksqlv()?"\nmysql_query(\"SET NAMES 'utf8'\", \$conn);\n\$charset_conn=1;":'')."\n?>";
					writeText($c_file,$c_content);
				}else{
					$e=1;
				}
			}else{
				$e=2;
			}
			echo '<script type="text/javascript">'.(isset($e)?'location.href=\'?e='.$e:'location.href=\'mysql.php').'\';</script>';
		}else{
			if(isset($_GET['e']))$msg=$_GET['e']==2?'无法连接数据库！':'无法建立数据库！';
			if(isset($msg))echo '<div class="msg_v">'.$msg.'</div>';
?>
	<div class="title">第1步：配置MySQL</div>
	<div class="lcontent">
		<form method="post" onsubmit="if(document.form1.hostname.value=='' || document.form1.database.value=='' || document.form1.username.value==''){alert('请输入主机名、数据库名、用户名。');return false;}" name="form1">
			<table>
				<tr><td>主机名：</td><td><input name="hostname" size="32" value="localhost"/></td></tr>
				<tr><td>数据库名：</td><td><input name="database" size="32"/></td></tr>
				<tr><td>用户名：</td><td><input name="username" size="32" value="root"/></td></tr>
				<tr><td>密码：</td><td><input name="password" size="32"/></td></tr>
				<tr><td>表前缀：</td><td><input name="dbprefix" size="32" value="alu_"/></td></tr>
				<tr><td colspan="2"><input type="submit" value="下一步" class="button" /> <input type="reset" value="重置" class="button" /></td></tr>
			</table>
		</form>
	</div>
<?php
		}
	}else{
		echo '<div class="gcontent">要安装'.$app_n.'，请先删除“'.$b_file.'”。</div>';
	}
	echo getsfoot();
}else{
	header('Location:../');
}
?>