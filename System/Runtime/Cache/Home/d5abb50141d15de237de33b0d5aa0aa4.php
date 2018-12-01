<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="qc:admins" content="55313033557612053051676375" />
<meta property="wb:webmaster" content="9ae2a72466b6eecc" />
<title><?php echo ($seo_title); ?></title>
<meta name="keywords" content="<?php echo ($seo_keywords); ?>" />
<meta name="description" content="<?php echo ($seo_description); ?>" />
<link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>


<script type="text/javascript" src="__TMPL__statics/js/jquery-1.12.1.min.js"></script>
<script src="__PUBLIC__/js/layer/layer.js"></script>
<script src="__PUBLIC__/js/web.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="__TMPL__statics/js/pagescroller.min.js"></script>

<script type="text/javascript" src="__TMPL__statics/js/jquery.flexslider-min.js"></script>



</head>
<body>
<iframe id="chuang_frm" name="chuang_frm" style="display:none;"></iframe>
<!--头部-->
<body>
<div class="top">
	<div class="reg">
		<div class="top_reg_wrap">
			<p class="rexian">官方客服热线：<span><?php echo ($CONFIG[site][tel]); ?></span>&nbsp;&nbsp;&nbsp;官方交流群：<span><?php echo ($CONFIG[site][qqqun]); ?></span></p>
            <?php if(empty($MEMBER)): ?><p class="zhuce"><a href="<?php echo U('home/passport/reg');?>">注册</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('home/passport/login');?>">登录</a>
            <?php else: ?>
            <p class="zhuce">欢迎您，<?php echo ($MEMBER["name"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;<a  target="chuang_frm" href="<?php echo U('home/passport/logout');?>">退出</a><?php endif; ?>
           <!-- &nbsp;&nbsp;|&nbsp;&nbsp;<span class="wap_but"><a href="javascript:void(0);">手机版</a></span>&nbsp;&nbsp;&nbsp;&nbsp;</p>
			<div class="wap_erweima"><img src="http://qr.liantu.com/api.php?&bg=ffffff&fg=000000&w=300&text=<?php echo ($CONFIG['site'][host]); ?>/mobile" alt="手机版" />-->
			
			</div>
		</div>
	</div>
	<div class="header">
		<div class="logo">
			<a href="<?php echo U('home/index/index');?>"><img src="__ATTACHS__/<?php echo (($CONFIG[site][logo])?($CONFIG[site][logo]):'__PUBLIC__/images/logo.png'); ?>" alt="助创cms汽车众筹系统" /></a>
		</div>

		<div class="nav">
			<ul>
                <?php if(is_array($DAOHANG)): foreach($DAOHANG as $key=>$var): if($var["parent_id"] == 0): ?><li><a <?php if($ctl == $var[link]): ?>class="cur_nav"<?php endif; ?> href="<?php echo U('/'.$var['link']);?>" ><?php echo ($var["title"]); ?></a>
                    </li><?php endif; endforeach; endif; ?>
                
			</ul>
		</div>
		 <div class="account">
			<a href="#">我的账号</a>
			<div class="account_cont">
				<ul>
					 <?php if($MEMBER): ?><li>欢迎你：<?php echo ($MEMBER["name"]); ?></li>
                    <?php else: ?>
                    <li><a href="<?php echo U('home/passport/login');?>">请登录</a></li><?php endif; ?>
					<li><a href="<?php echo U('member/index/index');?>">个人中心</a></li>
					<li><a href="<?php echo U('member/project/crowd_canyu');?>">我的投资</a></li>
					<li><a href="<?php echo U('member/member/priceup');?>">我的余额</a></li>
					<li><a target="chuang_frm" href="<?php echo U('home/passport/logout');?>" style="border:none">退出登录</a></li>
				</ul>	
			</div>
		</div>
        
    
	 
</div>
</div>

<div class="item_search">
<div class="search_wrap">
  <div class="bread_nav">您的位置：<a href="<?php echo U('/index');?>">首页</a>&nbsp;/&nbsp;<a href="<?php echo U('/project');?>">汽车众筹</a></div>
  <div class="searchlist">
    <ul>
      <li>车型分类：</li>
      <li> <a  
        <?php if($cate == 0): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>0,'status'=>$status,'eid'=>$eid,'order'=>$order));?>">不限</a> </li>
      <?php if(is_array($catelist)): foreach($catelist as $key=>$val): ?><li> <a 
          <?php if($cate == $val[cate_id]): ?>class="mr_style"<?php endif; ?>
          href="<?php echo U('project/index',array('cate'=>$val[cate_id],'status'=>$status,'eid'=>$eid,'order'=>$order));?>"><?php echo ($val["cate_name"]); ?></a> </li><?php endforeach; endif; ?>
    </ul>
    <ul>
      <li>项目进度：</li>
      <li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>0,'eid'=>$eid,'order'=>$order));?>" 
        <?php if($status == 0): ?>class="mr_style"<?php endif; ?>
        ><i></i>不限</a> </li>
      <?php if(is_array($status_list)): foreach($status_list as $key=>$val): ?><li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$key+1,'eid'=>$eid,'order'=>$order));?>" 
          <?php if($status == $key+1): ?>class="mr_style"<?php endif; ?>
          ><?php echo ($val); ?></a> </li><?php endforeach; endif; ?>
    </ul>
    <ul>
      <li>筹资额度：</li>
      <li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>0,'order'=>$order));?>" 
        <?php if($eid == 0): ?>class="mr_style"<?php endif; ?>
        ><i></i>不限</a> </li>
      <?php if(is_array($edu)): foreach($edu as $key=>$val): ?><li> <a href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$key,'order'=>$order));?>" 
          <?php if($eid == $key): ?>class="mr_style"<?php endif; ?>
          ><?php echo ($val); ?></a> </li><?php endforeach; endif; ?>
    </ul>
    <ul style="border:none">
      <li>综合排序：</li>
      <li> <a 
        <?php if($order == 1): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$eid,'order'=>1));?>">最新上线</a> </li>
      <li><a 
        <?php if($order == 2): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$eid,'order'=>2));?>">最热排行</a></li>
      <li><a 
        <?php if($order == 3): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$eid,'order'=>3));?>">金额最多</a></li>
      <li><a 
        <?php if($order == 4): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$eid,'order'=>4));?>">支持最多</a></li>
      <li><a 
        <?php if($order == 5): ?>class="mr_style"<?php endif; ?>
        href="<?php echo U('project/index',array('cate'=>$cate,'status'=>$status,'eid'=>$eid,'order'=>5));?>">即将结束</a></li>
    </ul>
    <div style="clear:both"> </div>
  </div>
</div>
<div class="itemwrap">
  <div class="itemlist qczc">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 12 );++$i; if($mod%2 == 0): ?><ul><?php endif; ?>
        <li> <a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="list_tit"><img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" /><span>【第<?php echo ($item["project_id"]); ?>期】<?php echo ($item["title"]); ?></span></a>
          <div class="jindu">
            <?php if($item[status] == 0 ): ?><div></div>
              <?php elseif($item[status] == 1 ): ?>
              <div class="cur_jindu" style="width:<?php echo zc_int($item[have_price]/$item[fund_raising]*100);?>%"></div>
              <?php elseif($item[status] > 1 ): ?>
              <div class="end_jindu"></div><?php endif; ?>
          </div>
          <div class="canshu">
            <p>时间：<?php echo (date("Y-m-d H:i",$item["dateline"])); ?><span>|</span>支持人数：<?php echo (($item["have_num"])?($item["have_num"]):'0'); ?>人</p>
            <p><b>众筹额：<font>￥<?php echo zhu_int($item[fund_raising]/1000000);?></font>万</b><span>|</span>已筹：￥<?php echo zhu_int($item[have_price]/1000000);?>万</p>
            <?php if($item[status] == 0 ): ?><p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="yuchou">预筹中</a></p>
              <?php elseif($item[status] == 1 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="yuchou">众筹中</a></p>
              <?php elseif($item[status] == 2 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">出售中</a></p>
              <?php elseif($item[status] == 3 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">投票中</a></p>
              <?php elseif($item[status] == 4 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">待回款</a></p>
              <?php elseif($item[status] == 5 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="jieshu">已完成</a></p>
              <?php elseif($item[status] == -2 ): ?>
              <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="jieshu">已回购</a></p><?php endif; ?>
          </div>
          <?php if($item['status'] == 5 || $item['status'] == -2): ?><div class="end_layer">
              <div>
                <p><?php echo zhu_msubstr($item[title],0,5);?>已分红</p>
                <p>万元收益：<?php echo (ceil($item[shouyi][shouyi])); ?>元</p>
                <p>回款周期：<?php echo ($item[shouyi][days]); ?>天</p>
                <p>年化收益：<?php echo ceil($item[shouyi][shouyi]*365/$item[shouyi][days])/100;?>%</p>
              </div>
            </div><?php endif; ?>
          <a href="<?php echo U('project/tips',array('tip_id'=>$item[tip_id]));?>" class="tag"><?php echo ($Projecttips[$item[tip_id]][cate_name]); ?></a> </li>
        <?php if($mod%2 == 1): ?></ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <div class="select_page"> <?php echo ($page); ?></div>
  </div>
</div>
<div class="fenge"></div>

<div class="footer">

	<div class="footwrap">
    	 <?php if(is_array($Articlecate)): $i = 0; $__LIST__ = $Articlecate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$var): $mod = ($i % 2 );++$i;?><ul>
                <li><?php echo ($var["title"]); ?></li>
                 <?php if(is_array($var["list"])): $i = 0; $__LIST__ = $var["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; $url = U('article/detail',array('article_id'=>$item[article_id])); ?>
                	<li><a href="<?php echo (($item[linkurl])?($item[linkurl]):$url); ?>"><?php echo ($item["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul><?php endforeach; endif; else: echo "" ;endif; ?>
   
		<!--
		<ul class="saoma">
			<li><img src="__TMPL__statics/images/erweima.jpg" alt="电话" /><br />android端</li>
		</ul>
		<ul class="saoma">
			<li><img src="__TMPL__statics/images/erweima.jpg" alt="电话" /><br />ios端</li>
		</ul>
		-->
		<ul class="saoma">
			<li><img src="__ATTACHS__/<?php echo ($CONFIG[attachs][erweima1]); ?>" alt="电话" /><br />关注微信</li>
		</ul>
		<ul class="fright">
			<li class="ftell"><img src="__TMPL__statics/images/tell.gif" alt="电话" />&nbsp;：<?php echo ($CONFIG[site][tel]); ?></li>
			<li><?php echo ($CONFIG[site][gzsj]); ?></li>
			<li class="fkefu"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($CONFIG[site][qq]); ?>&amp;site=qq&amp;menu=yes">联系在线客服</a></li>
		</ul>
		<div style="clear:both"></div>
	</div>

<!--  <?php if($ctl == index): ?><div class="flink hzhb">
		<ul>
			<li>合作伙伴：</li>
            <?php  $items = D("Huoban")->where(" 1=1")->order("orderby desc")->limit("0,5")->select(); $items = D("Huoban")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li><img src="__ATTACHS__/<?php echo ($item["img"]); ?>" alt="<?php echo ($item["title"]); ?>" /></li> <?php } ?>
		</ul>
	</div>
	<div class="flink">
		<ul>
			<li>友情链接：</li>
			
         <?php  $items = D("links")->where(" 1=1")->order("orderby asc")->limit("0,10")->select(); $index=0; foreach($items as $item){$index++; ?><li><a  href="<?php echo ($item["link_url"]); ?>"><?php echo ($item["link_name"]); ?></a></li> <?php } ?>

		</ul>
	</div><?php endif; ?> -->
	<div class="zhichi">
		Copyright © 2014-2016 技术支持：助创cms <?php echo ($CONFIG[site][icp]); ?>
	</div>

</div>

<div class="kefu">
	<ul>
		<li class="zxkf"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($CONFIG[site][qq]); ?>&amp;site=qq&amp;menu=yes">咨询客服</a></li>
		<li class="zxcz"><a href="<?php echo U('/guide');?>">新手指引</a></li>
		<li class="bzzx"><a href="<?php echo U('article/index',array('cate_id'=>150));?>">帮助中心</a></li>
		<li class="gotop"><img src="__TMPL__statics/images/gotop.png" alt="返回顶部" /></li>
	</ul>
</div>
<script type="text/javascript" src="__TMPL__statics/js/index.js"></script>
</body>
</html>