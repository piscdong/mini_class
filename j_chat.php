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
$c_log=chklog();
if($c_log){
	$m=(isset($_GET['m']) && intval($_GET['m'])>0)?intval($_GET['m']):0;
	$i=(isset($_GET['i']) && intval($_GET['i'])>0)?intval($_GET['i']):1;
	switch($m){
		case 2:
			if(isset($_POST['c']) && trim($_POST['c'])!='' && $i!=$_SESSION[$config['u_hash']]){
				$c=htmlspecialchars(trim($_POST['c']),ENT_QUOTES);
				$vdb=$config['veri']>0?'':' and status=0';
				$s_dbu=sprintf('select id from %s where id=%s%s limit 1', $dbprefix.'member', $i, $vdb);
				$q_dbu=mysql_query($s_dbu) or die('');
				$r_dbu=mysql_fetch_assoc($q_dbu);
				if(mysql_num_rows($q_dbu)>0){
					$i_db=sprintf('insert into %s (content, aid, tid, datetime, readed) values (%s, %s, %s, %s, 1)', $dbprefix.'message', 
						SQLString($c, 'text'),
						$_SESSION[$config['u_hash']],
						$r_dbu['id'],
						time());
					$result=mysql_query($i_db) or die('');
				}
				mysql_free_result($q_dbu);
				echo '<div class="chat_list chat_t_0">'.gbookencode($c).'<div>'.date('H:i', getftime()).'</div></div>';
			}
			break;
		case 1:
			$lid=(isset($_GET['l']) && intval($_GET['l'])>0)?intval($_GET['l']):0;
			$tid=(isset($_GET['t']) && intval($_GET['t'])>0)?$_GET['t']:time();
			$ldb=$lid>0?'id>'.$lid:'datetime>'.$tid;
			$s_dbg=sprintf('select id, content, datetime from %s where tid=%s and aid=%s and (readed=1 or %s) order by datetime', $dbprefix.'message', $_SESSION[$config['u_hash']], $i, $ldb);
			$q_dbg=mysql_query($s_dbg) or die('');
			$r_dbg=mysql_fetch_assoc($q_dbg);
			if(mysql_num_rows($q_dbg)>0){
				do{
					$tn=getftime($r_dbg['datetime']);
					$tc=getftime();
					echo '<div class="chat_list chat_t_1">'.gbookencode($r_dbg['content']).'<input type="hidden" name="chat_lid_'.$i.'" value="'.$r_dbg['id'].'"/><div>'.(date('Ymd', $tn)!=date('Ymd', $tc)?date('Y-n-j', $tn).' ':'').date('H:i', $tn).'</div></div>';
					$u_db=sprintf('update %s set readed=0 where id=%s', $dbprefix.'message', $r_dbg['id']);
					$result=mysql_query($u_db) or die('');
				}while($r_dbg=mysql_fetch_assoc($q_dbg));
			}
			mysql_free_result($q_dbg);
			break;
		default:
			$s_dbg=sprintf('select a.aid, b.name from %s as a, %s as b where a.tid=%s and a.aid=b.id and a.readed=1 order by a.datetime desc', $dbprefix.'message', $dbprefix.'member', $_SESSION[$config['u_hash']]);
			$q_dbg=mysql_query($s_dbg) or die('');
			$r_dbg=mysql_fetch_assoc($q_dbg);
			if(mysql_num_rows($q_dbg)>0){
				do{
					$a_mid[$r_dbg['aid']]=$r_dbg['aid'];
					$a_name[$r_dbg['aid']]=$r_dbg['name'];
				}while($r_dbg=mysql_fetch_assoc($q_dbg));
			}
			mysql_free_result($q_dbg);
			echo '<input id="chat_p_input"'.(isset($a_mid)?' value="'.join('|', $a_mid).'"':'').'/>';
			if(isset($a_name)){
				foreach($a_name as $k=>$v)echo '<input id="chat_pn_'.$k.'" value="'.$v.'"/>';
			}
			break;
	}
}
