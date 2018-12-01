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
            <span>支付设置</span><i></i><span>支付方式</span><i></i><span>安装</span>
           
            </div>
          </div>
		<form target="zhuchuang_frm"  action="<?php echo U('payment/install',array('payment_id'=>$detail['payment_id']));?>" method="post">
           <div class="daemonTable">
              	<table class="table_data" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="alginRt w-200"> 支付方式 </th>
                        <td><img src="__PUBLIC__/images/<?php echo ($detail["logo"]); ?>" /></td>
                    </tr>
                    <tr>
                        <th class="alginRt w-200"> 支付方式介绍 </th>
                         <td class="wsNormal"><?php echo ($detail["contents"]); ?></td>
                    </tr>
                    
                   
                    
                    <tr>
                        <th class="alginRt w-200"> <font class="pointcl">*</font> 商户ID </th>
                        <td> <input type="text" name="data[appid]" value="<?php echo ($detail["setting"]["appid"]); ?>" class="text w-400" /></td>
                    </tr>
                    
                    
                      <tr>
                        <th class="alginRt w-100"> </th>
                        <td>
                        <input type="submit" value="确认保存" class="pub_btn" />
                        </td>
                      </tr>
                </table>
           </div>
        </form>  
        
 

<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>