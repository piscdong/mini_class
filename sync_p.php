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
$f='sync_p.php';
if(chklog()){
	$s_dbt=sprintf('select id, content, sync_p from %s where aid=%s and length(sync_p)>0', $dbprefix.'topic', $_SESSION[$config['u_hash']]);
	$q_dbt=mysql_query($s_dbt) or die('');
	$r_dbt=mysql_fetch_assoc($q_dbt);
	if(mysql_num_rows($q_dbt)>0){
		do{
			$ucontent=gbookencode($r_dbt['content'], 1);
			$ucontent=strip_tags($ucontent);
			if($ucontent!='')$a[]=array($ucontent, $r_dbt['sync_p'], $r_dbt['id'], 'topic');
		}while($r_dbt=mysql_fetch_assoc($q_dbt));
	}
	mysql_free_result($q_dbt);
	if(!isset($a)){
		$s_dbr=sprintf('select id, content, sync_p from %s where aid=%s and length(sync_p)>0', $dbprefix.'pcomment', $_SESSION[$config['u_hash']]);
		$q_dbr=mysql_query($s_dbr) or die('');
		$r_dbr=mysql_fetch_assoc($q_dbr);
		if(mysql_num_rows($q_dbr)>0){
			do{
				$ucontent=gbookencode($r_dbr['content'], 1);
				$ucontent=strip_tags($ucontent);
				if($ucontent!='')$a[]=array($ucontent, $r_dbr['sync_p'], $r_dbr['id'], 'pcomment');
			}while($r_dbr=mysql_fetch_assoc($q_dbr));
		}
		mysql_free_result($q_dbr);
	}
	if(!isset($a)){
		$s_dbr=sprintf('select id, content, sync_p from %s where aid=%s and length(sync_p)>0', $dbprefix.'ccomment', $_SESSION[$config['u_hash']]);
		$q_dbr=mysql_query($s_dbr) or die('');
		$r_dbr=mysql_fetch_assoc($q_dbr);
		if(mysql_num_rows($q_dbr)>0){
			do{
				$ucontent=gbookencode($r_dbr['content'], 1);
				$ucontent=strip_tags($ucontent);
				if($ucontent!='')$a[]=array($ucontent, $r_dbr['sync_p'], $r_dbr['id'], 'ccomment');
			}while($r_dbr=mysql_fetch_assoc($q_dbr));
		}
		mysql_free_result($q_dbr);
	}
	if(isset($a)){
		$aid=$_SESSION[$config['u_hash']];
		$u_db=sprintf('update %s set sync_p=%s where id=%s', $dbprefix.$a[0][3],
			SQLString('', 'text'),
			$a[0][2]);
		$result=mysql_query($u_db) or die('');
		$am=explode('|', $a[0][1]);
		if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!='' && in_array('sina', $am)){
			$s_dby=sprintf('select s_t from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('sina', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/sina.php');
				$sina=new sinaPHP($config['sina_key'], $config['sina_se'], $r_dby['s_t']);
				$sina->update($a[0][0]);
			}
			mysql_free_result($q_dby);
		}
		if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!='')) && in_array('tqq', $am)){
			$s_dby=sprintf('select s_id, s_t from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('tqq', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/tqq.php');
				$tqq=new tqqPHP($config['tqq_key'], $config['tqq_se'], $r_dby['s_t'], $r_dby['s_id']);
				$tqq->postOne($a[0][0]);
			}
			mysql_free_result($q_dby);
		}
		if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!='' && in_array('renren', $am)){
			$s_dby=sprintf('select s_t from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('renren', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/renren.php');
				$rr_c=new renrenPHP($config['renren_key'], $config['renren_se'], $r_dby['s_t']);
				$rr_c->setStatus($a[0][0]);
			}
			mysql_free_result($q_dby);
		}
		if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!='' && in_array('kx001', $am)){
			$s_dby=sprintf('select s_t from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('kx001', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/kaixin.php');
				$kx_co=new kaixinPHP($config['kx001_key'], $config['kx001_se'], $r_dby['s_t']);
				$kx_co->records_add($a[0][0]);
			}
			mysql_free_result($q_dby);
		}
		if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!='' && in_array('t163', $am)){
			$s_dby=sprintf('select s_id, s_t, s_s from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('t163', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/t163.php');
				$tblog=new t163PHP($config['t163_key'], $config['t163_se'], $r_dby['s_t']);
				$tblog->update($a[0][0]);
			}
			mysql_free_result($q_dby);
		}
		if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!='')) && in_array('tsohu', $am)){
			$s_dby=sprintf('select s_t, s_s from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('tsohu', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/SohuOAuth.php');
				$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se'], $r_dby['s_t'], $r_dby['s_s']);
				$url='http://api.t.sohu.com/statuses/update.json';
				$oauth->post($url, array('status'=>urlencode($a[0][0])));
			}
			mysql_free_result($q_dby);
		}
		if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!='' && in_array('twitter', $am)){
			$s_dby=sprintf('select s_t, s_s from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('twitter', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/twitterOAuth.php');
				$twitter=new TwitterOAuth($config['tw_key'], $config['tw_se'], $r_dby['s_t'], $r_dby['s_s']);
				$t_c=$twitter->OAuthRequest('https://twitter.com/statuses/update.xml', array('status'=>$a[0][0]), 'POST');
			}
			mysql_free_result($q_dby);
		}
		if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!='' && in_array('facebook', $am)){
			$s_dby=sprintf('select s_id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $aid, SQLString('facebook', 'text'));
			$q_dby=mysql_query($s_dby) or die('');
			$r_dby=mysql_fetch_assoc($q_dby);
			if(mysql_num_rows($q_dby)>0){
				require_once('lib/facebook.php');
				$fb=new facebookPHP($config['fb_app_id'], $config['fb_se'], $r_dby['s_t']);
				$fb->update($a[0][0]);
			}
			mysql_free_result($q_dby);
		} 
	}
}
