<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && isset($r_dbu)){
	$title.='安全设置';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['question'])){
			if(enc_p($_POST['password0'])==$r_dbu['password']){
				$question=htmlspecialchars($_POST['question'],ENT_QUOTES);
				$answer=htmlspecialchars($_POST['answer'],ENT_QUOTES);
				$u_db=sprintf('update %s set question=%s, answer=%s where id=%s', $dbprefix.'member',
					SQLString($question, 'text'),
					SQLString($answer, 'text'),
					$r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$e=1;
			}else{
				$e=2;
			}
		}
		header('Location:./?m=profile&t=security'.(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'个人资料已修改。', '当前密码错误！');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">安全设置</div><div class="lcontent"><form method="post" action="" class="btform" id="seform"><table><tr><td>当前密码：</td><td><input type="password" name="password0" size="32" class="bt_input" rel="当前密码" /></td></tr><tr><td>安全问题：</td><td><input name="question" size="32" value="'.$r_dbu['question'].'" /></td></tr><tr><td>答案：</td><td><input name="answer" size="32" /></td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /></td></tr></table></form></div>';
	}
}
