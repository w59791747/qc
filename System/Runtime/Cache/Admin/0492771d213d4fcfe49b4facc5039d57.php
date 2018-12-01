<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($CONFIG["site"]["title"]); ?>管理后台</title>

<link href="<?php echo ($admin_statics); ?>/css/public.css" type="text/css" rel="stylesheet">
<link href="<?php echo ($admin_statics); ?>/css/style.css" type="text/css" rel="stylesheet">
<script>var PUBLIC = '__PUBLIC__';var ROOT = '__ROOT__';</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script src="__PUBLIC__/js/admin.js"></script>
<script src="__PUBLIC__/js/jquery-ui.min.js"></script>
<script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
</head>
<body>
<iframe id="zhuchuang_frm" name="zhuchuang_frm" style="display:none;"></iframe>

<body>

<script type="text/javascript">
<!--头部和左部公共JS-->
$(document).ready(function(){
	$('.right_side').height($(window).height()-60);
	$(".sidebar_menu > li.active a").click(function(){
		var thisli = $(this);
	    if(thisli.parent().find('.treeview_menu').is(':hidden')){
			thisli.parent().addClass('current');
			thisli.parent().find('ul.treeview_menu').slideDown(300);	
			thisli.parent().find("a i.fa").addClass('pull_right');
		}else{
			thisli.parent().removeClass('current');
			thisli.parent().find('ul.treeview_menu').slideUp(100);	
			thisli.parent().find("a i.fa").removeClass('pull_right');
		}
	});
	$('.treeview_menu li').click(function(){
		$('.treeview_menu li').removeClass('on');
		$(this).addClass('on');
	});
	
});
</script>


<div class="pad">
<div class="breadNav">
  <div class="flt breadNav_text"> <span>项目</span><i></i><span>项目管理</span><i></i><span>发布项目</span> </div>
</div>
<form  target="zhuchuang_frm" action="<?php echo U('Project/create');?>" method="post">
  <table class="table_data" cellpadding="0" cellspacing="0">
    <tr>
      <th class="alginRt w-200"> <font class="pointcl">*</font> 项目名称 </th>
      <td><input type="text" name="data[title]" value="<?php echo (($detail["title"])?($detail["title"]):''); ?>" class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-200"> 项目图片</th>
      <td class="xmtp">
            <input id="photo_file" name="photo_file" type="file" multiple="true" value="" />
         
            <div class="jq_uploads_img" style="float:left;">
            <?php if(is_array($detail[project_photo])): foreach($detail[project_photo] as $key=>$item): ?><span style="width: 200px; height: 120px; float: left; margin-right: 30px; margin-top: 10px;"> 
                    <img width="200" height="100" src="__ROOT__/attachs/<?php echo ($item); ?>">  
                    <input type="hidden" name="project_photo[]" value="<?php echo ($item); ?>" />  
                    <a href="javascript:void(0);">取消</a>  
                </span><?php endforeach; endif; ?>
        </div>
        
        
        </td>
    </tr>
    <tr>
      <th class="alginRt w-200"> 发布车商 </th>
      <td><select name='data[cheshang_id]' class="text w-200">
          <?php if(is_array($cheshanglist)): foreach($cheshanglist as $key=>$val): ?><option  
            
            <?php if($detail[cheshang_id] == $val[cheshang_id]): ?>selected="selected"<?php endif; ?>
            
             value='<?php echo ($val["cheshang_id"]); ?>'><?php echo ($val["title"]); ?>
            </option><?php endforeach; endif; ?>
        </select>
    </tr>
    <tr>
      <th class="alginRt w-200"> 筹资金额 </th>
      <td><input type="text" id='fund_raising' onchange="get_huigou()" name="data[fund_raising]" value="<?php echo (($detail[fund_raising]/100)?($detail[fund_raising]/100):'0'); ?>" class="text w-200" /><span class="comm">元</span></td>
    </tr>
    <tr>
      <th class="alginRt w-200"> 上线 </th>
      <td><label class="radio"> <input type="radio" name="data[audit]" value="1" 
          
          <?php if($detail["audit"] == 1): ?>checked="checked"<?php endif; ?>
          >
          上线</label>
        <label class="radio"> <input type="radio" name="data[audit]" value="0" 
          
          <?php if($detail["audit"] != 1): ?>checked="checked"<?php endif; ?>
          >
          下线</label></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 状态 </th>
      <td><select onchange="change(this.value)" name='data[status]' class="text w-200">
          <option value="0">预热中</option>
          <option value="1" selected="selected">众筹中</option>
        </select></td>
    </tr>
    <tr >
      <th class="alginRt w-100"> 开始时间 </th>
      <td><input type="text" name='data[stime]' value="<?php echo (date('Y-m-d',$nowtime)); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"   class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-200"> 车型 </th>
      <td><select name='data[cate_id]' class="text w-200">
          <?php if(is_array($catelist)): foreach($catelist as $key=>$val): ?><option  
            
            <?php if($detail[cate_id] == $val[cate_id]): ?>selected="selected"<?php endif; ?>
            
             value='<?php echo ($val["cate_id"]); ?>'><?php echo ($val["cate_name"]); ?>
            </option><?php endforeach; endif; ?>
        </select>
    </tr>
    <tr>
      <th class="alginRt w-100"> 标签 </th>
      <td><select name='data[tip_id]' class="text w-200">
          <?php if(is_array($tipslist)): foreach($tipslist as $key=>$val): ?><option  
              
            <?php if($detail[tip_id] == $val[tip_id]): ?>selected="selected"<?php endif; ?>
            
               value='<?php echo ($val["tip_id"]); ?>'><?php echo ($val["cate_name"]); ?>
            </option><?php endforeach; endif; ?>
        </select></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 筹资期限 </th>
      <td><input type="text" name="data[days]" value="<?php echo (($CONFIG[crowd][days])?($CONFIG[crowd][days]):'0'); ?>" class="text w-200" /><span class="comm">天</span></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 众筹最小金额 </th>
      <td><input type="text" name="data[min_price]" value="<?php echo (($CONFIG[crowd][min_price])?($CONFIG[crowd][min_price]):'0'); ?>" class="text w-200" /><span class="comm">元</span></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 最大投资额度百分比 </th>
      <td><input type="text" name="data[max_price]" value="<?php echo (($CONFIG[crowd][max_price])?($CONFIG[crowd][max_price]):'0'); ?>" class="text w-200" /><span class="comm">%</span></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 众筹最长持有期限 </th>
      <td><input type="text" id='chi_days' onchange="get_huigou()" name="data[chi_days]" value="<?php echo (($CONFIG[crowd][chi_days])?($CONFIG[crowd][chi_days]):'0'); ?>" class="text w-200" /><span class="comm">天</span></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 投票期限 </th>
      <td><input type="text" name="data[toupiao]" value="<?php echo (($CONFIG[crowd][toupiao])?($CONFIG[crowd][toupiao]):'0'); ?>" class="text w-200" /><span class="comm">天</span></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 分红比例 </th>
      <td><input type="text" name="data[fenhong]" value="<?php echo (($CONFIG[crowd][fenhong])?($CONFIG[crowd][fenhong]):'0'); ?>" class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 溢价回购 </th>
      <td><input type="text" name="data[huigou]" id='huigou' value="<?php echo (($CONFIG[crowd][huigou])?($CONFIG[crowd][huigou]):'0'); ?>" class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 投票百分比 </th>
      <td><input type="text" name="data[tpbfb]" id='huigou' value="<?php echo (($CONFIG[crowd][tpbfb])?($CONFIG[crowd][tpbfb]):'0'); ?>" class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 是否使用积分 </th>
      <td><label class="radio"> <input type="radio" name="data[is_gold]" value="1" 
          
          <?php if($detail["is_gold"] == 1): ?>checked="checked"<?php endif; ?>
          >
          是</label>
        <label class="radio"> <input type="radio" name="data[is_gold]" value="0" 
          
          <?php if($detail["is_gold"] != 1): ?>checked="checked"<?php endif; ?>
          >
          不</label></td>
    </tr>
    
    <tr>
      <th class="alginRt w-100"> 虚拟人数 </th>
      <td><input type="text" name="data[xuni_num]"  value="0" class="text w-200" /></td>
    </tr>
    
    <tr>
      <th class="alginRt w-100"> 虚拟金额 </th>
      <td><input type="text" name="data[xuni_price]"  value="0" class="text w-200" /></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 车辆参数 </th>
      <td class="wsNormal"><script type="text/plain"  style="width:100%;height:360px;" id="data_detail1" name="data[canshu]"><?php echo ($other["canshu"]); ?></script></td>
    </tr>
    <tr>
      <th class="alginRt w-100"> 合同 </th>
      <td class="wsNormal"><script type="text/plain"  style="width:100%;height:360px;" id="data_detail2" name="data[hetong]"><?php echo ($other["hetong"]); ?></script></td>
    </tr>
    <tr>
      <th class="alginRt w-100">描述汽车图片 </th>
      <td class="wsNormal"><script type="text/plain"  style="width:100%;height:360px;" id="data_details" name="data[content]"><?php echo ($detail["content"]); ?></script></td>
    </tr>
    <tr>
      <th class="alginRt w-100" style="z-index:1000"> </th>
      <td><input type="submit" value="添加项目" class="pub_btn" /></td>
    </tr>
  </table>
  </div>
</form>
<script>
	function get_huigou()
	{
		var	chi_days = $('#chi_days').val();
			
		var	fund_raising = $('#fund_raising').val();
		if(chi_days>0 && fund_raising>0){
			
			var result = parseInt(fund_raising)+parseInt(Math.round(<?php echo ($CONFIG[crowd][huigou]); ?>/365/100*chi_days*fund_raising));
			$('#huigou').val(result)
		}
	}
</script> 
<script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js?t=<?php echo ($nowtime); ?>"></script>
<link rel="stylesheet" href="__PUBLIC__/umeditor/themes/default/css/umeditor.min.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.config.js"></script> 
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/umeditor/umeditor.min.js"></script> 
<script type="text/javascript" src="__PUBLIC__/umeditor/lang/zh-cn/zh-cn.js"></script> 
<script>
                    um = UM.getEditor('data_details', {
                        imageUrl: "<?php echo U('up/upload/editor');?>",
                        imagePath: '__ROOT__/attachs/editor/',
                        lang: 'zh-cn',
                        langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                        focus: false
                    });
        </script> 
<script>
                    um = UM.getEditor('data_detail1', {
                        imageUrl: "<?php echo U('up/upload/editor');?>",
                        imagePath: '__ROOT__/attachs/editor/',
                        lang: 'zh-cn',
                        langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                        focus: false
                    });
        </script> 
<script>
                    um = UM.getEditor('data_detail2', {
                        imageUrl: "<?php echo U('up/upload/editor');?>",
                        imagePath: '__ROOT__/attachs/editor/',
                        lang: 'zh-cn',
                        langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
                        focus: false
                    });
        </script> 
<script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js?t=<?php echo ($nowtime); ?>"></script>
<link rel="stylesheet" href="__PUBLIC__/js/uploadify/uploadify.css">

    
     <script>
		$("#photo_file").uploadify({
			'swf': '__PUBLIC__/js/uploadify/uploadify.swf?t=<?php echo ($nowtime); ?>',
			'uploader': '<?php echo U("up/upload/uploadify",array("model"=>"crowd_thumb"));?>;jsessionid=${pageContext.session.id}',
			'cancelImg': '__PUBLIC__/js/uploadify/uploadify-cancel.png',
			'buttonText': '项目图片',
			'fileTypeExts': '*.gif;*.jpg;*.png',
			'queueSizeLimit': 8,
			'onUploadSuccess': function (file, data, response) {
				var str = '<span style="width: 200px; height: 120px; float: left; margin-right: 30px; margin-top: 10px;">  <img width="200" height="100" src="__ROOT__/attachs/' + data + '">  <input type="hidden" name="project_photo[]" value="' + data + '" />    <a href="javascript:void(0);">取消</a>  </span>';
				$(".jq_uploads_img").append(str);
			}
		});

		$(document).on("click", ".jq_uploads_img a", function () {
			$(this).parent().remove();
		});

	</script>
<div class="theme-popover">   
</div>
<div class="theme-popover-mask"></div>

</div>
</body>
</html>