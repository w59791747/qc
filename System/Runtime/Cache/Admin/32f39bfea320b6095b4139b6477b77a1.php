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


<div class="pad">
<div class="breadNav">
  <div class="flt breadNav_text"> <span>会员</span><i></i><span>会员日志</span><i></i><span>资金日志</span> </div>
  <div class="frt breadNav_other">
    <div class="bn_input theme-pop-btn">
      <form method="post" action="<?php echo U('memberlog/price');?>">
        <div class="bn_input">用户ID：
          <input type="text" name="uid" value="<?php echo ($uid); ?>" class="text">
        </div>
        <div class="bn_input">
          <input type="submit" class="pub_btn" value="搜索">
        </div>
      </form>
      <div class="bn_input"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<form target="zhuchuang_frm" method="post">
<div class="daemonTable">
  <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
    <tr>
      <th class="w-100"> 序号 </th>
      <th class="w-100">用户ID</th>
      <th class="w-50">资金</th>
      <th>日志</th>
      <th class="w-200">时间</th>
    </tr>
    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
        <td><?php echo ($var["log_id"]); ?></td>
        <td><?php echo ($memberlist[$var['uid']]['name']); ?>【<a class="linkcl" target="main_frm" href="<?php echo U('member/index',array('uid'=>$var['uid']));?>" >UID:<?php echo ($var["uid"]); ?></a>】</td>
        <td><?php echo ($var["number"]); ?></td>
        <td><?php echo ($var["log"]); ?></td>
        <td><?php echo (date("Y-m-d H:i:s",$var["dateline"])); ?></td>
      </tr><?php endforeach; endif; ?>
  </table>
</div>
<div class="daemonBot">
  <div class="flt daemonBot_cz"> </div>
  <div class="frt daemonPage"> <?php echo ($page); ?> </div>
  <div class="clear"></div>
</div>
</div>
<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>