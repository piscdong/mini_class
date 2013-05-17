<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

require_once('inc.php');
$lfile='update120601.lock';
if(!file_exists($lfile.'1')){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent">';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once($c_file);
		echo '<div class="title">升级MySQL数据库</div><div class="gcontent"><ul><li>数据库结构有较大变化，建议升级后重新备份数据库</li>';

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `aid`, add PRIMARY KEY (`aid`)", $dbprefix.'online');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'online：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'invite');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'invite：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'topic');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'vote');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'vote：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'photo');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'photo：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'pcomment');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'pcomment：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'camp');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'camp：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'ccomment');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'ccomment：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'cuser');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'cuser：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'message');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'message：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'adminop');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'adminop：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'link');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'link：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop index `id`, add PRIMARY KEY (`id`)", $dbprefix.'skin');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add is_baidu int(5) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add baidu_key varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add baidu_se varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add is_instagram int(5) NOT NULL default '0'", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add instagram_key varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s add instagram_se varchar(255) default NULL", $dbprefix.'main');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s change `lock` is_lock int(5) NOT NULL default '0'", $dbprefix.'topic');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query="create table {$dbprefix}m_sync (
		id int(10) NOT NULL auto_increment,
		aid int(10) NOT NULL default '0',
		name varchar(255) default NULL,
		s_id varchar(255) default NULL,
		s_t varchar(255) default NULL,
		s_s varchar(255) default NULL,
		s_n varchar(255) default NULL,
		is_show int(5) NOT NULL default '0',
		PRIMARY KEY (`id`)
		) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$q_author=sprintf('select * from %s', $dbprefix.'member');
		$author=mysql_query($q_author) or die('');
		$r_author=mysql_fetch_assoc($author);
		if(mysql_num_rows($author)>0){
			do{
				if($r_author['qq_to']!='' && $r_author['qq_se']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('qq', 'text'),
						SQLString($r_author['qq_oid'], 'text'),
						SQLString($r_author['qq_to'], 'text'),
						SQLString($r_author['qq_se'], 'text'),
						SQLString($r_author['qq_n'], 'text'));
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['sina_t']!='' && $r_author['sina_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('sina', 'text'),
						SQLString($r_author['sina_id'], 'text'),
						SQLString($r_author['sina_t'], 'text'),
						SQLString($r_author['sina_s'], 'text'),
						SQLString($r_author['sina_u'], 'text'),
						$r_author['isl_sina']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['tqq_t']!='' && $r_author['tqq_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('tqq', 'text'),
						SQLString($r_author['tqq_id'], 'text'),
						SQLString($r_author['tqq_t'], 'text'),
						SQLString($r_author['tqq_s'], 'text'),
						SQLString($r_author['tqq_u'], 'text'),
						$r_author['isl_tqq']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['renren_t']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_n, is_show) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('renren', 'text'),
						SQLString($r_author['renren_id'], 'text'),
						SQLString($r_author['renren_t'], 'text'),
						SQLString($r_author['renren_u'], 'text'),
						$r_author['isl_renren']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['kx001_t']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('kx001', 'text'),
						SQLString($r_author['kx001_id'], 'text'),
						SQLString($r_author['kx001_t'], 'text'),
						SQLString($r_author['kx001_s'], 'text'),
						SQLString($r_author['kx001_u'], 'text'),
						$r_author['isl_kx001']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['tsohu_t']!='' && $r_author['tsohu_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('tsohu', 'text'),
						SQLString($r_author['tsohu_id'], 'text'),
						SQLString($r_author['tsohu_t'], 'text'),
						SQLString($r_author['tsohu_s'], 'text'),
						SQLString($r_author['tsohu_u'], 'text'),
						$r_author['isl_tsohu']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['t163_t']!='' && $r_author['t163_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('t163', 'text'),
						SQLString($r_author['t163_id'], 'text'),
						SQLString($r_author['t163_t'], 'text'),
						SQLString($r_author['t163_s'], 'text'),
						SQLString($r_author['t163_u'], 'text'),
						$r_author['isl_t163']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['douban_t']!='' && $r_author['douban_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('douban', 'text'),
						SQLString($r_author['douban_id'], 'text'),
						SQLString($r_author['douban_t'], 'text'),
						SQLString($r_author['douban_s'], 'text'),
						SQLString($r_author['douban_u'], 'text'),
						$r_author['isl_douban']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['babab_key']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, is_show) values (%s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('babab', 'text'),
						SQLString($r_author['babab_id'], 'text'),
						SQLString($r_author['babab_key'], 'text'),
						$r_author['isl_babab']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['flickr_uid']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_n, is_show) values (%s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('flickr', 'text'),
						SQLString($r_author['flickr_uid'], 'text'),
						SQLString($r_author['flickr_url'], 'text'),
						SQLString($r_author['flickr_name'], 'text'),
						$r_author['isl_flickr']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['tw_t']!='' && $r_author['tw_s']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, s_t, s_s, s_n, is_show) values (%s, %s, %s, %s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('twitter', 'text'),
						SQLString($r_author['tw_n'], 'text'),
						SQLString($r_author['tw_t'], 'text'),
						SQLString($r_author['tw_s'], 'text'),
						SQLString($r_author['tw_u'], 'text'),
						$r_author['isl_tw']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
				if($r_author['fb_uid']!=''){
					$query=sprintf('insert into %s (aid, name, s_id, is_show) values (%s, %s, %s, %s)', $dbprefix.'m_sync',
						$r_author['id'],
						SQLString('facebook', 'text'),
						SQLString($r_author['fb_uid'], 'text'),
						$r_author['isl_fb']);
					$result=mysql_query($query);
					echo '<li>更新数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
					unset($query);
					unset($result);
				}
			}while($r_author=mysql_fetch_assoc($author));
		}
		mysql_free_result($author);

		$query=sprintf("alter table %s drop qq_oid", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop qq_to", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop qq_se", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop qq_n", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop sina_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop sina_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop sina_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop sina_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_sina", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tqq_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tqq_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tqq_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tqq_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_tqq", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop renren_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop renren_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop renren_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_renren", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop kx001_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop kx001_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop kx001_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop kx001_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_kx001", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tsohu_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tsohu_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tsohu_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tsohu_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_tsohu", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop t163_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop t163_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop t163_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop t163_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_t163", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop douban_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop douban_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop douban_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop douban_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_douban", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop babab_id", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop babab_key", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_babab", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop flickr_uid", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop flickr_url", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop flickr_name", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_flickr", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tw_n", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tw_t", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tw_s", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop tw_u", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_tw", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop fb_uid", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop isl_fb", $dbprefix.'member');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf("alter table %s drop ip", $dbprefix.'online');
		$result=mysql_query($query);
		echo '<li>更新数据表 '.$dbprefix.'online：<span style="font-weight:bold;color:#036;">成功</span></li>';
		unset($query);
		unset($result);

		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($lfile,time());
	}else{
?>
	<div class="title">从 1.1.5 升级到 1.1.6</div>
	<div class="lcontent">
		<form method="post">
			此升级程序只能从 <strong>1.1.5</strong> 升级到 1.1.6，版本太低的用户请先升级到 1.1.5，再使用此升级程序！<br/><br/>
			<div class="formline"><input type="submit" value="下一步" class="button" /></div>
		</form>
	</div>
<?php
	}
	echo getsfoot();
}else{
	header('Location:../');
}
?>