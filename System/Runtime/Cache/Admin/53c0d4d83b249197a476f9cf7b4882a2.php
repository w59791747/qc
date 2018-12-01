<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($CONFIG["site"]["title"]); ?>管理后台</title>
<link href="<?php echo ($admin_statics); ?>/css/public.css" type="text/css" rel="stylesheet">
<link href="<?php echo ($admin_statics); ?>/css/style.css" type="text/css" rel="stylesheet">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script src="__PUBLIC__/js/admin.js"></script>
</head>
<body>
<iframe id="zhuchuang_frm" name="zhuchuang_frm" style="display:none;"></iframe>
<div class="loginBody">
  <div class="smallpage loginContaint">
 	
    <div class=" loginRight"> <div class="center"><img src="__ROOT__/System/Tpl/statics/images/loginLogo.png">
     </div>
      <div class="login_enter">
       <h2>后台管理系统</h2>
        <form method="post" action="<?php echo U('login/loging');?>" target="zhuchuang_frm" >
          <div class="login_box">
            <input type="text" name="username" class="text" placeholder="请输入您的用户名"/>
            <i class="usIco"></i> </div>
          <div class="login_box">
            <input type="password" name="password" class="text" placeholder="请输入您的密码" />
            <i class="pdIco"></i> </div>
          <?php if($CONFIG[power][admin] == on): ?><div class="login_box login_yzm">
            <i class="yzIco"></i>
              <input type="text" name="yzm" class="text" placeholder="请输入验证码" />
              <span class="yzm_code" style="display:block; cursor:pointer;"><img src="__ROOT__/index.php?g=home&m=verify&a=index&mt=<?php echo time();?>" /></span></div><?php endif; ?>
          
          <input type="submit" class="pub_btn" value="登录" />
        </form>
      </div>
      <img src="__ROOT__/System/Tpl/statics/images/touying.png"> </div>
    <div class="clear"></div>
  </div>
  <div class="loginBottom">
    <p>合肥助创信息技术有限公司</p>
    <p>版权所有</p>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.login_enter .login_box input').focus(function(){
 		 $(this).parent().addClass('focus'); 
	  });
	  	$('.login_enter .login_box input').blur(function(){
 		 $(this).parent().removeClass('focus'); 
	  });

});
</script>
</body>
</html>