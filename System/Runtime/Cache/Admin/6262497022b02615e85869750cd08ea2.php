<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html style="background:#242D3C;">
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
</head><body>
  <div class="left_side">
    <ul class="sidebar_menu">
   			<?php if(is_array($menuList)): foreach($menuList as $key=>$var): ?><li class="active" rel="<?php echo ($var["menu_id"]); ?>"><a href="javascript:void(0);"><i class="fa"></i> <?php echo ($var["menu_name"]); ?></a>
                         	 <ul class="treeview_menu">
                                   <?php if(is_array($var["child"])): foreach($var["child"] as $key=>$var3): if($var3["is_show"] == 1): ?><li><i class="fa_dbrt"></i><a href="<?php echo U($var3['menu_action']);?>" target="main_frm"> <?php echo ($var3["menu_name"]); ?></a></li><?php endif; endforeach; endif; ?>    
                            </ul>
                        </li><?php endforeach; endif; ?>    
     </ul>
  </div>
  
<script type="text/javascript">
$(document).ready(function(){
	$("li[rel='menu_tree']>strong").click(function(){
		//$("li[rel='menu_tree']").not($(this).parent()).removeClass('open').addClass('close').children('ul').hide();
		if($(this).parent().hasClass('open')){
			$(this).parent().removeClass('open').addClass('close').children('ul').hide();
		}else{
			$(this).parent().removeClass('close').addClass('open').children('ul').show();
		}
	});//.last().click();
	$(".menu-item>li a").click(function(){
		$(".menu-item>li").not($(this).parent("li")).removeClass('active');
		$(this).parent("li").addClass("active");
	});
});
</script>

<script type="text/javascript">
<!--头部和左部公共JS-->
$(document).ready(function(){
	$('.right_side').height($(window).height()-60);
	$(".sidebar_menu > li.active a").click(function(){
		var thisli = $(this);
	    if(thisli.parent().find('.treeview_menu').is(':hidden')){
			thisli.parent().addClass('current');
			thisli.parent().find('ul.treeview_menu').slideDown(300);	
			thisli.parent().find("a i.fa").addClass('pull_right');
		}else{
			thisli.parent().removeClass('current');
			thisli.parent().find('ul.treeview_menu').slideUp(100);	
			thisli.parent().find("a i.fa").removeClass('pull_right');
		}
	});
	$('.treeview_menu li').click(function(){
		$('.treeview_menu li').removeClass('on');
		$(this).addClass('on');
	});
	
});

</script>
</body></html>