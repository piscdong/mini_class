<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='短消息群发';
	$a_msg=array(1=>'短消息已发送。');
	$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">短消息群发</div>';
	$vdb=$config['veri']>0?'':' where status=0 ';
	$s_dbu=sprintf('select id, name from %s%s order by id desc', $dbprefix.'member', $vdb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	$c_dbu=mysql_num_rows($q_dbu);
	if($c_dbu>0){
		if($_SERVER['REQUEST_METHOD']=='POST')$cont=htmlspecialchars(trim($_POST['rinfo']),ENT_QUOTES);
		$content.='<div class="lcontent">'.($c_dbu>1?'<form method="post" action="" class="btform" id="msgform">':'').'<div class="formline extr">';
		do{
			if(isset($_POST['nid']) && in_array($r_dbu['id'], $_POST['nid'])){
				$i_db=sprintf('insert into %s (content, aid, tid, datetime, readed) values (%s, %s, %s, %s, 1)', $dbprefix.'message', 
					SQLString($cont, 'text'),
					$_SESSION[$config['u_hash']],
					$r_dbu['id'],
					time());
				$result=mysql_query($i_db) or die('');
			}
			$content.='<div class="msg_nlist"><input type="checkbox"'.($r_dbu['id']==$ar['id']?' disabled="disabled"':' name="nid[]" value="'.$r_dbu['id'].'" checked="checked"').'/> '.$r_dbu['name'].'</div>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
		$content.='</div>'.($c_dbu>1?'<div class="formline extr"><textarea name="rinfo" id="forminfo0" rows="4" class="bt_input" rel="内容"></textarea></div><div class="formline"><input type="submit" value="发送" class="button" /> <input value="取消" class="button" type="reset" /></div></form>':'').'</div><ul class="ulist">';
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if($cont!='')$e=1;
			header('Location:./?m=setting&t=sendmsg'.(isset($e)?'&e=1':''));
			exit();
		}
	}
	mysql_free_result($q_dbu);
}
