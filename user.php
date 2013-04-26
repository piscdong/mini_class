<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

$content.='<div class="tcontent">';
if(isset($_GET['id']) && intval($_GET['id'])>0){
	$s_dbu=sprintf('select * from %s where id=%s limit 1', $dbprefix.'member', intval($_GET['id']));
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	if(mysql_num_rows($q_dbu)>0){
		require_once('lib/lunar.php');
		$lunar=new Lunar();
		if($c_log){
			if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('sina', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-sina.gif" alt="" title="新浪微博"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="sina_s_div"></div>';
						$js_c.='
	$("#sina_s_div").load(\'j_sync.php?t=sina&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('tqq', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tqq.gif" alt="" title="腾讯微博"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="tqq_s_div"></div>';
						$js_c.='
	$("#tqq_s_div").load(\'j_sync.php?t=tqq&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('renren', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-renren.gif" alt="" title="人人网"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="renren_s_div"></div>';
						$js_c.='
	$("#renren_s_div").load(\'j_sync.php?t=renren&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('kx001', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-kx001.gif" alt="" title="开心网"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="kx001_s_div"></div>';
						$js_c.='
	$("#kx001_s_div").load(\'j_sync.php?t=kx001&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!=''){
				$s_dby=sprintf('select s_id, s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('douban', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-douban.gif" alt="" title="豆瓣"/></a>';
					if($c_log)$a_sync_v[]='<div class="sync_list" style="background-image: url(images/i-douban.gif);"><div class="extr">豆瓣收藏秀</div><script type="text/javascript" src="http://www.douban.com/service/badge/'.$r_dby['s_id'].'/?show=dolist&amp;select=random&amp;n=5&amp;columns=5&amp;hidelogo=yes&amp;hideself=yes&amp;cat=movie|book|music"></script></div>';
				}
				mysql_free_result($q_dby);
			}
			if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('google', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-google.gif" alt="" title="Google"/></a>';
				}
				mysql_free_result($q_dby);
			}
			if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('t163', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-t163.gif" alt="" title="网易微博"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="t163_s_div"></div>';
						$js_c.='
	$("#t163_s_div").load(\'j_sync.php?t=t163&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('tsohu', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tsohu.gif" alt="" title="搜狐微博"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="tsohu_s_div"></div>';
						$js_c.='
	$("#tsohu_s_div").load(\'j_sync.php?t=tsohu&i='.$r_dbu['id'].'\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_instagram']>0 && $config['instagram_key']!='' && $config['instagram_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('instagram', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-instagram.gif" alt="" title="Instagram"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="instagram_s_div"></div>';
						$js_c.='
	$("#instagram_s_div").load(\'j_sync.php?t=instagram&i='.$r_dbu['id'].'&m=1\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
				$s_dby=sprintf('select s_id from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('babab', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="http://www.bababian.com/photo/'.$r_dby['s_id'].'/" rel="external"><img src="images/i-babab.gif" alt="" title="巴巴变"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="babab_s_div"></div>';
						$js_c.='
	$("#babab_s_div").load(\'j_sync.php?t=babab&i='.$r_dbu['id'].'&m=1\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_flickr']>0 && ($config['is_uflickr']>0 || $config['flickr_key']!='')){
				$s_dby=sprintf('select s_t, s_id from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('flickr', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="http://www.flickr.com/photos/'.$r_dby['s_t'].'/" rel="external"><img src="images/i-flickr.gif" alt="" title="Flickr"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="flickr_s_div"></div>';
						$js_c.='
	$.getJSON(\'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&user_id='.$r_dby['s_id'].'&per_page=5&page=1&format=json&api_key='.$config['flickr_key'].'&jsoncallback=?\', function(data){
		if(data.stat==\'ok\' && data.photos.total>0){
			$(\'#flickr_s_div\').html(\'<div id="flickr_sl_div" class="sync_list" style="background-image: url(images/i-flickr.gif);"><div class="extr">最新Flickr照片</div></div>\');
			$.each(data.photos.photo, function(i,v){
				$(\'#flickr_sl_div\').append(\'<div class="al_list"><a href="http://www.flickr.com/photos/\'+v.owner+\'/\'+v.id+\'/" target="_blank"><img src="http://farm\'+v.farm+\'.static.flickr.com/\'+v.server+\'/\'+v.id+\'_\'+v.secret+\'_s.jpg" width="70" height="70" class="al_t" alt="" title="\'+decodeURIComponent(v.title)+\'"/></a></div>\');
			});
			$(\'#flickr_sl_div\').append(\'<div class="extr"></div>\');
		}else{
			$(\'#flickr_s_div\').hide();
		}
	});';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
				$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('twitter', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="http://twitter.com/'.$r_dby['s_n'].'" rel="external"><img src="images/i-twitter.gif" alt="" title="Twitter"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="twitter_s_div"></div>';
						$js_c.='
	$("#twitter_s_div").load(\'j_sync.php?t=twitter&i='.$r_dbu['id'].'&m=1\');';
					}
				}
				mysql_free_result($q_dby);
			}
			if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!=''){
				$s_dby=sprintf('select s_id from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('facebook', 'text'));
				$q_dby=mysql_query($s_dby) or die('');
				$r_dby=mysql_fetch_assoc($q_dby);
				if(mysql_num_rows($q_dby)>0){
					$a_sync_i[]='<a href="http://www.facebook.com/profile.php?id='.$r_dby['s_id'].'" rel="external"><img src="images/i-facebook.gif" alt="" title="Facebook"/></a>';
					if($c_log){
						$a_sync_v[]='<div id="facebook_s_div"></div>';
						$js_c.='
	$("#facebook_s_div").load(\'j_sync.php?t=facebook&i='.$r_dbu['id'].'&m=1\');';
					}
				}
				mysql_free_result($q_dby);
			}
		}
		$title.=$r_dbu['name'];
		$content.='<div class="vcard"><div class="title fn"><img src="images/star_';
		$s_dbo=sprintf('select online, ip_i, inet_ntoa(ip_i) as ip_c from %s where aid=%s limit 1', $dbprefix.'online', $r_dbu['id']);
		$q_dbo=mysql_query($s_dbo) or die('');
		$r_dbo=mysql_fetch_assoc($q_dbo);
		if(mysql_num_rows($q_dbo)>0){
			if($r_dbo['online']==1){
				$content.='0.gif" alt="" title="在线';
			}else{
				$content.='1.gif" alt="" title="离线';
			}
			if($c_log && ($pa>0 || $_SESSION[$config['u_hash']]==$r_dbu['id']) && $r_dbo['ip_i']>0)$l_ip=$pa==9?$r_dbo['ip_c']:preg_replace("/\.\d{1,3}$/i",'.*',$r_dbo['ip_c']);
		}else{
			$content.='1.gif" alt="" title="离线';
		}
		mysql_free_result($q_dbo);
		$content.='"/ > '.$r_dbu['name'];
		if($r_dbu['status']>0 && $config['veri']==0){
			$content.=' <img src="images/unp.gif" alt="" title="审核中"/>';
			if($c_log && $pa>0)$content.=' <a href="?m=user&amp;pid='.$r_dbu['id'].'&amp;p=1"><img src="images/o_4.gif" alt="" title="通过审核" /></a>';
		}elseif($c_log){
			if($r_dbu['id']!=$_SESSION[$config['u_hash']])$content.=' <a href="?m=message&amp;id='.$r_dbu['id'].'#send"><img src="images/o_7.gif" alt="" title="发消息"/></a>';
			$content.=' <a href="vcf.php?id='.$r_dbu['id'].'"><img src="images/o_5.gif" alt="" title="导出为通讯录文件"/></a>';
			if($pa==9 && $r_dbu['power']<9)$content.=' <a href="?m=edituser&amp;id='.$r_dbu['id'].'"><img src="images/o_3.gif" alt="" title="修改个人资料"/></a>';
		}
		$content.='</div><div class="lcontent">';
		if($c_log){
			$cr=getuinfo($r_dbu, 1);
			$content.=$cr.($cr!=''?'<br/>':'');
			if($r_dbu['jaid']>0){
				$jadb=getainfo($r_dbu['jaid'], 'name');
				$content.='邀请人：<a href="?m=user&amp;id='.$jadb['id'].'">'.$jadb['name'].'</a><br/>';
			}
		}
		$content.='最后访问：'.($r_dbu['visitdate']>0?date('Y-n-j H:i', $r_dbu['visitdate']):'从未').(isset($l_ip)?'<br/>最后IP：'.($config['ip']!=''?str_replace('[ip]', $l_ip, $config['ip']):$l_ip):'').($r_dbu['visit']>0?'<br/>访问次数：'.$r_dbu['visit']:'').(isset($a_sync_i)?'<br/>'.join(' ', $a_sync_i).'<br/><br/>':'').(isset($a_sync_v)?join('', $a_sync_v):'').'</div></div>';
		$s_dbt=sprintf('select content, datetime from %s where aid=%s and disp=0 and tid=0 and mid=0 order by datetime desc limit 5', $dbprefix.'topic', $r_dbu['id']);
		$q_dbt=mysql_query($s_dbt) or die('');
		$r_dbt=mysql_fetch_assoc($q_dbt);
		if(mysql_num_rows($q_dbt)>0){
			$content.='<br/><div class="title">最近留言</div><br/><ul class="clist">';
			do{
				$content.='<li class="l_list"><img src="avator.php?id='.$r_dbu['id'].'" alt="" title="'.$r_dbu['name'].'" class="photo" width="55" height="55"/><div class="list_r"><div class="list_title"><span class="gdate">'.getldate($r_dbt['datetime']).'</span></div><div class="list_c">'.gbookencode($r_dbt['content']).'</div></div></li>';
			}while($r_dbt=mysql_fetch_assoc($q_dbt));
			$content.='</ul>';
		}
		mysql_free_result($q_dbt);
		$s_dbp=sprintf('select id, title, url, vid, upload, disp from %s where aid=%s and disp=0 order by datetime desc limit 10', $dbprefix.'photo', $r_dbu['id']);
		$q_dbp=mysql_query($s_dbp) or die('');
		$r_dbp=mysql_fetch_assoc($q_dbp);
		if(mysql_num_rows($q_dbp)>0){
			$content.='<br/><div class="title">最近照片视频</div><div class="gcontent">';
			do{
				$content.='<div class="al_list"><a href="?m=album&amp;id='.$r_dbp['id'].'"><img src="'.getthu($r_dbp).'" width="70" height="70" class="'.($r_dbp['disp']>0?'del_':'').'al_t" alt="" title="'.($r_dbp['vid']>0?'[视频]':'').($r_dbp['title']!=''?$r_dbp['title']:'').'"/></a></div>';
			}while($r_dbp=mysql_fetch_assoc($q_dbp));
			$content.='<div class="extr"></div><a href="?m=album&amp;user='.$r_dbu['id'].'">查看全部…</a></div>';
		}
		mysql_free_result($q_dbp);
	}else{
		header('Location:./');
		exit();
	}
	mysql_free_result($q_dbu);
}elseif(isset($_GET['t']) && $_GET['t']=='map'){
	$content.='<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script><div class="title">班级成员&nbsp;&nbsp;<span class="gdate"><a href="?m=user">列表模式</a></span></div>';
	$js_c.='
	var p=$(\'#jz_div\').offset();
	$(\'#map_container\').show();
	$(\'#map_container\').css({\'top\':p.top+\'px\', \'left\':p.left+\'px\'});
	var map=new BMap.Map(\'map_container\');

	map.addControl(new BMap.NavigationControl());
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
	$uid=(isset($_GET['uid']) && intval($_GET['uid'])>0)?intval($_GET['uid']):0;
	$s_dbu=sprintf('select id, name, address from %s where length(address)>0', $dbprefix.'member');
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	$c_dbu=mysql_num_rows($q_dbu);
	if($c_dbu>0){
		$js_c.='
	var myGeo=new BMap.Geocoder();';
		$i=0;
		do{
			$js_c.='
	myGeo.getPoint(\''.$r_dbu['address'].'\', function(point){
		if(point){
			var marker=new BMap.Marker(point);
			map.addOverlay(marker);
			var opts={width:0, height:0}
			var infoWindow=new BMap.InfoWindow(\'<img src="avator.php?id='.$r_dbu['id'].'" alt="" title="'.$r_dbu['name'].'" class="photo" style="float: left;margin-right: 5px;" width="55" height="55"/><a href="?m=user&amp;id='.$r_dbu['id'].'">'.$r_dbu['name'].'</a><br/><br/>'.$r_dbu['address'].'\', opts);
			marker.addEventListener(\'click\', function(){
				this.openInfoWindow(infoWindow);
			});';
			if($uid>0 && $uid==$r_dbu['id']){
				$js_c.='
			marker.openInfoWindow(infoWindow);
			map.centerAndZoom(point, 16);
			$(\'#cmid\').val(\'1\');';
			}elseif($uid==0){
				$js_c.='
			map.centerAndZoom(point, 6);
			$(\'#cmid\').val(\'1\');';
			}
			if($i==($c_dbu-1))$js_c.='
			$(\'#load_span\').hide();';
			$js_c.='
		}
	}, \'\');';
			$i++;
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
	}
	mysql_free_result($q_dbu);
	$js_c.='
	var myCity=new BMap.LocalCity();
	myCity.get(function(result){
		var cityName=result.name;
		if($(\'#cmid\').val()==\'0\'){
			map.centerAndZoom(cityName, 6);
			$(\'#cmid\').val(\'1\');
		}
	});
	$(\'#map_flink\').click(function(){
		$(\'#map_container\').animate({top:$(document).scrollTop()+\'px\', left:$(document).scrollLeft()+\'px\', width:($(window).width()-2)+\'px\', height:($(window).height()-2)+\'px\'}, 500, function(){
			$(\'#map_xdiv\').show();
			$(\'#map_xdiv\').css({\'top\':$(document).scrollTop()+\'px\', \'left\':$(document).scrollLeft()+\'px\'});
		});
	});
	$(\'#map_xdiv\').click(function(){
		$(\'#map_xdiv\').hide();
		var p=$(\'#jz_div\').offset();
		$(\'#map_container\').animate({top:p.top+\'px\', left:p.left+\'px\', width:$(\'#jz_div\').width()+\'px\', height:$(\'#jz_div\').height()+\'px\'}, 500);
	});';
	$content.='<input type="hidden" id="cmid" value="0"/><div style="text-align: center;"><span id="load_span"><img src="images/v.gif" alt="" title="载入中……" class="loading_va"/> </span>操作方法：左键按住移动，滚轮放大缩小 <span id="map_flink" class="mlink f_link">全屏查看</span><div id="jz_div" style="width: 650px;height: 500px;border:1px solid #fff;margin-left: auto;margin-right: auto;"></div></div><div style="width: 650px;height: 500px;border:1px solid #999;position: absolute;top: 0;left: 0;z-index: 5;display: none;" id="map_container"></div><div style="width: 20px;height: 20px;position: absolute;right: 0;top: 0;display: none;z-index: 10;background: #fff url(images/o_2.gif) no-repeat center center;border-top: 1px solid #999;border-left: 1px solid #999;" class="f_link" id="map_xdiv" title="关闭全屏"></div>';
}else{
	if(isset($_GET['v']) && $_GET['v']==1){
		$t='访问记录';
		$odb=($config['veri']>0?'':'where status=0 ').'order by visitdate';
		$menua[$mid][0]=5;
	}else{
		$t='班级成员';
		$odb='order by id';
	}
	$title.=$t;
	if(isset($_GET['pid']) && intval($_GET['pid'])>0 && $menua[$mid][0]!=5){
		if($c_log && $pa>0){
			$s_dbu=sprintf('select id, name, email from %s where id=%s and status=1 limit 1', $dbprefix.'member', SQLString($_GET['pid'], 'int'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				$u_db=sprintf('update %s set status=0 where id=%s', $dbprefix.'member', $r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				setsinfo($r_dbu['name'].' 通过审核', $r_dbu['id']);
				if($config['email']!=1 && $r_dbu['email']!=''){
					$mail_c='亲爱的'.$r_dbu['name']."：
您在 ".$config['title']." 的注册信息已通过审核，请点击下面的地址访问：

".$config['site_url']."

感谢您的注册，祝您使用愉快！

此致，
".$config['title']."
";
					if($config['email']==2)require_once('lib/smtp.php');
					smail($r_dbu['email'], '['.$config['title'].']您的注册已通过审核', $mail_c);
				}
			}
			mysql_free_result($q_dbu);
		}
		header('Location:./?m=user'.((isset($_GET['p']) && $_GET['p']==1)?'&id='.intval($_GET['pid']):''));
		exit();
	}
	if(isset($_GET['cid']) && intval($_GET['cid'])>0 && $menua[$mid][0]!=5){
		if($c_log && $pa==9){
			$s_dbu=sprintf('select id, name from %s where id=%s and status=0 limit 1', $dbprefix.'member', SQLString($_GET['cid'], 'int'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				$c=$r_dbu['name'].' 被设置为';
				if(isset($_GET['p']) && $_GET['p']==1){
					$c.='普通用户';
					$power=0;
				}else{
					$c.='管理员';
					$power=4;
				}
				setsinfo($c, $r_dbu['id']);
				$u_db=sprintf('update %s set power=%s where id=%s', $dbprefix.'member', SQLString($power, 'int'), $r_dbu['id']);
				$result=mysql_query($u_db) or die('');
			}
			mysql_free_result($q_dbu);
		}
		header('Location:./?m=user');
		exit();
	}
	if(isset($_GET['did']) && intval($_GET['did'])>0 && $menua[$mid][0]!=5){
		if($c_log && $pa==9){
			$s_dbu=sprintf('select id, photo from %s where id=%s limit 1', $dbprefix.'member', SQLString($_GET['did'], 'int'));
			$q_dbu=mysql_query($s_dbu) or die('');
			$r_dbu=mysql_fetch_assoc($q_dbu);
			if(mysql_num_rows($q_dbu)>0){
				if($r_dbu['photo']!=''){
					$a_pho=explode('|', $r_dbu['photo']);
					foreach($a_pho as $v){
						if(trim($v)!='' && !strstr($v, '://') && file_exists(trim($v)))unlink(trim($v));
					}
				}
				$d_db=sprintf('delete from %s where id=%s', $dbprefix.'member', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$s_dbt=sprintf('select id, rid, mid, lasttime from %s where aid=%s', $dbprefix.'topic', $r_dbu['id']);
				$q_dbt=mysql_query($s_dbt) or die('');
				$r_dbt=mysql_fetch_assoc($q_dbt);
				if(mysql_num_rows($q_dbt)>0){
					$d_db=sprintf('delete from %s where sid=%s and tid=0', $dbprefix.'adminop', $r_dbt['id']);
					$result=mysql_query($d_db) or die('');
					if($r_dbt['rid']==0){
						$s_dbr=sprintf('select id from %s where rid=%s order by datetime limit 1', $dbprefix.'topic', $r_dbt['id']);
						$q_dbr=mysql_query($s_dbr) or die('');
						$r_dbr=mysql_fetch_assoc($q_dbr);
						if(mysql_num_rows($q_dbr)>0){
							$u_db=sprintf('update %s set rid=%s where rid=%s', $dbprefix.'topic', $r_dbr['id'], $r_dbt['id']);
							$result=mysql_query($u_db) or die('');
							$u_db=sprintf('update %s set rip=0, lasttime=%s where id=%s', $dbprefix.'topic', $r_dbt['lasttime'], $r_dbr['id']);
							$result=mysql_query($u_db) or die('');
						}
						mysql_free_result($q_dbr);
					}
					if($r_dbt['mid']==1){
						$d_db=sprintf('delete from %s where tid=%s and sid=0', $dbprefix.'vote', $r_dbt['id']);
						$result=mysql_query($d_db) or die('');
					}
					$d_db=sprintf('delete from %s where id=%s', $dbprefix.'topic', $r_dbt['id']);
					$result=mysql_query($d_db) or die('');
				}
				mysql_free_result($q_dbt);
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'vote', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'m_sync', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'online', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'invite', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$u_db=sprintf('update %s set jaid=0 where jaid=%s', $dbprefix.'member', $r_dbu['id']);
				$result=mysql_query($u_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s or tid=%s', $dbprefix.'message', $r_dbu['id'], $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$s_dbp=sprintf('select id, upload, url from %s where aid=%s', $dbprefix.'photo', $r_dbu['id']);
				$q_dbp=mysql_query($s_dbp) or die('');
				$r_dbp=mysql_fetch_assoc($q_dbp);
				if(mysql_num_rows($q_dbp)>0){
					do{
						delphoto($r_dbp);
					}while($r_dbp=mysql_fetch_assoc($q_dbp));
				}
				mysql_free_result($q_dbp);
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'pcomment', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$s_dbc=sprintf('select id from %s where aid=%s', $dbprefix.'camp', $r_dbu['id']);
				$q_dbc=mysql_query($s_dbc) or die('');
				$r_dbc=mysql_fetch_assoc($q_dbc);
				if(mysql_num_rows($q_dbc)>0){
					do{
						delcamp($r_dbc);
					}while($r_dbc=mysql_fetch_assoc($q_dbc));
				}
				mysql_free_result($q_dbc);
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'ccomment', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'cuser', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
				$d_db=sprintf('delete from %s where aid=%s', $dbprefix.'adminop', $r_dbu['id']);
				$result=mysql_query($d_db) or die('');
			}
			mysql_free_result($q_dbu);
		}
		header('Location:./?m=user');
		exit();
	}
	$s_dbu=sprintf('select * from %s %s desc', $dbprefix.'member', $odb);
	$q_dbu=mysql_query($s_dbu) or die('');
	$r_dbu=mysql_fetch_assoc($q_dbu);
	$c_dbu=mysql_num_rows($q_dbu);
	if($c_dbu>0){
		$content.='<div class="title">'.$t.(($menua[$mid][0]!=5 && $c_log)?' ('.$c_dbu.' 人) <a href="vcf.php"><img src="images/o_5.gif" alt="" title="导出为通讯录文件"/></a> <a href="xls.php"><img src="images/o_6.gif" alt="" title="导出为Excel文件"/></a>':'').'&nbsp;&nbsp;<span class="gdate"><a href="?m=user&amp;t=map">地图模式</a></span></div><ul class="ulist">';
		if($c_log && $pa==9)$js_c.='
	$("img[name=\'del_img\']").click(function(){
		if(confirm(\'确认要删除？\'))location.href=\'?m=user&did=\'+$(this).data(\'id\');
	});';
		do{
			$jadb[$r_dbu['id']]=$r_dbu;
			if($c_log){
				if($config['is_sina']>0 && $config['sina_key']!='' && $config['sina_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('sina', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-sina.gif" alt="" title="新浪微博"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_tqq']>0 && ($config['is_utqq']>0 || ($config['tqq_key']!='' && $config['tqq_se']!=''))){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('tqq', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tqq.gif" alt="" title="腾讯微博"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_renren']>0 && $config['renren_key']!='' && $config['renren_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('renren', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-renren.gif" alt="" title="人人网"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_kx001']>0 && $config['kx001_key']!='' && $config['kx001_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('kx001', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-kx001.gif" alt="" title="开心网"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_douban']>0 && $config['douban_key']!='' && $config['douban_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('douban', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-douban.gif" alt="" title="豆瓣"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_google']>0 && $config['google_key']!='' && $config['google_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('google', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-google.gif" alt="" title="Google"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_t163']>0 && $config['t163_key']!='' && $config['t163_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('t163', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-t163.gif" alt="" title="网易微博"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_tsohu']>0 && ($config['is_utsohu']>0 || ($config['tsohu_key']!='' && $config['tsohu_se']!=''))){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('tsohu', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-tsohu.gif" alt="" title="搜狐微博"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_instagram']>0 && $config['instagram_key']!='' && $config['instagram_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('instagram', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="'.$r_dby['s_n'].'" rel="external"><img src="images/i-instagram.gif" alt="" title="Instagram"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_babab']>0 && ($config['is_ubabab']>0 || $config['babab_key']!='')){
					$s_dby=sprintf('select s_id from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('babab', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="http://www.bababian.com/photo/'.$r_dby['s_id'].'/" rel="external"><img src="images/i-babab.gif" alt="" title="巴巴变"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_flickr']>0 && ($config['is_uflickr']>0 || $config['flickr_key']!='')){
					$s_dby=sprintf('select s_t from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('flickr', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="http://www.flickr.com/photos/'.$r_dby['s_t'].'/" rel="external"><img src="images/i-flickr.gif" alt="" title="Flickr"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_tw']>0 && $config['tw_key']!='' && $config['tw_se']!=''){
					$s_dby=sprintf('select s_n from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('twitter', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="http://twitter.com/'.$r_dby['s_n'].'" rel="external"><img src="images/i-twitter.gif" alt="" title="Twitter"/></a>';
					mysql_free_result($q_dby);
				}
				if($config['is_fb']>0 && $config['fb_se']!='' && $config['fb_app_id']!=''){
					$s_dby=sprintf('select s_id from %s where aid=%s and name=%s and is_show=0 limit 1', $dbprefix.'m_sync', $r_dbu['id'], SQLString('facebook', 'text'));
					$q_dby=mysql_query($s_dby) or die('');
					$r_dby=mysql_fetch_assoc($q_dby);
					if(mysql_num_rows($q_dby)>0)$a_sync_i[$r_dbu['id']][]='<a href="http://www.facebook.com/profile.php?id='.$r_dby['s_id'].'" rel="external"><img src="images/i-facebook.gif" alt="" title="Twitter"/></a>';
					mysql_free_result($q_dby);
				}
			}
			$content.='<li class="vcard"><img src="avator.php?id='.$r_dbu['id'].'" alt="" title="'.$r_dbu['name'].'" class="photo" style="float: right;" width="55" height="55"/><span class="utitle fn"><img src="images/star_';
			$s_dbo=sprintf('select aid from %s where aid=%s and online=1 limit 1', $dbprefix.'online', $r_dbu['id']);
			$q_dbo=mysql_query($s_dbo) or die('');
			if(mysql_num_rows($q_dbo)>0){
				$content.='0.gif" title="在线';
			}else{
				$content.='1.gif" title="离线';
			}
			mysql_free_result($q_dbo);
			$content.='" alt=""/ > <a href="?m=user&amp;id='.$r_dbu['id'].'">'.$r_dbu['name'].'</a></span>';
			if($menua[$mid][0]!=5){
				$content.='<br/>';
				if($r_dbu['status']>0 && $config['veri']==0){
					$content.='<img src="images/unp.gif" alt="" title="审核中"/>';
					if($c_log && $pa>0)$content.=' <a href="?m=user&amp;pid='.$r_dbu['id'].'"><img src="images/o_4.gif" alt="" title="通过审核" /></a>';
				}else{
					if($r_dbu['power']>0)$content.=' <img src="images/admin.gif" alt="" title="管理员"/>';
					if($c_log){
						if($r_dbu['id']!=$_SESSION[$config['u_hash']])$content.=' <a href="?m=message&amp;id='.$r_dbu['id'].'#send"><img src="images/o_7.gif" alt="" title="发消息"/></a>';
						if($pa==9 && $r_dbu['power']<9)$content.=' <a href="?m=user&amp;cid='.$r_dbu['id'].'&amp;p='.($r_dbu['power']>0?'1"><img src="images/o_1.gif" alt="" title="降为普通成员':'0"><img src="images/o_0.gif" alt="" title="升为管理员').'"/></a> <a href="?m=edituser&amp;id='.$r_dbu['id'].'"><img src="images/o_3.gif" alt="" title="修改个人资料"/></a>';
					}
				}
				if($c_log && $pa==9 && $r_dbu['power']<9)$content.=' &nbsp; &nbsp; <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$r_dbu['id'].'" class="f_link"/>';
				if($c_log){
					$cr=getuinfo($r_dbu);
					$content.=($cr!=''?'<br/>':'').$cr;
					if($r_dbu['jaid']>0){
						if(!isset($jadb[$r_dbu['jaid']]))$jadb[$r_dbu['jaid']]=getainfo($r_dbu['jaid'], 'name');
						$content.='<br/>邀请人：<a href="?m=user&amp;id='.$r_dbu['jaid'].'">'.$jadb[$r_dbu['jaid']]['name'].'</a>';
					}
				}else{
					$content.='<br/>';
				}
			}else{
				$content.='<br/>';
			}
			$content.='<br/>最后访问：'.($r_dbu['visitdate']>0?date('Y-n-j H:i', getftime($r_dbu['visitdate'])):'从未').($r_dbu['visit']>0?'<br/>访问次数：'.$r_dbu['visit']:'').(isset($a_sync_i[$r_dbu['id']])?'<br/>'.join(' ', $a_sync_i[$r_dbu['id']]):'').'</li>';
		}while($r_dbu=mysql_fetch_assoc($q_dbu));
		$content.='</ul>';
	}else{
		header('Location:./');
		exit();
	}
	mysql_free_result($q_dbu);
}
$content.='</div>';
?>