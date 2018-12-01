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
<section class="contentWrapper">
<header>
  <div class="left"><a id="goback" href="javascript:void(0)" title="返回"><i class="iconfont icon-fanhui1"></i></a></div>
  <div class="title">修改密码</div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
  <span></span>
</header>
  <section class="page_center_box">
    <div class="introForm">
      <form method="post"  action="<?php echo U('set/passwd');?>"  target="chuang_frm">
       <div class="regGroup clearfix">
       <label class="title">旧密码</label>
        <div class="control_txt">
        <input class="text" type="text" name="oldpwd"  type="password" placeholder="旧密码">
        </div></div>
        <div class="regGroup clearfix">
       <label class="title">新密码</label>
        <div class="control_txt">
        <input class="text" type="text" name="newpwd"  type="password" placeholder="新密码">
        </div></div>
        <div class="regGroup clearfix">
       <label class="title">确认密码</label>
        <div class="control_txt">
        <input class="text" type="text" name="pwd2"  type="password" placeholder="确认密码">
        </div></div>
         <div class="regGroup clearfix">
        <input class="btn" type="submit" value="确认保存">
        </div>
      </form>
    </div>
    </div>
  </section>
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