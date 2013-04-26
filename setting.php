<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$m_a=array('sync', 'link', 'skin', 'file', 'adminop', 'sendmsg', 'sql', 'special');
if($c_log && $pa==9){
	$content.='<div class="rcontent"><div class="content">';
	if(isset($_GET['t']) && in_array($_GET['t'], $m_a)){
		require_once('s_'.$_GET['t'].'.php');
	}else{
		$title.='班级设置';
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(isset($_POST['title']) && trim($_POST['title'])!=''){
				$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
				$school=htmlspecialchars(trim($_POST['school']),ENT_QUOTES);
				$classname=htmlspecialchars(trim($_POST['classname']),ENT_QUOTES);
				$open=$_POST['open']!=1?0:1;
				$openreg=$_POST['openreg']!=1?0:1;
				$invreg=$_POST['invreg']!=1?0:1;
				$email=$_POST['email'];
				$smtp_server=htmlspecialchars(trim($_POST['smtp_server']),ENT_QUOTES);
				$smtp_port=htmlspecialchars(trim($_POST['smtp_port']),ENT_QUOTES);
				$smtp_email=htmlspecialchars(trim($_POST['smtp_email']),ENT_QUOTES);
				$smtp_isa=(isset($_POST['smtp_isa']) && $_POST['smtp_isa']==1)?1:0;
				$smtp_user=htmlspecialchars(trim($_POST['smtp_user']),ENT_QUOTES);
				$smtp_pwd=htmlspecialchars(trim($_POST['smtp_pwd']),ENT_QUOTES);
				$upload=$_POST['upload']!=1?0:1;
				$thum=$_POST['thum']!=1?0:1;
				$maxsize=intval($_POST['maxsize'])>0?intval($_POST['maxsize']):0;
				$filetype=htmlspecialchars(trim($_POST['filetype']),ENT_QUOTES);
				if($_POST['avator_r']>0){
					$avator=intval($_POST['avator_i'])>1?intval($_POST['avator_i']):2;
				}else{
					$avator=0;
				}
				$slink=$_POST['slink']!=1?0:1;
				$veri=$_POST['veri']!=1?0:1;
				$icp=htmlspecialchars(trim($_POST['icp']),ENT_QUOTES);
				$pagesize=intval($_POST['pagesize'])>0?intval($_POST['pagesize']):20;
				$gid=(isset($_POST['group']) && count($_POST['group']))?join('|', $_POST['group']):'';
				$timefix=intval($_POST['timefix']);
				$ip=trim($_POST['ip']);
				$u_db=sprintf('update %s set title=%s, school=%s, classname=%s, open=%s, openreg=%s, invreg=%s, email=%s, smtp_server=%s, smtp_port=%s, smtp_email=%s, smtp_isa=%s, smtp_user=%s, smtp_pwd=%s, upload=%s, thum=%s, maxsize=%s, filetype=%s, avator=%s, slink=%s, veri=%s, icp=%s, pagesize=%s, gid=%s, timefix=\'%s\', ip=%s', $dbprefix.'main',
					SQLString($title, 'text'),
					SQLString($school, 'text'),
					SQLString($classname, 'text'),
					$open,
					$openreg,
					$invreg,
					SQLString($email, 'int'),
					SQLString($smtp_server, 'text'),
					SQLString($smtp_port, 'text'),
					SQLString($smtp_email, 'text'),
					$smtp_isa,
					SQLString($smtp_user, 'text'),
					SQLString($smtp_pwd, 'text'),
					$upload,
					$thum,
					$maxsize,
					SQLString($filetype, 'text'),
					$avator,
					$slink,
					$veri,
					SQLString($icp, 'text'),
					$pagesize,
					SQLString($gid, 'text'),
					$timefix,
					SQLString($ip, 'text'));
				$result=mysql_query($u_db) or die('');
				$e=1;
			}
			header('Location:./?m=setting'.(isset($e)?'&e=1':''));
			exit();
		}else{
			require_once('lib/smtp.php');
			smail('piscdong@gmail.com', '['.$config['title'].']您的注册已通过审核', time().'abcd');
			$a_msg=array(1=>'设置已修改。');
			$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">班级设置</div><div class="lcontent"><form method="post" action="" class="btform" id="stform"><table><tr><td>标题：</td><td><input name="title" size="32" value="'.$config['title'].'" class="bt_input" rel="标题" /></td></tr><tr><td>学校：</td><td><input name="school" size="32" value="'.$config['school'].'" /></td></tr><tr><td>班级：</td><td><input name="classname" size="32" value="'.$config['classname'].'" /></td></tr><tr><td>开放访问：</td><td><input name="open" type="radio" value="0"'.($config['open']==0?' checked="checked"':'').' />是 <input name="open" type="radio" value="1"'.($config['open']==1?' checked="checked"':'').' />否</td></tr><tr><td>开放注册：</td><td><input name="openreg" type="radio" value="0"'.($config['openreg']==0?' checked="checked"':'').' />是 <input name="openreg" type="radio" value="1"'.($config['openreg']==1?' checked="checked"':'').' />否</td></tr><tr><td>邀请注册：</td><td><input name="invreg" type="radio" value="0"'.($config['invreg']==0?' checked="checked"':'').' />是 <input name="invreg" type="radio" value="1"'.($config['invreg']==1?' checked="checked"':'').' />否</td></tr>';
			if(isset($g_a) && count($g_a)>0){
				if($config['gid']!='')$g_c=explode('|', $config['gid']);
				$content.='<tr><td>用户组：</td><td>';
				foreach($g_a as $k=>$v)$content.='<input type="checkbox" name="group[]" value="'.$k.'"'.(((isset($g_c) && in_array($k, $g_c)) || $k==0)?' checked="checked"':'').($k==0?' disabled="disabled"':'').'/>'.$v.' ';
				$content.='</td></tr>';
			}
			$content.='<tr><td>注册审核：</td><td><input name="veri" type="radio" value="0"'.($config['veri']==0?' checked="checked"':'').' />是 <input name="veri" type="radio" value="1"'.($config['veri']==1?' checked="checked"':'').' />否</td></tr><tr><td>邮件通知：</td><td><input name="email" type="radio" rel="h_cbt" data-id="smtp_line" value="1"'.($config['email']==1?' checked="checked"':'').' />否 <input name="email" type="radio" rel="h_cbt" data-id="smtp_line" value="0"'.($config['email']==0?' checked="checked"':'').' title="PHP 需要已安装且正在运行的邮件系统，并已在 php.ini 中的完成配置" />使用邮件函数 <input name="email" type="radio" rel="s_cbt" data-id="smtp_line" value="2"'.($config['email']==2?' checked="checked"':'').' />使用SMTP</td></tr><tbody id="smtp_line"'.($config['email']==2?'':' style="display: none;"').'><tr class="altbg2"><td>　SMTP服务器：</td><td><input name="smtp_server" size="32" value="'.$config['smtp_server'].'"/></td></tr><tr class="altbg1"><td>　SMTP端口：</td><td><input name="smtp_port" size="32" value="'.$config['smtp_port'].'"/></td></tr><tr class="altbg2"><td>　邮箱：</td><td><input name="smtp_email" size="32" value="'.$config['smtp_email'].'"/></td></tr><tr class="altbg1"><td></td><td><input type="checkbox" name="smtp_isa" value="1"'.($config['smtp_isa']>0?' checked="checked"':'').'/>需要身份验证</td></tr><tr class="altbg2"><td>　用户名：</td><td><input name="smtp_user" size="32" value="'.$config['smtp_user'].'"/></td></tr><tr class="altbg1"><td>　密码：</td><td><input name="smtp_pwd" size="32" type="password" value="'.$config['smtp_pwd'].'"/></td></tr></tbody><tr><td>图片防盗链：</td><td><input name="slink" type="radio" value="0"'.($config['slink']==0?' checked="checked"':'').' />开启 <input name="slink" type="radio" value="1"'.($config['slink']==1?' checked="checked"':'').' />关闭</td></tr><tr><td>随机头像：</td><td><input name="avator_r" type="radio" value="1"'.($config['avator']>0?' checked="checked"':'').' rel="s_cbt" data-id="vline" />开启 <input name="avator_r" type="radio" value="0"'.($config['avator']==0?' checked="checked"':'').' rel="h_cbt" data-id="vline" />关闭</td></tr><tr id="vline"'.($config['avator']>0?'':' style="display: none;"').'><td>头像上限：</td><td><input name="avator_i" size="32" value="'.($config['avator']>0?$config['avator']:'2').'" />个</td></tr><tr><td>上传文件：</td><td><input name="upload" type="radio" value="0" rel="s_cbt" data-id="uline"'.($config['upload']==0?' checked="checked"':'').' />是 <input name="upload" type="radio" value="1" rel="h_cbt" data-id="uline"'.($config['upload']==1?' checked="checked"':'').' />否</td></tr><tbody id="uline"'.($config['upload']>0?' style="display: none;"':'').'><tr><td>缩略图：</td><td><input name="thum" type="radio" value="0"'.($config['thum']==0?' checked="checked"':'').' />开启 <input name="thum" type="radio" value="1"'.($config['thum']==1?' checked="checked"':'').' />关闭</td></tr><tr><td>最大上传：</td><td><input name="maxsize" size="32" value="'.$config['maxsize'].'" />K（0或空为不限制）</td></tr><tr><td>允许类型：</td><td><input name="filetype" size="32" value="'.$config['filetype'].'" />（用,分隔）</td></tr></tbody><tr><td>每页留言：</td><td><input name="pagesize" size="32" value="'.$config['pagesize'].'" /></td></tr><tr><td>时间修正：</td><td><input name="timefix" size="8" value="'.$config['timefix'].'" />（秒，实际显示时间与服务器系统时间相差的秒数）</td></tr><tr><td>　系统时间：</td><td><input size="20" value="'.date('Y-n-j H:i').'" disabled="disabled" />（服务器的系统时间）</td></tr><tr><td>　显示时间：</td><td><input size="20" value="'.date('Y-n-j H:i', getftime()).'" disabled="disabled" />（当前显示的时间）</td></tr><tr><td>备案编号：</td><td><input name="icp" size="32" value="'.$config['icp'].'" /></td></tr><tr><td>IP显示：</td><td><input name="ip" size="32" value="'.htmlspecialchars($config['ip'],ENT_QUOTES).'" />（允许html，“[ip]”代表IP地址）</td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /></td></tr></table></form></div><br/><div class="title">程序升级</div><div class="lcontent" id="chkupdate_div">检查新版本，请稍后……</div>';
			$js_c.='
	$("#chkupdate_div").load(\'j_update.php\');';
		}
	}
	$content.='</div></div><div class="lmenu"><ul><li>欢迎您，'.$pn.'<ol><li><a href="?m=setting">班级设置</a></li><li><a href="?m=setting&amp;t=sync">绑定设置</a></li><li><a href="?m=setting&amp;t=link">链接管理</a></li><li><a href="?m=setting&amp;t=skin">样式管理</a></li><li><a href="?m=setting&amp;t=file">上传文件管理</a></li><li><a href="?m=setting&amp;t=adminop">管理记录</a></li><li><a href="?m=setting&amp;t=sendmsg">短消息群发</a></li><li><a href="?m=setting&amp;t=sql">数据库管理</a></li><li><a href="?m=setting&amp;t=special">特殊功能</a></li></ol></li></ul></div>';
}else{
	header('Location:./');
	exit();
}
?>