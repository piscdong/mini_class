<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(!$c_log){
	if($config['invnreg']==0 && isset($_GET['c']) && trim($_GET['c'])!=''){
		$code=htmlspecialchars(trim($_GET['c']),ENT_QUOTES);
		$s_dbi=sprintf('select a.id, a.aid, a.code, b.name from %s as a, %s as b where a.code=%s and a.jid=0 and a.aid=b.id limit 1', $dbprefix.'invite', $dbprefix.'member', SQLString($code, 'text'));
		$q_dbi=mysql_query($s_dbi) or die('');
		$r_dbi=mysql_fetch_assoc($q_dbi);
		if(mysql_num_rows($q_dbi)>0)$idb=$r_dbi;
		mysql_free_result($q_dbi);
	}
	if($config['openreg']==0 || isset($idb)){
		$title.='加入本班';
		if($config['gid']!='')$g_c=explode('|', $config['gid']);
		if($config['is_qq']>0 && $config['qq_app_id']!='' && $config['qq_app_key']!='')$a_sync['qq']='QQ';
		if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!='')$a_sync['sina']='新浪微博';
		if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!='')))$a_sync['tqq']='腾讯微博';
		if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!='')$a_sync['renren']='人人网';
		if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!='')$a_sync['kx001']='开心网';
		if($config['is_baidu']>0 && $config['baidu_key']!='' && $config['baidu_se']!='')$a_sync['baidu']='百度';
		if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!='')$a_sync['douban']='豆瓣';
		if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!='')$a_sync['google']='Google';
		if($config['is_live']>0 && $config['live_key']!='' && $config['live_se']!='')$a_sync['live']='Microsoft';
		if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!='')$a_sync['t163']='网易微博';
		if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!='')))$a_sync['tsohu']='搜狐微博';
		if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!='')$a_sync['twitter']='Twitter';
		if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!='')$a_sync['facebook']='Facebook';
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(isset($_POST['username']) && trim($_POST['username'])!='' && isset($_POST['password']) && trim($_POST['password'])!='' && isset($_POST['name']) && trim($_POST['name'])!=''){
				$username=trim($_POST['username']);
				$password=enc_p(trim($_POST['password']));
				$name=htmlspecialchars(trim($_POST['name']),ENT_QUOTES);
				$status=$config['veri']>0?0:1;
				$gid=(isset($_POST['gid']) && isset($g_c) && in_array($_POST['gid'], $g_c) && isset($g_a[$_POST['gid']]))?$_POST['gid']:0;
				$jaid=isset($idb)?$idb['aid']:0;
				$rela=isset($_POST['rela'])?htmlspecialchars(trim($_POST['rela']),ENT_QUOTES):'';
				$email=htmlspecialchars(trim($_POST['email']),ENT_QUOTES);
				$s_dbu=sprintf('select id from %s where username=%s limit 1', $dbprefix.'member', SQLString($username, 'text'));
				$q_dbu=mysql_query($s_dbu) or die('');
				if(mysql_num_rows($q_dbu)>0){
					$e=1;
				}else{
					$i_db=sprintf('insert into %s (username, password, name, status, regdate, gid, jaid, rela, email) values (%s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbprefix.'member',
						SQLString($username, 'text'),
						SQLString($password, 'text'),
						SQLString($name, 'text'),
						SQLString($status, 'int'),
						time(),
						SQLString($gid, 'int'),
						SQLString($jaid, 'int'),
						SQLString($rela, 'text'),
						SQLString($email, 'text'));
					$result=mysql_query($i_db) or die('');
					$nid=mysql_insert_id();
					$i_db=sprintf('insert into %s (aid, datetime, ip_i, online) values (%s, %s, inet_aton(%s), 0)', $dbprefix.'online', $nid, time(), SQLString(getIP(), 'text'));
					$result=mysql_query($i_db) or die('');
					setsinfo($name.' 新用户注册'.(isset($g_a[$gid])?'，身份：'.$g_a[$gid]:'').(isset($idb)?'，邀请人：<a href="?m=user&amp;id='.$idb['aid'].'">'.$idb['name'].'</a>':'').($config['veri']>0?'':'，等待审核').($rela!=''?"\r\r".$rela:''), $nid);
					if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
						$i_db=sprintf('insert into %s (aid, name, s_id, s_t, s_r, s_s, edate) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
							$nid,
							SQLString($_SESSION['login_sync_tn'], 'text'),
							SQLString($_SESSION['login_sync_id'], 'text'),
							SQLString($_SESSION['login_sync_t'], 'text'),
							SQLString($_SESSION['login_sync_r'], 'text'),
							SQLString($_SESSION['login_sync_s'], 'text'),
							SQLString($_SESSION['login_sync_edate'], 'int'));
						$result=mysql_query($i_db) or die('');
						$_SESSION['login_sync_tn']='';
						$_SESSION['login_sync_id']='';
						$_SESSION['login_sync_t']='';
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='';
						$_SESSION['login_sync_edate']=0;
					}
					if(isset($idb)){
						$u_db=sprintf('update %s set jid=%s where id=%s', $dbprefix.'invite',
							$nid,
							$idb['id']);
						$result=mysql_query($u_db) or die('');
					}
					header('Location:./?m=login&e=3');
					exit();
				}
				mysql_free_result($q_dbu);
			}
			header('Location:./?m=reg'.(isset($e)?'&e=1':'').(isset($idb)?'&c='.$idb['code']:''));
			exit();
		}else{
			$a_msg=array(1=>'请使用其他的用户名！');
			$js_c.='
	$("#clbt").click(function(){
		location.href=\'./\';
	});';
			$content.='<div class="tcontent">'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">加入本班</div><div class="lcontent">';
			if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
				$js_c.='
	$(\'#login_sync_clink\').click(function(){
		$.get(\'j_loginsynce.php\');
		$(\'#login_sync_v\').slideUp(500);
	});';
				$content.='<div id="login_sync_v" class="sync_list" style="background-image: url(images/i-'.$_SESSION['login_sync_tn'].'.gif);margin-bottom: 10px;">目前没有账号绑定此'.$a_sync[$_SESSION['login_sync_tn']].'账号'.($_SESSION['login_sync_u']!=''?'：'.$_SESSION['login_sync_u']:'').'，注册后绑定 <span class="mlink f_link" id="login_sync_clink">取消</span></div>';
			}
			$content.='<form method="post" action="" class="btform_p" id="regform"><table><tr><td>用户名：</td><td><input name="username" size="32" maxlength="20" class="bt_input" rel="用户名" /></td></tr><tr><td>密码：</td><td><input name="password" id="formpw" size="32" maxlength="20" type="password" class="bt_input" rel="密码" /></td></tr><tr><td>确认：</td><td><input name="password1" id="formpw1" size="32" maxlength="20" type="password" /></td></tr><tr><td colspan="2"><br/>'.($config['veri']==0?'为了尽快得到同学的批准，':'').'请完善您的个人资料</td></tr><tr><td>真实姓名：</td><td><input name="name" size="32" maxlength="20" class="bt_input" rel="真实姓名" /></td></tr>';
			if(isset($g_c) && isset($g_a) && count($g_a)>1){
				$content.='<tr><td>加入身份：</td><td><select name="gid">';
				foreach($g_a as $k=>$v){
					if(in_array($k, $g_c) || $k==0)$content.='<option value="'.$k.'">'.$v.'</option>';
				}
				$content.='</select></td></tr>';
			}
			$content.='<tr><td>介绍：</td><td><input name="rela" size="32"'.(isset($idb)?' value="大家好，我是'.$idb['name'].'的朋友"':' title="例：大家好，我是张三的老婆啊！"').' /></td></tr><tr><td>电子邮件：</td><td><input email="email" size="32" maxlength="20" /></td></tr><tr><td colspan="2"><input type="submit" value="注册" class="button" /> <input type="button" value="取消" id="clbt" class="button" /></td></tr></table></form></div></div>';
		}
	}else{
		header('Location:./');
		exit();
	}
}else{
	header('Location:./');
	exit();
}
