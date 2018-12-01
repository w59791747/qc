<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<title><?php echo ($seo_title); ?></title>
<link href="__TMPL__statics/css/pub_app.css" rel="stylesheet" type="text/css">
<link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/icon/iconfont.css">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script src="__PUBLIC__/js/layer/layer.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script src="__TMPL__statics/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="__TMPL__statics/js/TouchSlide.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/jquery.flexslider-min.js"></script>
</head>
<iframe id="chuang_frm" name="chuang_frm" style="display:none;"></iframe> <section class="pageWrapper">
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
<section class="contentWrapper">
<header>
  <div class="left"><a id="goback" href="javascript:void(0)" title="返回"><i class="iconfont icon-fanhui1"></i></a></div>
  <div class="title">新手指引</div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
  <span></span>
</header>
<section class="page_center_box">
<div class="guideBr">  
</div>
<div class="guideSafe">
  <h2><span class="g_tit">如何保障投资安全</span></h2>
  <p>汽车众筹在借鉴国外众筹平台先进运营模式的同时，结合中国国情，打造出的创新金融服务产品，真正的帮助有发展梦想的中小微企业及 个体从业者获得金融融资金渠道，同时为有闲散资金的投资者创造一个资金增值平台，从而推动社会健康良好的发展。</p>
</div>
<div class="guideSafe">
  <h2><span class="g_tit">如何保障投资安全</span></h2>
  <ul class="clearfix">
    <li>
      <i class="iconfont icon-tuandui"></i>
      <h3>运营团队</h3>
      <p>专业运营团队让您的投资回报最大化</p>
    </li>
    <li>
      <i class="iconfont icon-cheliang-"></i>
      <h3>车辆评估</h3>
      <p>严格车辆审核机制，让您投资更放心</p>
    </li>
    <li>
      <i class="iconfont icon-qiandai"></i>
      <h3>资金安全</h3>
      <p>专业资金托管，第三方监管实施监督</p>
    </li>
    <li>
      <i class="iconfont icon-afsshujufengkong"></i>
      <h3>风控团队</h3>
      <p>业内金融顶尖风控专家为您保驾护航</p>
    </li>
  </ul>
</div>
<div class="guideLc guideSafe">
  <h2><span class="g_tit">汽车众筹流程</span></h2>
  <div class="center"><img src="__TMPL__statics/images/czclc.png" /></div>
  <a href="<?php echo U('mobile/project/index');?>" class="btn">我要众筹</a> </div>
<script>
	$('#goback').click(function(){
	history.back(-1);	
})
</script>

<div class="footerTxt">
  <p class="black9"><?php echo ($CONFIG[site][sitename]); ?> <?php echo ($CONFIG[site][icp]); ?></p>
</div>
</section>
</section>
</section>
<a class="goTop" href="javascript:goTop();"></a> 
<script>
	  TouchSlide({ slideCell:"#dreamtabBox" });
		TouchSlide({ slideCell:"#fabuOptionBox" });
		TouchSlide({ slideCell:"#memberOptionBox" });
		
	  ///回到顶部
    function goTop(){
      $('html,body').animate({'scrollTop':0},600);
    }
    var stu = true;
    $(window).scroll(function(){
      if($(window).scrollTop() > 150) {
          $('a.goTop,.usedFk').show();
      } else {
          $('a.goTop,.usedFk').hide();
      } 
      
      if($(window).scrollTop() > 0) {
        if(stu){
          stu = false;
          var headerClone = $('header').clone();
          headerClone.addClass('headerClone');
          headerClone.insertBefore($('.contentWrapper'));  
        }        
      } else {
        stu = true;
        $('.headerClone').remove();
      } 

    });
	  
</script>
<?php if($CONFIG['home']['project'] == on && $ctl == index): ?><script type="text/javascript" src="__PUBLIC__/js/area.js?t=<?php echo ($nowtime); ?>"></script> 
  <script type="text/javascript">_init_area();</script> 
  <script type="text/javascript">_init_area2();</script> 
  <script type="text/javascript">_init_area1();</script><?php endif; ?>
<footer>
  <ul>
    <li <?php if($ctl != project): ?>class="on"<?php endif; ?>> 
      <a href="<?php echo U('mobile/index/index');?>"> <i class="iconfont icon-shouye"></i>
        <p>首页</p>
      </a> 
    </li>
    <li> 
      <a <?php if($ctl == project): ?>class="on"<?php endif; ?> href="<?php echo U('mobile/project/index');?>"> <i class="iconfont icon-xiangmuxiaoxi"></i>
        <p>找项目</p>
      </a> 
    </li>
    <li> 
      <a href="tel:<?php echo ($CONFIG[site][tel]); ?>"> <i class="iconfont icon-dianhua"></i>
        <p>咨询</p>
      </a> 
    </li>
    <li> 
      <a href="<?php echo U('ucenter/index/index');?>"> <i class="iconfont icon-yonghuzhongxin"></i>
        <p>我的</p>
      </a> 
    </li>
  </ul>
</footer>
</body></html>