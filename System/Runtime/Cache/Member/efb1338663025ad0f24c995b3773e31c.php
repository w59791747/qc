<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript" src="__TMPL__statics/js/index.js"></script>
<script src="__PUBLIC__/js/my97/WdatePicker.js"></script>


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
            <p class="zhuce">欢迎您，<?php echo ($MEMBER["name"]); ?>！&nbsp;&nbsp;&nbsp;&nbsp;<a  target="chuang_frm" href="<?php echo U('home/passport/logout');?>">退出</a><?php endif; ?>
            &nbsp;&nbsp;|&nbsp;&nbsp;<span class="wap_but"><a href="<?php echo ($CONFIG['site'][host]); ?>/mobile">手机版</a></span>&nbsp;&nbsp;&nbsp;&nbsp;</p>
			<div class="wap_erweima"><img src="https://www.kuaizhan.com/common/encode-png?large=true&bg=ffffff&fg=000000&w=300&data=<?php echo ($CONFIG['site'][host]); ?>/mobile" alt="手机版" /></div>
		</div>
	</div>
	<div class="header">
		<div class="logo">
			<a href="<?php echo U('home/index/index');?>"><img src="__ATTACHS__/<?php echo (($CONFIG[site][logo])?($CONFIG[site][logo]):'__PUBLIC__/images/logo.jpg'); ?>" alt="助创cms汽车众筹系统" /></a>
		</div>

		<div class="nav">
			<ul>
                <?php if(is_array($DAOHANG)): foreach($DAOHANG as $key=>$var): if($var["parent_id"] == 0): ?><li><a 
                      
                   
                    href="<?php echo U('/'.$var['link']);?>" ><?php echo ($var["title"]); ?></a>
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

<div class="user_center">
	<div class="uc_top">
		<div class="mem_phpto">
			<img src="__ROOT__/attachs/<?php echo (($MEMBER[face])?($MEMBER[face]):$CONFIG['attachs'][member_face]); ?>" alt="会员头像" />
		</div>
		<div class="mem_info">
			<p>欢迎您：<?php echo ($MEMBER['name']); ?>
           
             <?php if($MEMBER["verify"] == 1 || $MEMBER["verify"] == 3 || $MEMBER["verify"] == 5 || $MEMBER["verify"] == 7): ?><a href=""><img src="__TMPL__statics/images/member/yxrz.png" alt="已经邮箱认证" title="已经邮箱认证" /></a>
             <?php else: ?>
        	  <a href="<?php echo U('set/mail');?>"><img src="__TMPL__statics/images/member/yxrz1.png" alt="未通过邮箱认证" title="未通过邮箱认证" /></a><?php endif; ?>
            
           <?php if($MEMBER["verify"] == 2 || $MEMBER["verify"] == 3 || $MEMBER["verify"] == 6 || $MEMBER["verify"] == 7): ?><a href=""><img src="__TMPL__statics/images/member/sjrz.png" alt="已经手机验证" title="已经手机验证" /></a>
             <?php else: ?>
        	  <a href="<?php echo U('set/mobile');?>"><img src="__TMPL__statics/images/member/sjrz1.png" alt="未通过手机验证" title="未通过手机认证" /></a><?php endif; ?>
            
            <?php if($MEMBER["verify"] == 4 || $MEMBER["verify"] == 5 || $MEMBER["verify"] == 6 || $MEMBER["verify"] == 7): ?><a href=""><img src="__TMPL__statics/images/member/rz.png" alt="已经实名认证" title="已经实名认证" /></a>
             <?php else: ?>
        	  <a href="<?php echo U('set/name');?>"><img src="__TMPL__statics/images/member/rz1.png" alt="未通过实名验证" title="未通过实名认证" /></a><?php endif; ?>
     
            
          <span>最后登录时间：<?php echo (date("Y-m-d",$MEMBER["lastlogin"])); ?></span></p>
			<p class="mem_info_but"><a href="<?php echo U('member/set/index');?>">修改资料</a><a href="<?php echo U('member/member/priceup');?>">余额充值</a><a href="<?php echo U('member/project/crowd_canyu');?>" class="my_touzi">我的投资</a></p>
		</div>
		<div class="mem_message">
			<p><a href="<?php echo U('project/order');?>"><?php echo (($count02)?($count02):'0'); ?></a><br /><span>订单</span></p>
			<p><a href="<?php echo U('message/follow_p');?>"><?php echo (($count03)?($count03):'0'); ?></a><br /><span>关注</span></p>
			<p style="border:none"><a href="<?php echo U('message/news');?>"><?php echo (($count01)?($count01):'0'); ?></a><br /><span>消息</span></p>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="uc_mid">
		<div class="uc_mid_l">
			<ul class="uc_nav">
				<li class="uc_nav_li uc_nav_li_gl"><a href="<?php echo U('member/index/index');?>" style="border:none">管理首页</a></li>
				<li class="uc_nav_li uc_nav_li_tz"><a href="javascript:void(0);">我的投资</a>
					<ul <?php if($ctl == project): ?>class="cur_uc_nav"<?php endif; ?>>
						<li><a href="<?php echo U('member/project/crowd_canyu');?>"
                       <?php if($act == crowd_canyu || $act == toupiao): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >已投项目</a></li>
						<li><a href="<?php echo U('member/project/order');?>"
                        <?php if($act == order): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >投资订单</a></li>
						<li><a href="<?php echo U('member/project/shouyi');?>"
                        <?php if($act == shouyi): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >投资收益</a></li>
					</ul>
				</li>
				<li class="uc_nav_li uc_nav_li_tx"><a href="javascript:void(0);">余额提现</a>
					<ul <?php if($ctl == member): ?>class="cur_uc_nav"<?php endif; ?>>
						<li><a href="<?php echo U('member/member/priceup');?>"
                        <?php if($act == priceup || $act == priceup2 || $act == pay): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >余额充值</a></li>
						<li><a href="<?php echo U('member/member/price');?>"
                        <?php if($act == price): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >余额日志</a></li>
						<li><a href="<?php echo U('member/member/order');?>"
                        <?php if($act == order): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >充值流水</a></li>
						<li><a href="<?php echo U('member/member/pricedown');?>"
                         <?php if($act == pricedown): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >我的提现</a></li>
					</ul>
				</li>
				<li class="uc_nav_li uc_nav_li_jf"><a href="javascript:void(0);">我的积分</a>
					<ul <?php if($ctl == jifen): ?>class="cur_uc_nav"<?php endif; ?>>
						<li><a href="<?php echo U('member/jifen/gold');?>"
                         <?php if($act == gold): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >积分日志</a></li>
						<li><a href="<?php echo U('member/jifen/lists');?>"
                         <?php if($act == lists || $act == orderdetail): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >积分订单</a></li>
					</ul>
				</li>
				<li class="uc_nav_li uc_nav_li_xx"><a href="javascript:void(0);">我的消息</a>
					<ul <?php if($ctl == message): ?>class="cur_uc_nav"<?php endif; ?>>
						<li><a href="<?php echo U('member/message/news');?>"
                        <?php if($ctl == message && $act == news): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >站内消息</a></li>
						<li><a href="<?php echo U('member/message/follow_p');?>"
                         <?php if($ctl == message && ($act == follow_p || $act == followed)): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >我的关注</a></li>
						<li><a href="<?php echo U('member/message/comments_project');?>"
                          <?php if($ctl == message && ($act == comments_project)): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >我的评论</a></li>
					</ul>
				</li>
				<li class="uc_nav_li uc_nav_li_yq"><a href="<?php echo U('member/fenxiao/lists');?>">我的邀请</a></li>
				<li class="uc_nav_li uc_nav_li_zh"><a href="javascript:void(0);">账号设置</a>
					<ul <?php if($ctl == set): ?>class="cur_uc_nav"<?php endif; ?>>
						
						<li><a href="<?php echo U('member/set/passwd');?>"
                         <?php if($ctl == set && $act == passwd): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >修改密码</a></li>
						<li><a href="<?php echo U('member/set/renzheng');?>"
                        <?php if($act == renzheng || $act == name || $act == mobile || $act == mail): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >账号认证</a></li>
						<li><a href="<?php echo U('member/set/pwd');?>"
                         <?php if($ctl == set && $act == pwd): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >支付密码</a></li>
						<li><a href="<?php echo U('member/set/card');?>"
                         <?php if($ctl == set && $act == card): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >我的银行卡</a></li>
						<li><a href="<?php echo U('member/set/adress');?>"
                        <?php if($ctl == set && ($act == adress || $act == adresscreate || $act == adressedit)): ?>class="cur_uc_nav_a"<?php endif; ?>
                        >收货地址</a></li>						
					</ul>
				</li>
			</ul>
		</div>


<div class="uc_mid_r yitou_list">
			<ul class="cz_tit">
				<li  class="cz_cur"><a href="<?php echo U('member/set/passwd');?>">修改密码</a></li>
				<li><a href="<?php echo U('member/set/renzheng');?>">账号认证</a></li>
				<li><a href="<?php echo U('member/set/pwd');?>">支付密码</a></li>
				<li><a href="<?php echo U('member/set/card');?>">绑定银行卡</a></li>
				<li><a href="<?php echo U('member/set/adress');?>">收货地址</a></li>
			</ul>
			<div class="xgzl">
              <form method="post"  action="<?php echo U('set/passwd');?>"  target="chuang_frm">
				<p><span>原密码</span><input name="oldpwd" type="password" class="mima_box" /></p>
				<p><span>新密码</span><input name="newpwd"  type="password" class="mima_box" /></p>
				<p><span>请确认</span><input name="pwd2"  type="password" class="mima_box" /></p>
                <input type="submit" class="cz_but" value="确认修改">
              </form>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
</div>
	

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


	<div class="zhichi">
		Copyright © 2014-2016 技术支持：助创cms <?php echo ($CONFIG[site][icp]); ?>
	</div>

</div>

<div class="kefu">
	<ul>
		<li class="zxkf"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($CONFIG[site][qq]); ?>&amp;site=qq&amp;menu=yes">咨询客服</a></li>
		<li class="zxcz"><a href="<?php echo U('/guide');?>">新手指引</a></li>
		<li class="bzzx"><a href="<?php echo U('article/index',array('cate_id'=>136));?>">帮助中心</a></li>
		<li class="gotop"><img src="__TMPL__statics/images/gotop.png" alt="返回顶部" /></li>
	</ul>
</div>
</body>
</html>