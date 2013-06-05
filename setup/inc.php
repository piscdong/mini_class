<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

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
			$c=($c!='')?"'".str_replace("'", '&#039;', $c)."'":'NULL';
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

function enc_p($c){
	return md5(md5($c));
}

function getstop($i=1){
	global $app_n;
	return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>安装 '.$app_n.' - 第'.$i.'步</title><link rel="stylesheet" type="text/css" href="../styles.css" /><link rel="stylesheet" type="text/css" href="../default.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
}

function getsfoot(){
	global $app_n;
	return '</div></div><div id="foot">&copy; '.date('Y').' '.$app_n.'<br/><a href="http://www.piscdong.com/mini_class/" rel="external"><img src="../images/powered.gif" alt="Powered by '.$app_n.'"/></a></div></div></body></html>';
}

function chksqlv(){
	return version_compare(mysql_get_server_info(), '4.1.0', '>=');
}

$app_n='迷你同学录';

$b_file='config.php';
$c_file='../'.$b_file;
$l_file='setup.lock';
