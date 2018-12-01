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
    <div class="flt breadNav_text"> <span>设置</span><i></i><span>支付设置</span><i></i><span>支付方式</span> </div>
    <form id="cate_action" action="<?php echo U('menu/update');?>" target="zhuchuang_frm" method="post">
      
      </div>
      <div class="daemonTable">
      <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
        <tr>
          <th> ID </th>
          <th> 支付方式 </th>
          <th> 图片 </th>
          <th> 内容 </th>
          <th> 操作 </th>
        </tr>
         <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                <td class="w-50"><?php echo ($var["payment_id"]); ?></td>
                <td width="100"><?php echo ($var["name"]); ?></td>
                <td width="100"> <img id="photo_img"  src="__PUBLIC__/images/<?php echo (($var["logo"])?($var["logo"]):'default.jpg'); ?>" /></td>
                
                <td class="wsNormal"><?php echo ($var["contents"]); ?></td>
                <td class="w-200">
                    <?php if(($var["is_open"]) == "1"): echo ROLE('payment2/uninstall',array("payment_id"=>$var["payment_id"]),'卸载','act','pub_btn grayBtn');?>
                     <?php echo ROLE('payment2/install',array("payment_id"=>$var["payment_id"]),'编辑','','pub_btn');?>
            <?php else: ?>
            <?php echo ROLE('payment2/install',array("payment_id"=>$var["payment_id"]),'安装','','pub_btn'); endif; ?>
            
           
            </td>
            </tr><?php endforeach; endif; ?>
      </table>
    </form>
  </div>
  <div class="daemonBot">
    <div class="frt daemonPage"> </div>
    <div class="clear"></div>
  </div>
</div>
<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>