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
  <div class="flt breadNav_text"> <span>后台管理</span><i></i><span>菜单设置</span><i></i><span>菜单列表</span> </div>
  <form id="cate_action" action="<?php echo U('menu/update');?>" target="zhuchuang_frm" method="post">
    <div class="frt breadNav_other">
      <div class="bn_input">
        <input type="submit" class="pub_btn" value="更新"  />
      </div>
      <div class="bn_input theme-pop-btn"> <?php echo ROLE('menu/create','','添加菜单','load','pub_btn',600,250);?> </div>
      <div class="clear"></div>
    </div>
    </div>
    <div class="daemonTable">
    <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
      <tr>
        <th> 分类 </th>
        <th> 排序 </th>
        <th class="w-150"> 操作 </th>
      </tr>
      <?php if(is_array($datas)): foreach($datas as $key=>$var): if(($var["parent_id"] == 0) && ($var["is_show"] == 1)): ?><tr>
            <td><?php echo ($var["menu_name"]); ?></td>
            <td ><input name="orderby[<?php echo ($var["menu_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" class="text w-80" /></td>
            <td><?php echo ROLE('menu/create',array("parent_id"=>$var['menu_id']),'添加','load','pub_btn',600,280);?>
              <?php echo ROLE('menu/delete',array("menu_id"=>$var['menu_id']),'删除','act','pub_btn grayBtn');?> </td>
          </tr>
          <?php if(is_array($datas)): foreach($datas as $key=>$var2): if(($var2["parent_id"]) == $var["menu_id"]): ?><tr >
                <td style="padding-left:70px; margin-left:70px;"><?php echo ($var2["menu_name"]); ?></td>
                <td style="padding-left:70px; margin-left:70px;"	><input name="orderby[<?php echo ($var2["menu_id"]); ?>]" value="<?php echo ($var2["orderby"]); ?>" type="text" class="text w-80" /></td>
                <td><?php echo ROLE('menu/action',array("parent_id"=>$var2['menu_id']),'添加','load','pub_btn',1000,400);?>
                  <?php echo ROLE('menu/delete',array("menu_id"=>$var2['menu_id']),'删除','act','pub_btn grayBtn');?> </td>
              </tr><?php endif; endforeach; endif; endif; endforeach; endif; ?>
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