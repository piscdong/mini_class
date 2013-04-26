<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

session_start();
require_once('../config.php');
require_once('../function.php');
require_once('function.php');
$c_log=chklog();
$menua=array('list', 'album', 'camp', 'user', 'message', 'login', 'logout');
if($c_log){
	$ar=getainfo($_SESSION[$config['u_hash']], 'name, power');
	$pa=$ar['power'];
	$pn=$ar['name'];
	$s_dbo=sprintf('select datetime from %s where aid=%s limit 1', $dbprefix.'online', $_SESSION[$config['u_hash']]);
	$q_dbo=mysql_query($s_dbo) or die('');
	$r_dbo=mysql_fetch_assoc($q_dbo);
	if(mysql_num_rows($q_dbo)>0){
		if(time()-$r_dbo['datetime']>600){
			$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $_SESSION[$config['u_hash']]);
			$result=mysql_query($u_db) or die('');
		}
		$u_db=sprintf('update %s set datetime=%s, online=1, ip_i=inet_aton(%s) where aid=%s', $dbprefix.'online', time(), SQLString(getIP(), 'text'), $_SESSION[$config['u_hash']]);
		$result=mysql_query($u_db) or die('');
	}else{
		$i_db=sprintf('insert into %s (aid, datetime, ip_i) values (%s, %s, inet_aton(%s))', $dbprefix.'online', $_SESSION[$config['u_hash']], time(), SQLString(getIP(), 'text'));
		$result=mysql_query($i_db) or die('');
	}
	mysql_free_result($q_dbo);
}elseif(isset($_COOKIE[$config['u_hash'].'_u']) && $_COOKIE[$config['u_hash'].'_u']!='' && isset($_COOKIE[$config['u_hash'].'_p']) && $_COOKIE[$config['u_hash'].'_p']!=''){
	$s_dbu=sprintf('select id, name, status, power from %s where username=%s and password=%s limit 1', $dbprefix.'member', SQLString($_COOKIE[$config['u_hash'].'_u'], 'text'), SQLString($_COOKIE[$config['u_hash'].'_p'], 'text'));
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		if($r_dbu['status']==0 || $config['veri']>0){
			$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
			$result=mysql_query($u_db) or die('');
			session_unset();
			session_start();
			$_SESSION[$config['u_hash']]=$r_dbu['id'];
			$pa=$r_dbu['power'];
			$pn=$r_dbu['name'];
			$c_log=true;
		}
	}
	mysql_free_result($q_dbu);
	if(!$c_log){
		session_unset();
		setcookie($config['u_hash'].'_u','',time());
		setcookie($config['u_hash'].'_p','',time());
	}
}
$u_db=sprintf('update %s set online=0 where %s-datetime>300', $dbprefix.'online', time());
$result=mysql_query($u_db) or die('');
$mid=(isset($_GET['m']) && in_array($_GET['m'], $menua))?$_GET['m']:$menua[0];
if($config['open']>0 && !$c_log)$mid='login';
$content='';
$title='';
$js_c='';
require_once($mid.'.php');
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=yes;"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($title!=''?$title.' | ':'').$config['title']; ?></title>
<link rel="shortcut icon" href="../favicon.ico" />
<link rel="apple-touch-icon" href="../images/iphone_logo.png" />
<link rel="apple-touch-icon" sizes="72x72" href="../images/ipad_logo.png" />
<link rel="apple-touch-icon" sizes="114x114" href="../images/iphone_retina_logo.png" />
<link rel="apple-touch-icon" sizes="144x144" href="../images/ipad_retina_logo.png" />
<style type="text/css">@import "styles.css";</style>
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../function.js"></script>
<?php
	if($js_c!='')echo '<script type="text/javascript">
$(function(){
	'.$js_c.'
});
</script>';
?>
</head>
<body>
<div class="logo"><a href="./" title="<?php echo $config['title']; ?>"><?php echo $config['title']; ?></a></div>
<?php if($config['open']==0 || $c_log){?><div class="menu"><a href="./">留言</a> | <a href="?m=album">照片</a> | <a href="?m=camp">活动</a> | <a href="?m=user">成员</a><?php
if($c_log){
	$s_dbg=sprintf('select id from %s where tid=%s and readed=1', $dbprefix.'message', $_SESSION[$config['u_hash']]);
	$q_dbg=mysql_query($s_dbg) or die('');
	$c_dbg=mysql_num_rows($q_dbg);
	mysql_free_result($q_dbg);
	echo ' | <a href="?m=message">消息</a>'.($c_dbg>0?'<span class="message_n">'.$c_dbg.'</span>':'').str_repeat('&nbsp;', 10).'<a href="?m=logout">退出</a>';
}
?></div><?php } ?>
<div class="content">
	<?php echo $content; ?>
</div>
<div class="foot">&copy; <?php
	echo date('Y', getftime()).' '.($config['classname']!=''?($config['school']!=''?$config['school'].' ':'').$config['classname']:$config['title']); ?><br/>掌上版 | <a href="../">普通版</a> <a href="http://www.piscdong.com/mini_class/" rel="external"><img src="../images/powered.gif" alt="" title="Powered by 迷你同学录"/></a></div>
</div>
<div style="display: none;"><iframe src="../sync_e.php" width="1" height="1" frameborder="0"></iframe></div>
</body>
</html>
<!-- <?php echo ((getMicrotime()-$mt)*1000); ?> ms. -->