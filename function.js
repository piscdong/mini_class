function chkform(id){
	var ac='';
	$('#'+id+' .bt_input').each(function(){
		if($.trim($(this).val())=='' || $.trim($(this).val())=='http://'){
			if(ac!='')ac+='、';
			ac+=$(this).attr('rel');
		}
	});
	if(ac!=''){
		alert('请输入'+ac+'！');
		return false;
	}else if($('#'+id+" input[type='submit']").length>0){
		$('#'+id+" input[type='submit']").attr('disabled', 'disabled');
	}
}

function dhdivf(n, c, t){
	for(var i=0;i<t;i++){
		if(i==c){
			$('#'+n+i).slideDown(500);
		}else{
			$('#'+n+i).slideUp(500);
		}
	}
}

function getchat(i, n, t){
	if(!t){
		chatdiv_lo();
		loadchat();
	}
	var l=0;
	if($('#chat_div_'+i).length>0){
		if(n!='' && $('#chat_name_'+i).html()!=n)$('#chat_name_'+i).html(n);
		$('#chat_div_'+i).show();
	}else{
		$('#chat_div').append('<div id="chat_div_'+i+'" class="chat_div"><div class="chat_div_t"><img src="images/close.gif" alt="" title="关闭" onclick="$(\'#chat_div_'+i+'\').hide(500);" class="f_link"/><img src="images/chat_h.gif" alt="" title="聊天记录" onclick="window.open(\'?m=message&id='+i+'#history\');" class="f_link"/><span id="chat_name_'+i+'">'+(n!=''?n:'&nbsp;')+'</span></div><div class="chat_div_i" id="chat_info_div_'+i+'"></div><div class="chat_div_in"><input id="chat_info_input_'+i+'" class="chat_in" onkeydown="if(event.keyCode==13)sendchat('+i+');" title="按回车发送"/></div></div>');
	}
	if($("input[name='chat_lid_"+i+"']").length>0){
		$("input[name='chat_lid_"+i+"']").each(function(){
			if(l<$(this).val())l=$(this).val();
		});
	}
	chatdiv_lo();
	addchat(i, l);
}

function sendchat(i){
	var m=$.trim($('#chat_info_input_'+i).val());
	$('#chat_info_input_'+i).val('');
	$('#chat_info_input_'+i).focus();
	if(m!=''){
		$.post('j_chat.php?m=2&i='+i, {c:m}, function(data){
			$('#chat_info_div_'+i).append(data);
			getchathe(i);
		})
	}
}

function getchathe(i){
	var h=0;
	if($('#chat_info_div_'+i+' .chat_list').length>0){
		$('#chat_info_div_'+i+' .chat_list').each(function(){
			h+=$(this).height();
		});
	}
	var ph=$('#chat_info_div_'+i).height();
	if(h>ph)$('#chat_info_div_'+i).scrollTop((h-ph)*2);
}

function addchat(i, l){
	$.get('j_chat.php?m=1&i='+i+'&t='+$('#login_date').val()+'&l='+l, function(data){
		$('#chat_info_div_'+i).append(data);
		getchathe(i);
	})
}

function chatdiv_lo(){
	if($('#chat_div').html()!=''){
		$('#chat_div').show();
		$('#chat_div').css({'top':($(window).height()+$(document).scrollTop()-$('#chat_div').height()-15)+'px', 'left':($(window).width()+$(document).scrollLeft()-$('#chat_div').width()-30)+'px'});
	}else{
		$('#chat_div').hide();
	}
}

function loadchat(){
	if($('#chat_p_div').length>0){
		$('#chat_p_div').load('j_chat.php?m=0');
		if($('#chat_p_input').length>0 && $('#chat_p_input').val()!=''){
			var chat_a=$('#chat_p_input').val().split('|');
			for(var i=0;i<chat_a.length;i++){
				var n=$('#chat_pn_'+chat_a[i]).length>0?$('#chat_pn_'+chat_a[i]).val():'';
				getchat(chat_a[i], n, 1);
			}
		}
	}
	setTimeout('loadchat()', 1500);
}

function ld(i, p, url){
	if($('#plist_'+i+'_'+p).length>0){
		$('.plist_'+i).each(function(){
			if($(this).attr('id')!='plist_'+i+'_'+p)$(this).hide();
		});
		$('#plist_'+i+'_'+p).show();
	}else{
		$('.plist_'+i+':last').after('<span class="plist_'+i+'" id="plist_'+i+'_'+p+'" style="display: none;"></span>');
		$('#p_'+i).show();
		$('#plist_'+i+'_'+p).load(url, function(){
			$('.plist_'+i).each(function(){
				if($(this).attr('id')!='plist_'+i+'_'+p)$(this).hide();
			});
			$('#plist_'+i+'_'+p).show();
			$('#p_'+i).hide();
		});
	}
}

function getflickr(p){
	if($('#flickr_isload').length==0)$('#flickr_sdiv').after('<input type="hidden" id="flickr_isload" value="0"/>');
	if($('#flickr_isload').val()=='0'){
		$('#flickr_isload').val('1');
		if($('#plist_flickr_'+p).length>0){
			$('.plist_flickr').each(function(){
				if($(this).attr('id')!='plist_flickr_'+p)$(this).hide();
			});
			$('#plist_flickr_'+p).show();
			$('#flickr_isload').val('0');
		}else{
			var k=$('#flickr_key').val();
			var id=$('#flickr_id').val();
			if($('#p_flickr').length>0)$('#p_flickr').show();
			$.getJSON('http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&user_id='+id+'&per_page=10&page='+p+'&format=json&api_key='+k+'&jsoncallback=?', function(data){
				if(data.stat=='ok' && data.photos.total>0 && p<=data.photos.pages){
					var html='<span class="plist_flickr" id="plist_flickr_'+p+'" style="display: none;">';
					$.each(data.photos.photo, function(i,v){
						html+='<div class="al_list"><img src="http://farm'+v.farm+'.static.flickr.com/'+v.server+'/'+v.id+'_'+v.secret+'_s.jpg" width="70" height="70" class="al_t f_link" onclick="$(\'#formurl0\').val(\'http://farm'+v.farm+'.static.flickr.com/'+v.server+'/'+v.id+'_'+v.secret+'.jpg\');$(\'#formtitle1\').val($(this).attr(\'src\'));$(\'#formtitle0\').val($(this).attr(\'title\'));" alt="" title="'+decodeURIComponent(v.title)+'"/></div>';
					});
					html+='<div class="extr"></div>';
					var tp=data.photos.pages;
					if(tp>1){
						for(var i=1;i<=tp;i++){
							if(i==p){
								html+='['+i+']';
							}else{
								html+='<span href="#" onclick="getflickr(\''+i+'\');" class="mlink f_link">'+i+'</span>';
							}
							html+=' ';
						}
						html+='| <span onclick="$(\'#album_sync_div\').slideUp(500);" class="mlink f_link">隐藏</span>';
					}
					html+='</span>';
					if($('.plist_flickr').length>0){
						$('.plist_flickr:last').after(html);
						$('#p_flickr').hide();
					}else{
						$('#flickr_sdiv').html(html+'<span id="p_flickr" style="display: none;"> <img src="images/v.gif" alt="" title="载入中……" class="loading_va"/></span>');
					}
					$('.plist_flickr').each(function(){
						if($(this).attr('id')!='plist_flickr_'+p)$(this).hide();
					});
					$('#plist_flickr_'+p).show();
					$('#flickr_isload').val('0');
				}
			});
		}
	}
}

function setStyle(p, i){
	$("head link[rel='stylesheet']").removeAttr('href');
	$("head link[rel='stylesheet']").attr('href', p);
	var exp=new Date();
	exp.setTime(exp.getTime()+(365*86400*10000));
	document.cookie='minic_skin='+i+'; expires='+ exp.toGMTString();
}

$(function(){
	$("a[rel='external'], a[rel='nofollow']").click(function(){
		window.open($(this).attr('href'));
		return false;
	});
	if($('#login_date').length>0 && $('#login_date').val()>0){
		chatdiv_lo();
		loadchat();
		$(window).scroll(function(){
			chatdiv_lo();
		});
	}
	$('.btform').submit(function(e){
		var id=$(this).attr('id');
		var ac='';
		$('#'+id+' .bt_input').each(function(){
			if($.trim($(this).val())=='' || $.trim($(this).val())=='http://'){
				if(ac!='')ac+='、';
				ac+=$(this).attr('rel');
			}
		});
		if(ac!=''){
			alert('请输入'+ac+'！');
			e.preventDefault();
		}else if($('#'+id+" input[type='submit']").length>0){
			$('#'+id+" input[type='submit']").attr('disabled', 'disabled');
		}
	});
	$('.btform_p').submit(function(e){
		var id=$(this).attr('id');
		var ac='';
		$('#'+id+' .bt_input').each(function(){
			if($.trim($(this).val())=='' || $.trim($(this).val())=='http://'){
				if(ac!='')ac+='、';
				ac+=$(this).attr('rel');
			}
		});
		if(ac!=''){
			alert('请输入'+ac+'！');
			e.preventDefault();
		}else if($('#formpw1').val()!=$('#formpw').val()){
			alert('请确认密码！');
			e.preventDefault();
		}else if($('#'+id+" input[type='submit']").length>0){
			$('#'+id+" input[type='submit']").attr('disabled', 'disabled');
		}
	});
	$('.btform_nv').submit(function(e){
		var id=$(this).attr('id');
		if($('#'+id+" input[type='submit']").length>0){
			$('#'+id+" input[type='submit']").attr('disabled', 'disabled');
		}
	});
	$('.em_img').click(function(){
		var c=$('#forminfo0').val();
		c+='[em'+$(this).data('id')+']';
		$('#forminfo0').val(c);
	});
	$('.url_img').click(function(){
		var txt=prompt('请输入要链接的网址','http://');
		if(txt!=null && txt!='' && txt!='http://'){
			var c=$('#forminfo0').val();
			c+='[url]'+txt+'[/url]';
			$('#forminfo0').val(c);
		}
	});
	$("span[name='alllink']").click(function(){
		$('#k_'+$(this).data('id')).hide();
		$('#s_'+$(this).data('id')).show();
	});
	$("span[name='s_cbt'], img[name='s_cbt'], input[rel='s_cbt']").click(function(){
		$('#'+$(this).data('id')).slideDown(500);
	});
	$("span[name='h_cbt'], input[name='h_cbt'], input[rel='h_cbt']").click(function(){
		$('#'+$(this).data('id')).slideUp(500);
	});
	$("img[name='hs_cbt'], input[name='hs_cbt'], span[name='hs_cbt']").click(function(){
		var f=$(this).data('id').split('|');
		$('#'+f[0]).slideUp(500);
		$('#'+f[1]).slideDown(500);
	});
	$("img[name='chat_img']").click(function(){
		var n=$(this).data('id').split('|');
		getchat(n[0], n[1]);
	});
	$(".img_lb").click(function(){
		var url=$(this).data('img');
		var is_newp=1;
		if($("#lightbox_bg").length==0){
			$("body").append('<img src='+url+' id="lightbox_img" style="display: none;" alt="" title="点击关闭"/><div id="lightbox_bg" style="display: none;" title="图片载入中，点击关闭"></div><div id="lightbox_c" class="loading_va" style="display: none;line-height: 4px;overflow: hidden;" title="图片载入中……"></div><input id="lightbox_s" type="hidden" value="1"/>');
		}else{
			if($('#lightbox_img').attr('src')!=url){
				$('#lightbox_img').attr('src', url);
			}else{
				is_newp=0;
			}
			$('#lightbox_s').val('1');
			$('#lightbox_bg').attr('title', '图片载入中，点击关闭');
		}
		var vw=$(document).width();
		var vh=$(document).height();
		var sl=$(document).scrollLeft();
		var st=$(document).scrollTop();
		if($.browser.msie){
			$("#lightbox_c").show();
			$("#lightbox_bg").show();
			$("#lightbox_bg").fadeTo(50, 0.5);
		}else{
			$("#lightbox_c").fadeIn(500);
			$("#lightbox_bg").fadeIn(500);
		}
		$("#lightbox_c").css({'top':(st+(($(window).height()-$("#lightbox_c").height())/2))+'px', 'left':(sl+(($(window).width()-$("#lightbox_c").width())/2))+'px'});
		$("#lightbox_bg").css({'width':vw+'px', 'height':vh+'px'});
		if(is_newp>0){
			$("#lightbox_img").load(function(){
				$('#lightbox_bg').attr('title', '点击关闭');
				var w=$(this).width();
				var h=$(this).height();
				w+=10;
				h+=10;
				var l=sl+($(window).width()-w)/2;
				if(l<0)l=0;
				var t=st+($(window).height()-h)/2;
				if(t<0)t=0;
				if(w>vw){
					vw=w;
					l=0;
				}
				if(h>vh){
					vh=h;
					t=0;
				}
				$("#lightbox_bg").css({'width':vw+'px', 'height':vh+'px'});
				$("#lightbox_img").css({'top':t+'px', 'left':l+'px'});
				if($('#lightbox_s').val()=='1')$("#lightbox_img").show();
				$("#lightbox_c").hide();
			});
		}else{
			$("#lightbox_c").hide();
			if($('#lightbox_s').val()=='1')$("#lightbox_img").show();
		}
		$("#lightbox_img, #lightbox_bg").click(function(){
			$("#lightbox_bg").fadeOut(500);
			$("#lightbox_img").fadeOut(500);
			$("#lightbox_c").fadeOut(500);
			$('#lightbox_s').val('0');
		});
		$(window).scroll(function(){
			var sl=$(document).scrollLeft();
			var st=$(document).scrollTop();
			$("#lightbox_c").css({'top':(st+(($(window).height()-$("#lightbox_c").height())/2))+'px', 'left':(sl+(($(window).width()-$("#lightbox_c").width())/2))+'px'});
		});
	});
	$(".video_slink").click(function(){
		$(this).slideUp(500);
		var i=$(this).data('id');
		$("#video_div_"+i).html($("#video_text_"+i).val());
		$("#video_div_"+i).slideDown(500);
	});
});