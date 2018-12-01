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
<div class="tjdd">
	<h1><?php echo ($project["title"]); ?></h1>
	<div class="step1">
		<ul class="steptit">
			<li class="cur_step">提交订单</li>
			<li>确认支付</li>
			<li>投资成功</li>
		</ul>
		<ul class="stepzf">
			<li class="cur_zf">线上支付</li>
			<li>线下支付</li>
		</ul>
         <form  method="post"  action="<?php echo U('project/crowdpay');?>" target="chuang_frm">
         <input type="hidden" name='data[contact]' value="<?php echo ($MEMBER["detail"]["realname"]); ?>">
         <input type="hidden" name='data[phone]' value="<?php echo ($MEMBER["mobile"]); ?>">
		<div class="zf_wrap">
			<div class="xszf">
				<div class="zffs">
					<p>请选择支付方式：</p>
					<ul class="zffs_cont">
                    	<?php if(is_array($payment)): foreach($payment as $key=>$var): if(($var["is_open"]) == "1"): ?><li><label><input type="radio" name="data[code]" value="<?php echo ($var["code"]); ?>" /><img src="__PUBLIC__/images/<?php echo ($var["logo"]); ?>" alt="<?php echo ($var["code"]); ?>" /></label></li><?php endif; endforeach; endif; ?>
					</ul>
				</div>
                
                <input type="hidden" name="data[number]"  value="<?php echo ($money); ?>">
                <input type="hidden" name="data[actual_pay]" id='actual_pay' value="0">
                <input type="hidden" name="data[project_id]" value="<?php echo ($project[project_id]); ?>">
                
                <input type="hidden" id='bili' value="<?php echo ($CONFIG['integral'][bili]); ?>">
                
                <?php if($project['is_gold'] == 1): ?><p>使用积分抵扣：</p>
                        <input type="text" id='jifen' class="text" onchange='change(this.value)' name='data[jifen]' value="0" placeholder="使用积分"><span class="ml10">当前拥积分：<font class="fontcl"><?php echo ($MEMBER["gold"]); ?></font>&nbsp;（<font class="black9"><?php echo ($CONFIG['integral'][bili]); ?>积分 = 1元</font>）</span><?php endif; ?>
				
				<div class="zffs">
					<p>实际投资金额：&nbsp;<b  id = 'amount'>¥<?php echo zhu_int($money);?></b></p>					
				</div>
				<input class="btn" type="submit" value="提交订单">
			</div>
            </form>
             <form  method="post"  action="<?php echo U('project/crowdpay');?>" target="chuang_frm">
            <input readonly="readonly"  type="hidden" name="data[type]"  value="2" class="text" />
            <input type="hidden" name='data[contact]' value="<?php echo ($MEMBER["detail"]["realname"]); ?>">
             <input type="hidden" name='data[phone]' value="<?php echo ($MEMBER["mobile"]); ?>">
			<div class="xxzf">
				<div class="zffs">
					<p>请选择支付方式：</p>
					<ul class="zffs_cont">
						<?php if(is_array($payment2)): foreach($payment2 as $key=>$var): if(($var["is_open"]) == "1"): ?><li><label><input type="radio" name="data[code]" value="<?php echo ($var["code"]); ?>" /><img src="__PUBLIC__/images/<?php echo ($var["logo"]); ?>" alt="<?php echo ($var["code"]); ?>" /></label></li><?php endif; endforeach; endif; ?>
					</ul>
				</div>
                 <input type="hidden" name="data[number]"  value="<?php echo ($money); ?>">
                <input type="hidden" name="data[actual_pay]" id='actual_pay2' value="0">
                <input type="hidden" name="data[project_id]" value="<?php echo ($project[project_id]); ?>">
                
                <input type="hidden" id='bili' value="<?php echo ($CONFIG['integral'][bili]); ?>">
                
                <?php if($project['is_gold'] == 1): ?><p>使用积分抵扣：</p>
                        <input type="text" id='jifen2' class="text" onchange='change2(this.value)' name='data[jifen]' value="0" placeholder="使用积分"><span class="ml10">当前拥积分：<font class="fontcl"><?php echo ($MEMBER["gold"]); ?></font>&nbsp;（<font class="black9"><?php echo ($CONFIG['integral'][bili]); ?>积分 = 1元</font>）</span><?php endif; ?>
				<div class="zffs">
					<p>实际投资金额：&nbsp;<b id = 'amount2'>¥<?php echo zhu_int($money);?></b></p>					
				</div>
				<input class="btn" type="submit" value="提交订单">
			</div>
		</div>
        </form>		
	</div>
</div>

<script>
function  change(value)
{
	if( value> parseInt('<?php echo ($MEMBER["gold"]); ?>')){
			value	= parseInt('<?php echo ($MEMBER["gold"]); ?>');
			$('#jifen').val(value);
			
	}
	var bili = $('#bili').val();
	var amount = '<?php echo ($money); ?>'-value/bili;
	if(amount<1){
		$('#jifen').val('0');
		 parent.bmsg("使用积分最少要支付1元","",3000);		
	}else{
		$('#amount').html('￥'+amount.toFixed(2));
		$('#actual_pay').val(amount.toFixed(2));
	}
	
}

function  change2(value)
{
	if( value> parseInt('<?php echo ($MEMBER["gold"]); ?>')){
			value	= parseInt('<?php echo ($MEMBER["gold"]); ?>');
			$('#jifen2').val(value);
			
	}
	var bili = $('#bili').val();
	var amount = '<?php echo ($money); ?>'-value/bili;
	if(amount<1){
		$('#jifen2').val('0');
		 parent.bmsg("使用积分最少要支付1元","",3000);		
	}else{
		$('#amount2').html('￥'+amount.toFixed(2));
		$('#actual_pay2').val(amount.toFixed(2));
	}
	
}
</script>


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