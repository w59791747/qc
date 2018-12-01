<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<meta name="Description" content="<?php echo ($CONFIG["site"]["title"]); ?>管理后台" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<title><?php echo ($CONFIG["site"]["title"]); ?>管理后台</title>
</head>



<frameset rows="60,*" cols="*" framespacing="0" frameborder="no" border="0" name="head" >
    <frame src="<?php echo U('index/top');?>" id="admin_top" name="top" frameborder="no" scrolling="no" noresize="noresize" />
    <frameset cols="180,*" framespacing="0" frameborder="no" border="0" id="frameset_main" name="frameset_main">
        <frame src="<?php echo U('index/menu',array('menu_id'=>$menu_id));?>" name="admin_left"  frameborder="no" scrolling="auto" style="overflow-x:hidden;" id="admin_left" />
       <!-- <frame src="<?php echo U('index/middle');?>" name="admin_mid" frameborder="no" scrolling="no" noresize="noresize" id="admin_mid" />-->
        <frame src="<?php echo U('index/main');?>" name="main_frm" frameborder="no" scrolling="auto" id="main_frm" />
    </frameset>
</frameset><noframes></noframes>
<script type="text/javascript">
<!--右侧JS-->
$(document).ready(function(){
$("table.daemon_data  tr:odd").addClass("cred");
	$('.theme-pop-btn').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-popover').slideDown(200);
	})
	$('.theme-poptit .close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-popover').slideUp(200);
	});
});
</script>
</div>
</body>
</html>