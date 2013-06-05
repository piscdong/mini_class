<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='绑定设置';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$is_qq=(!isset($_POST['is_qq']) || $_POST['is_qq']==0)?0:1;
		$qq_app_id=htmlspecialchars($_POST['qq_app_id'],ENT_QUOTES);
		$qq_app_key=htmlspecialchars($_POST['qq_app_key'],ENT_QUOTES);
		$is_tw=(!isset($_POST['is_tw']) || $_POST['is_tw']==0)?0:1;
		$tw_key=htmlspecialchars($_POST['tw_key'],ENT_QUOTES);
		$tw_se=htmlspecialchars($_POST['tw_se'],ENT_QUOTES);
		$is_fb=(!isset($_POST['is_fb']) || $_POST['is_fb']==0)?0:1;
		$fb_se=htmlspecialchars($_POST['fb_se'],ENT_QUOTES);
		$fb_app_id=htmlspecialchars($_POST['fb_app_id'],ENT_QUOTES);
		$is_flickr=(!isset($_POST['is_flickr']) || $_POST['is_flickr']==0)?0:1;
		$is_uflickr=(isset($_POST['is_flickr']) && $_POST['is_flickr']==1)?1:0;
		$flickr_key=htmlspecialchars($_POST['flickr_key'],ENT_QUOTES);
		$is_sina=(!isset($_POST['is_sina']) || $_POST['is_sina']==0)?0:1;
		$sina_key=htmlspecialchars($_POST['sina_key'],ENT_QUOTES);
		$sina_se=htmlspecialchars($_POST['sina_se'],ENT_QUOTES);
		$is_tqq=(!isset($_POST['is_tqq']) || $_POST['is_tqq']==0)?0:1;
		$is_utqq=(isset($_POST['is_tqq']) && $_POST['is_tqq']==1)?1:0;
		$tqq_key=htmlspecialchars($_POST['tqq_key'],ENT_QUOTES);
		$tqq_se=htmlspecialchars($_POST['tqq_se'],ENT_QUOTES);
		$is_t163=(!isset($_POST['is_t163']) || $_POST['is_t163']==0)?0:1;
		$t163_key=htmlspecialchars($_POST['t163_key'],ENT_QUOTES);
		$t163_se=htmlspecialchars($_POST['t163_se'],ENT_QUOTES);
		$is_tsohu=(!isset($_POST['is_tsohu']) || $_POST['is_tsohu']==0)?0:1;
		$is_utsohu=(isset($_POST['is_tsohu']) && $_POST['is_tsohu']==1)?1:0;
		$tsohu_key=htmlspecialchars($_POST['tsohu_key'],ENT_QUOTES);
		$tsohu_se=htmlspecialchars($_POST['tsohu_se'],ENT_QUOTES);
		$is_babab=(!isset($_POST['is_babab']) || $_POST['is_babab']==0)?0:1;
		$is_ubabab=(isset($_POST['is_babab']) && $_POST['is_babab']==1)?1:0;
		$babab_key=htmlspecialchars($_POST['babab_key'],ENT_QUOTES);
		$is_kx001=(!isset($_POST['is_kx001']) || $_POST['is_kx001']==0)?0:1;
		$kx001_key=htmlspecialchars($_POST['kx001_key'],ENT_QUOTES);
		$kx001_se=htmlspecialchars($_POST['kx001_se'],ENT_QUOTES);
		$is_renren=(!isset($_POST['is_renren']) || $_POST['is_renren']==0)?0:1;
		$renren_key=htmlspecialchars($_POST['renren_key'],ENT_QUOTES);
		$renren_se=htmlspecialchars($_POST['renren_se'],ENT_QUOTES);
		$is_douban=(!isset($_POST['is_douban']) || $_POST['is_douban']==0)?0:1;
		$douban_key=htmlspecialchars($_POST['douban_key'],ENT_QUOTES);
		$douban_se=htmlspecialchars($_POST['douban_se'],ENT_QUOTES);
		$is_baidu=(!isset($_POST['is_baidu']) || $_POST['is_baidu']==0)?0:1;
		$baidu_key=htmlspecialchars($_POST['baidu_key'],ENT_QUOTES);
		$baidu_se=htmlspecialchars($_POST['baidu_se'],ENT_QUOTES);
		$is_instagram=(!isset($_POST['is_instagram']) || $_POST['is_instagram']==0)?0:1;
		$instagram_key=htmlspecialchars($_POST['instagram_key'],ENT_QUOTES);
		$instagram_se=htmlspecialchars($_POST['instagram_se'],ENT_QUOTES);
		$is_google=(!isset($_POST['is_google']) || $_POST['is_google']==0)?0:1;
		$google_key=htmlspecialchars($_POST['google_key'],ENT_QUOTES);
		$google_se=htmlspecialchars($_POST['google_se'],ENT_QUOTES);
		$is_live=(!isset($_POST['is_live']) || $_POST['is_live']==0)?0:1;
		$live_key=htmlspecialchars($_POST['live_key'],ENT_QUOTES);
		$live_se=htmlspecialchars($_POST['live_se'],ENT_QUOTES);
		$u_db=sprintf('update %s set is_qq=%s, qq_app_id=%s, qq_app_key=%s, is_tw=%s, tw_key=%s, tw_se=%s, is_fb=%s, fb_se=%s, fb_app_id=%s, is_flickr=%s, is_uflickr=%s, flickr_key=%s, is_sina=%s, sina_key=%s, sina_se=%s, is_tqq=%s, is_utqq=%s, tqq_key=%s, tqq_se=%s, is_t163=%s, t163_key=%s, t163_se=%s, is_tsohu=%s, is_utsohu=%s, tsohu_key=%s, tsohu_se=%s, is_babab=%s, is_ubabab=%s, babab_key=%s, is_kx001=%s, kx001_key=%s, kx001_se=%s, is_renren=%s, renren_key=%s, renren_se=%s, is_douban=%s, douban_key=%s, douban_se=%s, is_baidu=%s, baidu_key=%s, baidu_se=%s, is_instagram=%s, instagram_key=%s, instagram_se=%s, is_google=%s, google_key=%s, google_se=%s, is_live=%s, live_key=%s, live_se=%s', $dbprefix.'main',
			$is_qq,
			SQLString($qq_app_id, 'text'),
			SQLString($qq_app_key, 'text'),
			$is_tw,
			SQLString($tw_key, 'text'),
			SQLString($tw_se, 'text'),
			$is_fb,
			SQLString($fb_se, 'text'),
			SQLString($fb_app_id, 'text'),
			$is_flickr,
			$is_uflickr,
			SQLString($flickr_key, 'text'),
			$is_sina,
			SQLString($sina_key, 'text'),
			SQLString($sina_se, 'text'),
			$is_tqq,
			$is_utqq,
			SQLString($tqq_key, 'text'),
			SQLString($tqq_se, 'text'),
			$is_t163,
			SQLString($t163_key, 'text'),
			SQLString($t163_se, 'text'),
			$is_tsohu,
			$is_utsohu,
			SQLString($tsohu_key, 'text'),
			SQLString($tsohu_se, 'text'),
			$is_babab,
			$is_ubabab,
			SQLString($babab_key, 'text'),
			$is_kx001,
			SQLString($kx001_key, 'text'),
			SQLString($kx001_se, 'text'),
			$is_renren,
			SQLString($renren_key, 'text'),
			SQLString($renren_se, 'text'),
			$is_douban,
			SQLString($douban_key, 'text'),
			SQLString($douban_se, 'text'),
			$is_baidu,
			SQLString($baidu_key, 'text'),
			SQLString($baidu_se, 'text'),
			$is_instagram,
			SQLString($instagram_key, 'text'),
			SQLString($instagram_se, 'text'),
			$is_google,
			SQLString($google_key, 'text'),
			SQLString($google_se, 'text'),
			$is_live,
			SQLString($live_key, 'text'),
			SQLString($live_se, 'text'));
		$result=mysql_query($u_db) or die('');
		$e=1;
		header('Location:./?m=setting&t=sync'.(isset($e)?'&e=1':''));
		exit();
	}else{
		$phpv=phpversion();
		$is_curl=function_exists('curl_init')?1:0;
		$is_json=function_exists('json_decode')?1:0;
		$a_msg=array(1=>'设置已修改。');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">绑定设置</div><div class="lcontent"><form method="post" action="">';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-qq.gif);">绑定QQ</div>
<div class="formline">
	<span name="hs_cbt" data-id="qq_h|qq_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="qq_s|qq_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="qq_s" style="display: none;">
	绑定QQ账号后将实现以下功能：
	<ol>
		<li>使用QQ账号登录</li>
		<li>注：QQ账号不可以重复绑定，用户绑定后，其他用户绑定的同一QQ账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="qq_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定QQ
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_qq" value="0"'.($config['is_qq']==0?' checked="checked"':'').' rel="h_cbt" data-id="qq_t_div"/>不开启绑定QQ<br/>
	<input type="radio" name="is_qq" value="1"'.($config['is_qq']>0?' checked="checked"':'').' rel="s_cbt" data-id="qq_t_div"/>开启绑定QQ
</div>
<div id="qq_t_div"'.($config['is_qq']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://connect.qq.com/" rel="external">QQ互联开放平台</a>申请接入，获得<strong>APP ID</strong>和<strong>APP KEY</strong><br/>回调地址：'.$config['site_url'].'qq_callback.php<br/>申请接入的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q8">点击查看</a></li>
			<li>将<strong>APP ID</strong>和<strong>APP KEY</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>APP ID：</td><td><input name="qq_app_id" size="32" value="'.$config['qq_app_id'].'" /></td></tr>
		<tr><td>APP KEY：</td><td><input name="qq_app_key" size="32" value="'.$config['qq_app_key'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-sina.gif);margin-top: 20px;">绑定新浪微博</div>
<div class="formline">
	<span name="hs_cbt" data-id="sina_h|sina_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="sina_s|sina_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="sina_s" style="display: none;">
	绑定新浪微博账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到新浪微博</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的新浪微博留言</li>
		<li>使用新浪微博账号登录</li>
		<li>注：新浪微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一新浪微博账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="sina_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定新浪微博
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_sina" value="0"'.($config['is_sina']==0?' checked="checked"':'').' rel="h_cbt" data-id="sina_t_div"/>不开启绑定新浪微博<br/>
	<input type="radio" name="is_sina" value="1"'.($config['is_sina']>0?' checked="checked"':'').' rel="s_cbt" data-id="sina_t_div"/>自定义App Key和App Secret开启绑定新浪微博
</div>
<div id="sina_t_div"'.($config['is_sina']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://open.weibo.com/" rel="external">新浪微博开放平台</a>注册一个应用，获得<strong>App Key</strong>和<strong>App Secret</strong><br/>回调地址：'.$config['site_url'].'sina_callback.php<br/>注册应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q3">点击查看</a></li>
			<li>将<strong>App Key</strong>和<strong>App Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>App Key：</td><td><input name="sina_key" size="32" value="'.$config['sina_key'].'" /></td></tr>
		<tr><td>App Secret：</td><td><input name="sina_se" size="32" value="'.$config['sina_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-tqq.gif);margin-top: 20px;">绑定腾讯微博</div>
<div class="formline">
	<span name="hs_cbt" data-id="tqq_h|tqq_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="tqq_s|tqq_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="tqq_s" style="display: none;">
	绑定腾讯微博账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到腾讯微博</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的腾讯微博留言</li>
		<li>使用腾讯微博账号登录</li>
		<li>注：腾讯微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一腾讯微博账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="tqq_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定腾讯微博
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_tqq" value="0"'.($config['is_tqq']==0?' checked="checked"':'').' rel="h_cbt" data-id="tqq_t_div"/>不开启绑定腾讯微博<br/>
	<input type="radio" name="is_tqq" value="1"'.(($config['is_tqq']>0 && $config['is_utqq']>0)?' checked="checked"':'').' rel="h_cbt" data-id="tqq_t_div"/>使用默认App Key和App Secret开启绑定腾讯微博（不保证一直可用）<br/>
	<input type="radio" name="is_tqq" value="2"'.(($config['is_tqq']>0 && $config['is_utqq']==0)?' checked="checked"':'').' rel="s_cbt" data-id="tqq_t_div"/>自定义App Key和App Secret开启绑定腾讯微博
</div>
<div id="tqq_t_div"'.(($config['is_tqq']>0 && $config['is_utqq']==0)?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://dev.t.qq.com/" rel="external">腾讯微博开放平台</a>创建一个应用，获得<strong>App Key</strong>和<strong>App Secret</strong><br/>回调地址：'.$config['site_url'].'tqq_callback.php<br/>创建应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q9">点击查看</a></li>
			<li>将<strong>App Key</strong>和<strong>App Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>App Key：</td><td><input name="tqq_key" size="32" value="'.$config['tqq_key'].'" /></td></tr>
		<tr><td>App Secret：</td><td><input name="tqq_se" size="32" value="'.$config['tqq_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-renren.gif);margin-top: 20px;">绑定人人网</div>
<div class="formline">
	<span name="hs_cbt" data-id="renren_h|renren_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="renren_s|renren_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="renren_s" style="display: none;">
	绑定人人网账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到人人网状态</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的人人网状态</li>
		<li>使用人人网账号登录</li>
		<li>注：人人网账号不可以重复绑定，用户绑定后，其他用户绑定的同一人人网账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="renren_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定人人网
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_renren" value="0"'.($config['is_renren']==0?' checked="checked"':'').' rel="h_cbt" data-id="renren_t_div"/>不开启绑定人人网<br/>
	<input type="radio" name="is_renren" value="1"'.($config['is_renren']>0?' checked="checked"':'').' rel="s_cbt" data-id="renren_t_div"/>开启绑定人人网
</div>
<div id="renren_t_div"'.($config['is_renren']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://dev.renren.com/" rel="external">人人网 开放平台</a>注册一个应用，获得<strong>API Key</strong>和<strong>Secret</strong><br/>回调地址：'.$config['site_url'].'renren_callback.php<br/>注册应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q13">点击查看</a></li>
			<li>将<strong>API Key</strong>和<strong>Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>API Key：</td><td><input name="renren_key" size="32" value="'.$config['renren_key'].'" /></td></tr>
		<tr><td>Secret：</td><td><input name="renren_se" size="32" value="'.$config['renren_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-kx001.gif);margin-top: 20px;">绑定开心网</div>
<div class="formline">
	<span name="hs_cbt" data-id="kx001_h|kx001_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="kx001_s|kx001_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="kx001_s" style="display: none;">
	绑定开心网账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到开心网动态</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的开心网动态</li>
		<li>使用开心网账号登录</li>
		<li>注：开心网账号不可以重复绑定，用户绑定后，其他用户绑定的同一开心网账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="kx001_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定开心网
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_kx001" value="0"'.($config['is_kx001']==0?' checked="checked"':'').' rel="h_cbt" data-id="kx001_t_div"/>不开启绑定开心网<br/>
	<input type="radio" name="is_kx001" value="1"'.($config['is_kx001']>0?' checked="checked"':'').' rel="s_cbt" data-id="kx001_t_div"/>开启绑定开心网
</div>
<div id="kx001_t_div"'.($config['is_kx001']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://open.kaixin001.com/" rel="external">开心网开放平台</a>注册一个组件，获得<strong>API Key</strong>和<strong>Secret Key</strong><br/>回调地址：'.$config['site_url'].'kx001_callback.php<br/>注册组件的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q12">点击查看</a></li>
			<li>将<strong>API Key</strong>和<strong>Secret Key</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>API Key：</td><td><input name="kx001_key" size="32" value="'.$config['kx001_key'].'" /></td></tr>
		<tr><td>Secret Key：</td><td><input name="kx001_se" size="32" value="'.$config['kx001_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-baidu.gif);margin-top: 20px;">绑定百度</div>
<div class="formline">
	<span name="hs_cbt" data-id="baidu_h|baidu_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="baidu_s|baidu_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="baidu_s" style="display: none;">
	绑定百度账号后将实现以下功能：
	<ol>
		<li>使用百度账号登录</li>
		<li>注：百度账号不可以重复绑定，用户绑定后，其他用户绑定的同一百度账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="baidu_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定百度
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_baidu" value="0"'.($config['is_baidu']==0?' checked="checked"':'').' rel="h_cbt" data-id="baidu_t_div"/>不开启绑定百度<br/>
	<input type="radio" name="is_baidu" value="1"'.($config['is_baidu']>0?' checked="checked"':'').' rel="s_cbt" data-id="baidu_t_div"/>开启绑定百度
</div>
<div id="baidu_t_div"'.($config['is_baidu']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://developer.baidu.com/" rel="external">百度开发者中心</a>创建一个应用，获得<strong>API Key</strong>和<strong>Secret Key</strong><br/>回调地址：'.$config['site_url'].'baidu_callback.php<br/>创建新应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q15">点击查看</a></li>
			<li>将<strong>API Key</strong>和<strong>Secret Key</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>API Key：</td><td><input name="baidu_key" size="32" value="'.$config['baidu_key'].'" /></td></tr>
		<tr><td>Secret Key：</td><td><input name="baidu_se" size="32" value="'.$config['baidu_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-douban.gif);margin-top: 20px;">绑定豆瓣</div>
<div class="formline">
	<span name="hs_cbt" data-id="douban_h|douban_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="douban_s|douban_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="douban_s" style="display: none;">
	绑定豆瓣账号后将实现以下功能：
	<ol>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示豆瓣收藏秀</li>
		<li>使用豆瓣账号登录</li>
		<li>注：豆瓣账号不可以重复绑定，用户绑定后，其他用户绑定的同一豆瓣账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="douban_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定豆瓣
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_douban" value="0"'.($config['is_douban']==0?' checked="checked"':'').' rel="h_cbt" data-id="douban_t_div"/>不开启绑定豆瓣<br/>
	<input type="radio" name="is_douban" value="1"'.($config['is_douban']>0?' checked="checked"':'').' rel="s_cbt" data-id="douban_t_div"/>开启绑定豆瓣
</div>
<div id="douban_t_div"'.($config['is_douban']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://developers.douban.com/" rel="external">豆瓣开发者服务</a>创建一个应用，获得<strong>API Key</strong>和<strong>Secret</strong><br/>回调地址：'.$config['site_url'].'douban_callback.php<br/>创建新应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q14">点击查看</a></li>
			<li>将<strong>API key</strong>和<strong>Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>API key：</td><td><input name="douban_key" size="32" value="'.$config['douban_key'].'" /></td></tr>
		<tr><td>Secret：</td><td><input name="douban_se" size="32" value="'.$config['douban_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-google.gif);margin-top: 20px;">绑定Google</div>
<div class="formline">
	<span name="hs_cbt" data-id="google_h|google_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="google_s|google_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="google_s" style="display: none;">
	绑定Google账号后将实现以下功能：
	<ol>
		<li>使用Google账号登录</li>
		<li>注：Google账号不可以重复绑定，用户绑定后，其他用户绑定的同一Google账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="google_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定Google
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_google" value="0"'.($config['is_google']==0?' checked="checked"':'').' rel="h_cbt" data-id="google_t_div"/>不开启绑定Google<br/>
	<input type="radio" name="is_google" value="1"'.($config['is_google']>0?' checked="checked"':'').' rel="s_cbt" data-id="google_t_div"/>开启绑定Google
</div>
<div id="google_t_div"'.($config['is_google']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="https://code.google.com/apis/console/" rel="external">Google APIs Console</a>创建一个Client ID，获得<strong>Client ID</strong>和<strong>Client secret</strong><br/>回调地址：'.$config['site_url'].'google_callback.php<br/>创建Client ID的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q17">点击查看</a></li>
			<li>将<strong>Client ID</strong>和<strong>Client secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Client ID：</td><td><input name="google_key" size="32" value="'.$config['google_key'].'" /></td></tr>
		<tr><td>Client secret：</td><td><input name="google_se" size="32" value="'.$config['google_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-live.gif);margin-top: 20px;">绑定Microsoft账户</div>
<div class="formline">
	<span name="hs_cbt" data-id="live_h|live_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="live_s|live_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="live_s" style="display: none;">
	绑定Microsoft账户后将实现以下功能：
	<ol>
		<li>使用Microsoft账户登录</li>
		<li>注：Microsoft账户不可以重复绑定，用户绑定后，其他用户绑定的同一Microsoft账户将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="live_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定Microsoft账户
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_live" value="0"'.($config['is_live']==0?' checked="checked"':'').' rel="h_cbt" data-id="live_t_div"/>不开启绑定Microsoft账户<br/>
	<input type="radio" name="is_live" value="1"'.($config['is_live']>0?' checked="checked"':'').' rel="s_cbt" data-id="live_t_div"/>开启绑定Microsoft账户
</div>
<div id="live_t_div"'.($config['is_live']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://msdn.microsoft.com/live/" rel="external">Live Connect 开发人员中心</a>创建一个应用程序，获得<strong>客户端 ID</strong>和<strong>客户端密钥</strong><br/>回调地址：'.$config['site_url'].'live_callback.php<br/>创建应用程序的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q18">点击查看</a></li>
			<li>将<strong>客户端 ID</strong>和<strong>客户端密钥</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>客户端 ID：</td><td><input name="live_key" size="32" value="'.$config['live_key'].'" /></td></tr>
		<tr><td>客户端密钥：</td><td><input name="live_se" size="32" value="'.$config['live_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-t163.gif);margin-top: 20px;">绑定网易微博</div>
<div class="formline">
	<span name="hs_cbt" data-id="t163_h|t163_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="t163_s|t163_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="t163_s" style="display: none;">
	绑定网易微博账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到网易微博</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的网易微博留言</li>
		<li>使用网易微博账号登录</li>
		<li>注：网易微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一网易微博账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="t163_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定网易微博
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_t163" value="0"'.($config['is_t163']==0?' checked="checked"':'').' rel="h_cbt" data-id="t163_t_div"/>不开启绑定网易微博<br/>
	<input type="radio" name="is_t163" value="1"'.($config['is_t163']>0?' checked="checked"':'').' rel="s_cbt" data-id="t163_t_div"/>开启绑定网易微博
</div>
<div id="t163_t_div"'.($config['is_t163']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://open.t.163.com/" rel="external">网易微博开放平台</a>创建一个应用，获得<strong>Consumer Key</strong>和<strong>Consumer Secret</strong><br/>回调地址：'.$config['site_url'].'t163_callback.php<br/>创建应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q11">点击查看</a></li>
			<li>将<strong>Consumer Key</strong>和<strong>Consumer Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Consumer Key：</td><td><input name="t163_key" size="32" value="'.$config['t163_key'].'" /></td></tr>
		<tr><td>Consumer Secret：</td><td><input name="t163_se" size="32" value="'.$config['t163_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-tsohu.gif);margin-top: 20px;">绑定搜狐微博</div>
<div class="formline">
	<span name="hs_cbt" data-id="tsohu_h|tsohu_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="tsohu_s|tsohu_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="tsohu_s" style="display: none;">
	绑定搜狐微博账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到搜狐微博</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的搜狐微博留言</li>
		<li>使用搜狐微博账号登录</li>
		<li>注：搜狐微博账号不可以重复绑定，用户绑定后，其他用户绑定的同一搜狐微博账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="tsohu_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定搜狐微博
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_tsohu" value="0"'.($config['is_tsohu']==0?' checked="checked"':'').' rel="h_cbt" data-id="tsohu_t_div"/>不开启绑定搜狐微博<br/>
	<input type="radio" name="is_tsohu" value="1"'.(($config['is_tsohu']>0 && $config['is_utsohu']>0)?' checked="checked"':'').' rel="h_cbt" data-id="tsohu_t_div"/>使用默认Consumer key和Consumer secret开启绑定搜狐微博（不保证一直可用）<br/>
	<input type="radio" name="is_tsohu" value="2"'.(($config['is_tsohu']>0 && $config['is_utsohu']==0)?' checked="checked"':'').' rel="s_cbt" data-id="tsohu_t_div"/>自定义Consumer key和Consumer secret开启绑定搜狐微博
</div>
<div id="tsohu_t_div"'.(($config['is_tsohu']>0 && $config['is_utsohu']==0)?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://open.t.sohu.com/" rel="external">搜狐微博 开放平台</a>创建一个应用，获得<strong>Consumer key</strong>和<strong>Consumer secret</strong><br/>回调地址：'.$config['site_url'].'tsohu_callback.php<br/>创建应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q10">点击查看</a></li>
			<li>将<strong>Consumer key</strong>和<strong>Consumer secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Consumer key：</td><td><input name="tsohu_key" size="32" value="'.$config['tsohu_key'].'" /></td></tr>
		<tr><td>Consumer secret：</td><td><input name="tsohu_se" size="32" value="'.$config['tsohu_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-instagram.gif);margin-top: 20px;">绑定Instagram</div>
<div class="formline">
	<span name="hs_cbt" data-id="instagram_h|instagram_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="instagram_s|instagram_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="instagram_s" style="display: none;">
	绑定Instagram账号后将实现以下功能：
	<ol>
		<li>可以选取Instagram图片添加到照片视频</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的Instagram图片</li>
	</ol>
</div>
<div class="formline" id="instagram_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定Instagram
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_instagram" value="0"'.($config['is_instagram']==0?' checked="checked"':'').' rel="h_cbt" data-id="instagram_t_div"/>不开启绑定Instagram<br/>
	<input type="radio" name="is_instagram" value="1"'.($config['is_instagram']>0?' checked="checked"':'').' rel="s_cbt" data-id="instagram_t_div"/>开启绑定Instagram
</div>
<div id="instagram_t_div"'.($config['is_instagram']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://instagram.com/developer/" rel="external">Instagram Developer Documentation</a>创建一个应用，获得<strong>CLIENT ID</strong>和<strong>CLIENT SECRET</strong><br/>回调地址：'.$config['site_url'].'instagram_callback.php<br/>创建新应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q16">点击查看</a></li>
			<li>将<strong>CLIENT ID</strong>和<strong>CLIENT SECRET</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>CLIENT ID：</td><td><input name="instagram_key" size="32" value="'.$config['instagram_key'].'" /></td></tr>
		<tr><td>CLIENT SECRET：</td><td><input name="instagram_se" size="32" value="'.$config['instagram_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-babab.gif);margin-top: 20px;">绑定巴巴变</div>
<div class="formline">
	<span name="s_cbt" data-id="babab_s" class="mlink f_link">功能说明</span>
</div>
<div class="formline" id="babab_s" style="display: none;">
	绑定巴巴变账号后将实现以下功能：
	<ol>
		<li>可以选取巴巴变公开图片添加到照片视频</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的巴巴变公开图片</li>
		<li>注：由于巴巴变的外链政策，非VIP账号无法外链，图片可能会无法正常显示，请酌情使用</li>
	</ol>
</div>
<div class="formline">
	<input type="radio" name="is_babab" value="0"'.($config['is_babab']==0?' checked="checked"':'').' rel="h_cbt" data-id="babab_t_div"/>不开启绑定巴巴变<br/>
	<input type="radio" name="is_babab" value="1"'.(($config['is_babab']>0 && $config['is_ubabab']>0)?' checked="checked"':'').' rel="h_cbt" data-id="babab_t_div"/>使用默认api_key开启绑定巴巴变（不保证一直可用）<br/>
	<input type="radio" name="is_babab" value="2"'.(($config['is_babab']>0 && $config['is_ubabab']==0)?' checked="checked"':'').' rel="s_cbt" data-id="babab_t_div"/>自定义api_key开启绑定巴巴变
</div>
<div id="babab_t_div"'.(($config['is_babab']>0 && $config['is_ubabab']==0)?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://www.bababian.com/api/api.htm" rel="external">巴巴变 api</a>申请一个<strong>api_key</strong><br/>申请的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q7">点击查看</a></li>
			<li>将<strong>api_key</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>api_key：</td><td><input name="babab_key" size="32" value="'.$config['babab_key'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-flickr.gif);margin-top: 20px;">绑定Flickr</div>
<div class="formline">
	<span name="s_cbt" data-id="flickr_s" class="mlink f_link">功能说明</span>
</div>
<div class="formline" id="flickr_s" style="display: none;">
	绑定Flickr账号后将实现以下功能：
	<ol>
		<li>可以选取Flickr公开图片添加到照片视频</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的Flickr公开图片</li>
	</ol>
</div>
<div class="formline">
	<input type="radio" name="is_flickr" value="0"'.($config['is_flickr']==0?' checked="checked"':'').' rel="h_cbt" data-id="flickr_t_div"/>不开启绑定Flickr<br/>
	<input type="radio" name="is_flickr" value="1"'.(($config['is_flickr']>0 && $config['is_uflickr']>0)?' checked="checked"':'').' rel="h_cbt" data-id="flickr_t_div"/>使用默认Key开启绑定Flickr（不保证一直可用）<br/>
	<input type="radio" name="is_flickr" value="2"'.(($config['is_flickr']>0 && $config['is_uflickr']==0)?' checked="checked"':'').' rel="s_cbt" data-id="flickr_t_div"/>自定义Key开启绑定Flickr
</div>
<div id="flickr_t_div"'.(($config['is_flickr']>0 && $config['is_uflickr']==0)?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="http://www.flickr.com/services/api/" rel="external">Flickr服务</a>注册一个应用，获得<strong>Key</strong><br/>注册应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q4">点击查看</a></li>
			<li>将<strong>Key</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Key：</td><td><input name="flickr_key" size="32" value="'.$config['flickr_key'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-twitter.gif);margin-top: 20px;">绑定Twitter</div>
<div class="formline">
	<span name="hs_cbt" data-id="twitter_h|twitter_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="twitter_s|twitter_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="twitter_s" style="display: none;">
	绑定Twitter账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到Twitter</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的Twitter留言</li>
		<li>使用Twitter账号登录</li>
		<li>注：Twitter账号不可以重复绑定，用户绑定后，其他用户绑定的同一Twitter账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="twitter_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定Twitter
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_tw" value="0"'.($config['is_tw']==0?' checked="checked"':'').' rel="h_cbt" data-id="tw_t_div"/>不开启绑定Twitter<br/>
	<input type="radio" name="is_tw" value="1"'.($config['is_tw']>0?' checked="checked"':'').' rel="s_cbt" data-id="tw_t_div"/>开启绑定Twitter
</div>
<div id="tw_t_div"'.($config['is_tw']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="https://dev.twitter.com/" rel="external">Twitter</a>注册一个应用，获得<strong>Consumer key</strong>和<strong>Consumer secret</strong><br/>回调地址：'.$config['site_url'].'twitter_callback.php<br/>注册应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q5">点击查看</a></li>
			<li>将<strong>Consumer key</strong>和<strong>Consumer secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Consumer key：</td><td><input name="tw_key" size="32" value="'.$config['tw_key'].'" /></td></tr>
		<tr><td>Consumer secret：</td><td><input name="tw_se" size="32" value="'.$config['tw_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="sync_list" style="font-weight: bold;background-image: url(images/i-facebook.gif);margin-top: 20px;">绑定Facebook</div>
<div class="formline">
	<span name="hs_cbt" data-id="facebook_h|facebook_s" class="mlink f_link">功能说明</span> <span name="hs_cbt" data-id="facebook_s|facebook_h" class="mlink f_link">环境要求</span>
</div>
<div class="formline" id="facebook_s" style="display: none;">
	绑定Facebook账号后将实现以下功能：
	<ol>
		<li>将留言、评论、回复发布到Facebook涂鸦墙</li>
		<li>在<a href="?m=user&amp;id='.$ar['id'].'">用户信息</a>页面显示最新的Facebook涂鸦墙内容</li>
		<li>使用Facebook账号登录</li>
		<li>注：Facebook账号不可以重复绑定，用户绑定后，其他用户绑定的同一Facebook账号将自动解除绑定</li>
	</ol>
</div>
<div class="formline" id="facebook_h" style="display: none;">
	请根据环境要求及当前环境实际情况决定是否开启绑定Facebook
	<table cellpadding="5">
		<tr><th></th><th>环境要求</th><th>当前环境</th></tr>
		<tr><td align="center">PHP版本</td><td align="center">5.2及以上</td><td align="center">'.$phpv.'</td></tr>
		<tr><td align="center">curl</td><td align="center">支持</td><td align="center">'.($is_curl>0?'':'不').'支持</td></tr>
		<tr><td align="center">json</td><td align="center">支持</td><td align="center">'.($is_json>0?'':'不').'支持</td></tr>
	</table>
</div>
<div class="formline">
	<input type="radio" name="is_fb" value="0"'.($config['is_fb']==0?' checked="checked"':'').' rel="h_cbt" data-id="fb_t_div"/>不开启绑定Facebook<br/>
	<input type="radio" name="is_fb" value="1"'.($config['is_fb']>0?' checked="checked"':'').' rel="s_cbt" data-id="fb_t_div"/>开启绑定Facebook
</div>
<div id="fb_t_div"'.($config['is_fb']>0?'':' style="display: none;"').'>
	<div class="formline">
		<ol>
			<li>到<a href="https://developers.facebook.com/" rel="external">Facebook组件开发人员</a>注册一个应用，获得<strong>Application ID</strong>和<strong>Application Secret</strong><br/>注册应用的详细方法请<a href="http://www.piscdong.com/mini_class/?m=help#q6">点击查看</a></li>
			<li>将<strong>Application ID</strong>和<strong>Application Secret</strong>填入下表</li>
		</ol>
	</div>
	<table>
		<tr><td>Application ID：</td><td><input name="fb_app_id" size="32" value="'.$config['fb_app_id'].'" /></td></tr>
		<tr><td>Application Secret：</td><td><input name="fb_se" size="32" value="'.$config['fb_se'].'" /></td></tr>
	</table>
</div>';
		$content.='<div class="formline"><input type="submit" value="修改" class="button" /></div></form></div>';
	}
}
