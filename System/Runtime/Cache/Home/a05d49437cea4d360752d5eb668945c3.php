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

<div class="item_top">
  <div class="item_top_wrap">
    <div class="imgplay">
      <div class="jqzoom"> <img src="__ATTACHS__/<?php echo ($project['project_photos'][0]); ?>" style="width:500px; height:313px;" alt="" jqimg="__ATTACHS__/<?php echo ($project['project_photos'][0]); ?>" id="bigImg"/> </div>
      <div class="ctrl_play">
        <ul>
          <?php if(is_array($project['project_photos'])): foreach($project['project_photos'] as $key=>$item): ?><li><img src="__ATTACHS__/<?php echo ($item); ?>" alt="" /></li><?php endforeach; endif; ?>
        </ul>
        <p class="play_prev"><img src="__TMPL__statics/images/cp/prev.png" alt="上一张" /></p>
        <p class="play_next"><img src="__TMPL__statics/images/cp/next.png" alt="下一张" /></p>
      </div>
    </div>
    <div class="item_top_right">
      <h1>【第<?php echo ($project["project_id"]); ?>期】<?php echo ($project["title"]); ?></h1>
      <div class="jindu">
        <?php if($project[status] == 0 ): ?><div></div>
          <?php elseif($project[status] == 1 ): ?>
          <div class="cur_jindu" style="width:<?php echo zc_int($project[have_price]/$project[fund_raising]*100);?>%"></div>
          <?php elseif($project[status] > 1 ): ?>
          <div class="end_jindu"></div><?php endif; ?>
        <div class="jindu_num">众筹进度：<?php echo zc_int($project[have_price]/$project[fund_raising]*100);?>%</div>
      </div>
      <div class="canshu">
        <p>众筹目标：<span>¥<?php echo zhu_int($project[fund_raising]/100);?></span>&nbsp;&nbsp;剩余可投：¥<?php echo zhu_int(($project[fund_raising]-$project[have_price])/100);?></p>
        <ul>
          <li><span><?php echo (($project["have_num"])?($project["have_num"]):'0'); ?></span><br />
            支持人数（个）</li>
          <li><span><?php echo zhu_int($project[fund_raising]/100*$project[max_price]/100);?></span><br />
            最大可投（元）</li>
          <li><span><?php echo zhu_int($project[min_price]);?></span><br />
            最小可投（元）</li>
          <li style="border:none"><span><?php echo ($project[chi_days]); ?></span><br />
            持有期限（天）</li>
        </ul>
      </div>
      <div class="canshu2"> 开始时间：<?php echo (date("Y-m-d H:i",$project["stime"])); ?><span>|</span>筹资结束:<?php echo (date("Y-m-d H:i",$project["ltime"])); ?> </div>
      <div class="qc_touzi">
        <?php if($project[status] == 0 ): ?><div class="zc_ready"> <a mini='act' href="<?php echo U('project/follow',array('project'=>$project['project_id']));?>" class="zc_but"> 项目开始通知我</a>
            <p>距离项目开始还剩：</p>
            <div id="fnTimeCountDown" data-end="<?php echo (date('Y/m/d H:i:s',$project["stime"])); ?>"> <span class="month">00</span>月 <span class="day">00</span>天 <span class="hour">00</span>时 <span class="mini">00</span>分 <span class="sec">00</span>秒 <span class="hm">000</span> </div>
          </div>
          <?php elseif($project[status] == 1 ): ?>
          <div class="zc_ing">
            <input type="text" id='money' placeholder="请输入投资金额" class="pop_num" />
            <a  class="zc_but" onclick="pay()">立即众筹</a>
            <?php if(empty($MEMBER)): ?><span>当前可用余额：【<a href="<?php echo U('home/passport/login');?>">登录</a>】后查看</span>
              <?php else: ?>
              <span>当前可用余额:￥<?php echo (($MEMBER[price])?($MEMBER[price]):'0'); ?></span><?php endif; ?>
          </div>
          <?php elseif($project[status] > 1 ): ?>
          <div class="zc_ing">
            <input type="text" value="请输入投资金额" class="pop_num" />
            <a href="" class="zc_but zc_end">筹资结束</a> <span>该项目筹资已结束</span> </div><?php endif; ?>
      </div>
	 
      
      <?php if($project['status'] == 5 || $project['status'] == -2): ?><div class="tuzhang">
            <p style="margin-top:25px;"><?php echo zhu_msubstr($project[title],0,5);?></p>
            <p>万元收益：<?php echo (ceil($project[shouyi][shouyi])); ?>元</p>
            <p>回款周期<?php echo ($project["shouyi"]["days"]); ?>天</p>
            <p>年化收益：<?php echo ceil($project[shouyi][shouyi]*365/$project[shouyi][days])/100;?>%</p>
          </div><?php endif; ?>
    </div>
    <div class="step">
      <?php if($project[status] == 0 ): ?>预筹中
        <?php elseif($project[status] == 1 ): ?>
        众筹中
        <?php elseif($project[status] == 2 ): ?>
        出售中
        <?php elseif($project[status] == 3 ): ?>
        投票中
        <?php elseif($project[status] == 4 ): ?>
        待回款
        <?php elseif($project[status] == 5 ): ?>
        已完成
        <?php elseif($project[status] == -2 ): ?>
        已回购<?php endif; ?>
    </div>
    <div style="clear:both"></div>
  </div>
</div>
<div class="item_mid">
  <div class="item_mid_tit">
    <ul>
      <li class="car_i"><a href="javascript:void(0);" class="car_info" id="cur_item">车辆照片</a></li>
      <li class="car_p"><a href="javascript:void(0);" class="car_pic" >车辆信息</a></li>
      <li class="car_r"><a href="javascript:void(0);" class="car_record">投资记录</a></li>
      <!-- <li class="car_c"><a href="javascript:void(0);" class="car_contract">合同样本</a></li> -->
      <li class="car_v"><a href="javascript:void(0);" class="car_vote">我要投票</a></li>
      <li class="car_t"><a href="javascript:void(0);" class="car_comment">项目评价</a></li>
    </ul>
  </div>
  <div class="item_mid_con">
    <div class="car_con car_info_con"> <?php echo ($project["content"]); ?> </div>
    <div class="car_con car_pic_con"> <?php echo ($project["canshu"]); ?> </div>
    <div class="car_con car_record_con">
      <div id="list_4"></div>
      <div class="propage clearfix">
        <ul>
          <?php if(is_array($list_arr)): foreach($list_arr as $key=>$val): ?><li onClick="getzhichi(<?php echo ($val); ?>)"><?php echo ($val); ?></li><?php endforeach; endif; ?>
        </ul>
      </div>
    </div>
    <!-- <div class="car_con car_contract_con"> <?php echo ($project["hetong"]); ?> </div> -->
    <div class="car_con car_vote_con">
      <?php if($project[status] < 3): ?><div class="toupiaotp"><img src="__TMPL__statics/images/zwtp.jpg" /></div>
        <?php else: ?>
        <div class="zcVote">
		  <div class="votetx">
			<ul>
			  <li>当前车辆出售价格：<?php echo ($chushou[amount]/100); ?>（元）</li>
			  <li>意向客户支付定金：<?php echo ($chushou[dingjin]/100); ?>（元）</li>
			</ul>
		  </div>
          <div class="zcVoteList"> <em class="yes"></em><span><?php echo ($zancheng); ?>票</span>
            <?php if($project[status] > 3): ?><a class="btn graybtn">√ 赞成</a>
              <?php else: ?>
              <a class="btn"  mini='act' href="<?php echo U('project/toupiao',array('project_id'=>$project['project_id'],'status'=>'1'));?>">√ 赞成</a><?php endif; ?>
          </div>
          <div class="zcVoteList"> <em class="no"></em><span><?php echo ($fandui); ?>票</span>
            <?php if($project[status] > 3): ?><a class="btn graybtn">× 反对</a>
              <?php else: ?>
              <a class="btn nobtn"  mini='act' href="<?php echo U('project/toupiao',array('project_id'=>$project['project_id'],'status'=>'-1'));?>">× 反对</a><?php endif; ?>
          </div>
          <div class="zhu">
            <p>备注：</p>
            <p>1、投票结果采取少数服从多数的原则，赞成（或不赞成）投票人数超过众筹总人数的 <?php echo ($project["tpbfb"]); ?>% 即视为当次投票通过（或未通过） </p>
            <p>2、众筹人在投票截止日期到期之后仍未参与投票的，系统将默认判定其为投“赞成”票，该投票截止时间：<?php echo (date('Y-m-d H:i:s',$project["toultime"])); ?></p>
          </div>
        </div><?php endif; ?>
    </div>
    <div class="car_con car_comment_con">
      <div class="commentBox clearfix">
        <form  method="post"  action="<?php echo U('project/comments',array('project_id'=>$project[project_id]));?>"  target="chuang_frm">
          <!--评价左侧头像及用户名-->
          <div class="comment_tx"> <img alt="<?php echo ($MEMBER[name]); ?>" src="__ATTACHS__/<?php echo (($MEMBER["face"])?($MEMBER["face"]):'default.jpg'); ?>">
            <p class="name"><?php echo ($MEMBER[name]); ?></p>
          </div>
          <!--评价右侧-->
          <div class="comment_cont">
            <div class="star">
              <input type="radio" id="rate5-1" name="rating" value="5">
              <label for="rate5-1" title="Amazing">5 stars</label>
              <input type="radio" id="rate4-1" name="rating" value="4">
              <label for="rate4-1" title="Very good">4 stars</label>
              <input type="radio" id="rate3-1" name="rating" value="3">
              <label for="rate3-1" title="Average">3 stars</label>
              <input type="radio" id="rate2-1" name="rating" value="2">
              <label for="rate2-1" title="Not good">2 stars</label>
              <input type="radio" id="rate1-1" name="rating" value="1">
              <label for="rate1-1" title="Terrible">1 star</label>
            </div>
            <textarea name='data[content]' placeholder="输入您想要跟大家交流的内容~"></textarea>
            <!--未登录时用灰色按钮，登录后用蓝色按钮（去掉graybtn即可）-->
            <div class="comment_button clearfix">
              <?php if($MEMBER == ''): ?><div class="flt black6">请登录后提问，立即 <a href="<?php echo U('passport/login');?>" class="fontcl1">登录</a> 或 <a href="<?php echo U('passport/reg');?>" class="fontcl1"> 注册 </a></div>
                <div class="frt"> <a  class="btn graybtn">发布</a> </div>
                <?php else: ?>
                <div class="frt">
                  <input type="submit" value="发布"  class="btn" />
                </div><?php endif; ?>
            </div>
          </div>
        </form>
      </div>
      <div id="list_3"></div>
      <div class="propage clearfix">
        <ul>
          <?php if(is_array($comment_arr)): foreach($comment_arr as $key=>$val): ?><li onClick="getcomment(<?php echo ($val); ?>)"><?php echo ($val); ?></li><?php endforeach; endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="fenge"></div>
<script type="text/javascript">
	<!--分页-->
	$(document).ready(function(){
		
		 $('.propage ul li').click(function () {
            var _this = $(this);
            _this.parents('.propage').find('ul li').removeClass('on');
            _this.addClass('on');
        });
        $('.propage').each(function () {
            $(this).find('ul li').eq(0).click();
        });
		
	});
</script> 
<script type="text/javascript">
	$(".zcDetail_left_box .zcDetail_title ul li").click(function () {
		var index = $(this).index();
		$(".zcDetail_left_box .zcDetail_title ul li").each(function (a) {
			if (a == index) {
				$(this).find('a').addClass('current');
				$('.zcDetail_left_box .zcDetail_cont').find('.section').eq(a).fadeIn(100);
			} else {
				$(this).find('a').removeClass('current');
				$('.zcDetail_left_box .zcDetail_cont').find('.section').eq(a).fadeOut(0);
			}
		})
	});
	$(".zcDetail_left_box .zcDetail_title ul li").eq(0).click();
	
	
	
	function pay(){
		var link = "<?php echo U('project/zhichi',array('money'=>'123money','project_id'=>$project[project_id]));?>";
		var money = $('#money').val();
		var minm = parseInt('<?php echo ($project[min_price]); ?>');
		var maxm = parseInt('<?php echo ($project[fund_raising]/100*$project[max_price]/100); ?>');
		var shengyu = parseInt('<?php echo ($project[fund_raising]/100-$project[have_price]/100); ?>');
		var uid = '<?php echo ($MEMBER[uid]); ?>';
		var verify = '<?php echo ($MEMBER[verify]); ?>';
		if(!isNaN(money) && money>0){
			if(money < minm){
				parent.bmsg("不能小于最小金额","",3000);		
			}else if(money > maxm){
				parent.bmsg("不能大于最大金额","",3000);		
			}else if(money > shengyu){
				parent.bmsg("不能超过剩余金额","",3000);		
			}else if(!uid){
				parent.bmsg("请先登录","<?php echo U('home/passport/login');?>",3000);		
			}else if(verify < 6){
				parent.bmsg("请先通过实名验证和手机验证","<?php echo U('member/set/name');?>",3000);		
			}else{
				window.location = link.replace('123money',money);
			}
		}else{
			parent.bmsg("金额不合法","",3000);
		}
	}
	
	
	
	function getzhichi(p){
		var link = "<?php echo U('project/zhichi_list',array('project_id'=>$project[project_id],'p'=>'page'));?>";
		$("#list_4").load(link.replace("page",p));
	}
	
	
	
	function getcomment(p){
		var link = "<?php echo U('project/comment_list',array('project_id'=>$project[project_id],'p'=>'page'));?>";
		$("#list_3").load(link.replace("page",p));
	}
	//头部标题定位效果
	(function(K, $){  
		getzhichi(1);
		getcomment(1);
	})(window.KT, window.jQuery)  
	
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