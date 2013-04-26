<?php
/**
 * 迷你同学录 (http://mini_class.piscdong.com/)
 * (c)PiscDong studio (http://www.piscdong.com/)
 *
 * 程序完全免费，请保留这段代码。
 * 请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。
 */

function mbookencode($c){
	global $em_a;
	$c=preg_replace("/\[url\](.*?)\[\/url\]/i",'<a href="$1">$1</a>',$c);
	$c=preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/is",'<a href="$1">$2</a>',$c);
	foreach($em_a as $k=>$v){
		$ei=str_pad($k, 2, '0', STR_PAD_LEFT);  
		$c=str_replace('[em'.$ei.']', '<img src="../images/em'.$ei.'.gif" alt="" title="'.$v.'" />', $c);
	}
	$c=str_replace("\r",'<br />',$c);
	return $c;
}

function getmco($c, $i, $n=0, $r=0){
	$m=200;
	return (($r>0 && strstr($c, "\r"))?'<br/>':'').(strlen($c)>$m?mbookencode(substrs($c, ($m-5))).'<br/><a href="?m=list&amp;id='.$i.($n>0?'#reply-'.$n:'').'">查看全部 &gt;&gt;</a>':mbookencode($c));
}

function getmthu($r){
	global $config;
	if($r['upload']>0){
		if($config['slink']>0){
			$ct='../file/'.getthi($r['url']);
			if(file_exists($ct)){
				$t=$ct;
			}else{
				$t='../file/'.$r['url'];
			}
		}else{
			$t='../img.php?t=1&amp;id='.$r['id'];
		}
	}else{
		$t=$r['vid']>0?'../images/video.jpg':$r['url'];
		if(strstr($r['url'], '[/]')){
			$a_u=explode('[/]', $r['url']);
			$t_u=$a_u[count($a_u)-1];
			if(trim($t_u)!='' && strstr(trim($t_u), '://'))$t=trim($t_u);
		}
	}
	return $t;
}
?>