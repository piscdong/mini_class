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
	$vdb=$config['veri']>0?'':' where status=0';
	$s_dbu=sprintf('select name, gender, bir_m, bir_d, bir_y, url, email, phone, work, tel, qq, msn, gtalk, address, location from %s%s', $dbprefix.'member', $vdb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		header('Content-Disposition:application/vnd.ms-excel; filename=user.xls');
		header('Content-Type:application/vnd.ms-excel;charset=UTF-8');
		echo '<?xml version="1.0" encoding="utf-8"
<?mso-application progid="Excel.Sheet"
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"><Styles><Style ss:ID="Default"><Font ss:Size="12"/></Style><Style ss:ID="stitle"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:Size="16" ss:Bold="1"/></Style><Style ss:ID="smenu"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:Size="11" ss:Bold="1"/></Style></Styles><Worksheet ss:Name="user"><Table><Row><Cell ss:MergeAcross="12" ss:StyleID="stitle"><Data ss:Type="String">'.$config['title'].'：通讯录</Data></Cell></Row><Row ss:StyleID="smenu"><Cell><Data ss:Type="String">姓名</Data></Cell><Cell><Data ss:Type="String">性别</Data></Cell><Cell><Data ss:Type="String">生日</Data></Cell><Cell><Data ss:Type="String">主页</Data></Cell><Cell><Data ss:Type="String">电子邮件</Data></Cell><Cell><Data ss:Type="String">手机</Data></Cell><Cell><Data ss:Type="String">工作单位</Data></Cell><Cell><Data ss:Type="String">联系电话</Data></Cell><Cell><Data ss:Type="String">QQ</Data></Cell><Cell><Data ss:Type="String">MSN</Data></Cell><Cell><Data ss:Type="String">Google Talk</Data></Cell><Cell><Data ss:Type="String">住址</Data></Cell><Cell><Data ss:Type="String">籍贯</Data></Cell></Row>';
		do{
			echo '<Row><Cell><Data ss:Type="String">'.$r_dbu['name'].'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['gender']>0?($r_dbu['gender']==1?'男':'女'):' ').'</Data></Cell><Cell><Data ss:Type="String">'.(($r_dbu['bir_m']>0 && $r_dbu['bir_d']>0)?($r_dbu['bir_y']>0?$r_dbu['bir_y'].'-':'').$r_dbu['bir_m'].'-'.$r_dbu['bir_d']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['url']!=''?$r_dbu['url']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['email']!=''?$r_dbu['email']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['phone']!=''?$r_dbu['phone']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['work']!=''?$r_dbu['work']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['tel']!=''?$r_dbu['tel']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['qq']!=''?$r_dbu['qq']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['msn']!=''?$r_dbu['msn']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['gtalk']!=''?$r_dbu['gtalk']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['address']!=''?$r_dbu['address']:' ').'</Data></Cell><Cell><Data ss:Type="String">'.($r_dbu['location']!=''?$r_dbu['location']:' ').'</Data></Cell></Row>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
		echo '</Table></Worksheet></Workbook>';
	}
	mysql_free_result($q_dbu);
}
