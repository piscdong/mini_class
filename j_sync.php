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
	$id=(isset($_GET['i']) && intval($_GET['i']))?intval($_GET['i']):$_SESSION[$config['u_hash']];
	switch($_GET['t']){
		case 'sina':
			if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!=''){
				$s_dby=sprintf('select s_id, s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('sina', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/sina.php');
					$so=new sinaPHP($config['sina_key'], $config['sina_se'], $r_dby['s_t']);
					$ma=$so->user_timeline($r_dby['s_id'], 5);
					if(!isset($ma['error_code']) && is_array($ma['statuses']) && count($ma['statuses'])>0){
						foreach($ma['statuses'] as $v){
							if(trim($v['text'])!='')echo '<div class="sync_list" style="background-image: url(images/i-sina.gif);">'.trim($v['text']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'tqq':
			if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
				$s_dby=sprintf('select s_id, s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('tqq', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/tqq.php');
					$o=new tqqPHP($config['tqq_key'], $config['tqq_se'], $r_dby['s_t'], $r_dby['s_id']);
					$ma=$o->getMyTweet(5);
					if(isset($ma['ret']) && $ma['ret']==0 && isset($ma['data']['info']) && is_array($ma['data']['info']) && count($ma['data']['info'])>0){
						foreach($ma['data']['info'] as $v){
							if(trim($v['text'])!='')echo '<div class="sync_list" style="background-image: url(images/i-tqq.gif);">'.trim($v['text']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'tsohu':
			if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
				$s_dby=sprintf('select s_id, s_t, s_s from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('tsohu', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/SohuOAuth.php');
					$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se'], $r_dby['s_t'], $r_dby['s_s']);
					$url='http://api.t.sohu.com/statuses/user_timeline/'.$r_dby['s_id'].'.json';
					$ma=$oauth->get($url, array('count'=>5));
					if(is_array($ma) && count($ma)>0){
						foreach($ma as $v){
							if(trim($v['text'])!='')echo '<div class="sync_list" style="background-image: url(images/i-tsohu.gif);">'.trim($v['text']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 't163':
			if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
				$s_dby=sprintf('select s_id, s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('t163', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/t163.php');
					$tblog=new t163PHP($config['t163_key'], $config['t163_se'], $r_dby['s_t']);
					$ms=$tblog->user_timeline($r_dby['s_id'], 5);
					if(is_array($ms) && count($ms)>0){
						foreach($ms as $v){
							if(trim($v['text'])!='')echo '<div class="sync_list" style="background-image: url(images/i-t163.gif);">'.trim($v['text']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'babab':
			if($config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
				$s_dby=sprintf('select s_t, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $id, SQLString('babab', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):'1';
					$isp=((isset($_GET['m']) && $_GET['m']=='1') || $id!=$_SESSION[$config['u_hash']])?1:0;
					$p_page='5';
					if($isp>0)$page='1';
					if($r_dby['is_show']==0 || $isp==0){
						require_once('lib/IXR.php');
						$IXR_c=new IXR_Client('http://www.bababian.com/xmlrpc');
						$IXR_c->query('bababian.photo.getPhotoList', $config['babab_key'], $r_dby['s_t'], $page, $p_page, '75');
						$IXR_r=$IXR_c->getResponse();
						if(is_array($IXR_r) && !isset($IXR_r['faultCode']) && count($IXR_r)>0 && isset($IXR_r[0]['photo_total']) && $IXR_r[0]['photo_total']>0){
							if($isp>0){
								echo '<div class="sync_list" style="background-image: url(images/i-babab.gif);"><div class="extr">最新巴巴变照片</div>';
							}elseif($page==1){
								echo '<span class="plist_babab" id="plist_babab_1"><input type="hidden" id="babab_isload" value="1"/>';
							}
							$babab_info=$IXR_r[0];
							unset($IXR_r[0]);
							foreach($IXR_r as $v){
								if($isp==0){
									$bimg=$v['src'];
									$IXR_c->query('bababian.photo.getPhotoInfo', $config['babab_key'], $v['did'], '1', '1');
									$bimg_a=$IXR_c->getResponse();
									if(isset($bimg_a[0]['src']) && $bimg_a[0]['src']!='')$bimg=$bimg_a[0]['src'];
								}
								echo '<div class="al_list">'.($isp>0?'<a href="http://www.bababian.com/phoinfo/'.$v['did'].'" target="_blank">':'').'<img src="'.$v['src'].'" width="70" height="70" class="al_t'.($isp>0?'':' f_link" onclick="$(\'#formurl0\').val(\''.$bimg.'\');$(\'#formtitle1\').val($(this).attr(\'src\'));$(\'#formtitle0\').val($(this).attr(\'title\'));').'" alt="" title="'.$v['title'].'"/>'.($isp>0?'</a>':'').'</div>';
							}
							echo '<div class="extr"></div>';
							if($isp>0){
								echo '</div>';
							}else{
								$tp=$babab_info['page_total'];
								if($tp>1){
									for($i=1;$i<=$tp;$i++){
										if($i==$page){
											echo '['.$i.']';
										}else{
											echo '<span href="#" onclick="ld(\'babab\', '.$i.', \'j_sync.php?t=babab&page='.$i.'\');" class="mlink f_link">'.$i.'</span>';
										}
										echo ' ';
									}
									echo '| ';
								}
								if($page==1)echo '</span><span onclick="$(\'#album_sync_div\').slideUp(500);" class="mlink f_link">隐藏</span><input type="hidden" id="is_ax" value="babab"/><span id="p_babab" style="display: none;"> <img src="images/v.gif" alt="" title="载入中……" class="loading_va"/></span>';
							}
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'twitter':
			if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
				$s_dby=sprintf('select s_id, s_t, s_s from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('twitter', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/twitterOAuth.php');
					$to=new TwitterOAuth($config['tw_key'], $config['tw_se'], $r_dby['s_t'], $r_dby['s_s']);
					$ma=$to->OAuthRequest('https://twitter.com/statuses/user_timeline.json', array('count'=>5, 'user_id'=>$r_dby['s_id']), 'GET');
					if(is_array($ma) && count($ma)>0){
						foreach($ma as $v){
							if(trim($v['text'])!='')echo '<div class="sync_list" style="background-image: url(images/i-twitter.gif);">'.trim($v['text']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'facebook':
			if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!=''){
				$s_dby=sprintf('select s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('sina', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/facebook.php');
					$fb=new facebookPHP($config['fb_app_id'], $config['fb_se'], $r_dby['s_t']);
					$sa=$fb->my_feed(5);
					if(isset($sa['data']) && is_array($sa['data']) && count($sa['data'])>0){
						foreach($sa['data'] as $v){
							switch($v['type']){
								case 'status':
									echo '<div class="sync_list" style="background-image: url(images/i-facebook.gif);">'.$v['message'].'</div>';
									break;
								case 'link':
									echo '<div class="sync_list" style="background-image: url(images/i-facebook.gif);"><a href="'.$v['link'].'" target="_blank">'.($v['name']!=''?$v['name']:$v['message']).'</a> '.$v['description'].'</div>';
									break;
							}
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'kx001':
			if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
				$s_dby=sprintf('select s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('kx001', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/kaixin.php');
					$kx_co=new kaixinPHP($config['kx001_key'], $config['kx001_se'], $r_dby['s_t']);
					$kx_re=$kx_co->records_me(5);
					if(isset($kx_re['data']) && count($kx_re['data'])>0){
						foreach($kx_re['data'] as $v){
							if(trim($v['main']['content'])!='')echo '<div class="sync_list" style="background-image: url(images/i-kx001.gif);">'.trim($v['main']['content']).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'renren':
			if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
				$s_dby=sprintf('select s_id, s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $id, SQLString('renren', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					require_once('lib/renren.php');
					$rr_c=new renrenPHP($config['renren_key'], $config['renren_se'], $r_dby['s_t']);
					$st=$rr_c->getStatus($r_dby['s_id'], 5);
					if(is_array($st) && count($st)>0){
						foreach($st as $v){
							if(htmlspecialchars(trim($v['message']),ENT_QUOTES)!='')echo '<div class="sync_list" style="background-image: url(images/i-renren.gif);">'.htmlspecialchars(trim($v['message']),ENT_QUOTES).'</div>';
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
		case 'instagram':
			if($config['is_instagram']>0 && $config['instagram_key']!='' && $config['instagram_se']!=''){
				$s_dby=sprintf('select s_id, s_t, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $id, SQLString('instagram', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$max_id=(isset($_GET['max_id']) && trim($_GET['max_id'])!='')?trim($_GET['max_id']):'';
					$page=(isset($_GET['page']) && intval($_GET['page'])>0)?intval($_GET['page']):1;
					$isp=((isset($_GET['m']) && $_GET['m']=='1') || $id!=$_SESSION[$config['u_hash']])?1:0;
					$p_page=$isp>0?'5':'10';
					if($isp>0){
						$max_id='';
						$page=1;
					}
					if($r_dby['is_show']==0 || $isp==0){
						require_once('lib/instagram.php');
						$io=new instagramPHP($config['instagram_key'], $config['instagram_se'], $r_dby['s_t']);
						$ia=$io->user_media($r_dby['s_id'], $p_page, $max_id);
						if(!isset($ia['meta']['error_type']) && isset($ia['data']) && is_array($ia['data']) && count($ia['data'])>0){
							if($isp>0){
								echo '<div class="sync_list" style="background-image: url(images/i-instagram.gif);"><div class="extr">最新Instagram照片</div>';
							}elseif($page==1){
								echo '<span class="plist_instagram" id="plist_instagram_1"><input type="hidden" id="instagram_isload" value="1"/>';
							}
							foreach($ia['data'] as $v){
								if($v['type']=='image')echo '<div class="al_list">'.($isp>0?'<a href="'.$v['link'].'" target="_blank">':'').'<img src="'.$v['images']['thumbnail']['url'].'" width="70" height="70" class="al_t'.($isp>0?'':' f_link" onclick="$(\'#formurl0\').val(\''.$v['images']['standard_resolution']['url'].'\');$(\'#formtitle1\').val($(this).attr(\'src\'));$(\'#formtitle0\').val($(this).attr(\'title\'));').'" alt="" title="'.$v['caption']['text'].'"/>'.($isp>0?'</a>':'').'</div>';
							}
							echo '<div class="extr"></div>';
							if($isp>0){
								echo '</div>';
							}else{
								if($page>1)$pa[]='<span href="#" onclick="ld(\'instagram\', '.($page-1).', \'j_sync.php?t=instagram&page='.($page-1).'\');" class="mlink f_link">前页</span>';
								if(isset($ia['pagination']['next_max_id']) && trim($ia['pagination']['next_max_id'])!='')$pa[]='<span href="#" onclick="ld(\'instagram\', '.($page+1).', \'j_sync.php?t=instagram&max_id='.$ia['pagination']['next_max_id'].'&page='.($page+1).'\');" class="mlink f_link">后页</span>';
								if(isset($pa))echo join(' ', $pa).' | ';
								if($page==1)echo '</span><span onclick="$(\'#album_sync_div\').slideUp(500);" class="mlink f_link">隐藏</span><input type="hidden" id="is_ax" value="instagram"/><span id="p_instagram" style="display: none;"> <img src="images/v.gif" alt="" title="载入中……" class="loading_va"/></span>';
							}
						}
					}
				}
				mysql_free_result($q_dby);
			}
			break;
	}
}
?>