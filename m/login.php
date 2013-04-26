<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(!$c_log){
	$title.='登录';
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
	$nct=(isset($_GET['t']) && isset($a_sync[$_GET['t']]))?$_GET['t']:'';
	if($nct!=''){
		$_SESSION[$config['u_hash'].'_m']=1;
		header('Location:../?m=login&t='.$nct);
		exit();
	}elseif(isset($_SESSION[$config['u_hash'].'_m']) && $_SESSION[$config['u_hash'].'_m']==1){
		$_SESSION[$config['u_hash'].'_m']='';
		unset($_SESSION[$config['u_hash'].'_m']);	
	}
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['username']) && trim($_POST['username'])!='' && isset($_POST['password']) && trim($_POST['password'])!=''){
			$username=trim($_POST['username']);
			$password=enc_p(trim($_POST['password']));
			$s_dbu=sprintf('select id, status, name from %s where username=%s and password=%s limit 1', $dbprefix.'member', SQLString($username, 'text'), SQLString($password, 'text'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				if($r_dbu['status']==0 || $config['veri']>0){
					$u_db=sprintf('update %s set visit=visit+1, visitdate=%s where id=%s', $dbprefix.'member', time(), $r_dbu['id']);
					$result=mysql_query($u_db) or die('');
					session_unset();
					session_start();
					$_SESSION[$config['u_hash']]=$r_dbu['id'];
					if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
						$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($_SESSION['login_sync_tn'], 'text'));
						$q_dby=mysql_query($s_dby) or die('');
						$r_dby=mysql_fetch_assoc($q_dby);
						if(mysql_num_rows($q_dby)>0){
							$u_db=sprintf('update %s set s_id=%s, s_t=%s, s_r=%s, s_s=%s, edate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($_SESSION['login_sync_id'], 'text'),
								SQLString($_SESSION['login_sync_t'], 'text'),
								SQLString($_SESSION['login_sync_r'], 'text'),
								SQLString($_SESSION['login_sync_s'], 'text'),
								SQLString($_SESSION['login_sync_edate'], 'int'),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}else{
							$i_db=sprintf('insert into %s (aid, name, s_id, s_t, s_r, s_s, edate) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
								$r_dbu['id'],
								SQLString($_SESSION['login_sync_tn'], 'text'),
								SQLString($_SESSION['login_sync_id'], 'text'),
								SQLString($_SESSION['login_sync_t'], 'text'),
								SQLString($_SESSION['login_sync_r'], 'text'),
								SQLString($_SESSION['login_sync_s'], 'text'),
								SQLString($_SESSION['login_sync_edate'], 'int'));
							$result=mysql_query($i_db) or die('');
						}
						mysql_free_result($q_dby);
						setsinfo($r_dbu['name'].' 绑定了'.$a_sync[$_SESSION['login_sync_tn']], $r_dbu['id']);
						$_SESSION['login_sync_tn']='';
						$_SESSION['login_sync_id']='';
						$_SESSION['login_sync_t']='';
						$_SESSION['login_sync_r']='';
						$_SESSION['login_sync_s']='';
						$_SESSION['login_sync_u']='';
						$_SESSION['login_sync_edate']=0;
					}
					if(isset($_POST['remember']) && $_POST['remember']=='1'){
						setcookie($config['u_hash'].'_u', $username, time()+86400*30);
						setcookie($config['u_hash'].'_p', $password, time()+86400*30);
					}else{
						setcookie($config['u_hash'].'_u','',time());
						setcookie($config['u_hash'].'_p','',time());
					}
				}else{
					$e=1;
				}
			}else{
				$e=2;
			}
			mysql_free_result($q_dbu);
		}
		if(isset($_GET['m']))$u[]='m='.$_GET['m'];
		if(isset($_GET['page']))$u[]='page='.$_GET['page'];
		if(isset($e))$u[]='e='.$e;
		header('Location:'.(isset($u)?'?'.join('&', $u):'./'));
		exit();
	}else{
		$a_msg=array(1=>'您的帐号还没有通过审核，请稍候再试。', '用户名/密码错误！');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">登录</div><div class="lcontent">';
		if(isset($_SESSION['login_sync_tn']) && $_SESSION['login_sync_tn']!='' && isset($a_sync[$_SESSION['login_sync_tn']])){
			$js_c.='
	$(\'#login_sync_clink\').click(function(){
		$.get(\'../j_loginsynce.php\');
		$(\'#login_sync_v\').slideUp(500);
	});';
			$content.='<div id="login_sync_v" class="sync_list" style="background-image: url(../images/i-'.$_SESSION['login_sync_tn'].'.gif);margin-bottom: 10px;">'.($_SESSION['login_sync_u']!=''?$_SESSION['login_sync_u'].' ':'').'目前没有账号绑定此'.$a_sync[$_SESSION['login_sync_tn']].'账号，登录后绑定 （<span id="login_sync_clink">取消</span>）</div>';
		}
		$content.='<form method="post" action="" class="btform" id="loginform">用户名：<br/><input name="username" id="formname" style="width: 90%;" maxlength="20" class="bt_input" rel="用户名" /><br/>密　码：<br/><input name="password" id="formpw" style="width: 90%;" maxlength="20" type="password" class="bt_input" rel="密码" /><br/><input name="remember" value="1" type="checkbox" title="为了确保信息安全，请不要在网吧或者公共机房选择此项！如果今后要取消此选项，只需点击“退出登录”即可。" />记住我<br/><input type="submit" value="登录" /></form></div>';
		if(isset($a_sync) && count($a_sync)>0){
			$content.='<br/><div class="title">其他账号登录</div><div class="lcontent" style="text-align: center;">';
			foreach($a_sync as $k=>$v)$content.='<a href="?m=login&amp;t='.$k.'" title="'.$v.'登录"><img src="../images/i-'.$k.'-l.gif" alt="" style="margin: 3px;"/></a><br/>';
			$content.='</div>';
		}
	}
}else{
	header('Location:./');
	exit();
}
?>