<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='样式管理';
	$js_c.='
		$(".skin_img").click(function(){
			var f=$(this).attr(\'rel\').split(\'|\');
			$("#skin_msg").load(\'j_skin.php?i=\'+f[0], function(){
				$("#skin_msg").slideDown(500);
			});
			setStyle(f[1], f[0]);
		});
		$("img[name=\'del_img\']").click(function(){
			if(confirm(\'确认要删除？\'))location.href=\'?m=setting&t=skin&did=\'+$(this).data(\'id\');
		});';
	foreach($skin_a as $k=>$v){
		if($k>0){
			$simg='skin/'.$v[1]['path'].'/skin_b.jpg';
			$lp[$v[1]['path']]='<li><img src="'.(file_exists($simg)?$simg:'images/skin_b0.jpg').'" width="120" height="90" rel="'.$k.'|'.$v[0].'" class="skin_img"/><br/>'.($v[1]['title']!=''?$v[1]['title']:'样式#'.$v[1]['id']).' <img src="images/o_2.gif" alt="" title="删除" name="del_img" data-id="'.$k.'" class="f_link"/></li>';
			if(isset($_GET['did']) && $_GET['did']==$k){
				$d_db=sprintf('delete from %s where id=%s', $dbprefix.'skin', $r_dbk['id']);
				$result=mysql_query($d_db) or die('');
				if($config['skin']==$r_dbk['id']){
					$u_db=sprintf('update %s set skin=0', $dbprefix.'main');
					$result=mysql_query($u_db) or die('');
				}
				header('Location:./?m=setting&t=skin');
				exit();
			}
		}else{
			$lp[0]='<li><img src="images/skin_b.jpg" width="120" height="90" rel="'.$k.'|'.$v[0].'" class="skin_img"/><br/>青青校园</li>';
		}
	}
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['path']) && file_exists('skin/'.$_POST['path'].'/info.php') && !isset($lp[$_POST['path']])){
			$path=$_POST['path'];
			require_once('skin/'.$_POST['path'].'/info.php');
			$stitle=isset($s_title)?htmlspecialchars($s_title,ENT_QUOTES):'';
			$sfile=isset($s_file)?htmlspecialchars($s_file,ENT_QUOTES):'styles.css';
			$i_db=sprintf('insert into %s (path, title, sfile) values (%s, %s, %s)', $dbprefix.'skin',
				SQLString($path, 'text'),
				SQLString($stitle, 'text'),
				SQLString($sfile, 'text'));
			$result=mysql_query($i_db) or die('');
			$e=2;
		}else{
			$e=1;
		}
		header('Location:./?m=setting&t=skin'.(isset($e)?'&e='.$e:''));
		exit();
	}else{
		$a_msg=array(1=>'文件不存在或者样式已经安装过！', '新样式已添加。');
		$content.='<div class="msg_v" id="skin_msg"'.((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'>'.$a_msg[$_GET['e']]:' style="display: none;">').'</div>'.(isset($lp)?'<div class="title">样式管理</div><div class="scontent"><ul id="skinlist">'.join('', $lp).'</ul><div class="extr"></div></div><br/>':'').'<div class="title">添加样式</div><div class="lcontent"><form method="post" action="" class="btform" id="skinform"><div class="formline">skin/<input name="path" size="32" class="bt_input" rel="样式路径" />/info.php</div><div class="formline"><input type="submit" value="添加" class="button" /> <input type="reset" value="取消" class="button" /> <a href="http://www.piscdong.com/mini_class/?m=skin" rel="external">下载更多样式</a></div></form></div>';
	}
}
