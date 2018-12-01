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

<body class="reg_wrap">

<div class="reg_logo">
	<a href="<?php echo U('/index');?>"><img src="__ATTACHS__/<?php echo ($CONFIG[site][loginlogo]); ?>" /> </a>
    
</div>

<div class="reg_form">
	<div class="account_reg">
		<form  id="login_frm"  action="<?php echo U('passport/login');?>" method="post" target="chuang_frm">
			<input type="text" name="name" class="user_name yhm" placeholder="请输入用户名/手机号码"/>
			<input type="password" name="passwd" class="user_name mima" placeholder="请输入密码"/>
			 <?php if($CONFIG[power][login] == on): ?><p><input type="text" name="yzm" id='yzm'  class="user_name yzm" placeholder="请输入验证码"/><span class="yzmImg" >
				<img class="vail" src="__ROOT__/index.php?g=home&m=verify&a=index&mt=<?php echo time();?>" /> 
            </span></p><?php endif; ?>
			<input type="hidden" name="backurl" value="<?php echo ($backurl); ?>">
			 <input type="submit" class="user_name reg_but" value="立即登录">
		</form>
	</div>
	<div class="phone_reg">
		
	</div>
	<div class="zc_note">还没有账号? 去 <a href="<?php echo U('passport/reg');?>">注册</a><a href="<?php echo U('passport/qqlogin');?>" class="sf_denglu"><img src="__TMPL__statics/images/qq.png" alt="qq登录"></a><a href="<?php echo U('passport/wblogin');?>" class="sf_denglu"><img src="__TMPL__statics/images/weibo.png" alt="微博登录"></a><a href="<?php echo U('passport/wxlogin');?>" class="sf_denglu"><img src="__TMPL__statics/images/weixin.png" alt="微信登录"></a></div>
</div>
<div class="footsm"><?php echo ($CONFIG[site][login_footer]); ?></div>
</body>
</html>