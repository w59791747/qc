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
  <section class="contentWrapper">
    <header>
      <div class="left"><a id="goback" href="javascript:void(0)" title="返回"></a></div>
      <div class="title">404页面</div>
      <div class="right"><a href="#" id="menuIco" title="目录"></a></div>
    </header>
    <section class="page_center_box">
    	<div class="wrong404">
        <div class="ico_wrong"></div>
        <h1>对不起，您访问的页面不存在!</h1>
        <a href="<?php echo U('index/index');?>">快速返回首页</a>
        </div>
    </section>
  </section>
</section>
 <script>
  	$('#goback').click(function(){
		history.back(-1);	
	})
  </script>  
</section>
</body>
</html>