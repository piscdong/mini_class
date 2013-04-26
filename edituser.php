<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	if($config['gid']!='')$g_c=explode('|', $config['gid']);
	$id=(isset($_GET['id']) && intval($_GET['id'])>0)?intval($_GET['id']):1;
	$s_dbu=sprintf('select * from %s where id=%s limit 1', $dbprefix.'member', $id);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(isset($_POST['name']) && trim($_POST['name'])!=''){
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
				$gid=(isset($_POST['gid']) && isset($g_c) && in_array($_POST['gid'], $g_c) && isset($g_a[$_POST['gid']]))?$_POST['gid']:0;
				$rela=htmlspecialchars(trim($_POST['rela']),ENT_QUOTES);
				$u_db=sprintf('update %s set name=%s, gender=%s, bir_y=%s, bir_m=%s, bir_d=%s, isnl=%s, url=%s, email=%s, phone=%s, work=%s, tel=%s, qq=%s, msn=%s, gtalk=%s, address=%s, location=%s, gid=%s, rela=%s where id=%s', $dbprefix.'member',
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
					SQLString($gid, 'int'),
					SQLString($rela, 'text'),
					$r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$e=1;
			}elseif(isset($_POST['username']) && trim($_POST['username'])!=''){
				$username=trim($_POST['username']);
				$s_dbe=sprintf('select id from %s where username=%s and id<>%s', $dbprefix.'member', SQLString($username, 'text'), $r_dbu['id']);
				$q_dbe=mysql_query($s_dbe) or die('');
				if(mysql_num_rows($q_dbe)>0){
					$e=2;
				}else{
					$password=trim($_POST['password'])!=''?enc_p(trim($_POST['password'])):$r_dbu['password'];
					$u_db=sprintf('update %s set username=%s, password=%s where id=%s', $dbprefix.'member',
						SQLString($username, 'text'),
						SQLString($password, 'text'),
						$r_dbu['id']);
					$result=mysql_query($u_db) or die('');
					$e=1;
				}
				mysql_free_result($q_dbe);
			}
			header('Location:./?m=edituser&id='.$id.(isset($_GET['t'])?'&t='.$_GET['t']:'').(isset($e)?'&e='.$e:''));
			exit();
		}else{
			$a_msg=array(1=>'个人资料已修改。', '请使用其他的用户名！');
			$content.='<div class="rcontent"><div class="content">'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'');
			if(isset($_GET['t']) && $_GET['t']=='login'){
				$title.='修改登录信息 - '.$r_dbu['name'];
				$content.='<div class="title">修改登录信息 - '.$r_dbu['name'].'</div><div class="lcontent"><form method="post" action="" class="btform" id="prform"><table><tr><td>用户名：</td><td><input name="username" size="32" value="'.htmlspecialchars($r_dbu['username'],ENT_QUOTES).'" class="bt_input" rel="用户名" /></td></tr><tr><td>密码：</td><td><input type="password" name="password" size="32" />如不需要更改密码，此处请留空</td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /></td></tr></table></form></div>';
			}elseif(isset($_GET['t']) && $_GET['t']=='avator'){
				$title.='设置头像 - '.$r_dbu['name'];
				$content.='<div class="title">设置头像 - '.$r_dbu['name'].'</div><div class="lcontent">';
				if(trim($r_dbu['photo'])!=''){
					$a_pho=explode('|', trim($r_dbu['photo']));
					$js_c.='
	$("img[name=\'del_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=edituser&id='.$id.'&t=avator&did=\'+$(this).data(\'id\');
	});';
					foreach($a_pho as $k=>$v){
						if(isset($_GET['did']) && $_GET['did']==$k){
							if(!strstr($a_pho[$k], '://') && file_exists($a_pho[$k]))unlink($a_pho[$k]);
							unset($a_pho[$k]);
							$u_pho=join('|', $a_pho);
							$u_db=sprintf('update %s set photo=%s where id=%s', $dbprefix.'member',
								SQLString($u_pho, 'text'),
								$r_dbu['id']);
							$result=mysql_query($u_db) or die('');
							header('Location:./?m=edituser&id='.$id.'&t=avator');
							exit();
						}
						$content.='<div class="photo_list"><img src="'.$v.'" class="photo" alt="" width="55" height="55"/>&nbsp; <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$k.'" class="f_link"/></div>';
					}
				}else{
					$content.='<img src="images/dphoto.jpg" class="photo" alt="" width="55" height="55"/>';
				}
				$content.='<div class="extr"></div></div>';
			}else{
				$title.='修改个人资料 - '.$r_dbu['name'];
				$content.='<div class="title">修改个人资料 - '.$r_dbu['name'].'</div><div class="lcontent"><form method="post" action="" class="btform" id="epform"><table><tr><td>姓名：</td><td><input name="name" size="32" value="'.$r_dbu['name'].'" class="bt_input" rel="姓名" /></td></tr>';
				if(isset($g_c) && isset($g_a) && count($g_a)>1){
					$content.='<tr><td>身份：</td><td><select name="gid">';
					foreach($g_a as $k=>$v){
						if(in_array($k, $g_c) || $k==0)$content.='<option value="'.$k.'"'.($k==$r_dbu['gid']?' selected="selected"':'').'>'.$v.'</option>';
					}
					$content.='</select></td></tr>';
				}
				$content.='<tr><td>介绍：</td><td><input name="rela" size="32" value="'.$r_dbu['rela'].'" /></td></tr><tr><td>性别：</td><td><input type="radio" name="gender" value="0"'.($r_dbu['gender']==0?' checked="checked"':'').' />保密 <input type="radio" name="gender" value="1"'.($r_dbu['gender']==1?' checked="checked"':'').' />男 <input type="radio" name="gender" value="2"'.($r_dbu['gender']==2?' checked="checked"':'').' />女</td></tr><tr><td>生日：</td><td><input name="bir_y" id="formby" size="5" maxsize="4" value="'.($r_dbu['bir_y']>0?$r_dbu['bir_y']:'').'" />-<select name="bir_m">';
				for($i=0;$i<13;$i++)$content.='<option value="'.$i.'"'.($r_dbu['bir_m']==$i?' selected="selected"':'').'>'.($i>0?$i:'-').'</option>';
				$content.='</select>-<select name="bir_d">';
				for($i=0;$i<32;$i++)$content.='<option value="'.$i.'"'.($r_dbu['bir_d']==$i?' selected="selected"':'').'>'.($i>0?$i:'-').'</option>';
				$content.='</select></td></tr><tr><td>历法：</td><td><input type="radio" name="isnl" value="0"'.($r_dbu['isnl']==0?' checked="checked"':'').' />公历 <input type="radio" name="isnl" value="1"'.($r_dbu['isnl']==1?' checked="checked"':'').' />农历</td></tr><tr><td>手机：</td><td><input name="phone" size="32" value="'.$r_dbu['phone'].'" /></td></tr><tr><td>联系电话：</td><td><input name="tel" size="32" value="'.$r_dbu['tel'].'" /></td></tr><tr><td>电子邮件：</td><td><input name="email" size="32" value="'.$r_dbu['email'].'" /></td></tr><tr><td>主页：</td><td><input name="url" size="32" value="'.$r_dbu['url'].'" /></td></tr><tr><td>QQ：</td><td><input name="qq" size="32" value="'.$r_dbu['qq'].'" /></td></tr><tr><td>MSN：</td><td><input name="msn" size="32" value="'.$r_dbu['msn'].'" /></td></tr><tr><td>GTalk：</td><td><input name="gtalk" size="32" value="'.$r_dbu['gtalk'].'" /></td></tr><tr><td>住址：</td><td><input name="address" id="formaddress" size="32" value="'.$r_dbu['address'].'" title="准确填写详细住址后可以在地图上显示" /><span name="s_cbt" data-id="map_tr" class="mlink f_link">从地图上选取</span></td></tr><tr id="map_tr" style="display: none;"><td></td><td><input type="hidden" id="cmid" value="0"/>操作方法：左键按住移动，滚轮放大缩小，左键单击选取地点 <span name="h_cbt" data-id="map_tr" class="mlink f_link">关闭地图</span><div style="width: 400px;height: 300px;border:1px solid #999;" id="map_container"></div></td></tr><tr><td>籍贯：</td><td><input name="location" size="32" value="'.$r_dbu['location'].'" /></td></tr><tr><td>工作单位：</td><td><input name="work" size="32" value="'.$r_dbu['work'].'" /></td></tr><tr><td colspan="2"><input type="submit" value="修改" class="button" /></td></tr></table></form></div>';
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
			$content.='</div></div><div class="lmenu"><ul><li>欢迎您，'.$pn.'<ol><li>'.$r_dbu['name'].'</li><li><a href="?m=edituser&amp;id='.$id.'">个人资料</a></li><li><a href="?m=edituser&amp;id='.$id.'&amp;t=login">登录信息</a></li><li><a href="?m=edituser&amp;id='.$id.'&amp;t=avator">设置头像</a></li></ol></li></ul></div>';
		}
	}else{
		header('Location:./?m=user');
		exit();
	}
	mysql_free_result($q_dbu);
}else{
	header('Location:./');
	exit();
}
?>