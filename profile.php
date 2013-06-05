<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$m_a=array('photo', 'sync', 'invite', 'security', 'password');
if($c_log){
	$s_dbu=sprintf('select * from %s where id=%s limit 1', $dbprefix.'member', $_SESSION[$config['u_hash']]);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		$content.='<div class="rcontent"><div class="content">';
		if(isset($_GET['t']) && in_array($_GET['t'], $m_a)){
			require_once('p_'.$_GET['t'].'.php');
		}else{
			$title.='个人资料';
			if($_SERVER['REQUEST_METHOD']=='POST'){
				if(isset($_POST['username']) && trim($_POST['username'])!='' && isset($_POST['name']) && trim($_POST['name'])!=''){
					$username=trim($_POST['username']);
					$s_dbe=sprintf('select id from %s where username=%s and id<>%s limit 1', $dbprefix.'member', SQLString($username, 'text'), $r_dbu['id']);
					$q_dbe=mysql_query($s_dbe) or die('');
					if(mysql_num_rows($q_dbe)>0){
						$e=2;
						$username=$r_dbu['username'];
					}else{
						$e=1;
					}
					mysql_free_result($q_dbe);
					$name=htmlspecialchars(trim($_POST['name']),ENT_QUOTES);
					$gender=$_POST['gender'];
					$bir_y=(intval($_POST['bir_y'])>0)?intval($_POST['bir_y']):0;
					$bir_m=$_POST['bir_m'];
					$bir_d=$_POST['bir_d'];
					$isnl=(isset($_POST['isnl']) && $_POST['isnl']==1)?1:0;
					$url=htmlspecialchars(trim($_POST['url']),ENT_QUOTES);
					$email=htmlspecialchars(trim($_POST['email']),ENT_QUOTES);
					$phone=htmlspecialchars(trim($_POST['phone']),ENT_QUOTES);
					$work=htmlspecialchars(trim($_POST['work']),ENT_QUOTES);
					$tel=htmlspecialchars(trim($_POST['tel']),ENT_QUOTES);
					$qq=htmlspecialchars(trim($_POST['qq']),ENT_QUOTES);
					$msn=htmlspecialchars(trim($_POST['msn']),ENT_QUOTES);
					$gtalk=htmlspecialchars(trim($_POST['gtalk']),ENT_QUOTES);
					$address=htmlspecialchars(trim($_POST['address']),ENT_QUOTES);
					$location=htmlspecialchars(trim($_POST['location']),ENT_QUOTES);
					$rela=htmlspecialchars(trim($_POST['rela']),ENT_QUOTES);
					$sylorm=(isset($_POST['sylorm']) && $_POST['sylorm']==1)?1:0;
					$u_db=sprintf('update %s set username=%s, name=%s, gender=%s, bir_y=%s, bir_m=%s, bir_d=%s, isnl=%s, url=%s, email=%s, phone=%s, work=%s, tel=%s, qq=%s, msn=%s, gtalk=%s, address=%s, location=%s, rela=%s, sylorm=%s where id=%s', $dbprefix.'member',
						SQLString($username, 'text'),
						SQLString($name, 'text'),
						SQLString($gender, 'int'),
						SQLString($bir_y, 'int'),
						SQLString($bir_m, 'int'),
						SQLString($bir_d, 'int'),
						$isnl,
						SQLString($url, 'text'),
						SQLString($email, 'text'),
						SQLString($phone, 'text'),
						SQLString($work, 'text'),
						SQLString($tel, 'text'),
						SQLString($qq, 'text'),
						SQLString($msn, 'text'),
						SQLString($gtalk, 'text'),
						SQLString($address, 'text'),
						SQLString($location, 'text'),
						SQLString($rela, 'text'),
						$sylorm,
						$r_dbu['id']);
					$result=mysql_query($u_db) or die('');
					setsinfo($name.' 更新了个人资料', $r_dbu['id']);
				}
				header('Location:./?m=profile'.(isset($e)?'&e='.$e:''));
				exit();
			}else{
				$a_msg=array(1=>'个人资料已修改。', '请使用其他的用户名！');
				$content.='<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">个人资料</div><div class="lcontent"><form method="post" action="" class="btform" id="pfform"><table><tr><td>用户名：</td><td><input name="username" size="32" value="'.htmlspecialchars($r_dbu['username'],ENT_QUOTES).'" class="bt_input" rel="用户名" /></td></tr><tr><td>姓名：</td><td><input name="name" size="32" value="'.$r_dbu['name'].'" class="bt_input" rel="姓名" /></td></tr><tr><td>介绍：</td><td><input name="rela" size="32" value="'.$r_dbu['rela'].'" /></td></tr><tr><td>性别：</td><td><input type="radio" name="gender" value="0"'.($r_dbu['gender']==0?' checked="checked"':'').' />保密 <input type="radio" name="gender" value="1"'.($r_dbu['gender']==1?' checked="checked"':'').' />男 <input type="radio" name="gender" value="2"'.($r_dbu['gender']==2?' checked="checked"':'').' />女</td></tr><tr><td>生日：</td><td><input name="bir_y" size="5" maxsize="4" value="'.($r_dbu['bir_y']>0?$r_dbu['bir_y']:'').'" />-<select name="bir_m">';
				for($i=0;$i<13;$i++)$content.='<option value="'.$i.'"'.($r_dbu['bir_m']==$i?' selected="selected"':'').'>'.($i>0?$i:'-').'</option>';
				$content.='</select>-<select name="bir_d">';
				for($i=0;$i<32;$i++)$content.='<option value="'.$i.'"'.($r_dbu['bir_d']==$i?' selected="selected"':'').'>'.($i>0?$i:'-').'</option>';
				$content.='</select></td></tr><tr><td>历法：</td><td><input type="radio" name="isnl" value="0"'.($r_dbu['isnl']==0?' checked="checked"':'').' />公历 <input type="radio" name="isnl" value="1"'.($r_dbu['isnl']==1?' checked="checked"':'').' />农历</td></tr><tr><td>手机：</td><td><input name="phone" id="formphone" size="32" value="'.$r_dbu['phone'].'" /></td></tr><tr><td>联系电话：</td><td><input name="tel" size="32" value="'.$r_dbu['tel'].'" /></td></tr><tr><td>电子邮件：</td><td><input name="email" size="32" value="'.$r_dbu['email'].'" /></td></tr><tr><td>主页：</td><td><input name="url" size="32" value="'.$r_dbu['url'].'" /></td></tr><tr><td>QQ：</td><td><input name="qq" size="32" value="'.$r_dbu['qq'].'" /></td></tr><tr><td>MSN：</td><td><input name="msn" size="32" value="'.$r_dbu['msn'].'" /></td></tr><tr><td>GTalk：</td><td><input name="gtalk" size="32" value="'.$r_dbu['gtalk'].'" /></td></tr><tr><td>住址：</td><td><input name="address" id="formaddress" size="32" value="'.$r_dbu['address'].'" title="准确填写详细住址后可以在地图上显示" /><span name="s_cbt" data-id="map_tr" class="mlink f_link">从地图上选取</span></td></tr><tr id="map_tr" style="display: none;"><td></td><td><input type="hidden" id="cmid" value="0"/>操作方法：左键按住移动，滚轮放大缩小，左键单击选取地点 <span name="h_cbt" data-id="map_tr" class="mlink f_link">关闭地图</span><div style="width: 400px;height: 300px;border:1px solid #999;" id="map_container"></div></td></tr><tr><td>籍贯：</td><td><input name="location" size="32" value="'.$r_dbu['location'].'" /></td></tr><tr><td>工作单位：</td><td><input name="work" size="32" value="'.$r_dbu['work'].'" /></td></tr><tr><td colspan="2"><input name="sylorm" type="checkbox" value="1"'.($r_dbu['sylorm']>0?' checked="checked"':'').'/>使用站外账号登录记住登录<br/><input type="submit" value="修改" class="button" /></td></tr></table></form></div>';
				$js_c.='
	var map=new BMap.Map(\'map_container\');
	var opts={type: BMAP_NAVIGATION_CONTROL_SMALL}
	map.addControl(new BMap.NavigationControl(opts));
	map.enableScrollWheelZoom();
	var contextMenu=new BMap.ContextMenu();
	var txtMenuItem=[
		{text:\'放大\', callback:function(){map.zoomIn()}},
		{text:\'缩小\', callback:function(){map.zoomOut()}},
		{text:\'放置到最大级\', callback:function(){map.setZoom(18)}},
		{text:\'查看全国\', callback:function(){map.setZoom(4)}}
	];
	for(var i=0;i<txtMenuItem.length;i++){
		contextMenu.addItem(new BMap.MenuItem(txtMenuItem[i].text,txtMenuItem[i].callback,100));
		if(i==1)contextMenu.addSeparator();
	}
	map.addContextMenu(contextMenu);';
				if($r_dbu['address']!='')$js_c.='
	var myGeo=new BMap.Geocoder();
	myGeo.getPoint(\''.$r_dbu['address'].'\', function(point){
		if(point){
			map.centerAndZoom(point, 16);
			$(\'#cmid\').val(\'1\');
		}
	}, \'\');';
				$js_c.='
	var myCity=new BMap.LocalCity();
	myCity.get(function(result){
		var cityName=result.name;
		if($(\'#cmid\').val()==\'0\'){
			map.centerAndZoom(cityName, 16);
			$(\'#cmid\').val(\'1\');
		}
	});

	var gc=new BMap.Geocoder();    
	map.addEventListener(\'click\', function(e){        
		var pt=e.point;
		gc.getLocation(pt, function(rs){
			var addComp=rs.addressComponents;
			var addr=addComp.city;
			if(addComp.province!=addComp.city)addr+=addComp.district;
			addr+=addComp.street+addComp.streetNumber;
			$(\'#formaddress\').val(addr);
		});        
	});';
			}
		}
		$content.='</div></div><div class="lmenu"><ul><li>欢迎您，'.$pn.'<ol><li><a href="?m=profile">个人资料</a></li><li><a href="?m=profile&amp;t=photo">设置头像</a></li><li><a href="?m=profile&amp;t=sync">绑定设置</a></li>'.($config['invreg']==0?'<li><a href="?m=profile&amp;t=invite">邀请朋友</a></li>':'').'<li><a href="?m=profile&amp;t=security">安全设置</a></li><li><a href="?m=profile&amp;t=password">修改密码</a></li></ol></li></ul></div>';
	}else{
		header('Location:./?m=logout');
		exit();
	}
	mysql_free_result($q_dbu);
}else{
	header('Location:./');
	exit();
}
