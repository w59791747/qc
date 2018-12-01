
$(document).ready(function(){
	
	/*轮播自动播放*/
	$('.flexslider').flexslider({
		directionNav: true,
		pauseOnAction: false
	});
	
	/*自动开启倒计时众筹详情页面*/
	$("#fnTimeCountDown").fnTimeCountDown("2017/09/09 18:45:13");

	$('.wap_but,.wap_erweima').hover(function(){
		$('.wap_erweima').stop().slideDown();
	},function(){
		$('.wap_erweima').stop().slideUp();
	})

	$('.account').hover(function(){
		$('.account_cont').stop().slideDown();;
	},function(){
		$('.account_cont').stop().slideUp();;
	})

	/*列表*/

	for(var i=0;i<$('.itemwrap .itemlist ul').length;i++){
		$('.itemwrap .itemlist ul:eq('+i+') li:last').css({marginRight:'0px'});
	}

	if(document.all){ 
		$('.itemwrap .itemlist ul li').hover(function(){		
			$(this).css({border:'2px solid #F9725E'})
		},function(){
			$(this).css({border:'2px solid #ededed'})
		})
	}


	$('.news .newswrap div.ph_list ul li:eq(0) a,.news .newswrap div.ph_list ul li:eq(0) font,.news .newswrap div.ph_list ul li:eq(0) span').css({color:'#F9725E'});
	$('.news .newswrap div.ph_list ul li:eq(1) a,.news .newswrap div.ph_list ul li:eq(1) font,.news .newswrap div.ph_list ul li:eq(1) span').css({color:'#1296DB'});
	$('.news .newswrap div.ph_list ul li:eq(2) a,.news .newswrap div.ph_list ul li:eq(2) font,.news .newswrap div.ph_list ul li:eq(2) span').css({color:'#6CA620'});

	$('.news .newswrap div.ph_list ul li').css({background:'url(../img/ph4.png) 8px center no-repeat'})
	$('.news .newswrap div.ph_list ul li:eq(0)').css({background:'url(../img/ph1.png) 8px center no-repeat'})
	$('.news .newswrap div.ph_list ul li:eq(1)').css({background:'url(../img/ph2.png) 8px center no-repeat'})
	$('.news .newswrap div.ph_list ul li:eq(2)').css({background:'url(../img/ph3.png) 8px center no-repeat'})


	$('.kefu ul li:not(".gotop")').hover(function(){
		$(this).stop().animate({right: '0'}, 300);
	},function(){
		$(this).stop().animate({right: '-70px'}, 300);
	})

	$('.gotop').click(function(){
		 $('body,html').stop().animate({scrollTop:0},500);
	})
	
	$('.select_page p').css({marginLeft:(1200-$('.select_page p').width())/2+'px'});
	$('.zx_list_page p').css({marginLeft:(930-$('.select_page p').width())/2+'px'});
	$('.us_center p').css({marginLeft:(885-$('.select_page p').width())/2+'px'});
	
	/*众筹详情页面缩略图封面效果*/

	$('.ctrl_play ul').css({width:$('.ctrl_play ul li').length*112+'px'})
	
	$('.ctrl_play ul li img').hover(function(){
	
		$(this).css({border:'1px solid #F9725E'});
		$('.imgplay .jqzoom img').attr({src: $(this).attr('src'), jqimg: $(this).attr('src')});
			
	},function(){
		$(this).css({border:'1px solid #ffffff'})
	})	

	$('.ctrl_play .play_prev').click(function(){
		
		var play_num = $('.ctrl_play').scrollLeft();

		play_num = play_num-112;

		$('.ctrl_play').scrollLeft(play_num);
	})

	$('.ctrl_play .play_next').click(function(){

		var play_num = $('.ctrl_play').scrollLeft();

		play_num = play_num+112;

		$('.ctrl_play').scrollLeft(play_num);
	})

	/*众筹详情页切换*/

	$('.item_mid .item_mid_tit ul li a').click(function(){
		
		$('.item_mid .item_mid_tit ul li a').removeAttr('id');

		$(this).attr('id','cur_item');

		var cur_index = $(this).parent('li').index();
		
		$('.item_mid_con div.car_con').hide();

		$('.item_mid_con div.car_con').eq(cur_index).fadeIn(100);

	})

	/*用户中心*/

	$('.uc_mid .uc_mid_l .uc_nav .uc_nav_li').click(function(){
		
		if($(this).has("ul").length != 0){
			if($(this).children("ul").css('display').search('none') == 0){
				$('.uc_mid .uc_mid_l .uc_nav .uc_nav_li').children("ul").stop().slideUp();
				$(this).children("ul").stop().slideDown();
			}else{
				$(this).children("ul").stop().slideUp();
			}
		}
		
	
	})

	$('.uc_mid .uc_mid_l .uc_nav .uc_nav_li ul li').click(function(e){
	
		if (e && e.stopPropagation) {//非IE浏览器 
		　　e.stopPropagation(); 
		} 
		else {//IE浏览器 
			window.event.cancelBubble = true; 
		} 
	})

	$('table tr:last td').css({borderBottom:'none'})

	$('.uc_mid_r .cz_cont ul li').click(function(){		
		$('.uc_mid_r .cz_cont ul li').css({border:'1px solid #e7e7eb'});
		$(this).css({border:'1px solid #24C56F'});
	})
	
	$('table tr td').hover(function(){
		
		$(this).find('div').stop().fadeIn(200);
		
	},function(){
		
		$(this).find('div').stop().fadeOut(10);
	})
	
	for(var i=0;i<$('table tr').length;i++){
		$('table tr:eq('+i+') td span.ad_text').text($('table tr:eq('+i+') td span.ad_text').text().substring(0,16)+'…');
	}

})

/*底部数据滚动展示*/
window.onload = function(){
	var zt = 1;
	$(window).scroll(function(){
	//$('.paihang').text($(window).scrollTop())
		if($(window).scrollTop() > 2110 && zt == 1){
			setTimeout(function(){			
				NumbersAnimate.Target=$(".textA");
				NumbersAnimate.Numbers=3297;
				NumbersAnimate.Duration=1500;
				NumbersAnimate.Animate();

				NumbersAnimate.Target=$(".textB");
				NumbersAnimate.Numbers=8465;
				NumbersAnimate.Duration=1500;
				NumbersAnimate.Animate();

				NumbersAnimate.Target=$(".textC");
				NumbersAnimate.Numbers=93293;
				NumbersAnimate.Duration=1500;
				NumbersAnimate.Animate();
			
			},100)
			zt = 0;	
		}
	})
}
var NumbersAnimate={
	Target:null,
	Numbers:0,
	Duration:500,
	Animate:function(){
		var array=NumbersAnimate.Numbers.toString().split("");
		//遍历数组
		for(var i=0;i<array.length;i++){
			var currentN=array[i];
			//数字append进容器
			var t=$("<span></span>");
			$(t).append("<span class=\"childNumber\">"+array[i]+"</span>");
			$(t).css("margin-left",30*i+"px");
			$(NumbersAnimate.Target).append(t);
			//生成滚动数字,根据当前数字大小来定
			for(var j=0;j<=currentN;j++){
				var tt;
				if(j==currentN){
					tt=$("<span class=\"main\"><span>"+j+"</span></span>");
				}else{
					tt=$("<span class=\"childNumber\">"+j+"</span>");
				}
				$(t).append(tt);
				$(tt).css("margin-top",(j+1)*50+"px");
			}
			$(t).animate({marginTop:-((parseInt(currentN)+1)*50)+"px"},NumbersAnimate.Duration,function(){
				$(this).find(".childNumber").remove();
			});
		}
	}
}
/*底部数据滚动展示结束*/

/*放大镜开始*/
$(function() {
	$(".jqzoom").jqueryzoom({
		xzoom: 400, //放大图的宽度(默认是 200)
		yzoom: 313, //放大图的高度(默认是 200)
		offset: 10, //离原图的距离(默认是 10)
		position: "right", //放大图的定位(默认是 "right")
		preload: 1
	});
});

(function ($) {
    $.fn.jqueryzoom = function (options) {
        var settings = {
            xzoom: 200,      
            yzoom: 200,    
            offset: 10,      
            position: "right",
            lens: 1,
            preload: 1
        };
        if (options)
        {
            $.extend(settings, options);
        }
        var noalt = '';
        $(this).hover(function () {
            var imageLeft = $(this).offset().left;
            var imageTop = $(this).offset().top;
            var imageWidth = $(this).children('img').get(0).offsetWidth;
            var imageHeight = $(this).children('img').get(0).offsetHeight;
            noalt = $(this).children("img").attr("alt");
            var bigimage = $(this).children("img").attr("jqimg");
            $(this).children("img").attr("alt", '');
            if ($("div.zoomdiv").get().length == 0)
            {
                $(this).after("<div class='zoomdiv'><img class='bigimg' src='" + bigimage + "'/></div>");
                $(this).append("<div class='jqZoomPup'>&nbsp;</div>");
            }
            if (settings.position == "right")
            {
                if (imageLeft + imageWidth + settings.offset + settings.xzoom > screen.width)
                {
                    leftpos = imageLeft - settings.offset - settings.xzoom;
                } else
                {
                    leftpos = imageLeft + imageWidth + settings.offset;
                }
            } else
            {
                leftpos = imageLeft - settings.xzoom - settings.offset;
                if (leftpos < 0)
                {
                    leftpos = imageLeft + imageWidth + settings.offset;
                }
            }
            $("div.zoomdiv").css({ top: 0, left: 510 });
            $("div.zoomdiv").width(settings.xzoom);
            $("div.zoomdiv").height(settings.yzoom);
            $("div.zoomdiv").show();
            if (!settings.lens)
            {
                $(this).css('cursor', 'crosshair');
            }
            $(document.body).mousemove(function (e) {
                mouse = new MouseEvent(e);
                /*$("div.jqZoomPup").hide();*/
                var bigwidth = $(".bigimg").get(0).offsetWidth;
                var bigheight = $(".bigimg").get(0).offsetHeight;
                var scaley = 'x';
                var scalex = 'y';
                if (isNaN(scalex) | isNaN(scaley))
                {
                    var scalex = (bigwidth / imageWidth);
                    var scaley = (bigheight / imageHeight);
                    $("div.jqZoomPup").width((settings.xzoom) / scalex);
                    $("div.jqZoomPup").height((settings.yzoom) / scaley);
                    if (settings.lens)
                    {
                        $("div.jqZoomPup").css('visibility', 'visible');
                    }
                }
                xpos = mouse.x - $("div.jqZoomPup").width() / 2 - imageLeft;
                ypos = mouse.y - $("div.jqZoomPup").height() / 2 - imageTop;
                if (settings.lens)
                {
                    xpos = (mouse.x - $("div.jqZoomPup").width() / 2 < imageLeft) ? 0 : (mouse.x + $("div.jqZoomPup").width() / 2 > imageWidth + imageLeft) ? (imageWidth - $("div.jqZoomPup").width() - 2) : xpos;
                    ypos = (mouse.y - $("div.jqZoomPup").height() / 2 < imageTop) ? 0 : (mouse.y + $("div.jqZoomPup").height() / 2 > imageHeight + imageTop) ? (imageHeight - $("div.jqZoomPup").height() - 2) : ypos;
                }
                if (settings.lens)
                {
                    $("div.jqZoomPup").css({ top: ypos, left: xpos });
                }
                scrolly = ypos;
                $("div.zoomdiv").get(0).scrollTop = scrolly * scaley;
                scrollx = xpos;
                $("div.zoomdiv").get(0).scrollLeft = (scrollx) * scalex;
            });
        }, function () {
            $(this).children("img").attr("alt", noalt);
            $(document.body).unbind("mousemove");
            if (settings.lens)
            {
                $("div.jqZoomPup").remove();
            }
            $("div.zoomdiv").remove();
        });
        count = 0;
        if (settings.preload)
        {
            $('body').append("<div style='display:none;' class='jqPreload" + count + "'>sdsdssdsd</div>");
            $(this).each(function () {
                var imagetopreload = $(this).children("img").attr("jqimg");
                var content = jQuery('div.jqPreload' + count + '').html();
                jQuery('div.jqPreload' + count + '').html(content + '<img src=\"' + imagetopreload + '\">');
            });
        }
    }
})(jQuery);
function MouseEvent(e) {
    this.x = e.pageX;
    this.y = e.pageY;
}
/*放大镜结束*/
/*倒计时*/
	$.extend($.fn,{
        fnTimeCountDown:function(d){
            this.each(function(){
                var $this = $(this);
                var o = {
                    hm: $this.find(".hm"),
                    sec: $this.find(".sec"),
                    mini: $this.find(".mini"),
                    hour: $this.find(".hour"),
                    day: $this.find(".day"),
                    month:$this.find(".month"),
                    year: $this.find(".year")
                };
                var f = {
                    haomiao: function(n){
                        if(n < 10)return "00" + n.toString();
                        if(n < 100)return "0" + n.toString();
                        return n.toString();
                    },
                    zero: function(n){
                        var _n = parseInt(n, 10);//解析字符串,返回整数
                        if(_n > 0){
                            if(_n <= 9){
                                _n = "0" + _n
                            }
                            return String(_n);
                        }else{
                            return "00";
                        }
                    },
                    dv: function(){
                        //d = d || Date.UTC(2050, 0, 1); //如果未定义时间，则我们设定倒计时日期是2050年1月1日
                        var _d = $this.data("end") || d;
                        var now = new Date(),
                            endDate = new Date(_d);
                        //现在将来秒差值
                        //alert(future.getTimezoneOffset());
                        var dur = (endDate - now.getTime()) / 1000 , mss = endDate - now.getTime() ,pms = {
                            hm:"000",
                            sec: "00",
                            mini: "00",
                            hour: "00",
                            day: "00",
                            month: "00",
                            year: "0"
                        };
                        if(mss > 0){
                            pms.hm = f.haomiao(mss % 1000);
                            pms.sec = f.zero(dur % 60);
                            pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
                            pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                            pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400)) % 30) : "00";
                            //月份，以实际平均每月秒数计算
                            pms.month = Math.floor((dur / 2629744)) > 0? f.zero(Math.floor((dur / 2629744)) % 12) : "00";
                            //年份，按按回归年365天5时48分46秒算
                            pms.year = Math.floor((dur / 31556926)) > 0? Math.floor((dur / 31556926)) : "0";
                        }else{
                            pms.year=pms.month=pms.day=pms.hour=pms.mini=pms.sec="00";
                            pms.hm = "000";
                            //alert('结束了');
                            return;
                        }
                        return pms;
                    },
                    ui: function(){
                        if(o.hm){
                            o.hm.html(f.dv().hm);
                        }
                        if(o.sec){
                            o.sec.html(f.dv().sec);
                        }
                        if(o.mini){
                            o.mini.html(f.dv().mini);
                        }
                        if(o.hour){
                            o.hour.html(f.dv().hour);
                        }
                        if(o.day){
                            o.day.html(f.dv().day);
                        }
                        if(o.month){
                            o.month.html(f.dv().month);
                        }
                        if(o.year){
                            o.year.html(f.dv().year);
                        }
                        setTimeout(f.ui, 1);
                    }
                };
                f.ui();
            });
        }
    });
/*倒计时结束*/