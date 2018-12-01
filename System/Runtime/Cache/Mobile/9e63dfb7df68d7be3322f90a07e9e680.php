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
  <div class="title">项目列表</div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
  <span></span>
</header>
<div id="search-bar">
  <ul class="search-bar_list clearfix">
    <li class="list list_three"><a href="javascript:void(0)"><?php echo (($catelist[$cate][cate_name])?($catelist[$cate][cate_name]):'分类排序'); ?></a></li>
    <li class="list list_three "><a href="javascript:void(0)"><?php echo (($status_list[$status-1])?($status_list[$status-1]):'项目进程'); ?></a></li>
    <li class="list list_three "><a href="javascript:void(0)"><?php echo (($edu[$eid])?($edu[$eid]):'筹资额度'); ?></a></li>
    <li class="list list_three last"><a href="javascript:void(0)"><?php echo (($order_str)?($order_str):'排序方式'); ?></a></li>
  </ul>
  <span></span>
  <div class="search-bar_pull">
    <div class="list_box">
      <ul>
        <li><a <?php if($cate == 0): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>0,'status'=>$status,'order'=>$order));?>">全部</a></li>
        <?php if(is_array($catelist)): foreach($catelist as $key=>$val): ?><li> <a  <?php if($cate == $val[cate_id]): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$val[cate_id],'status'=>$status,'order'=>$order));?>"><?php echo ($val["cate_name"]); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
    <div class="list_box">
      <ul>
        <li><a <?php if($status == 0): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$cate,'status'=>0,'order'=>$order));?>" >全部</a></li>
        <?php if(is_array($status_list)): foreach($status_list as $key=>$val): ?><li><a  <?php if($status == $key+1): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$key+1,'order'=>$order));?>" ><?php echo ($val); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
    <div class="list_box">
      <ul>
        <li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>0,'order'=>$order));?>" 
        <?php if($eid == 0): ?>class="on"<?php endif; ?>
        ><i></i>不限</a> </li>
        <?php if(is_array($edu)): foreach($edu as $key=>$val): ?><li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$key,'order'=>$order));?>" 
          <?php if($eid == $key): ?>class="on"<?php endif; ?>
          ><?php echo ($val); ?></a> </li><?php endforeach; endif; ?>
      </ul>
    </div>
    <div class="list_box">
      <ul>
        <li><a <?php if($order == 1): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'order'=>1));?>">最热排行</a></li>
        <li> <a <?php if($order == 2): ?>class="on"<?php endif; ?> 
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'order'=>2));?>">最新上线</a> </li>
        <li><a  <?php if($order == 3): ?>class="on"<?php endif; ?> 
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'order'=>3));?>">金额最多</a></li>
        <li> <a <?php if($order == 4): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'order'=>4));?>">支持最多</a></li>
        <li> <a <?php if($order == 5): ?>class="on"<?php endif; ?> href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'order'=>5));?>">即将结束</a> </li>
      </ul>
    </div>
    <div class="mask_bg"></div>
  </div>
</div>
<section class="page_center_box">
<div class="zcList">
  <ul class="mpubList wpicList">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 15 );++$i;?><li> <a href="<?php echo U('project/detail',array('project_id'=>$item['project_id']));?>">
        <div class="fl mpubPic"><img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" /></div>
        <div class="mpubTxt">
          <h4 class="overflow_clear"><?php echo ($item["title"]); ?></h4>
          <div class="jindu">
            <div class="lineJindu">
              <div class="lineJindu_line" w="<?php echo (ceil($item[have_price]/$item[fund_raising]*100)); ?>"></div>
            </div>
            <div class="jdCont"><?php echo zc_int($item[have_price]/$item[fund_raising]*100);?>%</div>
            <div class="jdMatter">
              <?php if($item['status'] == 1): ?>众筹中
              <?php else: ?>
                <?php echo ($status_list[$item[status]]); endif; ?>
            </div>
          </div>
          <p>
            <font class="black9">目标</font>￥<?php echo zhu_int($item[fund_raising]/100);?>
            <em class="fg">|</em>            
            <font class="black9">已筹</font>￥<?php echo (($item[have_price]/100)?($item[have_price]/100):'0'); ?>
          </p>
        </div>
        </a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
  <?php if(strlen($page) > 12){?>
    <div class="page"> <?php echo ($page); ?> </div>
  <?php } ?>
</div>
<script>
/*头部下来分类开始*/
	$("#search-bar .search-bar_list .list").each(function(e){
		$(this).click(function(){
			if($(this).hasClass("on")){
				$(this).parent().find(".list").removeClass("on");
				$(this).removeClass("on");
				$(".mask_bg").hide();
				$("#search-bar .search-bar_pull").hide();
			}
			else{
				$(this).parent().find(".list").removeClass("on");
				$(this).addClass("on");
				$(".mask_bg").show();
				$("#search-bar .search-bar_pull").show();
			}
			$("#search-bar .search-bar_pull .list_box").each(function(i){
				if(e==i){
					$(this).parent().find(".list_box").hide();
					$(this).show();
				}
				else{
					$(this).hide();
				}
				$(this).find("li").click(function(){
					$(this).parent().find("li").removeClass("on");
					$(this).addClass("on");
					$(".mask_bg").hide();
					$("#search-bar .search-bar_pull").hide();
					$("#search-bar .search-bar_list .list").removeClass("on");
				});
			});
		});
	});
</script> 
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