<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<title><?php echo ($seo_title); ?></title>
<link href="__TMPL__statics/css/pub_app.css" rel="stylesheet" type="text/css">
<link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__TMPL__statics/css/ucenter.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/icon/iconfont.css">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script src="__PUBLIC__/js/layer/layer.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script src="__TMPL__statics/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="__TMPL__statics/js/TouchSlide.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/side.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/jquery.flexslider-min.js"></script>
</head>
<iframe id="chuang_frm" name="chuang_frm" style="display:none;"></iframe>
<section class="pageWrapper">
<!--左侧menu部分start-->
<section class="menuWrapper">
  <ul class="menu">
    <?php if(is_array($DAOHANG)): foreach($DAOHANG as $key=>$var): if($var[link] != baogao): if($var["parent_id"] == 0): ?><li  
				  
			<?php if($ctl == $var[link]): ?>class=""
			  <?php else: ?>
			  class="a_submenu"<?php endif; ?>
			>
			  <?php if($var[link] == user): ?><a href="<?php echo U('/mobile/member');?>" ><?php echo ($var["title"]); ?></a></h3>
			<?php else: ?>
			<a href="<?php echo U('/mobile/'.$var['link']);?>" ><?php echo ($var["title"]); ?></a><?php endif; ?>
			</li><?php endif; endif; endforeach; endif; ?>
  </ul>
  <div class="arrow_up">

  </div>
</section>
<header>
  <div class="left"><a id="goback" href="javascript:void(0)" title="返回"><i class="iconfont icon-fanhui1"></i></a></div>
  <div class="title"><?php echo ($MEMBER['name']); ?></div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
  <span></span>
</header>
<section class="page_ucenter_box">
  <div class="u_top">
      <div class="u_top_t">
          <div class="u_top_t_img"><img src="__ROOT__/attachs/<?php echo ($MEMBER["face"]); ?>"></div>
          <div class="u_top_t_text">
              <span class="m_name"><?php echo ($MEMBER['name']); ?></span>
              <span class="m_account">账户余额：¥ <?php echo ($MEMBER["price"]); ?></span>
              <span class="m_btn">
                <a href="<?php echo U('ucenter/member/priceup');?>">充值</a>
                <a href="<?php echo U('ucenter/member/pricedown');?>">提现</a>
              </span>
          </div>
          <div class="msg_btn">
              <a href="<?php echo U('message/news');?>"><i class="iconfont icon-xiaoxi"></i></a>
              <!-- <a href="<?php echo U('member/qiandao/qiandao');?>"><i class="iconfont icon-yonghu_qiandao"></i></a> -->
          </div>
      </div>
      <div class="u_top_b"></div>
  </div>
  
  <div class="u_indexlist">
      <ul>
        <li><a href=""><em><i><?php echo ($MEMBER[dong_price]/100); ?></i> 元</em>已冻结</a></li>
        <li><a href="<?php echo U('order/lists');?>"><em><i><?php echo (($count02)?($count02):'0'); ?></i> 个</em>订单</a></li>
        <li><a href="<?php echo U('message/follow_p');?>"><em><i><?php echo (($count03)?($count03):'0'); ?></i> 个</em>关注</a></li>
        <li><a href=""><em><i><?php echo ($MEMBER["gold"]); ?></i> 分</em>积分</a></li>        
      </ul>
  </div>
  <div class="floor_fg"></div>
  <div class="basicList">
    <ul class="menu_dl">
      <li class="grzx"><a href="<?php echo U('ucenter/member/index');?>"><i class="iconfont icon-shouye"></i>会员中心</a></li>
      <li class="xmfl"><a href="<?php echo U('ucenter/project/crowd_canyu');?>"><i class="iconfont icon-xiangmuxiaoxi"></i>项目管理</a></li>
      <li class="ddgl"><a href="<?php echo U('ucenter/order/index');?>"><i class="iconfont icon-dingdan"></i>订单管理</a></li>
        
      <li class="fxgl"><a href="<?php echo U('ucenter/fenxiao/lists');?>"><i class="iconfont icon-fenxiang"></i>我的邀请</a></li>
      <li class="xxgl"><a href="<?php echo U('ucenter/message/index');?>"><i class="iconfont icon-xiaoxi1"></i>消息管理</a></li>
      <li class="jbsz"><a href="<?php echo U('ucenter/set/set');?>"><i class="iconfont icon-shezhi"></i>设置</a></li>
    </ul>
  </div>
  <div class="basicBtn"> <a href="<?php echo U('mobile/passport/logout');?>" class="btn">退出账号</a> </div>
</section>
 <script>
	$('#goback').click(function(){
		history.back(-1);	
	})

	function goTop(){
			$('html,body').animate({'scrollTop':0},600);
		}
	//顶部置顶效果和返回顶部效果
    var stu = true;
	$(window).scroll(function(){
  	  if($(window).scrollTop() > 150) {
          $('a.goTop').show();
      } else {
          $('a.goTop').hide();
      }	
      
      if($(window).scrollTop() > 0) {
        if(stu){
          stu = false;
          var headerClone = $('header').clone();
          headerClone.addClass('headerClone');
          headerClone.insertBefore($('.pageWrapper'));  
        }        
      } else {
        stu = true;
        $('.headerClone').remove();
      } 
	});
</script>  
</section>
</body>
</html>