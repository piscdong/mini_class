<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

if($c_log && $pa==9){
	$title.='数据库管理';
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$a_db=array(
			'main'=>array('title', 'school', 'classname', 'open', 'openreg', 'invreg', 'gid', 'content', 'email', 'pagesize', 'upload', 'maxsize', 'filetype', 'thum', 'avator', 'slink', 'veri', 'icp', 'skin', 'timefix', 'ip', 'g_open', 'g_name', 'g_pwd', 'g_vdate', 'g_vc', 'g_ip_i', 'is_tw', 'tw_key', 'tw_se', 'is_fb', 'fb_se', 'fb_app_id', 'is_flickr', 'is_uflickr', 'flickr_key', 'is_sina', 'sina_key', 'sina_se', 'is_babab', 'is_ubabab', 'babab_key', 'is_qq', 'qq_app_id', 'qq_app_key', 'is_tqq', 'is_utqq', 'tqq_key', 'tqq_se', 'is_tsohu', 'is_utsohu', 'tsohu_key', 'tsohu_se', 'is_t163', 't163_key', 't163_se', 'is_kx001', 'kx001_key', 'kx001_se', 'is_renren', 'renren_key', 'renren_se', 'is_douban', 'douban_key', 'douban_se', 'is_baidu', 'baidu_key', 'baidu_se', 'is_instagram', 'instagram_key', 'instagram_se', 'is_google', 'google_key', 'google_se', 'is_live', 'live_key', 'live_se', 'smtp_server', 'smtp_port', 'smtp_email', 'smtp_isa', 'smtp_user', 'smtp_pwd'),
			'member'=>array('username', 'password', 'name', 'status', 'power', 'regdate', 'visit', 'visitdate', 'question', 'answer', 'email', 'gender', 'bir_y', 'bir_m', 'bir_d', 'isnl', 'address', 'location', 'url', 'work', 'phone', 'tel', 'qq', 'msn', 'gtalk', 'gid', 'jaid', 'rela', 'photo', 'sylorm'),
			'm_sync'=>array('aid', 'name', 's_id', 's_t', 's_r', 's_s', 's_n', 'edate', 'mdate', 'is_show'),
			'invite'=>array('aid', 'datetime', 'code', 'jid'),
			'topic'=>array('content', 'aid', 'datetime', 'sticky', 'sid', 'tid', 'mid', 'disp', 'is_lock', 'rid', 'lasttime', 'sync_p'),
			'vote'=>array('aid', 'tid', 'vid', 'datetime'),
			'photo'=>array('url', 'title', 'aid', 'cid', 'datetime', 'upload', 'disp', 'vid'),
			'pcomment'=>array('aid', 'pid', 'disp', 'datetime', 'content', 'sync_p'),
			'camp'=>array('title', 'aid', 'sticky', 'closed', 'disp', 'datetime', 'cdate', 'cloc', 'cpay', 'content'),
			'ccomment'=>array('aid', 'cid', 'sid', 'disp', 'datetime', 'content', 'sync_p'),
			'cuser'=>array('aid', 'cid', 'tid', 'datetime'),
			'message'=>array('aid', 'tid', 'datetime', 'readed', 'content'),
			'adminop'=>array('aid', 'sid', 'tid', 'datetime', 'content'),
			'link'=>array('title', 'url', 'thread'),
			'skin'=>array('path', 'title', 'sfile')
		);
		$a_ndb=array('online');
		if(isset($_POST['import']) && $_POST['import']>0 && isset($_POST['file']) && trim($_POST['file'])!='' && file_exists('setup/'.trim($_POST['file']))){
			require_once('setup/'.trim($_POST['file']));
			foreach($a_db as $db=>$db_v){
				$sql=sprintf('truncate table %s', $dbprefix.$db);
				$result=mysql_query($sql) or die(mysql_error());
				if(isset($data[$db]) && isset($data[$db][0]) && count($data[$db])>1){
					$i0db='';
					$i0db_a=array();
					$im=0;
					foreach($data[$db][0] as $k=>$v){
						if(in_array($v, $db_v)){
							$i0db.=', '.$v;
							$im++;
						}else{
							$i0db_a[]=$k;
						}
					}
					if($i0db!=''){
						unset($data[$db][0]);
						foreach($data[$db] as $k=>$v){
							$i1db='';
							$in=0;
							foreach($v as $sk=>$sv){
								if(!in_array($sk, $i0db_a)){
									if($in<$im)$i1db.=', '.imString($sv);
									$in++;
								}
							}
							if($in<$im){
								for($i=0;$i<($im-$in);$i++)$i1db.=', '.imString('');
							}
							$i_db=sprintf('insert into %s (id%s) values (%s%s)', $dbprefix.$db, $i0db, $k, $i1db);
							$result=mysql_query($i_db) or die('数据表：'.$dbprefix.$db.'，ID：'.$k.'，信息：'.mysql_error());
						}
					}
				}
			}
			foreach($a_ndb as $db){
				$sql=sprintf('truncate table %s', $dbprefix.$db);
				$result=mysql_query($sql) or die(mysql_error());
			}
			$e=1;
		}elseif(isset($_POST['export']) && $_POST['export']>0){
			foreach($a_db as $db=>$db_v){
				$vdb=join(', ', $db_v);
				$s_dbq=sprintf('select id, %s from %s order by id', $vdb, $dbprefix.$db);
				$q_dbq=mysql_query($s_dbq) or die(mysql_error());
				$r_dbq=mysql_fetch_assoc($q_dbq);
				if(mysql_num_rows($q_dbq)>0){
					$i=0;
					do{
						foreach($r_dbq as $k=>$v){
							if($k!='id'){
								if($i==0)$q[$db][0][]=$k;
								$q[$db][$r_dbq['id']][]=$v;
							}
						}
						$i++;
					}while($r_dbq=mysql_fetch_assoc($q_dbq));
				}
				mysql_free_result($q_dbq);
			}
			if(isset($q) && count($q)>0){
				$e=2;
				$is_inc=(isset($_POST['file_t']) && $_POST['file_t']==1)?1:0;
				$f='setup/sql-'.time().'.'.($is_inc>0?'inc':'php');
				foreach($q as $k=>$v){
					unset($qt1);
					foreach($v as $kv=>$qv){
						unset($qt0);
						foreach($qv as $qav)$qt0[]="'".str_replace(array("'","\r","\n"), array('<&#039;>','<r>','<n>'), $qav)."'";
						$qt1[]=$kv."=>array(".join(', ', $qt0).")";
					}
					$qt2[]="'".$k."'=>array(\n\t\t".join(",\n\t\t", $qt1)."\n\t)";
				}
				$c="<?php\n\$data=array(\n\t".join(",\n\t", $qt2)."\n);\n
";
				writeText($f,$c);
			}
		}
		header('Location:./?m=setting&t=sql'.(isset($e)?'&e='.$e:'').(isset($f)?'&f='.$f:'').((isset($is_inc) && $is_inc>0)?'&inc=1':''));
		exit();
	}else{
		$a_msg[1]='导入成功！';
		if(isset($_GET['f']))$a_msg[2]='数据已备份到“'.htmlspecialchars($_GET['f'], ENT_QUOTES).'”中'.((isset($_GET['inc']) && $_GET['inc']==1)?'，<a href="'.htmlspecialchars($_GET['f'], ENT_QUOTES).'">点击右键另存</a>':'').'。';
		$js_c.='
		$("#drform").submit(function(e){
			if(!confirm(\'确认要导入新数据？\')){
				e.preventDefault();
			}else if($("#drform input[type=\'submit\']").length>0){
				$("#drform input[type=\'submit\']").attr(\'disabled\', \'disabled\');
			}
		})';
		$content.=((isset($_GET['e']) && isset($a_msg[$_GET['e']]))?'<div class="msg_v">'.$a_msg[$_GET['e']].'</div>':'').'<div class="title">数据库备份</div><div class="lcontent">使用数据库备份功能可以把数据库中的数据备份到文本文件并保存到“setup/”目录中，备份后可以通过ftp下载。<form method="post" action="" class="btform_nv"><div class="formline"><input type="radio" name="file_t" value="0" checked="checked"/>备份为php文件，只可以通过ftp下载</div><div class="formline"><input type="radio" name="file_t" value="1"/>备份为inc文件，备份后可以即时下载</div><div class="formline"><input type="hidden" name="export" value="1"/><input type="submit" value="备份" class="button" /></div></form></div><br/><div class="title">数据库恢复</div><div class="lcontent">使用数据库恢复功能后将会用新的数据替换数据库中原有的数据，原有数据将<strong>不可恢复</strong>，请谨慎使用此功能！<br/><br/><div id="sql_i_0">确定要使用数据库恢复功能？<br/><input type="button" value="确定" name="hs_cbt" data-id="sql_i_0|sql_i_1" class="button" /></div><div id="sql_i_1" style="display: none;">请先把使用数据库备份功能生成的文件上传到“setup/”目录，再使用下面的表单恢复数据。<form method="post" action="" id="drform"><div class="formline">setup/<input name="file" size="32" /></div><div class="formline"><input type="hidden" name="import" value="1"/><input type="submit" value="导入" class="button" /> <input type="button" value="取消" name="hs_cbt" data-id="sql_i_1|sql_i_0" class="button" /></div></form></div></div>';
	}
}
