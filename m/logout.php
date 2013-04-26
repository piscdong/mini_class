<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$u_db=sprintf('update %s set online=0 where aid=%s', $dbprefix.'online', $_SESSION[$config['u_hash']]);
$result=mysql_query($u_db) or die('');
session_start();
session_unset();
setcookie($config['u_hash'].'_u','',time());
setcookie($config['u_hash'].'_p','',time());
header('Location:'.(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'./'));
exit();
?>