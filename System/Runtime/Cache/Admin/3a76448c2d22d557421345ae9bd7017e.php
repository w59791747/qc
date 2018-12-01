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
  <div class="flt breadNav_text"> <span>设置</span><i></i><span>众筹配置</span> </div>
</div>
<form target="zhuchuang_frm" action="<?php echo U('setting/crowd');?>" method="post">
  <div class="daemonTable">
    <table class="table_data" cellpadding="0" cellspacing="0">
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 最小投资额度(元): </th>
        <td><input type="text" name="data[min_price]" value="<?php echo ($CONFIG["crowd"]["min_price"]); ?>" class="text w-300" /><span class="comm">单次最低众筹投资额度</span></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 最大投资额度百分比: </th>
        <td><input type="text" name="data[max_price]" value="<?php echo ($CONFIG["crowd"]["max_price"]); ?>" class="text w-300" /><span class="comm">单次最大众筹投资额度比例（最大额度=筹资总金*最大比例）</span></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 众筹集资期限: </th>
        <td><input type="text" name="data[days]" value="<?php echo ($CONFIG["crowd"]["days"]); ?>" class="text w-300" /><span class="comm">发布项目到筹资结束最长时间</span></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 众筹最长持有期限: </th>
        <td><input type="text" name="data[chi_days]" value="<?php echo ($CONFIG["crowd"]["chi_days"]); ?>" class="text w-300" /><span class="comm">发布项目到项目回款分成最长时间</span></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 投票期限 : </th>
        <td><input type="text" name="data[toupiao]" value="<?php echo ($CONFIG["crowd"]["toupiao"]); ?>" class="text w-300" /><span class="comm">投资者参与投票最长时间</span></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 分红比例 : </th>
        <td><input type="text" name="data[fenhong]" value="<?php echo ($CONFIG["crowd"]["fenhong"]); ?>" class="text w-300" /><span class="comm">最终车辆销售回款中投资者参与分红比例</span>
          <!--*(该比例为投资人所分红比例,若填写10表示，该次众筹总收益的10%由投资人所分配，90%归平台所有)--></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 溢价回购利率(年) : </th>
        <td><input type="text" name="data[huigou]" value="<?php echo ($CONFIG["crowd"]["huigou"]); ?>" class="text w-300" /><span class="comm">回购投资人收益：投资本金*（年化收益率/365）*最长持有期限，年化收益率即为这里设置的比例</span>
          <!--*(众筹金额)--></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 投票百分比通过 : </th>
        <td><input type="text" name="data[tpbfb]" value="<?php echo ($CONFIG["crowd"]["tpbfb"]); ?>" class="text w-300" /><span class="comm">投票人数通过比例，如设置60，超过60%投票即通过</span>
          <!--*(众筹金额)--></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 车辆参数 : </th>
        <td class="wsNormal"><script type="text/plain"  style="width:100%;height:360px;" id="data_details2" name="data[canshu]"><?php echo ($other["canshu"]); ?></script></td>
      </tr>
      <tr>
        <th class="alginRt w-200"> <font class="pointcl">*</font> 合同 : </th>
        <td class="wsNormal"><script type="text/plain"  style="width:100%;height:360px;" id="data_details" name="data[hetong]"><?php echo ($other["hetong"]); ?></script></td>
      </tr>
      <tr>
        <th class="alginRt w-100"> </th>
        <td><input type="submit" value="确认保存" class="pub_btn" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js?t=<?php echo ($nowtime); ?>"></script>
<link rel="stylesheet" href="__PUBLIC__/umeditor/themes/default/css/umeditor.min.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.config.js"></script> 
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.min.js"></script> 
<script type="text/javascript" src="__PUBLIC__/umeditor/lang/zh-cn/zh-cn.js"></script> 
<script>
                    um = UM.getEditor('data_details', {
                        imageUrl: "<?php echo U('up/upload/editor');?>",
                        imagePath: '__ROOT__/attachs/editor/',
                        lang: 'zh-cn',
                        langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                        focus: false
                    });
        </script> 
<script>
                    um = UM.getEditor('data_details2', {
                        imageUrl: "<?php echo U('up/upload/editor');?>",
                        imagePath: '__ROOT__/attachs/editor/',
                        lang: 'zh-cn',
                        langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                        focus: false
                    });
        </script> 
<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>