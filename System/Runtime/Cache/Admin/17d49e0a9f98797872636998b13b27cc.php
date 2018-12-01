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
          	<div class="flt breadNav_text">
            <span>后台管理</span><i></i><span>运营管理</span><i></i><span>分享设置</span>
           
            </div>
            <div class="frt breadNav_other">
           
                <div class="bn_input theme-pop-btn">
               
                   <div class="bn_input"> 
                   <?php echo ROLE('fx/create','','添加分享链接','load','pub_btn',500,250);?>
                   </div>
                </div>
            <div class="clear"></div>
          </div>
          </div>
		<form target="zhuchuang_frm" method="post">
             <div class="daemonTable">
              <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
                
                <tr>
                	<th class="w-100">
                    序号
                    </th>
                    <th>名称</th>
                    <th>电话</th>
                    <th>链接地址</th>
                    <th class="w-150">
                    操作
                    </th>
                </tr>
                   <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><?php echo ($var["fx_id"]); ?></td>
                            <td><?php echo ($var["name"]); ?></td>
                            
                            <td><?php echo ($var["mobile"]); ?></td>
                            <td><?php echo ($var["link"]); ?></td>
                            <td>
                            	<?php echo ROLE('fx/buy',array("fx_id"=>$var["fx_id"]),'投资数据','','pub_btn');?>
                                <?php echo ROLE('fx/reg',array("fx_id"=>$var["fx_id"]),'注册数据','','pub_btn');?>
                                <?php echo ROLE('fx/edit',array("fx_id"=>$var["fx_id"]),'编辑','load','pub_btn',500,250);?>
                                <?php echo ROLE('fx/delete',array("fx_id"=>$var["fx_id"]),'删除','act','pub_btn gry');?>
                               
                            </td>
                        </tr><?php endforeach; endif; ?>

                </table>
           </div>
              <div class="daemonBot">
                <div class="flt daemonBot_cz">
                </div>
                <div class="frt daemonPage">
                   <?php echo ($page); ?>
                </div>
                <div class="clear"></div>
              </div>
          </div>    
          

<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>