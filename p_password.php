<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && isset($r_dbu)){
	$title.='修改密码';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['password']) && $_POST['password']!=''){
			if(enc_p($_POST['password0'])==$r_dbu['password']){
				$u_db=sprintf('update %s set password=%s where id=%s', $dbprefix.'member',
					SQLString(enc_p($_POST['password']), 'text'),
					$r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$e=1;
			}else{
				$e=2;
			}
		}
		header('Location:./?m=profile&t=password'.(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'密码已修改。', '当前密码错误！');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">修改密码</div><div class="lcontent"><form method="post" action="" class="btform_p" id="seform"><table><tr><td>当前密码：</td><td><input type="password" name="password0" size="32" class="bt_input" rel="当前密码" /></td></tr><tr><td>新密码：</td><td><input type="password" name="password" id="formpw" size="32" class="bt_input" rel="新密码" /></td></tr><tr><td>确认：</td><td><input type="password" name="password1" id="formpw1" size="32" /></td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /></td></tr></table></form></div>';
	}
}
