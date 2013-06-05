<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

function pathstripsl($p){
	if(substr($p, 0, 1)=='/'){
		$p=substr($p, 1);
		$p=pathstripsl($p);
	}
	if(substr($p, -1)=='/'){
		$p=substr($p, 0, -1);
		$p=pathstripsl($p);
	}
	return $p;
}

if($c_log && $pa==9){
	$title.='上传文件管理';
	$path='file/';
	$pm='';
	if(isset($_GET['p']) && trim($_GET['p'])!=''){
		$pn=htmlspecialchars(trim($_GET['p']),ENT_QUOTES);
		$pn=pathstripsl($pn);
		if($pn!='' && substr($pn, 0, 1)!='.' && is_dir($path.$pn)){
			$pa=explode('/', $pn);
			$c_pa=count($pa);
			if($c_pa>1){
				unset($pa[($c_pa-1)]);
				$up=join('/', $pa);
			}else{
				$up='';
			}
			$pm=$pn.'/';
		}
	}
	$path.=$pm;
	$path_id=opendir($path);
	$folder_t=time()+86400*365;
	while($file_name=readdir($path_id)){
		if($file_name!='.' && $file_name!='..'){
			if(is_dir($path.$file_name)){
				$ft=$folder_t;
				$is_f=1;
			}else{
				$ft=filemtime($path.$file_name);
				$is_f=0;
			}
			$file_a[]=array($ft, $file_name, $is_f);
		}
	}
	closedir($path_id);
	$content.='<div class="title">上传文件管理</div><div class="lcontent"><div class="file_list" style="background-image: url(images/file_fo.gif);"><b>'.$path.'</b></div><br/>';
	if($pm!='')$content.='<div class="file_list" style="background-image: url(images/o_0.gif);"><a href="?m=setting&amp;t=file&amp;p='.$up.'">../</a></div>';
	if(isset($file_a) && count($file_a)>0){
		$js_c.='
		$("img[name=\'del_img\']").click(function(){
			if(confirm(\'确认要删除？\'))location.href=\'?m=setting&t=file&p='.$pm.'&did=\'+$(this).data(\'id\');
		});';
		rsort($file_a);
		foreach($file_a as $v){
			if($v[2]>0){
				$content.='<div class="file_list" style="background-image: url(images/file_fc.gif);"><a href="?m=setting&amp;t=file&amp;p='.$pm.$v[1].'">'.$v[1].'/</a></div>';
			}else{
				$fid=md5($v[1]);
				if(isset($_GET['did']) && $_GET['did']==$fid){
					unlink($path.$v[1]);
					header('Location:./?m=setting&t=file&p='.$pm);
					exit();
				}
				$content.='<div class="file_list" style="background-image: url(images/file_ff.gif);"><a href="'.$path.$v[1].'">'.$v[1].'</a>  - '.getldate($v[0]).' <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$fid.'" class="f_link"/></div>';
			}
		}
	}else{
		$content.='<br/>当前目录没有文件';
	}
	$content.='</div>';
}
