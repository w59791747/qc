<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class TongjimemberAction extends CommonAction{

    public  function index($b_time='',$e_time='',$from='',$status=''){
	   $obj = D('Member');
	   $map = array('closed'=>'0');
	   $from_list = D('Member')->_from_list();
	   if (!$b_time && !$e_time && !$status){
			$status = '1';
	   }
       if($b_time){
		    $s = strtotime($b_time);
			$map['b_time'] = $s;
			$this->assign('b_time',$b_time);
	   }
	   if($e_time){
		    $e = strtotime($e_time)+60*60*24;
			if($e>time()){
				$e = time();
				$e_time = date('Y-m-d');
			}
			$map['e_time'] = $e;
			$this->assign('e_time',$e_time);
	   }
	   if($from){
			$map['from'] = $from;
			$this->assign('from',$from);
			$title_from = $from_list[$from];
	   }
	   if(!$title_from){
			$title_from = '全部';
	   }
	   $title = $b_time.'-'.$e_time.$title_from."用户注册统计图";
	   if($status){
		    $m=date("m");$d=date("d");$y=date("Y");$w=date("w");
			$t = date("t", strtotime($y.'-'.($m-1)));
			if($status == 1){
				$s = mktime(0,0,0,$m,$d,$y);
				$e = time();
				$title = "本日".$title_from."用户注册统计图";
			}
			if($status == 2){
				$s = mktime(0,0,0,$m,$d-1,$y);
				$e = mktime(23,59,59,$m,$d-1,$y);
				$title = "昨日".$title_from."用户注册统计图";
			}
			if($status == 3){
				$s = mktime(0,0,0,$m,$d-2,$y);
				$e = time();
				$title = "最近三天".$title_from."用户注册统计图";
			}
			if($status == 4){
				$s = mktime(0,0,0,$m,$d-$w+1,$y);
				$e = time();
				$title = "本周".$title_from."用户注册统计图";
			}
			if($status == 5){
				$s = mktime(0,0,0,$m,$d-$w-6,$y);
				$e = mktime(23,59,59,$m,$d-$w,$y);
				$title = "上周".$title_from."用户注册统计图";
			}
			if($status == 6){
				$s = mktime(0,0,0,$m,1,$y);
				$e = time();
				$title = "本月".$title_from."用户注册统计图";
			}
			if($status == 7){
				$s = mktime(0,0,0,$m-1,1,$y);
				$e = mktime(23,59,59,$m-1,$t,$y);
				$title = "上月".$title_from."用户注册统计图";
			}

			if($status == 8){
				$s = mktime(0,0,0,$m,$d-6,$y);
				$e = time();
				$title = "最近七天".$title_from."用户注册统计图";
			}
		   
			$map['b_time'] = $s;
			$map['e_time'] = $e;
			$this->assign('b_time',date('Y-m-d',$s));
			$this->assign('e_time',date('Y-m-d',$e));
	   }
	   $this->assign('tongji',D('Tongjimember')->tongjiComm($map));
	   $this->assign('title',$title);
	   $this->assign('fromlist',$from_list);
       $this->display(); // 输出模板
    }

}
