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
            <span>后台管理</span><i></i><span>运营管理</span><i></i><span>投资数据</span>
           
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
                	<th> 订单号 </th>
                      <th> 电话 </th>
                      <th> 总金额 </th>
                      <th> 支付方式 </th>
                      <th> 支付时间 </th>
                     
                </tr>
                   <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td width="200"><?php echo ($var["order_no"]); ?></td>
        <td width="200"><?php echo ($memberlist[$var['uid']]['mobile']); ?>【<a class="linkcl" target="main_frm" href="<?php echo U('member/index',array('uid'=>$var['uid']));?>" >UID:<?php echo ($var["uid"]); ?></a>】</td>
        
        
        
        <td width="200">￥<?php echo ($var["amount"]); ?></td>
        <td >
         <?php if($var[log]['payment'] == bank): ?>银行卡支付<?php elseif($var[log]['payment'] == alipay): ?>支付宝支付<?php elseif($var[log]['payment'] == wxpay): ?>微信支付<?php else: ?>
        <?php echo ($payment[$var[log]['payment']][name]); endif; ?></td>
        <td><?php echo (date("Y-m-d H:i:s",$var["dateline"])); ?></td>
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