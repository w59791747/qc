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
            <span>设置</span><i></i><span>基本设置</span><i></i><span>权限设置</span>
            
            </div>
          </div>
		<form target="zhuchuang_frm" action="<?php echo U('setting/power');?>" method="post">
           <div class="daemonTable">
              	<table class="table_data" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="alginRt w-200"> 验证码开启关闭: </th>
                        <td>
                        	
                              <label class="checkbox">
                                <input type="checkbox" name="data[reg]" <?php if(($CONFIG["power"]["reg"]) == "on"): ?>checked="checked"<?php endif; ?>>
                                注册</label>
                              <label class="checkbox">
                                <input type="checkbox" name="data[login]" <?php if(($CONFIG["power"]["login"]) == "on"): ?>checked="checked"<?php endif; ?>>
                                登录</label>
                             
                              <label class="checkbox"> 
                                <input type="checkbox" name="data[admin]" <?php if(($CONFIG["power"]["admin"]) == "on"): ?>checked="checked"<?php endif; ?>>
                                后台</label>
                        </td>
                    </tr>
                    
                    
                    
                    <tr>
                        <th class="alginRt w-200"> 审核功能: </th>
                      	 
                        <td>
                        	
                              <label class="checkbox">
                                <input type="checkbox" name="data[audit_comments]" <?php if(($CONFIG["power"]["audit_comments"]) == "on"): ?>checked="checked"<?php endif; ?>>
                                评论审核</label>
                                <label class="checkbox">
                               
                        </td>
                        
                        
                    </tr>
                    
                    <tr>
                        <th class="alginRt w-200"> 积分商城开启关闭: </th>
                      	 
                        <td>
                        	
                              <label class="checkbox">
                                <input type="checkbox" name="data[product]" <?php if(($CONFIG["power"]["product"]) == "on"): ?>checked="checked"<?php endif; ?>>
                                积分商城</label>
                        </td>
                    </tr>
                    
                    
                    
                   
                    <tr>
                        <th class="alginRt w-200">  一天最多注册用户: </th>
                        <td><input type="text"  name="data[fabu_reg]" value="<?php echo ($CONFIG["power"]["fabu_reg"]); ?>" class="text w-200 " /><span class="comm">次</span></td>
                    </tr>
                     <tr>
                        <th class="alginRt w-200">  注册用户间隔: </th>
                        <td><input type="text"  name="data[reg_jiange]" value="<?php echo ($CONFIG["power"]["reg_jiange"]); ?>" class="text w-200 " /><span class="comm">分钟</span></td>
                    </tr>
                    
                     <tr>
                        <th class="alginRt w-200">  预约间隔: </th>
                        <td><input type="text"  name="data[yuyue_jiange]" value="<?php echo ($CONFIG["power"]["yuyue_jiange"]); ?>" class="text w-200 " /><span class="comm">分钟</span></td>
                    </tr>
                     <tr>
                        <th class="alginRt w-200">  一天最多短信: </th>
                        <td><input type="text"  name="data[fabu_sms]" value="<?php echo ($CONFIG["power"]["fabu_sms"]); ?>" class="text w-200 " /><span class="comm">次</span></td>
                    </tr>
                    <tr>
                        <th class="alginRt w-200">  短信间隔: </th>
                        <td><input type="text"  name="data[sms_jiange]" value="<?php echo ($CONFIG["power"]["sms_jiange"]); ?>" class="text w-200 " /><span class="comm">分钟</span></td>
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