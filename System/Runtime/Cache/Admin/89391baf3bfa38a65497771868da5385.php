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
<script src="__PUBLIC__/js/jquery-ui.min.js"></script>
<script src="__PUBLIC__/js/my97/WdatePicker.js"></script>

</head>
<body>
<iframe id="zhuchuang_frm" name="zhuchuang_frm" style="display:none;"></iframe>
<body>
<div class="header"> <a href="<?php echo U('index');?>" target="_top"  class="logo" title="后台首页"> <img src="<?php echo ($admin_statics); ?>/images/logo.png"> </a>
  <div class="navbar">
    <div class="topNav flt">
      <ul>
      	<?php if(is_array($menuList)): foreach($menuList as $key=>$var): ?><li><a href="<?php echo U('index/menu',array('menu_id'=>$var['menu_id']));?>" class='change_menu'  rel="<?php echo ($var["menu_id"]); ?>" target="admin_left"><?php echo ($var["menu_name"]); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
    <div class="topUsed frt">
      <i class="user"></i><span><b><?php echo ($admin["admin_name"]); ?></b>,欢迎您!</span><a target="_blank" class="indexIoc" href="<?php echo U('/home/index');?>" title="前台首页"></a><a class="updateIco" target="main_frm" href="<?php echo U('clean/cache');?>" title="更新缓存"></a><a target="_top" class="quitIco" href="<?php echo U('login/logout');?>" title="退出"></a>
    </div>
  </div>
</div>

<script>
	$(document).ready(function (e) {
		$(".change_menu").click(function () {
			var rel = $(this).attr('rel');
			$(".change_menu").removeClass('current');
			$(this).addClass('current');
			$(".sidebar_menu .active").each(function (a) {
				if ($(this).attr('rel') == rel) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		});
		
		$(".topNav li").eq(0).find('.change_menu').click();
	});
</script>