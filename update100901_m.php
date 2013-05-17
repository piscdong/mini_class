<?php
/////////////////////////////////////////////////////////////////////////////
// 迷你同学录 (http://www.piscdong.com/?m=mini_class)
//
// (c)PiscDong studio (http://www.piscdong.com/)
//
// 程序完全免费，请保留这段代码。
// 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
/////////////////////////////////////////////////////////////////////////////

function chksqlv(){
	return version_compare(mysql_get_server_info(), '4.1.0', '>=');
}

$app_n='迷你同学录';

$lname='update100901';
$lfile=$lname.'.lock';
$sql_f='sql-107.php';
$b_file='config.php';
$c_file='../'.$b_file;

if(!file_exists($lfile)){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>升级 '.$app_n.'</title><link rel="stylesheet" type="text/css" title="Default" href="../styles.css" /></head><body><div id="body"><div id="top"><div id="logo">'.$app_n.'</div></div><div id="main"><div class="tcontent"><div class="title">从 1.0.7 升级到 1.1.0</div>';
	if(file_exists($sql_f)){
		if(isset($_GET['n']) && $_GET['n']==md5('1')){
			require_once($c_file);
			echo '<div class="gcontent"><strong>更新数据库</strong><ul>';
			$query="create table {$dbprefix}main (
			id int(10) NOT NULL auto_increment,
			title varchar(255) NOT NULL,
			school varchar(255) default NULL,
			classname varchar(255) default NULL,
			open int(5) NOT NULL default '0',
			openreg int(5) NOT NULL default '0',
			gid varchar(255) default '1|2|3',
			content text,
			email int(5) NOT NULL default '0',
			pagesize int(10) NOT NULL default '20',
			upload int(5) NOT NULL default '0',
			maxsize int(10) NOT NULL default '0',
			filetype varchar(255) default 'jpg',
			thum int(5) NOT NULL default '0',
			avator int(5) NOT NULL default '10',
			slink int(5) NOT NULL default '0',
			veri int(5) NOT NULL default '0',
			icp varchar(255) default NULL,
			skin int(10) NOT NULL default '0',
			timefix varchar(255) NOT NULL default '0',
			ip varchar(255) default NULL,
			is_tw int(5) NOT NULL default '0',
			tw_key varchar(255) default NULL,
			tw_se varchar(255) default NULL,
			is_fb int(5) NOT NULL default '0',
			fb_se varchar(255) default NULL,
			fb_app_id varchar(255) default NULL,
			is_flickr int(5) NOT NULL default '0',
			is_uflickr int(5) NOT NULL default '0',
			flickr_key varchar(255) default NULL,
			is_sina int(5) NOT NULL default '0',
			is_usina int(5) NOT NULL default '0',
			sina_key varchar(255) default NULL,
			sina_se varchar(255) default NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}member (
			id int(10) NOT NULL auto_increment,
			username varchar(255) NOT NULL,
			password varchar(255) NOT NULL,
			name varchar(255) NOT NULL,
			status int(5) NOT NULL default '0',
			power int(5) NOT NULL default '0',
			regdate int(15) NOT NULL default '0',
			visit int(10) NOT NULL default '0',
			visitdate int(15) NOT NULL default '0',
			question varchar(255) default NULL,
			answer varchar(255) default NULL,
			email varchar(255) default NULL,
			gender int(5) NOT NULL default '0',
			bir_y int(4) NOT NULL default '0',
			bir_m int(2) NOT NULL default '0',
			bir_d int(2) NOT NULL default '0',
			address varchar(255) default NULL,
			location varchar(255) default NULL,
			url varchar(255) default NULL,
			work varchar(255) default NULL,
			phone varchar(255) default NULL,
			tel varchar(255) default NULL,
			qq varchar(255) default NULL,
			msn varchar(255) default NULL,
			gtalk varchar(255) default NULL,
			gid int(5) NOT NULL default '0',
			rela varchar(255) default NULL,
			photo text,
			tw_t varchar(255) default NULL,
			tw_s varchar(255) default NULL,
			tw_n varchar(255) default NULL,
			tw_u varchar(255) default NULL,
			fb_uid varchar(255) default NULL,
			flickr_url varchar(255) default NULL,
			flickr_uid varchar(255) default NULL,
			flickr_name varchar(255) default NULL,
			sina_t varchar(255) default NULL,
			sina_s varchar(255) default NULL,
			sina_u varchar(255) default NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}online (
			aid int(10) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			online int(5) NOT NULL default '1',
			ip char(15) default NULL,
			UNIQUE KEY aid (aid)
			) ".(chksqlv()?'ENGINE=MEMORY DEFAULT CHARSET=utf8':'type=HEAP');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'online：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}topic (
			id int(10) NOT NULL auto_increment,
			content text NOT NULL,
			aid int(10) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			sticky int(5) NOT NULL default '0',
			sid int(10) NOT NULL default '0',
			tid int(5) NOT NULL default '0',
			mid int(5) NOT NULL default '0',
			disp int(5) NOT NULL default '0',
			`lock` int(5) NOT NULL default '0',
			rid int(10) NOT NULL default '0',
			lasttime int(15) NOT NULL default '0',
			sync_p varchar(255) default NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'topic：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}vote (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			tid int(10) NOT NULL default '0',
			vid int(10) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'vote：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}photo (
			id int(10) NOT NULL auto_increment,
			url text NOT NULL,
			title varchar(255) default NULL,
			aid int(10) NOT NULL default '0',
			cid int(10) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			upload int(5) NOT NULL default '0',
			disp int(5) NOT NULL default '0',
			vid int(5) NOT NULL default '0',
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'photo：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}pcomment (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			pid int(10) NOT NULL default '0',
			disp int(5) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			content text NOT NULL,
			sync_p varchar(255) default NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'pcomment：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}camp (
			id int(10) NOT NULL auto_increment,
			title varchar(255) NOT NULL,
			aid int(10) NOT NULL default '0',
			sticky int(5) NOT NULL default '0',
			closed int(5) NOT NULL default '0',
			disp int(5) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			cdate varchar(255) default NULL,
			cloc varchar(255) default NULL,
			cpay varchar(255) default NULL,
			content text,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'camp：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}ccomment (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			cid int(10) NOT NULL default '0',
			sid int(10) NOT NULL default '0',
			disp int(5) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			content text,
			sync_p varchar(255) default NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'ccomment：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}cuser (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			cid int(10) NOT NULL default '0',
			tid int(10) NOT NULL default '1',
			datetime int(15) NOT NULL default '0',
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'cuser：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}message (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			tid int(10) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			readed int(5) NOT NULL default '0',
			content text NOT NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'message：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}adminop (
			id int(10) NOT NULL auto_increment,
			aid int(10) NOT NULL default '0',
			sid int(10) NOT NULL default '0',
			tid int(5) NOT NULL default '0',
			datetime int(15) NOT NULL default '0',
			content text NOT NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'adminop：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}link (
			id int(10) NOT NULL auto_increment,
			title varchar(255) NOT NULL,
			url varchar(255) NOT NULL,
			thread int(10) NOT NULL default '0',
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'link：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);

			$query="create table {$dbprefix}skin (
			id int(10) NOT NULL auto_increment,
			path varchar(255) NOT NULL,
			title varchar(255) default NULL,
			sfile varchar(255) NOT NULL,
			UNIQUE KEY id (id)
			) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
			$result=mysql_query($query);
			echo '<li>建立数据表 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
			unset($query);
			unset($result);
			echo '</ul><script type="text/JavaScript">location.href=\''.$lname.'_s.php?n='.md5('2').'\';</script>';
		}else{
			echo '<script type="text/JavaScript">location.href=\''.$lname.'.php\';</script>';
		}
	}else{
		echo '<script type="text/JavaScript">alert(\''.$sql_f.'不存在\');location.href=\''.$lname.'.php\';</script>';
	}
	echo '</div></div></div><div id="foot">&copy; '.date('Y').' '.$app_n.'<br/><a href="http://www.piscdong.com/?m=mini_class" rel="external"><img src="../images/powered.gif" alt="Powered by '.$app_n.'"/></a></div></div></body></html>';
}else{
	header('Location:../');
}
?>