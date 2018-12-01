<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
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
        
    
	 
<!--头部结束-->
<!--首页弹出二维码-->
<!-- <div class="sy_layer">
			<p class="fd_shouyi">浮动年化收益率</p>
			<p class="nianhua"><?php echo ($CONFIG[site][fdnh]); ?>%+</p>
			<?php if(empty($MEMBER)): ?><p class="kj_zhuce"><a href="<?php echo U('home/passport/login');?>"><input type="submit" value="快速注册享收益" /></a></p>
			<?php else: ?>
			<p class="kj_zhuce"><a href="<?php echo U('/project');?>"><input type="submit" value="快速去众筹" /></a></p><?php endif; ?>
			<p><a href="<?php echo U('/guide');?>"><img src="__TMPL__statics/images/whatis.jpg" alt="什么是汽车众筹系统" /></a></p>
		</div> -->
	</div>
</div>



<div class="flexslider">
	<ul class="slides">
     <?php  $items = D("Advitem")->where("adv_id=1 and audit=1  and ((`stime`=0 && `ltime`=0) || (`stime`<1542961184&& `ltime`>1542961184))")->order("orderby asc")->limit("0,6")->select(); $index=0; foreach($items as $item){$index++; ?><li style="background:url(__ATTACHS__/<?php echo ($item["thumb"]); ?>) 50% 0 no-repeat;"  onclick="window.open('<?php echo ($item["link"]); ?>','<?php echo ($item["target"]); ?>')"></li> <?php } ?>
	</ul>
</div>

<div class="youshi">
	<div>
		<ul>
			<li><img src="__TMPL__statics/images/cz.png" alt="专注车众筹" /><span>专注车众筹</span><span class="youshi_note">优势高，期限短、回报高</span></li>
			<li><img src="__TMPL__statics/images/hb.png" alt="高收益低门槛" /><span>高收益低门槛</span><span class="youshi_note">1元即可众筹，门槛低</span></li>
			<li><img src="__TMPL__statics/images/td.png" alt="专业评估团队" /><span>专业评估团队</span><span class="youshi_note">专业评估中心控制风险</span></li>
			<li><img src="__TMPL__statics/images/hg.png" alt="限时回购" /><span>限时回购</span><span class="youshi_note">60天滞销回购保障</span></li>
			<li style="border:none"><img src="__TMPL__statics/images/aq.png" alt="安全可靠" /><span>安全可靠</span><span class="youshi_note">众筹项目100%真实可靠</span></li>
		</ul>
		<div style="clear:both"></div>
	</div>
</div>

<div class="itemwrap">
  <div class="ad_area">
    <ul>
       <?php  $items = D("Advitem")->where("adv_id=99 and audit=1  and ((`stime`=0 && `ltime`=0) || (`stime`<1542961184&& `ltime`>1542961184))")->order("orderby asc")->limit("0,6")->select(); $index=0; foreach($items as $item){$index++; ?><li style="background:url(__ATTACHS__/<?php echo ($item["thumb"]); ?>) 50% 0 no-repeat;"  onclick="window.open('<?php echo ($item["link"]); ?>','<?php echo ($item["target"]); ?>')"></li> <?php } ?>
    </ul>
  </div>
	<div class="itemlist">
<!-- 		<h1>中国领先的二手车众筹平台，安全可靠</h1>
		<h2>众筹一笔资金，用于购买相应的车辆<br />车辆属于全体投资人，销售车辆赚取价差进行分红，省心、放心、安心！</h2> -->
		<ul>
        	<?php if(is_array($list1)): foreach($list1 as $key=>$item): if($key < 4): ?><li>
                    	<a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="list_tit"><img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" /><span>【第<?php echo ($item["project_id"]); ?>期】<?php echo ($item["title"]); ?></span></a>
                        <div class="jindu">
                        	<?php if($item[status] == 0 ): ?><div></div>
                            <?php elseif($item[status] == 1 ): ?>
                            <div class="cur_jindu" style="width:<?php echo zc_int($item[have_price]/$item[fund_raising]*100);?>%"></div>
                            <?php elseif($item[status] > 1 ): ?>
                            	<div class="end_jindu"></div><?php endif; ?>
                        </div>
                        <div class="canshu">
                            <p>时间：<?php echo (date("Y-m-d H:i",$item["dateline"])); ?><span>|</span>支持人数：<?php echo (($item["have_num"])?($item["have_num"]):'0'); ?>人</p>
                            <p><b>众筹额：<font>￥<?php echo zhu_int($item[fund_raising]/1000000);?></font>万</b><span>|</span>已筹：￥<?php echo ($item[have_price]/1000000); ?>万</p>
                            
                            <?php if($item[status] == 0 ): ?><p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="yuchou">预筹中</a></p>
                            <?php elseif($item[status] == 1 ): ?>
                            <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="yuchou">众筹中</a></p>
                            <?php elseif($item[status] == 2 ): ?>
                            <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">出售中</a></p>
							<?php elseif($item[status] == 3 ): ?>
                            <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">投票中</a></p>
							<?php elseif($item[status] == 4 ): ?>
                            <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">待回款</a></p><?php endif; ?>
                        </div>
                        <a href="<?php echo U('project/tips',array('tip_id'=>$item[tip_id]));?>" class="tag"><?php echo ($Projecttips[$item[tip_id]][cate_name]); ?></a>
                    </li><?php endif; endforeach; endif; ?>
		</ul>

		<ul>
			<?php if(is_array($list1)): foreach($list1 as $key=>$item): if($key > 3): ?><li>
                    	<a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="list_tit"><img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" /><span>【第<?php echo ($item["project_id"]); ?>期】<?php echo ($item["title"]); ?></span></a>
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
                            <p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>">待回款</a></p><?php endif; ?>
                        </div>
                        <a href="<?php echo U('project/tips',array('tip_id'=>$item[tip_id]));?>" class="tag"><?php echo ($Projecttips[$item[tip_id]][cate_name]); ?></a>
                    </li><?php endif; endforeach; endif; ?>
		</ul>

		<!-- <div class="zc_more"><a href="<?php echo U('/project');?>">+ 更多众筹车辆</a></div> -->
	</div>
	<div class="fenge"></div>
  <div class="ad_area">
    <ul>
       <?php  $items = D("Advitem")->where("adv_id=100 and audit=1  and ((`stime`=0 && `ltime`=0) || (`stime`<1542961184&& `ltime`>1542961184))")->order("orderby asc")->limit("0,6")->select(); $index=0; foreach($items as $item){$index++; ?><li style="background:url(__ATTACHS__/<?php echo ($item["thumb"]); ?>) 50% 0 no-repeat;"  onclick="window.open('<?php echo ($item["link"]); ?>','<?php echo ($item["target"]); ?>')"></li> <?php } ?>
    </ul>
  </div>
	<div class="itemlist">
		<!-- <h1>成功案例</h1>
		<h2>在这里，您可以看出我们的实力！</h2> -->
		<ul>
        	 <?php  $items = D("project")->where("`audit`=1 and `status` IN (5,-2)  and `closed`=0 ")->order("dateline desc")->limit("0,4")->select(); $items = D("Project")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li>
				<a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="list_tit"><img src="__ATTACHS__/<?php echo ($Projecttips[$item[tip_id]][img]); ?>" alt="<?php echo ($item["title"]); ?>" /><span>【第<?php echo ($item["project_id"]); ?>期】<?php echo ($item["title"]); ?></span></a>
				<div class="jindu">
					<div class="end_jindu"></div>
				</div>
				<div class="canshu">
					<p>时间：<?php echo (date("Y-m-d H:i",$item["dateline"])); ?><span>|</span>支持人数<?php echo (($item["have_num"])?($item["have_num"]):'0'); ?>人</p>
					<p><b>众筹额：<font>￥<?php echo zhu_int($item[fund_raising]/10000);?></font>万</b><span>|</span>已筹：￥<?php echo zhu_int($item[have_price]/10000);?>万</p>
					<p><a href="<?php echo U('project/detail',array('project_id'=>$item[project_id]));?>" class="jieshu">已分红，项目完成</a></p>
				</div>
				<a href="" class="tag">大众</a>
				<div class="end_layer">
					<div>
						<p><?php echo zhu_msubstr($item[title],0,5);?>已分红</p>
						<p>万元收益：<?php echo (ceil($item[shouyi][shouyi])); ?>元</p>
						<p>回款周期：<?php echo ($item[shouyi][days]); ?>天</p>
						<p>年化收益：<?php echo ceil($item[shouyi][shouyi]*365/$item[shouyi][days])/100;?>%</p>
					</div>
				</div>
			</li> <?php } ?>
		</ul>

		<!-- <div class="zc_more"><a href="<?php echo U('project/index',array('status'=>'6'));?>">+ 更多成功案例</a></div> -->
	</div>
	<div class="fenge"></div>
</div>
<div class="news">
	<div class="newswrap">
		<div class="tslice">
         	<?php if(is_array($articlelist)): foreach($articlelist as $key=>$val): if($key == 0): ?><p class="gonggao"><?php echo ($val[title]); ?><a href="<?php echo U('article/index',array('cate_id'=>$val[cate_id]));?>" class="more">MORE</a></p>
			<ul>
               <?php  $items = D("Article")->where("`closed`=0 and `audit`=1 and `cate_id`={$val['cate_id']} ")->order("dateline desc")->limit("0,7")->select(); $items = D("Article")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li><a href="<?php echo U('article/detail',array('article_id'=>$item[article_id]));?>"><?php echo ($item[title]); ?></a><font><?php echo (date("Y-m-d",$item["dateline"])); ?></font></li> <?php } ?>
			</ul><?php endif; endforeach; endif; ?>			
		</div>
		<div class="tslice">
			<?php if(is_array($articlelist)): foreach($articlelist as $key=>$val): if($key == 1): ?><p class="gonggao"><?php echo ($val[title]); ?><a href="<?php echo U('article/index',array('cate_id'=>$val[cate_id]));?>" class="more">MORE</a></p>
			<ul>
               <?php  $items = D("Article")->where("`closed`=0 and `audit`=1 and `cate_id`={$val['cate_id']} ")->order("dateline desc")->limit("0,7")->select(); $items = D("Article")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; ?><li><a href="<?php echo U('article/detail',array('article_id'=>$item[article_id]));?>"><?php echo ($item[title]); ?></a><font><?php echo (date("Y-m-d",$item["dateline"])); ?></font></li> <?php } ?>
			</ul><?php endif; endforeach; endif; ?>	
		</div>
		<div style="margin-right:0" class="ph_list">
			<p class="paihang">投资排行</p>
			<ul>
			
                
                 <?php  $items = D("Member")->where("`closed`  IN (1,0) ")->order("tou_price desc")->limit("0,7")->select(); $items = D("Member")->CallDataForMat($items); $index=0; foreach($items as $item){$index++; if($index < 7 ): ?><li><span><?php echo ($index); ?></span><a href=""><?php echo zhu_name($item[name]);?></a><font>投资总额：&nbsp;¥ <?php echo zhu_int($item[tou_price]/100);?><font></li>
                  <?php else: ?>
                  <li class="noBorder"><span><?php echo ($index); ?></span><a href=""><?php echo zhu_name($item[name]);?></a><font>投资总额：&nbsp;¥ <?php echo zhu_int($item[tou_price]/100);?><font></li><?php endif; ?> <?php } ?>
                
			</ul>			
		</div>
	</div>
	<div style="clear:both"></div>
</div>
<div class="fenge"></div>
<!-- <div class="shuju">
<input type="hidden" id='v1' value="<?php echo ($count[count1]); ?>" />
<input type="hidden" id='v2' value="<?php echo ($count[count2]); ?>" />
<input type="hidden" id='v3' value="<?php echo ($count[count3]); ?>" />
	<div class="data">
		<div class="textA"></div>
		<div class="textA1">发布项目总数</div>
		<div class="fenge1"></div>
		<div class="textB"></div>
		<div class="textB1">累计支持人数</div>
		<div class="fenge2"></div>
		<div class="textC"></div>
		<div class="textC1">累计投资资金</div>
	</div>
</div> -->
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