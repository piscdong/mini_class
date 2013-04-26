<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && isset($r_dbu)){
	$c_pho=0;
	if($r_dbu['photo']!=''){
		$a_pho=explode('|', $r_dbu['photo']);
		$c_pho=count($a_pho);
	}
	$title.='设置头像';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['upload']) && $_POST['upload']==1 && $config['upload']==0 && isset($_FILES['photo'])){
			$e=0;
			$u_s=$config['maxsize']*1024;
			$u_f='file/';
			if(!is_dir($u_f))mkdir($u_f);
			$u_f.='face/';
			if(!is_dir($u_f))mkdir($u_f);
			if(trim($config['filetype'])!=''){
				$u_e=explode(',',trim(strtolower($config['filetype'])));
				foreach($u_e as $v){
					if(trim($v)!='')$u_ea[]=trim($v);
				}
			}
			$f_a=$_FILES['photo'];
			if(is_uploaded_file($f_a['tmp_name']) && $f_a['error']==0){
				if($f_a['size']>$u_s && $u_s>0)$e=2;
				$f_e=explode('.', $f_a['name']);
				$f_e=strtolower($f_e[count($f_e)-1]);
				if(isset($u_ea) && !in_array($f_e, $u_ea))$e=3;
				if(!is_dir($u_f) && is_writeable($u_f))$e=4;
				if($e==0){
					$f_m=md5($_SESSION[$config['u_hash']].time().rand(0,1000));
					$photo=$u_f.$f_m.'.'.$f_e;
					if(@copy($f_a['tmp_name'], $photo)){
						$data=GetImageSize($photo);
						if($data && $data[2]<=3){
							if($config['avator']>0){
								if($c_pho>=$config['avator']){
									foreach($a_pho as $k=>$v){
										if($k>=$config['avator']){
											if(!strstr($v, '://') && file_exists($v))unlink($v);
											unset($a_pho[$k]);
										}
									}
									if(!strstr($a_pho[0], '://') && file_exists($a_pho[0]))unlink($a_pho[0]);
									$a_pho[0]=$photo;
								}else{
									$a_pho[]=$photo;
								}
								$u_pho=join('|', $a_pho);
							}else{
								if($r_dbu['photo']!=''){
									foreach($a_pho as $v){
										if(trim($v)!='' && !strstr($v, '://') && file_exists(trim($v)))unlink(trim($v));
									}
								}
								$u_pho=$photo;
							}
							$u_db=sprintf('update %s set photo=%s where id=%s', $dbprefix.'member',
								SQLString($u_pho, 'text'),
								$r_dbu['id']);
							$result=mysql_query($u_db) or die('');
							setsinfo($r_dbu['name'].' 更新了个人资料', $r_dbu['id']);
							$e=1;
						}else{
							$e=5;
							unlink($photo);
						}
					}else{
						$e=6;
					}
				}
			}else{
				$e=6;
			}
		}elseif(isset($_POST['photo']) && trim($_POST['photo'])!=''){
			$photo=getfurl(htmlspecialchars(trim($_POST['photo']),ENT_QUOTES));
			if($config['avator']>0){
				if($c_pho>=$config['avator']){
					foreach($a_pho as $k=>$v){
						if($k>=$config['avator']){
							if(!strstr($v, '://') && file_exists($v))unlink($v);
							unset($a_pho[$k]);
						}
					}
					if(!strstr($a_pho[0], '://') && file_exists($a_pho[0]))unlink($a_pho[0]);
					$a_pho[0]=$photo;
				}else{
					$a_pho[]=$photo;
				}
				$u_pho=join('|', $a_pho);
			}else{
				if($r_dbu['photo']!=''){
					foreach($a_pho as $v){
						if(trim($v)!='' && !strstr($v, '://') && file_exists(trim($v)))unlink(trim($v));
					}
				}
				$u_pho=$photo;
			}
			$u_db=sprintf('update %s set photo=%s where id=%s', $dbprefix.'member',
				SQLString($u_pho, 'text'),
				$r_dbu['id']);
			$result=mysql_query($u_db) or die('');
			setsinfo($r_dbu['name'].' 更新了个人资料', $r_dbu['id']);
			$e=1;
		}
		header('Location:./?m=profile&t=photo'.((isset($e) && $e>0)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'个人资料已修改。', '文件太大！', '文件类型不可用！', '上传路径不可用！', '上传的不是图片文件！', '上传出错！');
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">当前头像</div><div class="lcontent">';
		if($r_dbu['photo']!=''){
			$js_c.='
		$("img[name=\'del_img\']").click(function(){
			if(confirm(\'确认要删除？\'))location.href=\'?m=profile&t=photo&did=\'+$(this).data(\'id\');
		});';
			if($config['avator']>0){
				foreach($a_pho as $k=>$v){
					if($k<$config['avator']){
						if(isset($_GET['did']) && $_GET['did']==$k){
							if($c_pho>$config['avator']){
								foreach($a_pho as $k1=>$v1){
									if($k1>=$config['avator']){
										if(!strstr($v1, '://') && file_exists($v1))unlink($v1);
										unset($a_pho[$k1]);
									}
								}
							}
							if(!strstr($a_pho[$k], '://') && file_exists($a_pho[$k]))unlink($a_pho[$k]);
							unset($a_pho[$k]);
							$u_pho=join('|', $a_pho);
							$u_db=sprintf('update %s set photo=%s where id=%s', $dbprefix.'member',
								SQLString($u_pho, 'text'),
								$r_dbu['id']);
							$result=mysql_query($u_db) or die('');
							setsinfo($r_dbu['name'].' 更新了个人资料', $r_dbu['id']);
							header('Location:./?m=profile&t=photo&e=1');
							exit();
						}
						$content.='<div class="photo_list"><img src="'.$v.'" class="photo" alt="" width="55" height="55"/>&nbsp; <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$k.'" class="f_link"/></div>';
					}
				}
			}else{
				if(isset($_GET['did']) && $_GET['did']==1){
					foreach($a_pho as $v){
						if(trim($v)!='' && !strstr($v, '://') && file_exists(trim($v)))unlink(trim($v));
					}
					$u_pho='';
					$u_db=sprintf('update %s set photo=%s where id=%s', $dbprefix.'member',
						SQLString($u_pho, 'text'),
						$r_dbu['id']);
					$result=mysql_query($u_db) or die('');
					setsinfo($r_dbu['name'].' 更新了个人资料', $r_dbu['id']);
					header('Location:./?m=profile&t=photo&e=1');
					exit();
				}
				$content.='<img src="'.$a_pho[0].'" class="photo" alt="" width="55" height="55"/>&nbsp; <img src="images/o_2.gif" alt="" title="删除"  name="del_img" data-id="1" class="f_link"/>';
			}
		}else{
			$content.='<img src="images/dphoto.jpg" class="photo" alt="" width="55" height="55"/>';
		}
		$content.='<div class="extr"></div></div><br/><div class="title">'.($config['avator']>0?'添加':'设置').'头像';
		if($c_pho>=$config['avator'] && $config['avator']>0){
			$content.='</div><div class="lcontent">您已经有'.$config['avator'].'个头像，达到头像数的最大上限，不能再添加头像！</div>';
		}else{
			if($config['upload']==0)$js_c.='
		$("span[name=\'melink\']").click(function(){
			dhdivf(\'topicform\', $(this).data(\'id\'), 2);
		});';
			$content.=($config['upload']>0?'':'&nbsp;&nbsp;<span class="gdate"><span name="melink" data-id="1" class="mlink f_link">转贴</span> | <span name="melink" data-id="0" class="mlink f_link">上传</span></span>').'</div><div class="lcontent"'.($config['upload']>0?'':' id="topicform0" style="display: none;"><form method="post" action="" enctype="multipart/form-data"><div class="formline"><input type="file" name="photo" size="32" /></div><div class="formline">'.($config['maxsize']>0?'最大上传：'.$config['maxsize'].'K、':'').'允许类型：'.$config['filetype'].'、图片尺寸：55*55px'.($config['avator']>0?'、您还可以再添加 '.($config['avator']-$c_pho).' 个头像':'').'</div><div class="formline"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /><input type="hidden" name="upload" value="1"/></div></form></div><div class="lcontent" id="topicform1"').'><form method="post" action="" class="btform" id="pform_u"><div class="formline"><input name="photo" id="formurl" size="32" class="bt_input" rel="照片地址" /></div><div class="formline">图片尺寸：55*55px'.($config['avator']>0?'、您还可以再添加 '.($config['avator']-$c_pho).' 个头像':'').'</div><div class="formline"><input type="submit" value="发布" class="button" /> <input type="reset" value="取消" class="button" /></div></form></div>';
		}
	}
}
?>