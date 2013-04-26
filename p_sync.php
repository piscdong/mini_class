<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && isset($r_dbu)){
	$title.='绑定设置';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['babab_email'])){
			if(trim($_POST['babab_email'])!='' && trim($_POST['babab_pwd'])!='' && $config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
				require_once('lib/IXR.php');
				$IXR_c=new IXR_Client('http://www.bababian.com/xmlrpc');
				$IXR_c->query('bababian.user.bind', $config['babab_key'], trim($_POST['babab_email']), trim($_POST['babab_pwd']));
				$IXR_r=$IXR_c->getResponse();
				if(isset($IXR_r['faultCode'])){
					if($IXR_r['faultCode']=='60002'){
						$e=3;
					}else{
						$e=2;
					}
				}elseif(is_array($IXR_r) && isset($IXR_r[0]) && $IXR_r[0]!='' && isset($IXR_r[1]) && $IXR_r[1]!=''){
					$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('babab', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$u_db=sprintf('update %s set s_id=%s, s_t=%s where id=%s', $dbprefix.'member',
							SQLString($IXR_r[1], 'text'),
							SQLString($IXR_r[0], 'text'),
							$r_dby['id']);
						$result=mysql_query($u_db) or die('');
					}else{
						$i_db=sprintf('insert into %s (aid, name, s_id, s_t) values (%s, %s, %s, %s)', $dbprefix.'m_sync',
							$r_dbu['id'],
							SQLString('babab', 'text'),
							SQLString($IXR_r[1], 'text'),
							SQLString($IXR_r[0], 'text'));
						$result=mysql_query($i_db) or die('');
					}
					mysql_free_result($q_dby);
					setsinfo($r_dbu['name'].' 绑定了巴巴变', $r_dbu['id']);
					$e=1;
				}else{
					$e=2;
				}
			}else{
				$e=3;
			}
		}elseif(isset($_POST['flickr_url'])){
			if(trim($_POST['flickr_url'])!='' && trim($_POST['flickr_id'])!='' && trim($_POST['flickr_name'])!='' && $config['is_flickr']>0 && ($config['is_uflickr']>0 || $config['flickr_key']!='')){
				$flickr_url=htmlspecialchars(trim($_POST['flickr_url']),ENT_QUOTES);
				$flickr_id=htmlspecialchars(trim($_POST['flickr_id']),ENT_QUOTES);
				$flickr_name=htmlspecialchars(trim($_POST['flickr_name']),ENT_QUOTES);
				$s_dby=sprintf('select id from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('flickr', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$u_db=sprintf('update %s set s_id=%s, s_t=%s, s_n=%s where id=%s', $dbprefix.'member',
						SQLString($flickr_id, 'text'),
						SQLString($flickr_url, 'text'),
						SQLString($flickr_name, 'text'),
						$r_dby['id']);
					$result=mysql_query($u_db) or die('');
				}else{
					$i_db=sprintf('insert into %s (aid, name, s_id, s_t, s_n) values (%s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_dbu['id'],
						SQLString('flickr', 'text'),
						SQLString($flickr_id, 'text'),
						SQLString($flickr_url, 'text'),
						SQLString($flickr_name, 'text'));
					$result=mysql_query($i_db) or die('');
				}
				mysql_free_result($q_dby);
				setsinfo($r_dbu['name'].' 绑定了Flickr', $r_dbu['id']);
				$e=1;
			}else{
				$e=2;
			}
		}elseif(isset($_POST['isl_sina_h']) && intval($_POST['isl_sina_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_sina_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_tqq_h']) && intval($_POST['isl_tqq_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_tqq_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_fb_h']) && intval($_POST['isl_fb_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_fb_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_tw_h']) && intval($_POST['isl_tw_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_tw_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_t163_h']) && intval($_POST['isl_t163_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_t163_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_tsohu_h']) && intval($_POST['isl_tsohu_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_tsohu_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_flickr_h']) && intval($_POST['isl_flickr_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_flickr_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_babab_h']) && intval($_POST['isl_babab_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_babab_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_kx001_h']) && intval($_POST['isl_kx001_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_kx001_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_renren_h']) && intval($_POST['isl_renren_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_renren_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_douban_h']) && intval($_POST['isl_douban_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_douban_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_google_h']) && intval($_POST['isl_google_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_google_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}elseif(isset($_POST['isl_instagram_h']) && intval($_POST['isl_instagram_h'])>0){
			$is_show=(isset($_POST['is_show']) && $_POST['is_show']==1)?1:0;
			$u_db=sprintf('update %s set is_show=%s where id=%s', $dbprefix.'m_sync',
				$is_show,
				intval($_POST['isl_instagram_h']));
			$result=mysql_query($u_db) or die('');
			$e=1;
		}
		header('Location:./?m=profile&t=sync'.(isset($_GET['n'])?'&n='.$_GET['n']:'').(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'绑定设置已修改。', '无法获取信息，请检查输入或者稍后再试。', '登录信息不正确。');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">绑定设置</div><div class="lcontent">';
		if($config['is_qq']>0 && $config['qq_app_id']!='' && $config['qq_app_key']!=''){
			$a_sync_l[]='qq';
			$a_sync['qq']['title']='QQ登录';
		}
		if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!=''){
			$a_sync_l[]='sina';
			$a_sync['sina']['title']='新浪微博';
		}
		if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
			$a_sync_l[]='tqq';
			$a_sync['tqq']['title']='腾讯微博';
		}
		if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
			$a_sync_l[]='renren';
			$a_sync['renren']['title']='人人网';
		}
		if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
			$a_sync_l[]='kx001';
			$a_sync['kx001']['title']='开心网';
		}
		if($config['is_baidu']>0 && $config['baidu_key']!='' && $config['baidu_se']!=''){
			$a_sync_l[]='baidu';
			$a_sync['baidu']['title']='百度登录';
		}
		if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!=''){
			$a_sync_l[]='douban';
			$a_sync['douban']['title']='豆瓣';
		}
		if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!=''){
			$a_sync_l[]='google';
			$a_sync['google']['title']='Google登录';
		}
		if($config['is_live']>0 && $config['live_key']!='' && $config['live_se']!=''){
			$a_sync_l[]='live';
			$a_sync['live']['title']='Microsoft账户登录';
		}
		if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
			$a_sync_l[]='t163';
			$a_sync['t163']['title']='网易微博';
		}
		if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
			$a_sync_l[]='tsohu';
			$a_sync['tsohu']['title']='搜狐微博';
		}
		if($config['is_instagram']>0 && $config['instagram_key']!='' && $config['instagram_se']!=''){
			$a_sync_l[]='instagram';
			$a_sync['instagram']['title']='Instagram';
		}
		if($config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
			$a_sync_l[]='babab';
			$a_sync['babab']['title']='巴巴变';
		}
		if($config['is_flickr']>0 && ($config['is_uflickr']>0 || $config['flickr_key']!='')){
			$a_sync_l[]='flickr';
			$a_sync['flickr']['title']='Flickr';
		}
		if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
			$a_sync_l[]='twitter';
			$a_sync['twitter']['title']='Twitter';
		}
		if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!=''){
			$a_sync_l[]='facebook';
			$a_sync['facebook']['title']='Facebook';
		}
		if(isset($a_sync_l)){
			$c_sync=count($a_sync_l);
			$nct=(isset($_GET['n']) && isset($a_sync[$_GET['n']]))?$_GET['n']:$a_sync_l[0];
			if($c_sync>1){
				$content.='<div class="formline" style="text-align: center;">';
				$i=0;
				foreach($a_sync as $k=>$v){
					if($i>0)$content.=' | ';
					if($k==$nct){
						$content.=$v['title'];
					}else{
						$content.='<a href="?m=profile&amp;t=sync&amp;n='.$k.'">'.$v['title'].'</a>';
					}
					$i++;
				}
				$content.='</div>';
			}
			$content.='<br/><div class="sync_list" style="font-weight: bold;background-image: url(images/i-'.$nct.'.gif);">'.$a_sync[$nct]['title'].'</div><div class="formline"><br/>';
			switch($nct){
				case 'facebook':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/facebook.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$so=new facebookPHP($config['fb_app_id'], $config['fb_se'], $r_dby['s_t']);
						$ma=$so->me();
						if(isset($ma['id']) && $ma['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url=$ma['link'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$ma['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($ma['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定Facebook账号<table width="200"><tr><td align="center"><img src="https://graph.facebook.com/'.$ma['username'].'/picture" alt=""/><br/><a href="'.$me_url.'" target="_blank">'.$ma['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定Facebook账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_fb_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$so=new facebookPHP($config['fb_app_id'], $config['fb_se']);
							$aurl=$so->login_url($config['site_url'].'facebook_callback.php', 'user_about_me');
							header('Location:'.$aurl);
							exit();
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$so=new facebookPHP($config['fb_app_id'], $config['fb_se']);
						$aurl=$so->login_url($config['site_url'].'facebook_callback.php', 'user_about_me');
						$content.='<a href="'.$aurl.'">点击此处和您的Facebook账号建立连接</a>';
					}
					$content.='<br/><br/>绑定Facebook账号后将实现以下功能：<ol><li>将留言、评论、回复发布到Facebook涂鸦墙</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的Facebook涂鸦墙内容</li><li>使用Facebook账号登录</li><li>注：Facebook账号不可以重复绑定，用户绑定后，其他用户绑定的同一Facebook账号将自动解除绑定</li></ol>';
					break;
				case 'twitter':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/twitterOAuth.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_s, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$to=new TwitterOAuth($config['tw_key'], $config['tw_se'], $r_dby['s_t'], $r_dby['s_s']);
						$ma=$to->OAuthRequest('https://twitter.com/account/verify_credentials.json', array(), 'GET');
						if(isset($ma['id']) && $ma['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							if($r_dby['s_id']!=$ma['id'] || $r_dby['s_n']!=$ma['screen_name']){
								$u_db=sprintf('update %s set s_id=%s, s_n=%s where id=%s', $dbprefix.'m_sync',
									SQLString($ma['id'], 'text'),
									SQLString($ma['screen_name'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定Twitter账号<table width="200"><tr><td align="center">'.($ma['profile_image_url']!=''?'<img src="'.$ma['profile_image_url'].'" alt=""/><br/>':'').'<a href="http://twitter.com/'.$ma['screen_name'].'" target="_blank">'.$ma['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="isl_tw" value="1"'.($r_dbu['isl_tw']>0?' checked="checked"':'').'/>隐藏已绑定Twitter账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_tw_h" value="1"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$to=new TwitterOAuth($config['tw_key'], $config['tw_se']);
						$tok=$to->getRequestToken();
						$_SESSION['tw_token']=$tok['oauth_token'];
						$_SESSION['tw_secret']=$tok['oauth_token_secret'];
						$aurl=$to->getAuthorizeURL($tok['oauth_token']);
						$content.='<a href="'.$aurl.'">点击此处和您的Twitter账号建立连接</a>';
					}
					$content.='<br/><br/>绑定Twitter账号后将实现以下功能：<ol><li>将留言、评论、回复发布到Twitter</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的Twitter留言</li><li>使用Twitter账号登录</li><li>注：Twitter账号不可以重复绑定，用户绑定后，其他用户绑定的同一Twitter账号将自动解除绑定</li></ol>';
					break;
				case 'flickr':
					$s_dby=sprintf('select id, s_t, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if(isset($_GET['lt']) && $_GET['lt']==1){
							$d_db=sprintf('delete from %s where id=%s', $dbprefix.'m_sync', $r_dby['id']);
							$result=mysql_query($d_db) or die('');
							header('Location:./?m=profile&t=sync&n='.$nct);
							exit();
						}
						$content.='当前已绑定Flickr账号： <a href="http://www.flickr.com/photos/'.$r_dby['s_t'].'/" rel="external">'.$r_dby['s_n'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）<br/>';
						$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定Flickr账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_flickr_h" value="'.$r_dby['id'].'"/></form>';
					}else{
						$js_c.='
	$(\'#flickr_bt\').click(function(){
		var flickr_u=$.trim($(\'#flickr_url_i\').val());
		if(flickr_u!=\'\'){
			$(\'#flickr_url_i\').attr(\'disabled\', \'disabled\');
			$(this).attr(\'disabled\', \'disabled\');
			$.getJSON(\'http://api.flickr.com/services/rest/?method=flickr.urls.lookupUser&url=\'+encodeURIComponent(\'http://www.flickr.com/photos/\'+flickr_u)+\'&format=json&api_key='.$config['flickr_key'].'&jsoncallback=?\', function(data){
				if(data.stat==\'ok\'){
					$(\'#flickr_url\').val(flickr_u);
					$(\'#flickr_id\').val(data.user.id);
					$(\'#flickr_name\').val(data.user.username._content);
					$(\'#flickr_form\').submit();
				}else{
					alert(\'无法获取信息，请检查输入或者稍后再试！\');
					$(\'#flickr_url_i\').removeAttr(\'disabled\');
					$(this).removeAttr(\'disabled\');
				}
			});
		}else{
			alert(\'请输入网址！\');
		}
	});';
						$content.='<table><tr><td>您的Flickr图片页面网址：</td><td>http://www.flickr.com/photos/<input id="flickr_url_i" size="20" class="bt_input" />/</td></tr><tr><td colspan="2"><input type="button" value="绑定" id="flickr_bt" class="button" /></td></tr></table><form method="post" action="" id="flickr_form" style="display: none;"><input type="hidden" name="flickr_url" id="flickr_url"/><input type="hidden" name="flickr_id" id="flickr_id"/><input type="hidden" name="flickr_name" id="flickr_name"/></form>';
					}
					mysql_free_result($q_dby);
					$content.='<br/>绑定Flickr账号后将实现以下功能：<ol><li>可以选取Flickr公开图片添加到照片视频</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的Flickr公开图片</li></ol>';
					break;
				case 'babab':
					$s_dby=sprintf('select id, s_id, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if(isset($_GET['lt']) && $_GET['lt']==1){
							$d_db=sprintf('delete from %s where id=%s', $dbprefix.'m_sync', $r_dby['id']);
							$result=mysql_query($d_db) or die('');
							header('Location:./?m=profile&t=sync&n='.$nct);
							exit();
						}
						$content.='当前已绑定巴巴变账号的id： <a href="http://www.bababian.com/photo/'.$r_dby['s_id'].'/" rel="external">'.$r_dby['s_id'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）<br/>';
						$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定巴巴变账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_babab_h" value="'.$r_dby['id'].'"/></form>';
					}else{
						$content.='<form method="post" action="" class="btform"><table><tr><td>登录巴巴变的Email：</td><td><input name="babab_email" size="32" class="bt_input" rel="Email" /></td></tr><tr><td>登录巴巴变的密码：</td><td><input name="babab_pwd" type="password" size="32" class="bt_input" rel="密码" /></td></tr><tr><td colspan="2"><input type="submit" value="绑定" class="button" /> <input type="reset" value="取消" class="button" /><br/>Email和密码仅绑定巴巴变时使用，绑定完成后不再需要，<strong>本站不会保存</strong>。</td></tr></table></form>';
					}
					mysql_free_result($q_dby);
					$content.='<br/>绑定巴巴变账号后将实现以下功能：<ol><li>可以选取巴巴变公开图片添加到照片视频</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的巴巴变公开图片</li><li>注：由于巴巴变的外链政策，非VIP账号无法外链，图片可能会无法正常显示，请酌情使用</li></ol>';
					break;
				case 't163':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/t163.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new t163PHP($config['t163_key'], $config['t163_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$tblog=new t163PHP($config['t163_key'], $config['t163_se'], $r_dby['s_t']);
						$me=$tblog->me();
						if(isset($me['id']) && $me['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($me['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url='http://t.163.com/'.$me['screen_name'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$me['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($me['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定网易微博账号<table width="200"><tr><td align="center">'.($me['profile_image_url']!=''?'<img src="'.$me['profile_image_url'].'" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$me['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定网易微博账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_t163_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$oauth=new t163PHP($config['t163_key'], $config['t163_se']);
						$aurl=$oauth->login_url($config['site_url'].'t163_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的网易微博账号建立连接</a>';
					}
					$content.='<br/><br/>绑定网易微博账号后将实现以下功能：<ol><li>将留言、评论、回复发布到网易微博</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的网易微博留言</li><li>使用网易微博账号登录</li><li>注：网易微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一网易微博账号将自动解除绑定</li></ol>';
					break;
				case 'tsohu':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/SohuOAuth.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_s, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se'], $r_dby['s_t'], $r_dby['s_s']);
						$url='http://api.t.sohu.com/users/show.json';
						$ma=$oauth->get($url);
						if(isset($ma['id']) && $ma['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url='http://t.sohu.com/u/'.$ma['id'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$ma['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($ma['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定搜狐微博账号<table width="200"><tr><td align="center">'.($ma['profile_image_url']!=''?'<img src="'.$ma['profile_image_url'].'" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$ma['screen_name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定搜狐微博账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_tsohu_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$oauth=new SohuOAuth($config['tsohu_key'], $config['tsohu_se']);
						$request_token=$oauth->getRequestToken($config['site_url'].'tsohu_callback.php');
						$_SESSION['tsohu_token']=$request_token['oauth_token'];
						$_SESSION['tsohu_secret']=$request_token['oauth_token_secret'];
						switch($oauth->http_code){
							case 200:
								$aurl=$oauth->getAuthorizeUrl1($request_token['oauth_token'], $config['site_url'].'tsohu_callback.php');
								$content.='<a href="'.$aurl.'">点击此处和您的搜狐微博账号建立连接</a>';
								break;
							default:
								$content.='出错了，请稍后重试';
								break;
						}
					}
					$content.='<br/><br/>绑定搜狐微博账号后将实现以下功能：<ol><li>将留言、评论、回复发布到搜狐微博</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的搜狐微博留言</li><li>使用搜狐微博账号登录</li><li>注：搜狐微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一搜狐微博账号将自动解除绑定</li></ol>';
					break;
				case 'tqq':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/tqq.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new tqqPHP($config['tqq_key'], $config['tqq_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$o=new tqqPHP($config['tqq_key'], $config['tqq_se'], $r_dby['s_t'], $r_dby['s_id']);
						$ma=$o->me();
						if(isset($ma['ret']) && $ma['ret']==0 && isset($ma['data']) && is_array($ma['data'])){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['data']['openid'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url='http://t.qq.com/'.$ma['data']['name'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$ma['data']['openid']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($ma['data']['openid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定腾讯微博账号<table width="200"><tr><td align="center">'.($ma['data']['head']!=''?'<img src="'.$ma['data']['head'].'/50" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$ma['data']['nick'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定腾讯微博账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_tqq_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$o=new tqqPHP($config['tqq_key'], $config['tqq_se']);
						$aurl=$o->login_url($config['site_url'].'tqq_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的腾讯微博账号建立连接</a>';
					}
					$content.='<br/><br/>绑定腾讯微博账号后将实现以下功能：<ol><li>将留言、评论、回复发布到腾讯微博</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的腾讯微博留言</li><li>使用腾讯微博账号登录</li><li>注：腾讯微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一腾讯微博账号将自动解除绑定</li></ol>';
					break;
				case 'sina':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/sina.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$so=new sinaPHP($config['sina_key'], $config['sina_se'], $r_dby['s_t']);
						$ma=$so->get_uid();
						if(isset($ma['uid']) && !isset($ma['error'])){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['uid'], 'text'));
							$result=mysql_query($d_db) or die('');
							$sina_u=$so->show_user_by_id($ma['uid']);
							$me_url='http://weibo.com/'.((isset($sina_u['domain']) && $sina_u['domain']!='')?$sina_u['domain']:$ma['uid']);
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$ma['uid']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($ma['uid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定新浪微博账号<table width="200"><tr><td align="center">'.($sina_u['profile_image_url']!=''?'<img src="'.$sina_u['profile_image_url'].'" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$sina_u['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定新浪微博账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_sina_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$so=new sinaPHP($config['sina_key'], $config['sina_se']);
							$aurl=$so->login_url($config['site_url'].'sina_callback.php');
							header('Location:'.$aurl);
							exit();
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$so=new sinaPHP($config['sina_key'], $config['sina_se']);
						$aurl=$so->login_url($config['site_url'].'sina_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的新浪微博账号建立连接</a>';
					}
					$content.='<br/><br/>绑定新浪微博账号后将实现以下功能：<ol><li>将留言、评论、回复发布到新浪微博</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的新浪微博留言</li><li>使用新浪微博账号登录</li><li>注：新浪微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一新浪微博账号将自动解除绑定</li></ol>';
					break;
				case 'qq':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/qq.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_n from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$qq=qqPHP($config['qq_app_id'], $config['qq_app_key'], $r_dby['s_t']);
						$q_a=$qq->get_openid();
						if(isset($q_a['openid']) && $q_a['openid']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ma['uid'], 'text'));
							$result=mysql_query($d_db) or die('');
							$qq_a=$qq->get_user_info($q_a['openid']);
							$q_name=$qq_a['nickname'];
							if($r_dby['s_n']!=$qq_name || $r_dby['s_id']!=$q_a['openid']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($qq_name, 'text'),
									SQLString($q_a['openid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定QQ账号<table width="200"><tr><td align="center">'.($qq_a['figureurl_1']!=''?'<img src="'.$qq_a['figureurl_1'].'" alt=""/><br/>':'').$q_name.'（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$qq=new qqPHP($config['qq_app_id'], $config['qq_app_key']);
						$qurl=$qq->login_url($config['site_url'].'qq_callback.php');
						$content.='<a href="'.$qurl.'">点击此处和您的QQ账号建立连接</a>';
					}
					$content.='<br/><br/>绑定QQ账号后将实现以下功能：<ol><li>使用QQ账号登录</li><li>注：QQ账号不可以重复绑定，用户绑定后，其他用户绑定的同一QQ账号将自动解除绑定</li></ol>';
					break;
				case 'kx001':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/kaixin.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new kaixinPHP($config['kx001_key'], $config['kx001_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$kx_co=new kaixinPHP($config['kx001_key'], $config['kx001_se'], $r_dby['s_t']);
						$kx_re=$kx_co->me();
						if(isset($kx_re['uid']) && $kx_re['uid']!='' && !isset($kx_re['error_code'])){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($kx_re['uid'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url='http://www.kaixin001.com/home/?uid='.$kx_re['uid'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$kx_re['uid']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($kx_re['uid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定开心网账号<table width="200"><tr><td align="center">'.($kx_re['logo50']!=''?'<img src="'.$kx_re['logo50'].'" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$kx_re['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定开心网账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_kx001_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$kx_uco=new kaixinPHP($config['kx001_key'], $config['kx001_se']);
						$qurl=$kx_uco->login_url($config['site_url'].'kx001_callback.php', 'user_records create_records');
						$content.='<a href="'.$qurl.'">点击此处和您的开心网账号建立连接</a>';
					}
					$content.='<br/><br/>绑定开心网账号后将实现以下功能：<ol><li>将留言、评论、回复发布到开心网动态</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的开心网动态</li><li>使用开心网账号登录</li><li>注：开心网账号不可以重复绑定，用户绑定后，其他用户绑定的同一开心网账号将自动解除绑定</li></ol>';
					break;
				case 'renren':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/renren.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new renrenPHP($config['renren_key'], $config['renren_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$rr_c=new renrenPHP($config['renren_key'], $config['renren_se'], $r_dby['s_t']);
						$rr_me=$rr_c->me();
						if(isset($rr_me[0]['uid']) && $rr_me[0]['uid']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($rr_me[0]['uid'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url='http://www.renren.com/'.$rr_me[0]['uid'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$rr_me[0]['uid']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($rr_me[0]['uid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定人人网账号<table width="200"><tr><td align="center">'.($rr_me[0]['tinyurl']!=''?'<img src="'.$rr_me[0]['tinyurl'].'" alt=""/><br/>':'').'<a href="'.$me_url.'" target="_blank">'.$rr_me[0]['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定人人网账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_renren_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$rr_co=new renrenPHP($config['renren_key'], $config['renren_se']);
						$qurl=$rr_co->login_url($config['site_url'].'renren_callback.php', 'status_update read_user_status');
						$content.='<a href="'.$qurl.'">点击此处和您的人人网账号建立连接</a>';
					}
					$content.='<br/><br/>绑定人人网账号后将实现以下功能：<ol><li>将留言、评论、回复发布到人人网状态</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的人人网状态</li><li>使用人人网账号登录</li><li>注：人人网账号不可以重复绑定，用户绑定后，其他用户绑定的同一人人网账号将自动解除绑定</li></ol>';
					break;
				case 'douban':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/douban.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new doubanPHP($config['douban_key'], $config['douban_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$db_o=new doubanPHP($config['douban_key'], $config['douban_se'], $r_dby['s_t']);
						$me=$db_o->me();
						if(isset($me['id']) && $me['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($me['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_title=$me['name'];
							$me_url=$me['alt'];
							$me_photo=$me['avatar'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$me['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($me['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定豆瓣账号<table width="200"><tr><td align="center"><img src="'.$me_photo.'" alt=""/><br/><a href="'.$me_url.'" target="_blank">'.$me_title.'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定豆瓣账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_douban_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$db_o=new doubanPHP($config['douban_key'], $config['douban_se']);
						$aurl=$db_o->login_url($config['site_url'].'douban_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的豆瓣账号建立连接</a>';
					}
					$content.='<br/><br/>绑定豆瓣账号后将实现以下功能：<ol><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示豆瓣收藏秀</li><li>使用豆瓣账号登录</li><li>注：豆瓣账号不可以重复绑定，用户绑定后，其他用户绑定的同一豆瓣账号将自动解除绑定</li></ol>';
					break;
				case 'baidu':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/baidu.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new baiduPHP($config['baidu_key'], $config['baidu_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$bo=new baiduPHP($config['baidu_key'], $config['baidu_se'], $r_dby['s_t']);
						$ba=$bo->user();
						if(!isset($ba['error_code']) && isset($ba['uid']) && $ba['uid']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ba['uid'], 'text'));
							$result=mysql_query($d_db) or die('');
							if($r_dby['s_id']!=$ba['uid']){
								$u_db=sprintf('update %s set s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($ba['uid'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定百度账号<table width="200"><tr><td align="center"><img src="http://himg.bdimg.com/sys/portraitn/item/'.$ba['portrait'].'.jpg" alt=""/><br/>'.$ba['uname'].'（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$bo=new baiduPHP($config['baidu_key'], $config['baidu_se']);
						$aurl=$bo->login_url($config['site_url'].'baidu_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的百度账号建立连接</a>';
					}
					$content.='<br/><br/>绑定百度账号后将实现以下功能：<ol><li>使用百度账号登录</li><li>注：百度账号不可以重复绑定，用户绑定后，其他用户绑定的同一百度账号将自动解除绑定</li></ol>';
					break;
				case 'google':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/google.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, s_n, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new googlePHP($config['google_key'], $config['google_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$gg_o=new googlePHP($config['google_key'], $config['google_se'], $r_dby['s_t']);
						$gg_a=$gg_o->me();
						if(isset($gg_a['id']) && $gg_a['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($gg_a['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							$me_url=$gg_a['link'];
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$gg_a['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($gg_a['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定Google账号<table width="200"><tr><td align="center"><img src="'.$gg_a['picture'].'" alt=""/><br/><a href="'.$me_url.'" target="_blank">'.$gg_a['name'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定Google账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_google_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$gg_o=new googlePHP($config['google_key'], $config['google_se']);
						$aurl=$gg_o->login_url($config['site_url'].'google_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的Google账号建立连接</a>';
					}
					$content.='<br/><br/>绑定Google账号后将实现以下功能：<ol><li>使用Google账号登录</li><li>注：Google账号不可以重复绑定，用户绑定后，其他用户绑定的同一Google账号将自动解除绑定</li></ol>';
					break;
				case 'live':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/live.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_r, edate, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						if($r_dby['edate']<time() && $r_dby['edate']>0 && $r_dby['s_r']!=''){
							$o=new livePHP($config['live_key'], $config['live_se']);
							$result=$o->access_token_refresh($r_dby['s_r']);
							if(isset($result['access_token']) && $result['access_token']!=''){
								$r_dby['s_t']=$result['access_token'];
								$r_dby['s_r']=$result['refresh_token'];
								$r_dby['edate']=time()+$result['expires_in'];
							}
							$u_db=sprintf('update %s set s_t=%s, s_r=%s, edate=%s, mdate=%s where id=%s', $dbprefix.'m_sync',
								SQLString($r_dby['s_t'], 'text'),
								SQLString($r_dby['s_r'], 'text'),
								SQLString($r_dby['edate'], 'int'),
								time(),
								$r_dby['id']);
							$result=mysql_query($u_db) or die('');
						}
						$ms_o=new livePHP($config['live_key'], $config['live_se'], $r_dby['s_t']);
						$ms_a=$ms_o->me();
						if(isset($ms_a['id']) && $ms_a['id']!=''){
							$is_sync=1;
							$d_db=sprintf('delete from %s where aid<>%s and name=%s and s_id=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'), SQLString($ms_a['id'], 'text'));
							$result=mysql_query($d_db) or die('');
							if($r_dby['s_id']!=$ms_a['id']){
								$u_db=sprintf('update %s set s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($ms_a['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定Microsoft账户<table width="200"><tr><td align="center"><img src="https://apis.live.net/v5.0/me/picture?access_token='.$r_dby['s_t'].'" alt=""/><br/>'.$ms_a['name'].'（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$ms_o=new livePHP($config['live_key'], $config['live_se']);
						$aurl=$ms_o->login_url($config['site_url'].'live_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的Microsoft账户建立连接</a>';
					}
					$content.='<br/><br/>绑定Microsoft账户后将实现以下功能：<ol><li>使用Microsoft账户登录</li><li>注：Microsoft账户不可以重复绑定，用户绑定后，其他用户绑定的同一Microsoft账户将自动解除绑定</li></ol>';
					break;
				case 'instagram':
					if(isset($_GET['lt']) && $_GET['lt']==1){
						$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
						$result=mysql_query($d_db) or die('');
						header('Location:./?m=profile&t=sync&n='.$nct);
						exit();
					}
					require_once('lib/instagram.php');
					$is_sync=0;
					$s_dby=sprintf('select id, s_id, s_t, s_n, is_show from %s where aid=%s and name=%s limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0){
						$io=new instagramPHP($config['instagram_key'], $config['instagram_se'], $r_dby['s_t']);
						$ia=$io->user($r_dby['s_id']);
						if(!isset($ia['meta']['error_type']) && isset($ia['data']['id']) && $ia['data']['id']!=''){
							$is_sync=1;
							$me_url='http://instagram.com/'.$ia['data']['username'].'/';
							if($r_dby['s_n']!=$me_url || $r_dby['s_id']!=$ia['data']['id']){
								$u_db=sprintf('update %s set s_n=%s, s_id=%s where id=%s', $dbprefix.'m_sync',
									SQLString($me_url, 'text'),
									SQLString($ia['data']['id'], 'text'),
									$r_dby['id']);
								$result=mysql_query($u_db) or die('');
							}
							$content.='当前已绑定Instagram账号<table width="200"><tr><td align="center"><img src="'.$ia['data']['profile_picture'].'" alt=""/><br/><a href="'.$me_url.'" target="_blank">'.$ia['data']['username'].'</a>（<a href="?m=profile&amp;t=sync&amp;n='.$nct.'&amp;lt=1">取消绑定</a>）</td></tr></table>';
							$content.='<br/><br/><form method="post" action=""><input type="checkbox" name="is_show" value="1"'.($r_dby['is_show']>0?' checked="checked"':'').'/>隐藏已绑定Instagram账号相关信息<br/><input type="submit" value="更新" class="button"/><input type="hidden" name="isl_instagram_h" value="'.$r_dby['id'].'"/></form>';
						}else{
							$d_db=sprintf('delete from %s where aid=%s and name=%s', $dbprefix.'m_sync', $r_dbu['id'], SQLString($nct, 'text'));
							$result=mysql_query($d_db) or die('');
						}
					}
					mysql_free_result($q_dby);
					if($is_sync==0){
						$io=new instagramPHP($config['instagram_key'], $config['instagram_se']);
						$aurl=$io->login_url($config['site_url'].'instagram_callback.php');
						$content.='<a href="'.$aurl.'">点击此处和您的Instagram账号建立连接</a>';
					}
					$content.='<br/><br/>绑定Instagram账号后将实现以下功能：<ol><li>可以选取Instagram图片添加到照片视频</li><li>在<a href="?m=user&amp;id='.$r_dbu['id'].'">用户信息</a>页面显示最新的Instagram图片</li</ol>';
					break;
			}
			$content.='</div>';
		}else{
			$content.='<div class="formline">管理员还没有'.($pa==9?'<a href="?m=setting&amp;t=sync">':'').'开启绑定功能'.($pa==9?'</a>':'').'。</div>';
		}
		$content.='</div>';
	}
}
?>