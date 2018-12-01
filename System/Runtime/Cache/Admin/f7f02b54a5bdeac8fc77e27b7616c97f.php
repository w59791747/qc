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


<link href="<?php echo ($admin_statics); ?>/css/jquery.dad.css" type="text/css" rel="stylesheet">
<div class="pad">
<div class="inTongzhi"> <i class="laba"></i> 欢迎<b> <?php echo ($admin["admin_name"]); ?></b> 来到<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台！ </div>
<div>
  <div class="intjList clearfix">
    <div class="cont firstcont"> <a  target="main_frm"  href="<?php echo U('project/create');?>">
    <i class="ico1"></i>
        <h4>发起众筹</h4>
     
      </a> </div>
    <div class="cont secondcont"> <a  target="main_frm"  href="<?php echo U('project/crowd_toupiao');?>">
      <i class="ico2"></i>
        <h4>众筹投票</h4>
      
      </a> </div>
    <div class="cont thirdcont"> <a  target="main_frm" href="<?php echo U('verify/index');?>">
       <i class="ico3"></i>
        <h4>实名认证</h4>
      
      </a> </div>
    <div class="cont fourthcont"> <a target="main_frm" href="<?php echo U('memberlog/tixian');?>">
       <i class="ico4"></i>
        <h4>提现审核</h4>
     
      </a> </div>
    <div class="clear"></div>
  </div>
  <div class="indexCont">
    <div class="publicTit clearfix"><span><i class="fbxm"></i>发布项目统计</span></div>
    <div class="tongjiFull clearfix">
      <div class="tjd_chose clearfix"></div>
      <div class="bjb" id="container"></div>
      <a target="main_frm" href="<?php echo U('tongjiproject/index');?>">查看详细项目统计报表</a> </div>
    </div>
  </div>
  <div class="indexCont">
    <div class="publicTit clearfix"><span><i class="hyzc"></i>会员注册统计</span></div>
    <div class="tongjiFull clearfix">
      <div class="tjd_chose clearfix"></div>
      <div class="bjb" id='container2'></div>
      <div class="tongjiCont">
        <?php if(is_array($tongji2[from_lists])): foreach($tongji2[from_lists] as $key=>$v): if($key == 'all'): ?><span><i>总数</i><b><?php echo ($v); ?></b></span>
            <?php else: ?>
            <span><i><?php echo ($from_list[$key]); ?></i><b><?php echo ($v); ?></b></span><?php endif; endforeach; endif; ?>
        <a target="main_frm" href="<?php echo U('tongjimember/index');?>">查看详细会员统计报表</a> </div>
    </div>
  </div>
 
  
  <div class="clearfix">
    <div class="indexCont_half flt">
      <div class="publicTit clearfix"><span><i class="kfxx"></i>助创系统开发信息</span></div>
      <div class="indexCont_kfxx">
        <p><font class="black6">安装环境：apache+php+mysql</font></p>
        <p><font class="black6">系统开发：</font>合肥助创信息科技有限公司开发部</p>
        <p><font class="black6">联系电话：</font>0551-65568651</p>
        <p><font class="black6">售前QQ：</font><a class="qqjt" href="http://wpa.qq.com/msgrd?v=3&amp;uin=3029347583&amp;site=qq&amp;menu=yes" target="_blank">QQ交谈</a> </p>
        <p><font class="black6">系统官网：</font><a target="_blank" href="http://www.izhuchuang.com">www.izhuchuang.com</a></p>
      </div>
    </div>
    <div class="indexCont_half frt">
      <div class="publicTit"><span class="flt"><i class="gfxx"></i>官方公告信息</span></div>
      <ul class="indexCont_ul">
      	<?php if(is_array($gonggao)): foreach($gonggao as $key=>$var): ?><li> <a target="_blank" href="http://www.izhuchuang.com/article/detail/article_id/<?php echo ($var["article_id"]); ?>.html"  class="black6"><?php echo ($var["title"]); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
  </div>
    <div class="indexCont">
      <div class="publicTit clearfix"><span class="flt"><i class="yhfk"></i>最新用户反馈</span><a target="main_frm" href="<?php echo U('feedback/index');?>" class="frt black9">更多</a></div>
      <ul class="indexCont_ul">
        <?php if(is_array($feedback)): foreach($feedback as $key=>$var): ?><li> 
          <?php echo msubstr($var[content],0,40);?>
            <?php echo ROLE('feedback/edit',array("feedback_id"=>$var["feedback_id"]),'去处理','','frt');?> </li><?php endforeach; endif; ?>
      </ul>
   
  </div>
</div>
</aside>
<script src="<?php echo ($admin_statics); ?>/js/jquery-1.11.3.min.js"></script> 
<script src="<?php echo ($admin_statics); ?>/js/jquery.dad.min.js"></script> 
<script src="__PUBLIC__/js/highcharts/highcharts.js"></script> 
<script src="__PUBLIC__/js/highcharts/modules/exporting.js"></script> 
<!--右侧JS--> 
<script type="text/javascript">

$(document).ready(function(){
$("table.table_data  tr:odd").addClass("cred");


	});
	
</script> 
<script type="text/javascript">
<!--右侧JS-->
$(document).ready(function(){

	$('.jq22').dad({
		draggable: 'h4'
	});
	
	});
	
</script> 
<script>
	$(function () {
    $('#container').highcharts({
        title: {
            text: '<?php echo ($title); ?>',
            x: -20 //center
        },
        
        xAxis: {
            categories: [<?php echo ($tongji[x]); ?>]
        },
        yAxis: {
            title: {
                text: '项目人数'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [ {
            name: '<?php echo ($type_list[crowd]); ?>',
            data: [<?php echo ($tongji[y][crowd]); ?>]
        }]
    });
});
				
</script> 
<script>
          $(function () {
            $('#container2').highcharts({
                chart: {
                    type: 'column',
                    margin: [ 50, 50, 100, 80]
                },
                title: {
                    text: '<?php echo ($title2); ?>'
                },
                xAxis: {
                    categories: [
                        <?php echo ($tongji2[x]); ?>
                    ],
                    labels: {
                        rotation: 0,
                        align: 'center',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '注册人数'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.1f}</b>',
                },
                series: [{
                    name: 'Population',
                    data: [<?php echo ($tongji2[y]); ?>],
                    dataLabels: {
                        enabled: false,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        x: 4,
                        y: 10,
                        style: {
                           
                        }
                    }
                }]
            });
        });
        </script> 
<script>
 	$(function () {
    $('#container3').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '<?php echo ($title3); ?>'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
			 <?php echo ($tongji3[tongji]); ?>
            ]
        }]
    });
});				
 
 </script> 
<script>
 	$(function () {
    $('#container4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '<?php echo ($title4); ?>'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
			 <?php echo ($tongji4[tongji]); ?>
            ]
        }]
    });
});				
 
 </script>