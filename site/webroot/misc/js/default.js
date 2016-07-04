$(document).ready(function(){
	$("#banners img.first").load(function(){
		var bfwidth = $(this).width();
		var wwidth = $(window).width();
		if(bfwidth > wwidth) $(this).css('margin-left', -(bfwidth-wwidth)/2+'px');
	});
	
	show_img();
	homeSelectProject();
	
	customzIMG();
	searchResult();
	getCitys(0,0,0);
	
	/*
	$("#bgMask").click(function(){
		$("#bgMask").fadeOut('slow');
		$("#bgTransparent").fadeOut('slow');
		$("#POPBox").fadeOut('slow');
	});
	*/
});
function scrollTop(){return $("html, body").animate({scrollTop: 0},"slow"),!1}
function scrollBottom(){return $("html, body").animate({scrollTop: $(document).height()},"slow"),!1}


function homeSelectProject(){
  	$("#projects").focus(function(){
  		$("#projects_list").show();
  	}).blur(function(){
  		$("#projects_list").fadeOut('slow');
  	});
  	$("#searchBox .left .inputs span i").click(function(){
  		$("#projects_list").show();
  	});
  	$("#projects_list p").click(function(){
  		$("#projects").val($(this).text());
  		$("#searchBox .left .inputs span .delete").show();
  		$("#projects_list").hide();
  		getSearchKey($(this).attr('data'));
  	});
}
function removeSearchInfo(){
	$("#projects").val('');
	$("#searchBox .left .inputs span .delete").hide();
	$("#searchMore").hide();
}
function getSearchKey(n){
	$.get('/ajax',{a:'searchkey', id:n},function(data){
		$("#searchMore").html(data);
		$("#searchMore").show();
	});
}

function searchMore()
{
	if($("#searchBox").height() < 200){
		$("#searchBox").animate({height:"400px"});
		$("#searchBox .right a.more").css('background','url(/misc/images/misc/up-arrow.png) center right no-repeat');
		$("#moreSelect").show();
	}else{
		$("#searchBox").animate({height:"160px"});
		$("#searchBox .right a.more").css('background','url(/misc/images/misc/down-arrow.png) center right no-repeat');
		$("#moreSelect").fadeOut('fast');
	}
}

var MOVEY = 0;
function homeChangePage()
{
	$("#home_pageup a").click(function(){		
		var obj=$("#home_content .moveupbox");
		MOVEY = MOVEY + 392;
		var divh = obj.height();
		if(divh < MOVEY) MOVEY=0;
		var n = MOVEY/252;
		obj.animate({"margin-top": -MOVEY +"px"}, 600, function(){
			//
		});
	});
}

function aboutMenu(n)
{
	var height = n*621;
	var times = 600;
	$("#about_right .moveupbox").animate({"margin-top": -height +"px"}, times, function(){
		$("#about_left .aboutmenu").removeClass('active');
		$("#about_left .menu"+n).addClass('active');
	});
}

function show_img()
{
	var _bodies = $("#banners img");
	if(_bodies.length < 1) return false;
	
	$("#banners").append('<dl id="slide_b" class="clearfix"><dt class="hover"></dt></dl>');
	for(var i=1; i<_bodies.length; i++) $("#slide_b").append('<dt class=""></dt>');
	_bodies.eq(0).show();
	var defaultOpts = { interval: 8000, fadeInTime: 300, fadeOutTime: 0 };
	var _slide_b = $("dl#slide_b");
	_slide_b.css("margin-left",-(_slide_b.width()/2)+"px");
	
	var _titles = $("dl#slide_b dt");
	var _count = _titles.length;
	var _current = 0;
	var _intervalID = null;
	var stop = function () { window.clearInterval(_intervalID);};
	var slide = function (opts) {
		if (opts) {
			_current = opts.current || 0;
		} else {
			_current = (_current >= (_count - 1)) ? 0 : (++_current);
		};
		_bodies.filter(":visible").fadeOut(defaultOpts.fadeOutTime, function () {
			_bodies.eq(_current).fadeIn(defaultOpts.fadeInTime,function(){
				$("#banners").css('background','url("'+_bodies.eq(_current).attr("src")+'") center center no-repeat');
			}).css("display","block");
			_bodies.removeClass("cur").eq(_current).addClass("cur");
			var wwidth = $(window).width();
			var bwidth = _bodies.width();
			if(bwidth > wwidth) _bodies.css('margin-left', -(bwidth-wwidth)/2+'px');
		});
		_titles.removeClass("hover").eq(_current).addClass("hover");
	};
	var go = function () {
		stop();
		_intervalID = window.setInterval(function () { slide(); }, defaultOpts.interval);
	};
	var itemMouseOver = function (target, items) {
		stop();
		var i = $.inArray(target, items);
		slide({ current: i });
	};
	_titles.hover(function () { if ($(this).attr('class') != 'cur') { itemMouseOver(this, _titles); } else { stop(); } }, go);
	_bodies.hover(stop, go);

	go();
}

function footerShow()
{
	if('none'==$("#footer .main").css('display')){
		$("#footer .main").show('fast');
		$("#footer_show").removeClass('show');
		$("#footer_show").addClass('close');
		//scrollBottom();
	}else{
		$("#footer .main").hide('fast');
		$("#footer_show").removeClass('close');
		$("#footer_show").addClass('show');
	}
}

function getLegal(){
	$.get('/ajax',{a:'legal'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getPrivacy(){
	$.get('/ajax',{a:'privacy'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getNewsletter(){
	$.get('/ajax',{a:'newsletter'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getBusiness(){
	$.get('/ajax',{a:'business'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getBaoXian(){
	$.get('/ajax',{a:'baoxian'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getPages(page){
	$.get('/ajax',{a:page},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function getCatePage(cate){
	$.get('/ajax',{a:cate},function(data){
		$("#POPBox").html(data);
		$("#POPBox").show();
		$("#closePOPBox").show();
		var top = $(window).scrollTop();
		$("#POPBox").css('top',top+25);
		$("#closePOPBox").css('top',top);
		//$("html, body").animate({scrollTop: 100},"slow");
	});
	$("#bgMask").show();
}
function sigin(){
	$.get('/ajax',{a:'sign'},function(data){
		$("#POPBox").html(data);
		$("#POPBox").css({"width":"800px","marginLeft":"-400px","-webkit-border-radius":"6px", "-moz-border-radius":"6px", "border-radius":"6px"});
		$("#POPBox").show();
		$("#closePOPBox").css({"marginLeft":"390px"});
		$("#closePOPBox").show();
		var top = $(window).scrollTop();
		$("#POPBox").css('top',top+55);
		$("#closePOPBox").css('top',top+30);
	});
	$("#bgMask").show();
}

function customzIMG()
{
	$("#mimgs .area1").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-1.jpg");
	});
	$("#mimgs .area2").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-2.jpg");
	});
	$("#mimgs .area3").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-3.jpg");
	});
	$("#mimgs .area4").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-4.jpg");
	});
	$("#mimgs .area5").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-5.jpg");
	});
	$("#mimgs .area6").hover(function(){
		$("#mimgs img").attr("src","/misc/images/customize/chat-6.jpg");
	});
}

function showQLCAP()
{
	$("#qlcapdiv").show();
	showDDZRZCFW();
	$("#ddzrzctitab").show();
}
function hideQLCAP()
{
	$("#qlcapdiv").hide();
	hideDDZRZCFW();
	$("#ddzrzctitab").hide();
}
function showDDZRZCFW()
{
	$("#ddzrzcfwdiv").show();
}
function hideDDZRZCFW()
{
	$("#ddzrzcfwdiv").hide();
}

function changeWoman()
{
	$("#destination").change(function(){
		if('switzerland'==$(this).val()){
			$("tr.sex").show();
			$("tr.nosex").hide();
			$("#woman_num1").attr('disabled','disabled');
			$("#man_num").removeAttr('disabled');
			$("#woman_num2").removeAttr('disabled');
			$("#projecttt").removeAttr('disabled');
		}else{
			$("tr.sex").hide();
			$("tr.nosex").show();
			$("#projecttt").find("option[value='base']").attr("selected",true);
			$("#projecttt").attr('disabled','disabled');
			$("#woman_num2").attr('disabled','disabled');
			$("#woman_num1").removeAttr('disabled');
			$("#man_num").attr('disabled','disabled');
		}
	});
}

function searchResult()
{
	$("#search_btn").click(function(){
		$.ajax({      
			type: "POST",
			//dataType: "json",
			url: "/checkout/",     
			data: $("#search_form").serialize(),
			success: function(data){
				$("#searchResult").html(data);
				$("#bgMask").show();
				$("#bgTransparent").show();
			}  
		});
	});
	$("#xiugai_btn").click(function(){
		$("#bgMask").hide();
		$("#bgTransparent").hide();
	});
}

function payForm()
{
	var userid = $("#userid").val();
	if(userid < 1){
		sigin();
		return false;
	}
	return true;
}

function userSignIn()
{
	var username = $("#username_in").val();
	var passwd = $("#passwd_in").val();
	var yzm = $("#yzm_in").val();
	
	$("#username_in").removeAttr("style");
	$("#passwd_in").removeAttr("style");
	$("#yzm_in").removeAttr("style");
	$("#sigintb .username_error").html('');
	$("#sigintb .passwd_error").html('');
	$("#sigintb .yzm_error").html('');
	$("#signin_btn").attr("disabled","disabled");
	$("#loadinggifin").show();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/sign/?a=sigin&t=ajax",
		data: {"username":username, "passwd":passwd, "yzm":yzm},
		success: function(data){
			switch(data.status){
				case 2: $("#username_in").css("border","1px red solid"); $("#sigintb .username_error").html(data.msg); break;
				case 3: $("#passwd_in").css("border","1px red solid"); $("#sigintb .passwd_error").html(data.msg); break;
				case 4: $("#yzm_in").css("border","1px red solid"); $("#sigintb .yzm_error").html(data.msg); break;
				case 5: $("#username_in").css("border","1px red solid"); $("#sigintb .username_error").html(data.msg);break;
				case 0: $("#usermember").html('<a class="sigin first clearfix" href="/user/">'+username+'</a> <a class="sigin clearfix" href="/sign?out">退出</a>'); closePOPBox(); $("#userid").val(1); break;
				default:$("#username_in").css("border","1px red solid"); $("#sigintb .username_error").html("用户注册失败");
			}
			$("#Image1").attr("src","/vcode");
			$("#signin_btn").removeAttr("disabled");
			$("#loadinggifin").hide();
		}  
	});	
	return false;
}

function userSignUp()
{
	var username = $("#username_up").val();
	var phone = $("#phone_up").val();
	var passwd = $("#passwd_up").val();
	var repasswd = $("#repasswd_up").val();
	var yzm = $("#yzm_up").val();
	
	$("#username_up").removeAttr("style");
	$("#phone_up").removeAttr("style");
	$("#passwd_up").removeAttr("style");
	$("#repasswd_up").removeAttr("style");
	$("#yzm_up").removeAttr("style");
	$("#siguptb .username_up_error").html('');
	$("#siguptb .phone_up_error").html('');
	$("#siguptb .passwd_up_error").html('');
	$("#siguptb .repasswd_up_error").html('');
	$("#siguptb .yzm_up_error").html('');
	$("#signup_btn").attr("disabled","disabled");
	$("#loadinggif").show();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/sign/?a=sigup&t=ajax",
		data: {"username":username, "phone":phone, "passwd":passwd, "repasswd":repasswd, "yzm":yzm},
		success: function(data){
			switch(data.status){
				case 2: $("#username_up").css("border","1px red solid"); $("#siguptb .username_up_error").html(data.msg); break;
				case 3: $("#phone_up").css("border","1px red solid"); $("#siguptb .phone_up_error").html(data.msg); break;
				case 4: $("#passwd_up").css("border","1px red solid"); $("#siguptb .passwd_up_error").html(data.msg); break;
				case 5: $("#repasswd_up").css("border","1px red solid"); $("#siguptb .repasswd_up_error").html(data.msg); break;
				case 6: $("#yzm_up").css("border","1px red solid"); $("#siguptb .yzm_up_error").html(data.msg); break;
				case 7: $("#username_up").css("border","1px red solid"); $("#siguptb .username_up_error").html(data.msg);break;
				case 8: $("#username_up").css("border","1px red solid"); $("#siguptb .username_up_error").html(data.msg);break;
				case 0: $("#sigintable table.main").hide(); $("#sigintable .result").show(); break;
				default:$("#username_up").css("border","1px red solid"); $("#siguptb .username_up_error").html("用户注册失败");
			}
			$("#Image2").attr("src","/vcode");
			$("#signup_btn").removeAttr("disabled");
			$("#loadinggif").hide();
		}  
	});
	return false;
}

function refreshYZM(id){
	var el =document.getElementById("Image"+id);
	el.src=el.src+'?';
}

function findpasswd()
{
	var username = $("#username").val();
	var yzm = $("#yzm").val();

	$("#username").removeAttr("style");
	$("#yzm").removeAttr("style");
	$("#usersbox .username_error").html('');
	$("#usersbox .yzm_error").html('');
	
	if(""==username){$("#username").css("border","1px red solid"); $("#usersbox .username_error").html("不能为空"); return false;}
	if(""==yzm){$("#yzm").css("border","1px red solid"); $("#usersbox .yzm_error").html("不能为空"); return false;}
	
	$("#passwbtn").attr("disabled","disabled");
	$("#loadinggif").show();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/user/?a=findpasswd&t=ajax",
		data: {"username":username, "yzm":yzm},
		success: function(data){
			switch(data.status){
				case 0: $("#usersbox .maintable tr.main").hide(); $("#usersbox .maintable tr.result").show(); break;
				case 2: $("#username").css("border","1px red solid"); $("#usersbox .username_error").html(data.msg); break;
				case 3: $("#yzm").css("border","1px red solid"); $("#usersbox .yzm_error").html(data.msg); break;
				case 4: $("#username").css("border","1px red solid"); $("#usersbox .username_error").html(data.msg); break;
				case 8: $("#username").css("border","1px red solid"); $("#usersbox .username_error").html(data.msg);break;
				default:$("#username").css("border","1px red solid"); $("#usersbox .username_error").html("用户验证失败");
			}
			$("#Image3").attr("src","/vcode");
			$("#passwbtn").removeAttr("disabled");
			$("#loadinggif").hide();
		}  
	});
	return false;
}

function resetPasswd()
{
	var passwd = $("#passwd").val();
	var repasswd = $("#repasswd").val();
	var code = $("#code").val();
	
	$("#passwd").removeAttr("style");
	$("#repasswd").removeAttr("style");
	$("#usersbox .passwd_error").html('');
	$("#usersbox .repasswd_error").html('');
	
	if(passwd.length < 6){$("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html("密码至少需要6位"); return false;}
	if(repasswd != passwd){$("#repasswd").css("border","1px red solid"); $("#usersbox .repasswd_error").html("两次密码不匹配"); return false;}
	
	$("#passwbtn").attr("disabled","disabled");
	$("#loadinggif").show();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/user/?a=setpasswd&t=ajax",
		data: {"passwd":passwd, "repasswd":repasswd, "code":code},
		success: function(data){
			switch(data.status){
				case 0: $("#usersbox .maintable tr.main").hide(); $("#usersbox .maintable tr.result").show(); break;
				case 2: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				case 4: $("#repasswd").css("border","1px red solid"); $("#usersbox .repasswd_error").html(data.msg); break;
				case 3: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				case 5: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				case 6: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				case 7: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				case 8: $("#passwd").css("border","1px red solid"); $("#usersbox .passwd_error").html(data.msg); break;
				default:$("#username").css("border","1px red solid"); $("#usersbox .username_error").html("密码修改失败");
			}
			$("#passwbtn").removeAttr("disabled");
			$("#loadinggif").hide();
		}  
	});
	return false;
}

var PROVID, CITYID, COUNTYID;
function getCitys(province, city, county)
{
	PROVID = province;
	CITYID = city;
	COUNTYID = county;
	
	$("#province").change(function(){
		ajaxCity($(this).val());
	});
	$("#city").change(function(){
		ajaxCouty($(this).val());
	});
	
	if(province) ajaxCity(province);
	if(city) ajaxCouty(city);
}
function ajaxCity(province)
{
	$("#city").empty();
	$("#county").empty();
	$.getJSON('/public/county/?pid='+province+'&ajax=1',function(json){
		var data = json.data;
		for(var i=0;i<data.length;i++){
			var select='';
			if(CITYID==data[i].id) select=' selected="selected"';
			$("#city").append($("<option"+select+">").val(data[i].id).text(data[i].name));
		}
		ajaxCouty(data[0].id);
	});
}
function ajaxCouty(city)
{
	$("#county").empty();
	$.getJSON('/public/county/?pid='+city+'&ajax=1',function(json){
		var data = json.data;
		for(var i=0;i<data.length;i++){
			var select='';
			if(COUNTYID==data[i].id) select=' selected="selected"';
			$("#county").append($("<option"+select+">").val(data[i].id).text(data[i].name));
		}
	});
}
function orderInfo(order_code, status)
{
	$.get('/order/',{order_code:order_code},function(html){
		$("#searchResult").html(html);
		$("#bgMask").show();
		$("#bgTransparent").show();
		$("#pay_form .box .bottom").html('');
		if(status < 2) $("#pay_form .box .bottom").html('<input type="button" id="pay_btn" class="orderpay" value="立即支付">');
		$("#pay_form .box .bottom").append('<input type="button" id="closeorder_btn" class="orderpay" value="关闭">');
		$("#closeorder_btn").click(function(){$("#bgMask").hide();$("#bgTransparent").hide();});
		$("#pay_btn").click(function(){location.href="/pay/?order_code="+order_code;});
	});
}

//------------------ AJAX UPLOAD
function uploads()
{
	if('undefined'!=typeof($("#bjpdzpic").attr('id'))) fileUpLoad("bjpdzpic");
	if('undefined'!=typeof($("#d3mbgszl").attr('id'))) fileUpLoad("d3mbgszl");
	if('undefined'!=typeof($("#yydzpic").attr('id'))) fileUpLoad("yydzpic");
}
function fileUpLoad(id)
{
    'use strict';
    var uploadpath = ('undefined'==typeof($("#fileuploadpath").val())) ? window.location.pathname : '/'+$("#fileuploadpath").val();
    // Change this to the location of your server-side upload handler:
    var url = '/public/upload'+uploadpath+'/'+id,
        uploadButton = $('<button/>')
            .addClass('btn btn-primary uploadBtn')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $("#"+id).fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        //data.context = $('<div/>').appendTo("#"+id+'_files');		//一个按钮可以上传多个图片
    	data.context = $('<div/>');
        $.each(data.files, function (index, file) {
            var node = $('<p/>').append($('<span/>').text(file.name));
            if (!index) {
                node.append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
        $("#"+id+'_files').html(data.context);		//一个按钮只能上传一个图片
        $("#"+id+'_progress').show();
        $("#"+id+'_progress .progress-bar').css('width','0');
		
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node.prepend('<br>').prepend(file.preview);
        }
        if (file.error) {
            node.append('<br>').append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button').text('上传').prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $("#"+id+'_progress .progress-bar').css('width',progress + '%');
		$("#"+id+'_progress .progress-bar').addClass("addTotle");
		if(progress==100){
			$("#"+id+'_progress').fadeOut(3000);
		}
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result[id], function (index, file) {
            if (file.url) {
                var link = $('<a>').attr('target', '_blank').prop('href', file.url);
                $(data.context.children()[index]).wrap(link);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index]).append('<br>').append(error);
            }
            $("#"+id+'_val').val(file.name);
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index]).append('<br>').append(error);
        });
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
}
//------------------------------- END AJAX UPLOAD

//------------------------------ 
function bjpdz()
{
	var file = $("#bjpdzpic_val").val();
	msgsave(file);
}
function d3mbgszl()
{
	var file = $("#d3mbgszl_val").val();
	msgsave(file);
}
function yydz()
{
	var file = $("#yydzpic_val").val();
	msgsave(file);
}
//-------------------------------
function msgsave(file_path)
{
	var name = $("#name").val();
	var channel = $("#channel").val();
	var phone = $("#tel").val();
	var email = $("#email").val();
	var content = $("#info").val();
	var organiz = $("#organiz").val();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/msgs/?a=save&t=ajax",
		data: {"channel":channel, "organiz":organiz, "name":name, "phone":phone, "email":email, "content":content, "file_path":file_path},
		success: function(data){
			switch(data.status){
				case 0: $("#searchkeyBox").html('感谢您的支持，您的信息已经提交成功，工作人员很快就会和您取得联系，请耐心等待！'); break;
				default:$("#errormsg").html(data.msg);
			}
		}  
	});
}
function bussiness()
{
	var name = $("#name").val();
	var channel = $("#channel").val();
	var phone = $("#tel").val();
	var organiz = $("#organiz").val();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/msgs/?a=savebussiness&t=ajax",
		data: {"channel":channel, "organiz":organiz, "name":name, "phone":phone},
		success: function(data){
			switch(data.status){
				case 0: $("#resultbox").html('感谢您的支持，您的信息已经提交成功，工作人员很快就会和您取得联系，请耐心等待！'); break;
				default:$("#errormsg").html(data.msg);
			}
		}  
	});
}
function msgscontent()
{
	var channel = $("#channel").val();
	var content = $("#msgcontent").val();
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/msgs/?a=savecontent&t=ajax",
		data: {"channel":channel, "content":content},
		success: function(data){
			switch(data.status){
				case 0: $("#resultbox").html('感谢您的支持，您的信息已经提交成功，工作人员很快就会和您取得联系，请耐心等待！'); break;
				default:$("#errormsg").html(data.msg);
			}
		}  
	});
}



