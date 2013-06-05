<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

session_start();
require_once('config.php');
require_once('function.php');
if(chklog()){
	$odb[]=$config['veri']==0?'status=0':'';
	if(isset($_GET['id']) && intval($_GET['id'])>0)$odb[]='id='.intval($_GET['id']);
	$vdb=(isset($odb) && count($odb)>0)?' where '.join(' and ', $odb):'';
	$s_dbu=sprintf('select name, phone, tel, address, work, url, email from %s%s', $dbprefix.'member', $vdb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		header('Content-Disposition:text/vcf; filename=user.vcf');
		header('Content-Type:text/vcf;charset=UTF-8');
		do{
			echo "BEGIN:VCARD\r\nVERSION:2.1\r\nN:;".$r_dbu['name']."\r\nFN:".$r_dbu['name'].($r_dbu['phone']!=''?"\r\nTEL;CELL;VOICE:".$r_dbu['phone']:'').($r_dbu['tel']!=''?"\r\nTEL;HOME;VOICE:".$r_dbu['tel']:'').($r_dbu['address']!=''?"\r\nLABEL;HOME:".$r_dbu['address']:'').($r_dbu['work']!=''?"\r\nORG:".$r_dbu['work']:'').($r_dbu['url']!=''?"\r\nURL;HOME:".$r_dbu['url']:'').($r_dbu['email']!=''?"\r\nEMAIL;PREF;INTERNET:".$r_dbu['email']:'')."\r\nEND:VCARD\r\n";
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
	}
	mysql_free_result($q_dbu);
}
