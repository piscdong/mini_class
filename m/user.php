<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if(isset($_GET['id']) && intval($_GET['id'])>0){
	$odb=$config['veri']>0?'':' and status=0';
	$s_dbu=sprintf('select id, name, photo, rela, gender, bir_y, bir_m, bir_d, url, email, phone, work, tel, qq, msn, gtalk, address, location, gid, regdate, jaid, visitdate, visit from %s where id=%s%s limit 1', $dbprefix.'member', intval($_GET['id']), $odb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		$title.=$r_dbu['name'];
		$content.='<div class="title">'.$r_dbu['name'].'</div><div class="lcontent">';
		$s_dbo=sprintf('select aid from %s where aid=%s and online=1 limit 1', $dbprefix.'online', $r_dbu['id']);
		$q_dbo=mysql_query($s_dbo) or die('');
		if(mysql_num_rows($q_dbo)>0)$content.='当前在线<br/>';
		mysql_free_result($q_dbo);
		if($c_log){
			if(trim($r_dbu['photo'])!=''){
				$content.='<center>';
				$a_pho=explode('|', trim($r_dbu['photo']));
				$m_pho=$config['avator']>0?$config['avator']:1;
				foreach($a_pho as $k=>$v){
					if($k<$m_pho)$content.='<img src="'.(strstr($v, '://')?'':'../').$v.'" alt="" width="55" height="55" class="photo"/>';
				}
				$content.='</center>';
			}
			if($_SESSION[$config['u_hash']]!=$r_dbu['id'])$content.='<a href="?m=message&amp;id='.$r_dbu['id'].'">发短信</a><br/><br/>';
			if($r_dbu['rela']!='')$content.=$r_dbu['rela'].'<br/><br/>';
			if($r_dbu['gender']>0)$content.='性别：'.($r_dbu['gender']==1?'帅哥':'美女').'<br/>';
			if($r_dbu['bir_m']>0 && $r_dbu['bir_d']>0)$content.='生日：'.($r_dbu['bir_y']>0?$r_dbu['bir_y'].'-':'').$r_dbu['bir_m'].'-'.$r_dbu['bir_d'].'<br/>';
			if($r_dbu['url']!='')$content.='主页：<a href="'.getfurl($r_dbu['url']).'">'.$r_dbu['url'].'</a><br/>';
			if($r_dbu['email']!='')$content.='邮箱：<a href="mailto:'.$r_dbu['email'].'">'.$r_dbu['email'].'</a><br/>';
			if($r_dbu['phone']!='')$content.='手机：'.$r_dbu['phone'].'<br/>';
			if($r_dbu['work']!='')$content.='工作单位：'.$r_dbu['work'].'<br/>';
			if($r_dbu['tel']!='')$content.='联系电话：'.$r_dbu['tel'].'<br/>';
			if($r_dbu['qq']!='')$content.='QQ：'.$r_dbu['qq'].'<br/>';
			if($r_dbu['msn']!='')$content.='MSN：'.$r_dbu['msn'].'<br/>';
			if($r_dbu['gtalk']!='')$content.='GTalk：'.$r_dbu['gtalk'].'<br/>';
			if($r_dbu['address']!='')$content.='住址：'.$r_dbu['address'].'<br/>';
			if($r_dbu['location']!='')$content.='籍贯：'.$r_dbu['location'].'<br/>';
			if(isset($g_a[$r_dbu['gid']]))$content.='身份：'.$g_a[$r_dbu['gid']].'<br/>';
			$content.='注册日期：'.date('Y-n-j H:i', getftime($r_dbu['regdate'])).'<br/>';
			if($r_dbu['jaid']>0){
				$jadb=getainfo($r_dbu['jaid'], 'name');
				$content.='邀请人：<a href="?m=user&amp;id='.$jadb['id'].'">'.$jadb['name'].'</a><br/>';
			}
		}
		$content.='最后访问：'.($r_dbu['visitdate']>0?date('Y-n-j H:i', $r_dbu['visitdate']):'从未').($r_dbu['visit']>0?'<br/>访问次数：'.$r_dbu['visit']:'').'</div>';
	}else{
		header('Location:./?m=user');
		exit();
	}
	mysql_free_result($q_dbu);
}else{
	$title.='班级成员';
	$odb=$config['veri']>0?'':' where status=0';
	$s_dbu=sprintf('select id, name, rela, gender, phone, gid, regdate, jaid, visitdate, visit from %s%s order by visitdate desc', $dbprefix.'member', $odb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		do{
			$jadb[$r_dbu['id']]=$r_dbu;
			$content.='<div class="topic"><a href="?m=user&amp;id='.$r_dbu['id'].'">'.$r_dbu['name'].'</a>';
			$s_dbo=sprintf('select aid from %s where aid=%s and online=1 limit 1', $dbprefix.'online', $r_dbu['id']);
			$q_dbo=mysql_query($s_dbo) or die('');
			if(mysql_num_rows($q_dbo)>0)$content.='&nbsp;&nbsp;当前在线';
			mysql_free_result($q_dbo);
			$content.='<div class="list_c">';
			if($c_log){
				if($_SESSION[$config['u_hash']]!=$r_dbu['id'])$content.='<a href="?m=message&amp;id='.$r_dbu['id'].'">发短信</a><br/><br/>';
				if($r_dbu['rela']!='')$content.=$r_dbu['rela'].'<br/><br/>';
				if($r_dbu['gender']>0)$content.='性别：'.($r_dbu['gender']==1?'帅哥':'美女').'<br/>';
				if($r_dbu['phone']!='')$content.='手机：'.$r_dbu['phone'].'<br/>';
				if(isset($g_a[$r_dbu['gid']]))$content.='身份：'.$g_a[$r_dbu['gid']].'<br/>';
				$content.='注册日期：'.date('Y-n-j H:i', getftime($r_dbu['regdate'])).'<br/>';
				if($r_dbu['jaid']>0){
					if(!isset($jadb[$r_dbu['jaid']]))$jadb[$r_dbu['jaid']]=getainfo($r_dbu['jaid'], 'name');
					$content.='邀请人：<a href="?m=user&amp;id='.$r_dbu['jaid'].'">'.$jadb[$r_dbu['jaid']]['name'].'</a><br/>';
				}
			}
			$content.='最后访问：'.($r_dbu['visitdate']>0?date('Y-n-j H:i', getftime($r_dbu['visitdate'])):'从未').($r_dbu['visit']>0?'<br/>访问次数：'.$r_dbu['visit']:'').(($c_log && $_SESSION[$config['u_hash']]!=$r_dbu['id'])?'<br/><br/><a href="?m=message&amp;id='.$r_dbu['id'].'">发短信</a>':'').'</div></div>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
	}else{
		header('Location:./');
		exit();
	}
	mysql_free_result($q_dbu);
}
?>