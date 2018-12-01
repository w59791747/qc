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
<iframe id="chuang_frm" name="chuang_frm" style="display:none;"></iframe><section class="pageWrapper">
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
<!--左侧menu部分end-->
<div class="usedFk"> 
  <a href="<?php echo U('feedback');?>">
    <i class="iconfont icon-yijianfankui"></i>
  </a> 
</div> 
<section class="contentWrapper">
<header>
  <div class="left"><!-- <a id="goback" href="javascript:void(0)" title="返回"><i class="iconfont icon-fanhui1"></i> --></a></div>
  <div class="title"><?php echo ($seo_title); ?></div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
</header>
<section class="page_center_box">
<div class="banner">
  <div class="flexslider">
    <ul class="slides">
      <?php  $items = D("Advitem")->where("adv_id=101 and audit=1  and ((`stime`=0 && `ltime`=0) || (`stime`<1542946954&& `ltime`>1542946954))")->order("orderby asc")->limit("0,3")->select(); $index=0; foreach($items as $item){$index++; ?><li><a href="<?php echo ($item["link"]); ?>" target="<?php echo ($item["target"]); ?>"><img src="__ATTACHS__/<?php echo ($item["thumb"]); ?>" /></a></li> <?php } ?>
    </ul>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		 $('.flexslider').flexslider({
			directionNav: true,
			pauseOnAction: false,
	});//首页轮播js结束
	});
</script>
<div class="menuCate">
  <ul>
    <li><a href="<?php echo U('mobile/project/index');?>"><i class="ico_2"></i>
      <p>汽车众筹</p>
      </a></li>
    <li><a href="<?php echo U('mobile/guide/index');?>"><i class="ico_3"></i>
      <p>新手指引</p>
      </a></li>
    <li><a href="<?php echo U('mobile/help/index');?>"><i class="ico_4"></i>
      <p>帮助中心</p>
      </a></li>
    <li><a href="<?php echo U('mobile/product/index');?>"><i class="ico_5"></i>
      <p>积分商城</p>
      </a></li>
  </ul>
</div>
<div class="index_gonggao_wrap">
  <i class="iconfont icon-laba gg_icon"></i>
  <div class="index_gonggao">
      <ul>      
       <?php if(is_array($articlelist)): foreach($articlelist as $key=>$val): if($key == 0): $items = D("Article")->where("`closed`=0 and `audit`=1 and `cate_id`={$val['cate_id']} ")->order("dateline desc")->limit("0,5")->select(); $items = D("Article")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li><a href="<?php echo U('article/detail',array('article_id'=>$item[article_id]));?>"><?php echo ($item[title]); ?></a></li> <?php } endif; endforeach; endif; ?>    
      </ul>
  </div>
</div>
<div class="web_data">
    <div class="data_left">        
        <em class="data_num">¥ <?php echo ($count['count3']); ?></em>
        <em class="data_text">累计金额</em>
    </div>
    <div class="data_right">
        <div class="data_r_t">            
            <em class="data_text">投资人数</em>
            <em class="data_num"><?php echo ($count['count2']); ?> 个</em>
        </div>
        <div class="data_r_b">            
            <em class="data_text">累计发布</em>
            <em class="data_num"><?php echo ($count['count1']); ?> 个</em>
        </div>
    </div>
</div>
<div id="projectOptionBox" class="optionBox">
  <div class="hd">
    <ul>      
      <?php if($CONFIG['home']['xiangmu'] == on): ?><li><a href="javascript:void(0)">众筹中</a></li><?php endif; ?>
      <?php if($CONFIG['home']['yugao'] == on): ?><li><a href="javascript:void(0)">预筹中</a></li><?php endif; ?>
      <?php if($CONFIG['home']['success'] == on): ?><li><a href="javascript:void(0)">已成功</a></li><?php endif; ?>
    </ul>
    <span></span>
  </div>
  <div class="bd" id="tabBox-bd">
   
    <?php if($CONFIG['home']['xiangmu'] == on): ?><div class="hot_rec_wrap" >
        <ul>
          <?php  $items = D("project")->where("`audit`=1 and `status`=1  and `closed`=0 ")->order("dateline desc")->limit("0,4")->select(); $items = D("Project")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>"> 
              <img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" />
              <div class="rectit"><?php echo ($item["title"]); ?></div>
              <div class="htrecTxt clearfix">
                <div class="z-raising">已筹：<em class="fontcl">￥<?php echo ($item[have_price]/100); ?></em></div>
                <div class="p-outer">
                  <div class="p-bar">
                    <div class="p-bar-blue" style="width:<?php echo ($item[have_price]/$item[fund_raising]*100); ?>%"></div>
                  </div>
                </div>
                <div class="item-rate clearfix"> <span class="fl"><?php echo zc_int($item[have_price]/$item[fund_raising]*100);?>%</span> <span class="fr black9">剩余<?php echo (ceil($item[ltime]/3600/24-$nowtime/3600/24)); ?>天</span> </div>
              </div>
              </a> 
            </li> <?php } ?>
        </ul>
      </div><?php endif; ?>
     <?php if($CONFIG['home']['yugao'] == on): ?><div class="hot_rec_wrap" >
        <ul>
          <?php  $items = D("project")->where("`audit`=1 and `status`=0  and `closed`=0 ")->order("dateline desc")->limit("0,3")->select(); $items = D("Project")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li>
              <div class="spanbtn"><a mini='act' href="<?php echo U('project/follow',array('project'=>$item['project_id']));?>" title="关注"><i class="iconfont icon-xin"></i></a></div>
              <a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">   
              <img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" />
              <div class="rectit"><?php echo ($item["title"]); ?></div>
              <div class="htrecTxt clearfix">
                <div class="z-i-raising"><em class="fontcl">￥ <?php echo zhu_int($item[fund_raising]/100);?></em>目标筹资</div>
                <div class="z-i-raising"><em class="fontcl"><?php echo (ceil($item[stime]/3600/24-$nowtime/3600/24)); ?> 天</em>剩余时间</div>
              </div>
              </a> 
            </li> <?php } ?>
        </ul>
      </div><?php endif; ?>
    <?php if($CONFIG['home']['success'] == on): ?><div class="hot_rec_wrap" >
        <ul>
          <?php  $items = D("project")->where("`audit`=1 and `status` IN (5,-2)  and `closed`=0 ")->order("dateline desc")->limit("0,4")->select(); $items = D("Project")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li>              
              <a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">
                <img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" />
                <div class="rectit"><?php echo ($item["title"]); ?></div>
                <div class="htrecTxt clearfix">
                  <div class="z-i-raising"><em class="fontcl">￥ <?php echo zhu_int($item[have_price]/100);?></em>已筹资</div>
                  <div class="z-i-raising"><em class="fontcl"><?php echo (($item["have_num"])?($item["have_num"]):'0'); ?> 个</em>投资人</div>
                </div>
                <div class="shou_box">
                    <span>万元收益：<?php echo (ceil($item[shouyi][shouyi])); ?>元</span>
                    <span>回款周期：<?php echo ($item[shouyi][days]); ?>天</span>
                </div>
              </a> 
            </li> <?php } ?>
        </ul>
      </div><?php endif; ?>
  </div>
</div>
<div class="tz_top">
    <div class="tz_top_tit"><span><i class="iconfont icon-paixingbang"></i>投资排行榜</span></div>
    <ul>
        <li>
            <em class="tz_id">排位</em>
            <em class="tz_name">姓名</em>
            <em class="tz_price">投资额</em>
        </li>        
        <?php  $items = D("Member")->where("`closed`  IN (1,0) ")->order("tou_price desc")->limit("0,8")->select(); $items = D("Member")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; if($index < 8 ): ?><li>
                <em class="tz_id"><?php echo ($index); ?></em>
                <em class="tz_name"><?php echo zhu_name($item[name]);?></em>
                <em class="tz_price">¥ <?php echo zhu_int($item[tou_price]/100);?></em>
            </li><?php endif; ?> <?php } ?>
    </ul>
</div>

<script type="text/javascript">
  TouchSlide( { slideCell:"#projectOptionBox",
		endFun:function(i){ //高度自适应
			var bd = document.getElementById("tabBox-bd");
			bd.parentNode.style.height = bd.children[i].children[0].offsetHeight+"px";
			if(i>0)bd.parentNode.style.transition="200ms";//添加动画效果
		}
	} );
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