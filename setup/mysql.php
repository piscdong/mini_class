<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

require_once('inc.php');
if(!file_exists($l_file)){
	echo getstop(2);
	if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['title']!='' && $_POST['username']!='' && $_POST['name']!='' && $_POST['password']!=''){
		require_once($c_file);
		echo '<div class="title">安装MySQL数据库</div><div class="gcontent"><ul>';
		$query="create table {$dbprefix}main (
id int(10) NOT NULL auto_increment,
title varchar(255) NOT NULL,
school varchar(255) default NULL,
classname varchar(255) default NULL,
open int(5) NOT NULL default '0',
openreg int(5) NOT NULL default '0',
invreg int(5) NOT NULL default '0',
gid varchar(255) default '1|2|3',
content text,
email int(5) NOT NULL default '0',
smtp_server varchar(255) default NULL,
smtp_port varchar(255) default NULL,
smtp_email varchar(255) default NULL,
smtp_isa int(5) NOT NULL default '0',
smtp_user varchar(255) default NULL,
smtp_pwd varchar(255) default NULL,
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
g_open int(5) NOT NULL default '0',
g_name varchar(255) default NULL,
g_pwd varchar(255) default NULL,
g_vdate int(15) NOT NULL default '0',
g_vc int(10) NOT NULL default '0',
g_ip_i int unsigned NOT NULL default '0',
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
sina_key varchar(255) default NULL,
sina_se varchar(255) default NULL,
is_babab int(5) NOT NULL default '0',
is_ubabab int(5) NOT NULL default '0',
babab_key varchar(255) default NULL,
is_qq int(5) NOT NULL default '0',
qq_app_id varchar(255) default NULL,
qq_app_key varchar(255) default NULL,
is_tqq int(5) NOT NULL default '0',
is_utqq int(5) NOT NULL default '0',
tqq_key varchar(255) default NULL,
tqq_se varchar(255) default NULL,
is_tsohu int(5) NOT NULL default '0',
is_utsohu int(5) NOT NULL default '0',
tsohu_key varchar(255) default NULL,
tsohu_se varchar(255) default NULL,
is_t163 int(5) NOT NULL default '0',
t163_key varchar(255) default NULL,
t163_se varchar(255) default NULL,
is_kx001 int(5) NOT NULL default '0',
kx001_key varchar(255) default NULL,
kx001_se varchar(255) default NULL,
is_renren int(5) NOT NULL default '0',
renren_key varchar(255) default NULL,
renren_se varchar(255) default NULL,
is_douban int(5) NOT NULL default '0',
douban_key varchar(255) default NULL,
douban_se varchar(255) default NULL,
is_baidu int(5) NOT NULL default '0',
baidu_key varchar(255) default NULL,
baidu_se varchar(255) default NULL,
is_instagram int(5) NOT NULL default '0',
instagram_key varchar(255) default NULL,
instagram_se varchar(255) default NULL,
is_google int(5) NOT NULL default '0',
google_key varchar(255) default NULL,
google_se varchar(255) default NULL,
is_live int(5) NOT NULL default '0',
live_key varchar(255) default NULL,
live_se varchar(255) default NULL,
PRIMARY KEY (`id`)
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
isnl int(5) NOT NULL default '0',
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
jaid int(10) NOT NULL default '0',
rela varchar(255) default NULL,
photo text,
sylorm int(5) NOT NULL default '0',
PRIMARY KEY (`id`)
) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query="create table {$dbprefix}m_sync (
id int(10) NOT NULL auto_increment,
aid int(10) NOT NULL default '0',
name varchar(255) default NULL,
s_id varchar(255) default NULL,
s_t text,
s_r text,
s_s varchar(255) default NULL,
s_n varchar(255) default NULL,
edate int(15) NOT NULL default '0',
mdate int(15) NOT NULL default '0',
is_show int(5) NOT NULL default '0',
PRIMARY KEY (`id`)
) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'m_sync：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query="create table {$dbprefix}online (
aid int(10) NOT NULL default '0',
datetime int(15) NOT NULL default '0',
online int(5) NOT NULL default '1',
ip_i int unsigned NOT NULL default '0',
PRIMARY KEY (`aid`)
) ".(chksqlv()?'ENGINE=MEMORY DEFAULT CHARSET=utf8':'type=HEAP');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'online：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query="create table {$dbprefix}invite (
id int(10) NOT NULL auto_increment,
aid int(10) NOT NULL default '0',
datetime int(15) NOT NULL default '0',
code varchar(255) default NULL,
jid int(10) NOT NULL default '0',
PRIMARY KEY (`id`)
) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'invite：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
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
is_lock int(5) NOT NULL default '0',
rid int(10) NOT NULL default '0',
lasttime int(15) NOT NULL default '0',
sync_p varchar(255) default NULL,
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
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
PRIMARY KEY (`id`)
) ".(chksqlv()?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'type=MyISAM');
		$result=mysql_query($query);
		echo '<li>建立数据表 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$title=htmlspecialchars(trim($_POST['title']),ENT_QUOTES);
		$query=sprintf('insert into %s (title) values (%s)', $dbprefix.'main',
			SQLString($title, 'text'));
		$result=mysql_query($query);
		echo '<li>写入新数据 '.$dbprefix.'main：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$username=trim($_POST['username']);
		$password=enc_p(trim($_POST['password']));
		$name=htmlspecialchars(trim($_POST['name']),ENT_QUOTES);
		$query=sprintf('insert into %s (username, password, name, power, regdate) values (%s, %s, %s, 9, %s)', $dbprefix.'member',
			SQLString($username, 'text'),
			SQLString($password, 'text'),
			SQLString($name, 'text'),
			time());
		$result=mysql_query($query);
		echo '<li>写入新数据 '.$dbprefix.'member：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);

		$query=sprintf('insert into %s (path, title, sfile) values (%s, %s, %s)', $dbprefix.'skin',
			SQLString('blue', 'text'),
			SQLString('蓝色梦想', 'text'),
			SQLString('styles.css', 'text'));
		$result=mysql_query($query);
		echo '<li>写入新数据 '.$dbprefix.'skin：<span style="font-weight:bold;color:#'.($result==true?'036;">成功':'f00;">失败').'</span></li>';
		unset($query);
		unset($result);
		echo '</ul><input type="button" value="完成" class="button" onclick="location.href=\'../\';"/></div>';
		writeText($l_file,time());
	}else{
?>
	<div class="title">第2步：配置信息</div>
	<div class="lcontent">
		<form method="post" onsubmit="if(document.form1.title.value=='' || document.form1.username.value=='' || document.form1.password.value=='' || document.form1.name.value==''){alert('请输入配置信息。');return false;}else if(document.form1.password.value!='' && document.form1.password.value!=document.form1.password2.value){alert('请确认密码。');return false;}" name="form1">
			<table>
				<tr><td>标题：</td><td><input name="title" size="32"/></td></tr>
				<tr><td colspan="2">管理员信息</td></tr>
				<tr><td>用户名：</td><td><input name="username" size="32"/></td></tr>
				<tr><td>姓名：</td><td><input name="name" size="32"/></td></tr>
				<tr><td>密码：</td><td><input type="password" name="password" size="32"/></td></tr>
				<tr><td>确认：</td><td><input type="password" name="password2" size="32"/></td></tr>
				<tr><td colspan="2"><input type="submit" value="下一步" class="button" /> <input type="reset" value="重置" class="button" /></td></tr>
			</table>
		</form>
	</div>
<?php
	}
	echo getsfoot();
}else{
	header('Location:../');
}
?>