<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='特殊功能';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['g_open'])){
			$g_open=$_POST['g_open']!=1?0:1;
			$g_name=htmlspecialchars(trim($_POST['g_name']),ENT_QUOTES);
			$g_pwd=htmlspecialchars(trim($_POST['g_pwd']),ENT_QUOTES);
			$u_db=sprintf('update %s set g_open=%s, g_name=%s, g_pwd=%s', $dbprefix.'main',
				SQLString($g_open, 'int'),
				SQLString($g_name, 'text'),
				SQLString($g_pwd, 'text'));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['mgc']) && trim($_POST['mgc'])!=''){
			if(isset($a_mgc) && count($a_mgc)>0){
				foreach($a_mgc as $v){
					if(trim($v)!='')$at_mgc[trim($v)]=trim($v);
				}
			}
			$nmgc=$g_name=htmlspecialchars(trim($_POST['mgc']),ENT_QUOTES);
			$at_mgc[trim($nmgc)]=trim($nmgc);
			$mgc_c="<?php";
			foreach($at_mgc as $v)$mgc_c.="\r\n\$a_mgc[]='".$v."';";
			$mgc_c.="\r\n//\$a_mgc[]='';\r\n//请自行添加敏感词，一行一个\r\n?>";
			writeText($mgc_file, $mgc_c);
			$e=2;
		}elseif(isset($_POST['delmgc']) && $_POST['delmgc']==1){
			writeText($mgc_file, "<?php\r\n//\$a_mgc[]='';\r\n//请自行添加敏感词，一行一个\r\n?>");
			$e=3;
		}
		header('Location:./?m=setting&t=special'.(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'设置已修改。', '敏感词已保存。', '敏感词过滤功能已关闭。');
		$is_disa=$config['open']>0?'':' disabled="disabled"';
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">访客账号</div><div class="lcontent">基于某些特殊原因，部分主管部门或者相关机构可能会要求审查内容，在没有开放访问的情况下可以通过开启访客账号来提供给相关部门进行审查。<br/><br/>此功能只有在不开放访问时生效。<br/><br/>'.($config['open']>0?'<form method="post" action="">':'').'<table><tr><td>访客账号：</td><td><input name="g_open" type="radio" value="1"'.($config['g_open']>0?' checked="checked"':'').' rel="s_cbt" data-id="gline"'.$is_disa.' />开启 <input name="g_open" type="radio" value="0"'.($config['g_open']==0?' checked="checked"':'').' rel="h_cbt" data-id="gline"'.$is_disa.' />关闭</td></tr><tbody id="gline"'.($config['g_open']>0?'':' style="display: none;"').'><tr><td>用户名：</td><td><input name="g_name" size="15" value="'.$config['g_name'].'"'.$is_disa.'/>（不可以与现有用户的用户名一样）</td></tr><tr><td>密码：</td><td><input name="g_pwd" size="15" value="'.$config['g_pwd'].'"'.$is_disa.'/></td></tr></tbody><tr><td colspan="2"><input type="submit" value="修改" class="button"'.$is_disa.' /></td></tr></table>';
		if($config['open']>0)$content.='</form>';
		if($config['g_vdate']>0)$content.='<br/>最后使用：'.date('Y-n-j H:i', $config['g_vdate']);
		if($config['g_vc']>0)$content.='<br/>使用次数：'.$config['g_vc'];
		if($config['g_ip_i']>0){
			$ip=long2ip($config['g_ip_i']);
			$content.='<br/>最后IP：'.($config['ip']!=''?str_replace('[ip]', $ip, $config['ip']):$ip);
		}
		$content.='</div><br/><div class="title">敏感词过滤</div><div class="lcontent">基于某些特殊原因，部分服务器开启了敏感词过滤功能。遗憾的是部分过滤功能并不完善，在过滤的时候并不是过滤敏感词本身，而是把整个网页都屏蔽掉，导致无法再进行删、改等操作。在遇到这种情况的时候可以使用这一功能，将敏感词输入下面的表单增加到敏感词列表，这样程序将会在敏感词显示之前就先替换掉。<br/><br/>为了保证这个设置页面的显示，敏感词列表中的内容并不会显示，而是保存在程序安装目录下的<strong>'.$mgc_file.'</strong>中，可以通过ftp下载此文件进行修改。<br/><br/>建议管理员将此设置页面添加到收藏夹，当首页因出现敏感词被屏蔽后可以方便的访问这个页面进行设置，或者直接通过ftp下载<strong>'.$mgc_file.'</strong>进行修改。<br/><br/>';
		if(!isset($a_mgc) || count($a_mgc)==0){
			$content.='当前此功能并未开启，如需开启请直接通过下表增加敏感词。<br/><br/>';
		}else{
			$content.='当前此功能已开启，如需关闭请点击“关闭敏感词过滤”或者直接通过ftp删除<strong>'.$mgc_file.'</strong>。<form method="post" action=""><div class="formline"><input type="submit" value="关闭敏感词过滤" class="button" /><input type="hidden" name="delmgc" value="1"/></div></form><br/>增加敏感词';
		}
		$content.='<form method="post" action="" class="btform" id="mgcform"><table><tr><td>敏感词：</td><td><input name="mgc" class="bt_input" rel="敏感词" size="30"/></td></tr><tr><td colspan="2"><input type="submit" value="增加" class="button" /></td></tr></table></form></div>';
	}
}
?>