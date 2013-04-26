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
$f='sync_e.php';
$s_dby=sprintf('select id, name, s_t, s_r, edate from %s where mdate<%s and edate<%s and edate>0 and length(s_r)>0 order by id desc limit 1', $dbprefix.'m_sync', (time()-3600), time());
$q_dby=mysql_query($s_dby) or die('');
$r_dby=mysql_fetch_assoc($q_dby);
if(mysql_num_rows($q_dby)>0){
	switch($r_dby['name']){
		case 'tqq':
			if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
				require_once('lib/tqq.php');
				$o=new tqqPHP($config['tqq_key'], $config['tqq_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'renren':
			if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
				require_once('lib/renren.php');
				$o=new renrenPHP($config['renren_key'], $config['renren_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'kx001':
			if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
				require_once('lib/kaixin.php');
				$o=new kaixinPHP($config['kx001_key'], $config['kx001_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 't163':
			if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
				require_once('lib/t163.php');
				$o=new t163PHP($config['t163_key'], $config['t163_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'douban':
			if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!=''){
				require_once('lib/douban.php');
				$o=new doubanPHP($config['douban_key'], $config['douban_se']);
				$result=$o->access_token_refresh($config['site_url'].'douban_callback.php', $r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'baidu':
			if($config['is_baidu']>0 && $config['baidu_key']!='' && $config['baidu_se']!=''){
				require_once('lib/baidu.php');
				$o=new baiduPHP($config['baidu_key'], $config['baidu_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'google':
			if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!=''){
				require_once('lib/google.php');
				$o=new googlePHP($config['google_key'], $config['google_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		case 'live':
			if($config['is_live']>0 && $config['live_key']!='' && $config['live_se']!=''){
				require_once('lib/live.php');
				$o=new livePHP($config['live_key'], $config['live_se']);
				$result=$o->access_token_refresh($r_dby['s_r']);
				if(isset($result['access_token']) && $result['access_token']!=''){
					$r_dby['s_t']=$result['access_token'];
					$r_dby['s_r']=$result['refresh_token'];
					$r_dby['edate']=time()+$result['expires_in'];
				}
			}
			break;
		default:
			break;
	}
	$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
		SQLString($r_dby['s_t'], 'text'),
		SQLString($r_dby['s_r'], 'text'),
		SQLString($r_dby['edate'], 'int'),
		time(),
		$r_dby['id']);
	$result=mysql_query($u_db) or die('');
	echo '<script type="text/javascript">location.href=\''.$f.'\';</script>';
}
mysql_free_result($q_dby);
?>