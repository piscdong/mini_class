<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(!file_exists('config.php')){
	header('Location:setup/');
}else{
	session_start();
	require_once('config.php');
	require_once('function.php');
	$c_log=chklog();
	$menua=array(
		'list'=>array(1, 0),
		'album'=>array(2, 0),
		'camp'=>array(3, 0),
		'user'=>array(4, 0),
		'profile'=>array(0, 0),
		'message'=>array(0, 0),
		'setting'=>array(0, 0),
		'edituser'=>array(0, 0),
		'login'=>array(0, 0),
		'logout'=>array(0, 1),
		'reg'=>array(0, 1),
		'lostpw'=>array(0, 1)
	);
	if(!$c_log && isset($_COOKIE[$config['u_hash'].'_u']) && $_COOKIE[$config['u_hash'].'_u']!='' && isset($_COOKIE[$config['u_hash'].'_p']) && $_COOKIE[$config['u_hash'].'_p']!=''){
		$s_dbu=sprintf('select * from %s where username=%s and password=%s limit 1', $dbprefix.'member', SQLString($_COOKIE[$config['u_hash'].'_u'], 'text'), SQLString($_COOKIE[$config['u_hash'].'_p'], 'text'));
		$q_dbu=mysql_query($s_dbu) or die('');
		$r_dbu=mysql_fetch_assoc($q_dbu);
		if(mysql_num_rows($q_dbu)>0){
			if($r_dbu['status']==0 || $config['veri']>0){
				$ar=$r_dbu;
				$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$_SESSION[$config['u_hash']]=$r_dbu['id'];
				$c_log=true;
			}
		}
		mysql_free_result($q_dbu);
		if(!$c_log){
			$_SESSION[$config['u_hash']]=0;
			unset($_SESSION[$config['u_hash']]);
			session_unset();
			setcookie($config['u_hash'].'_u','',time());
			setcookie($config['u_hash'].'_p','',time());
		}
	}
	if($c_log){
		if(!isset($ar))$ar=getainfo($_SESSION[$config['u_hash']]);
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
	}
	$u_db=sprintf('update %s set online=0 where %s-datetime>300', $dbprefix.'online', time());
	$result=mysql_query($u_db) or die('');
	$u_db=sprintf('update %s set disp=1 where %s-lasttime>86400 and tid=1', $dbprefix.'topic', time());
	$result=mysql_query($u_db) or die('');
	$mid=(isset($_GET['m']) && isset($menua[$_GET['m']]))?$_GET['m']:'list';
	if($config['open']>0 && !$c_log && $menua[$mid][1]==0)$mid='login';
	$content='';
	$title='';
	$js_c='';
	$skin_a[0][]='styles.css';
	$s_dbk=sprintf('select * from %s', $dbprefix.'skin');
	$q_dbk=mysql_query($s_dbk) or die('');
	$r_dbk=mysql_fetch_assoc($q_dbk);
	if(mysql_num_rows($q_dbk)>0){
		do{
			$skin_a[$r_dbk['id']][]='skin/'.$r_dbk['path'].'/'.$r_dbk['sfile'];
			$skin_a[$r_dbk['id']][]=$r_dbk;
		}while($r_dbk=mysql_fetch_assoc($q_dbk));
	}
	mysql_free_result($q_dbk);
	$dsid=isset($skin_a[$config['skin']])?$config['skin']:0;
	$sid=(isset($_COOKIE['minic_skin']) && isset($skin_a[$_COOKIE['minic_skin']]))?$_COOKIE['minic_skin']:$dsid;
	if(file_exists($mgc_file))require_once($mgc_file);
	require_once($mid.'.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($title!=''?$title.' | ':'').$config['title']; ?></title>
<meta name="msapplication-tooltip" content="<?php echo $config['title']; ?>" />
<meta name="msapplication-task" content="name=班级留言; action-uri=./; icon-uri=favicon.ico" />
<meta name="msapplication-task" content="name=照片视频; action-uri=./?m=album; icon-uri=favicon.ico" />
<meta name="msapplication-task" content="name=班级活动; action-uri=./?m=camp; icon-uri=favicon.ico" />
<meta name="msapplication-task" content="name=班级成员; action-uri=./?m=user; icon-uri=favicon.ico" />
<meta name="msapplication-task" content="name=迷你同学录; action-uri=http://www.piscdong.com/mini_class/; icon-uri=http://www.piscdong.com/mini_class/favicon.ico" />
<meta name="msapplication-navbutton-color" content="#ffcc77" />
<meta name="msapplication-window" content="width=device-width;height=device-height" />
<meta name="msapplication-starturl" content="./" />
<link rel="shortcut icon" href="favicon.ico" />
<link rel="apple-touch-icon" href="images/iphone_logo.png" />
<link rel="apple-touch-icon" sizes="72x72" href="images/ipad_logo.png" />
<link rel="apple-touch-icon" sizes="114x114" href="images/iphone_retina_logo.png" />
<link rel="apple-touch-icon" sizes="144x144" href="images/ipad_retina_logo.png" />
<link rel="stylesheet" type="text/css" href="<?php echo $skin_a[$sid][0]; ?>" />
<style type="text/css">@import "default.css";</style>
<script type="text/javascript" src="lib/jquery.js"></script>
<script type="text/javascript" src="function.js"></script>
<script type="text/javascript">
$(function(){
	<?php if($js_c!='')echo $js_c; ?>

	$.get('sync_p.php');
});
</script>
</head>
<body>
<div id="chat_div"></div>
<div id="body">
	<div id="top">
		<div id="logo">
			<a href="./" title="<?php echo $config['title']; ?>"><?php echo $config['title']; ?></a>
		</div><?php if($config['open']==0 || $c_log){?>
		<div id="menu"><a href="./" title="班级留言"<?php if($menua[$mid][0]==1)echo ' id="mn"'; ?>>班级留言</a> | <a href="?m=album" title="班级相册"<?php if($menua[$mid][0]==2)echo ' id="mn"'; ?>>照片视频</a> | <a href="?m=camp" title="班级活动"<?php if($menua[$mid][0]==3)echo ' id="mn"'; ?>>班级活动</a> | <a href="?m=user" title="班级成员"<?php if($menua[$mid][0]==4)echo ' id="mn"'; ?>>班级成员</a> | <a href="?m=user&amp;v=1" title="访问记录"<?php if($menua[$mid][0]==5)echo ' id="mn"'; ?>>访问记录</a></div><?php } ?>
	</div>
	<div id="main">
		<?php
	if(isset($a_mgc)){
		foreach($a_mgc as $v)$content=str_replace($v, '[敏感词]', $content);
	}
	echo $content;
?>

		<div class="extr"></div>
	</div>
	<div id="foot">&copy; <?php
	echo date('Y', getftime()).' '.($config['classname']!=''?($config['school']!=''?$config['school'].' ':'').$config['classname']:$config['title']); ?> | <a href="m/">掌上版</a><br/><a href="http://www.piscdong.com/mini_class/" rel="external"><img src="images/powered.gif" alt="" title="Powered by 迷你同学录"/></a><?php if(($menua[$mid][0]==1 || $mid=='login') && $config['icp']!='')echo '<br/><br/><a href="http://www.miibeian.gov.cn/" rel="nofollow">'.$config['icp'].'</a>';
	if($c_log)echo '<input type="hidden" id="login_date" value="'.time().'"/><div id="chat_p_div" style="display: none;"><input id="chat_p_input"/></div>';
?></div>
</div>
<div style="display: none;"><iframe src="sync_e.php" width="1" height="1" frameborder="0"></iframe></div>
</body>
</html>
<!-- <?php echo ((getMicrotime()-$mt)*1000); ?> ms. --><?php } ?>