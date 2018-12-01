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
  <div class="flt breadNav_text"> <span>会员</span><i></i><span>会员管理</span><i></i><span>会员管理</span> </div>
  <div class="frt breadNav_other">
    <div class="bn_input theme-pop-btn">
      <form method="post" action="<?php echo U('member/index');?>">
        <div class="bn_input">用户名：
          <input type="text" name="name" value="<?php echo ($name); ?>" class="text">
          类型：
          <select name="from" class="text">
            <option  value="1" 
            <?php if(1 == $from): ?>selected="selected"<?php endif; ?>
            >全部
            </option>
            <?php if(is_array($fromlist)): foreach($fromlist as $key=>$val): ?><option  value="<?php echo ($key); ?>" 
              <?php if($key == $from): ?>selected="selected"<?php endif; ?>
              ><?php echo ($val); ?>
              </option><?php endforeach; endif; ?>
          </select>
        </div>
        <div class="bn_input">
          <input type="submit" class="pub_btn" value="搜索">
        </div>
      </form>
      <div class="bn_input"> <?php echo ROLE('member/create','','添加用户','','pub_btn');?></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<form target="zhuchuang_frm" method="post">
<div class="daemonTable">
  <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
    <tr>
      <th> 序号 </th>
      <th> 会员名 </th>
      <th > 实名 </th>
      <th> 手机 </th>
      <th> 积分 </th>
      <th> 资金 </th>
      <th> 状态 </th>
      <th class="w-300"> 操作 </th>
    </tr>
    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
        <td class="w-50"><label class="checkbox">
            <input class="child_uid" type="checkbox" name="uid[]" value="<?php echo ($var["uid"]); ?>" />
<?php echo ($var["uid"]); ?></label></td>
        <td width="100"><?php echo ($var["name"]); ?></td>
        <td ><?php echo ($var["detail"]["realname"]); ?><br />
          <?php if($var["verify"] == 4 || $var["verify"] == 5 || $var["verify"] == 6 || $var["verify"] == 7): ?><span class="green">[已验证]
          <?php else: ?>
          <span class="red">[未验证]<?php endif; ?>
          </span></td>
        <td  ><?php echo ($var["mobile"]); ?><br />
          <?php if($var["verify"] == 2 || $var["verify"] == 3 || $var["verify"] == 6 || $var["verify"] == 7): ?><span class="green">[已验证]
          <?php else: ?>
          <span class="red">[未验证]<?php endif; ?>
          </span></td>
        <td width="100"><?php echo ($var["gold"]); ?>  <span class="czjia czjf" title="充值积分"><?php echo ROLE('member/gold',array("uid"=>$var["uid"]),'+','load','',600,330);?></span></td>
        <td width="100"><?php echo ($var["price"]); ?> <span class="czjia" title="充值资金"><?php echo ROLE('member/price',array("uid"=>$var["uid"]),'+','load','',600,330);?></span></td>
        <td width="100"><span class="green">
          <?php if($var["xuni"] == 1): ?>[虚拟账号]
		  <?php else: ?>
		  [正常]<?php endif; ?>
		  </span>
          </td>
        <td class="w-300"><a target="_blank" href="<?php echo U('member/manage',array('uid'=>$var['uid']));?>" class="pub_btn">管理</a> <?php echo ROLE('member/detail',array("uid"=>$var["uid"]),'查看','','pub_btn');?>
          <?php echo ROLE('member/edit',array("uid"=>$var["uid"]),'编辑','','pub_btn');?>
          <?php echo ROLE('member/delete',array("uid"=>$var["uid"]),'删除','act','pub_btn grayBtn');?> </td>
      </tr><?php endforeach; endif; ?>
  </table>
</div>
<div class="daemonBot">
  <div class="flt daemonBot_cz">
    <label class="checkbox">
      <input type="checkbox" class="checkAll" rel="uid">
      全选</label>
    <?php echo ROLE('member/audit','','批量审核','list','pub_btn');?>
    <?php echo ROLE('member/delete','','批量删除','list','pub_btn grayBtn');?> </div>
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