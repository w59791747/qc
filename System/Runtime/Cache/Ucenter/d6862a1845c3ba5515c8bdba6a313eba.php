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
  <div class="title">设置资料</div>
  <div class="right"><a href="javascript:void(0)" class="m_menu"><i class="iconfont icon-daohangliebiao"></i></a></div>
  <span></span>
</header>
  <section class="page_center_box">
    <div class="introForm">
      <form method="post"  action="<?php echo U('set/index');?>"  target="chuang_frm">
        <div class="regGroup clearfix">
          <label class="title">头像</label>
          <div class="fileImg clearfix"> <img class="flt txImg" id="photo_img" width="100px;"  src="__ATTACHS__/<?php echo (($MEMBER["face"])?($MEMBER["face"]):'default.jpg'); ?>" />
            <input type="hidden" name="data[face]" value="<?php echo ($MEMBER["face"]); ?>" id="data_photo" />
            <label class="fileLabel">
              <input type="file"  id="fileToUpload" name="fileToUpload">
              <p>从手机相册选择</p></label>
          </div>
        </div>
        <div class="regGroup clearfix">
          <label class="title">城市</label>
          <div class="control_txt">
            <select id="s_province" name="s_province" class="text city">
            </select>
            <select id="s_city" name="s_city" class="text city">
            </select>
            <select id="s_county" name="data[city]" class="text city">
            </select>
            <input type="hidden" name="data[citys]" value="<?php echo ($MEMBER["city"]); ?>">
          </div>
        </div>
        <div class="regGroup clearfix">
          <label class="title">出生日期</label>
          <div class="control_txt">
            <select name="data[Y]" class="text sel_year time" rel="<?php echo (($MEMBER["Y"])?($MEMBER["Y"]):'2000'); ?>">
            </select>
            <span class="tm fl">年</span>
            <select name="data[M]" class="text sel_month time" rel="<?php echo (($MEMBER["M"])?($MEMBER["M"]):'2'); ?>">
            </select>
            <span class="tm fl">月</span>
            <select name="data[D]" class="text sel_day time" rel="<?php echo (($MEMBER["D"])?($MEMBER["D"]):'14'); ?>">
            </select>
            <span class="tm fl">日</span> </div>
        </div>
        <div class="regGroup clearfix">
          <label class="title">性别</label>
          <div class="control_txt">
            <div class="chose">
              <label>
              <input type="radio" name="data[gender]" 
              
                
                <?php if($MEMBER["gender"] == woman): ?>checked="checked"<?php endif; ?>
                value="woman" />女士</label>
            </div>
            <div class="chose">
              <label> <input type="radio" name="data[gender]" 
              
                
                <?php if($MEMBER["gender"] == man): ?>checked="checked"<?php endif; ?>
                value="man" />男士</label>
              </label>
            </div>
          </div>
        </div>
		
        <div class="regGroup clearfix">
          <input class="btn" type="submit" value="确认保存">
        </div>
      </form>
    </div>
    </div>
  </section>
</section>
<script type="text/javascript" src="__TMPL__statics/js/ajaxfileupload.js"></script> 
<script>
	function ajaxupload() {
		
			$.ajaxFileUpload({
				url: '<?php echo U("up/upload/upload",array("model"=>"member_thumb"));?>',
				type: 'post',
				fileElementId: 'fileToUpload',
				dataType: 'text',
				secureuri: false, //一般设置为false
				success: function (data, status) {
					$("#data_photo").val(data);
					$("#photo_img").attr('src', '__ATTACHS__/' + data).show();
					$("#fileToUpload").unbind('change');
					$("#fileToUpload").change(function () {
						ajaxupload();
					});
					$('.serch-bar-mask').hide();
				}
			});
		
	}

	$(document).ready(function () {
		$("#fileToUpload").change(function () {
			ajaxupload();
		});
	   

	});
</script> 
<script type="text/javascript" src="__PUBLIC__/js/area.js?t=<?php echo ($nowtime); ?>"></script> 
<script type="text/javascript" src="__PUBLIC__/js/birthday.js"></script> 
<script type="text/javascript">_init_area();</script> 
<script type="text/javascript">
		var Gid  = document.getElementById ;
		var showArea = function(){
			Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" + 	
			Gid('s_city').value + " - 县/区" + 
			Gid('s_county').value + "</h3>"
									}
		</script> 
<script>  
        $(function () {
            $.ms_DatePicker({
                    YearSelector: ".sel_year",
                    MonthSelector: ".sel_month",
                    DaySelector: ".sel_day"
            });
            $.ms_DatePicker();
        }); 
		
		function company(val){
			if(val == 1){
				$('#company').css('display','block');
			}else{
				$('#company').css('display','none');
			}	
		}
        </script> 
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