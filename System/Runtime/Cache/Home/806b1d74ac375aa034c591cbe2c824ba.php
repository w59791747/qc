<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="qc:admins" content="55313033557612053051676375" />
<meta property="wb:webmaster" content="9ae2a72466b6eecc" />
<title><?php echo ($seo_title); ?></title>
<meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
<meta name="description" content="<?php echo ($seo_description); ?>" />
<link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>


<script type="text/javascript" src="__TMPL__statics/js/jquery-1.12.1.min.js"></script>
<script src="__PUBLIC__/js/layer/layer.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/pagescroller.min.js"></script>

<script type="text/javascript" src="__TMPL__statics/js/jquery.flexslider-min.js"></script>



</head>
<body>
<iframe id="chuang_frm" name="chuang_frm" style="display:none;"></iframe>

<link rel="stylesheet" href="__TMPL__statics/css/drag.css" />
<script type="text/javascript" src="__TMPL__statics/js/drag.js"></script>
<body class="reg_wrap">
<div class="reg_logo"> <a href="<?php echo U('/index');?>"><img src="__ATTACHS__/<?php echo ($CONFIG[site][loginlogo]); ?>" /> </a> </div>
<div class="reg_form">
  <div class="change_reg"><a href="<?php echo U('passport/reg');?>">切换到账号注册</a></div>
  <div class="account_reg">
    <form action="<?php echo U('passport/reg2');?>" method="post" target="chuang_frm" onSubmit="return check_from()">
      <input type="text" id='mobile' name='mobile' class="user_name yhm" placeholder="请输入手机号码"/>
      <p>
        <input type="text"  name="yzm" id="yzm" class="user_name yzm" placeholder="请输入验证码"/>
     <span id="jq_send">免费获取验证码</span></p>
      <div class="dragBox">
        <div id="drag"> </div>
      </div>
      <input type="submit" class="user_name reg_but" value="立即注册">
    </form>
    <div class="tixing">
      <ul>
        <li id='mobile_show'>请填写手机号码</li>
      </ul>
    </div>
  </div>
  <div class="zc_note">已经有账号了? 去 <a href="<?php echo U('passport/login');?>">登录</a></div>
</div>
<script>
  	$(document).ready(function(){
		$(".registBox .findpswd_tit span").click(function () {
			var index = $(this).index();
			$(".registBox .findpswd_tit span").each(function (a) {
				if (a == index) {
					$(this).addClass('current');
					$('.registBox').find('form').eq(a).show();
				} else {
					$(this).removeClass('current');
					$('.registBox').find('form').eq(a).hide();
				}
			})
		});
		$(".registBox .findpswd_tit span").eq(0).click();
	  });
  </script> 
<script>
 	 $("#mobile").blur(function(){
		var mobile  = $('#mobile').val();
		if(IsTel(mobile)){
			$('#mobile_show').html('');
		}else{
			$('#mobile_show').css('display','block');
			$('#mobile_show').html('手机号码格式不正确');
		}
	 });
	 
	 function check_from(){
		if($('#mobile_show').html() == ''){
			return true;	
		}else{
			return false;	
		} 
	 }
 </script> 
<script type="text/javascript">
       var mobile_timeout;
        var mobile_count = 100;
        var mobile_lock = 0;
		function sendsms (mobile) {
			if (mobile_lock == 0) {
				mobile_lock = 1;
				$.post("<?php echo U('passport/sendsms');?>",{mobile:mobile},function(data){
					if(data.status == 'success'){
						mobile_count = 60;
						layer.msg(data.msg,{icon:1});
						BtnCount();
					}else{
						mobile_lock = 0;
						layer.msg(data.msg,{icon:2});
					}
				},'json');
			}
		}
		 $(function () {
			$("#jq_send").click(function () {
				var mobile =  $("#mobile").val();
				if($('.drag_text').html() == '验证通过'){
					sendsms($("#mobile").val());

				}else{
					if (mobile_lock == 0) {
						if(IsTel(mobile)){
							$('.dragBg').fadeIn(100);
							$('.dragBox').slideDown(200);
						}else{
							layer.msg('请输入正确手机号码',{icon:2});
						}
					}
				}
			});
		 });
		$('#drag').drag();	
		
		function delayer(){
			$('.dragBg').hide(100);
			$('.dragBox').hide();
			sendsms($("#mobile").val());
		}	
		
		
		function IsTel(Tel){ 
			if(!(/^1[3|4|5|8|7]\d{9}$/.test(Tel))){ 
				return false; 
			}else{
				return true;
			}
		} 
		
		
        function BtnCount () {
            if (mobile_count == 0) {
                $('#jq_send').html("重新发送");
                mobile_lock = 0;
                clearTimeout(mobile_timeout);
            }
            else {
                mobile_count--;
                $('#jq_send').html("重新发送(" + mobile_count.toString() + ")秒");
                mobile_timeout = setTimeout(BtnCount, 1000);
            }
        };

		$('.account_reg .tixing ul li:eq(0)').css({position:'absolute',top:'20px',left:'0px'});
	$('.account_reg .tixing ul li:eq(1)').css({position:'absolute',top:'80px',left:'0px'});
	$('.account_reg .tixing ul li:eq(2)').css({position:'absolute',top:'140px',left:'0px'});
	$('.account_reg .tixing ul li:eq(3)').css({position:'absolute',top:'200px',left:'0px'});
    </script> 
<div class="footsm"><?php echo ($CONFIG[site][login_footer]); ?></div>
</body>
</html>