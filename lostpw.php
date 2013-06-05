<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(!$c_log){
	$title.='忘记密码';
	$a_msg=array(1=>'密码已修改。', '答案错误！', '您没有设置安全问题！', '用户名错误！');
	$content.='<div class="tcontent">'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">忘记密码 - 第';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['id']) && intval($_POST['id'])>0 && isset($_POST['password']) && trim($_POST['password'])!=''){
			$password=enc_p(trim($_POST['password']));
			$answer=htmlspecialchars($_POST['answer'],ENT_QUOTES);
			$s_dbu=sprintf('select id from %s where id=%s limit 1', $dbprefix.'member', SQLString($_POST['id'], 'int'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0 && $r_dbu['answer']==$answer){
				$u_db=sprintf('update %s set password=%s where id=%s', $dbprefix.'member', SQLString($password, 'text'), $r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$e=1;
			}else{
				$e=2;
			}
			mysql_free_result($q_dbu);
			header('Location:./?m=lostpwd&e='.$e);
			exit();
		}elseif(isset($_POST['username']) && trim($_POST['username'])!=''){
			$username=trim($_POST['username']);
			$s_dbu=sprintf('select id, question, answer from %s where username=%s limit 1', $dbprefix.'member', SQLString($username, 'text'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				if($r_dbu['answer']!=''){
					$content.='2步</div><div class="lcontent"><form method="post" action="" class="btform_p" id="lwform"><table><tr><td>安全问题：</td><td>'.$r_dbu['question'].'</td></tr><tr><td>答案：</td><td><input name="answer" size="32" maxlength="20" class="bt_input" rel="答案" /></td></tr><tr><td>新密码：</td><td><input name="password" id="formpw" size="32" maxlength="20" type="password" class="bt_input" rel="新密码" /></td></tr><tr><td>确认：</td><td><input name="password1" id="formpw1" size="32" maxlength="20" type="password" /><input type="hidden" name="id" value="'.$r_dbu['id'].'"/></td></tr>';
				}else{
					header('Location:./?m=lostpw&e=3');
					exit();
				}
			}else{
				header('Location:./?m=lostpw&e=4');
				exit();
			}
			mysql_free_result($q_dbu);
		}else{
			header('Location:./');
			exit();
		}
	}else{
		$content.='1步</div><div class="lcontent"><form method="post" action=""><table><tr><td>用户名：</td><td><input name="username" size="32" maxlength="20" /></td></tr>';
	}
	$js_c.='
	$("#clbt").click(function(){
		location.href=\'./\';
	});';
	$content.='<tr><td colspan="2"><input type="submit" value="下一步" class="button" /> <input type="button" value="取消" id="clbt" class="button" /></td></tr></table></form></div></div>';
}else{
	header('Location:./');
	exit();
}
