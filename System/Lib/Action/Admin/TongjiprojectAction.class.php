<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class TongjiprojectAction extends CommonAction{

    public  function index($b_time='',$e_time='',$d_b_time='',$type='',$status=''){
	   $obj = D('Project');
	   $map = array();
	   $type_list = D('Project')->type_list();
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
	   if($d_b_time){
		    $d_s = strtotime($d_b_time);
			$d_e = $d_s+$e-$s;
			$map['d_b_time'] = $d_s;
			$map['d_e_time'] = $d_e;
			$d_e_time = date('Y-m-d',$d_e);
			$this->assign('d_b_time',$d_b_time);
			$this->assign('d_e_time',$d_e_time);
	   }
	   
	   if(!$title_type){
			$title_type = '全部';
	   }
	   $title = $b_time.'到'.$e_time.$title_type."项目统计图";
	   if($status){
		    $m=date("m");$d=date("d");$y=date("Y");$w=date("w");
			$t = date("t", strtotime($y.'-'.($m-1)));
			if($status == 1){
				$s = mktime(0,0,0,$m,$d,$y);
				$e = time();
				$title = "本日".$title_type."项目统计图";
			}
			if($status == 2){
				$s = mktime(0,0,0,$m,$d-1,$y);
				$e = mktime(23,59,59,$m,$d-1,$y);
				$title = "昨日".$title_type."项目统计图";
			}
			if($status == 3){
				$s = mktime(0,0,0,$m,$d-2,$y);
				$e = time();
				$title = "最近三天".$title_type."项目统计图";
			}
			if($status == 4){
				$s = mktime(0,0,0,$m,$d-$w+1,$y);
				$e = time();
				$title = "本周".$title_type."项目统计图";
			}
			if($status == 5){
				$s = mktime(0,0,0,$m,$d-$w-6,$y);
				$e = mktime(23,59,59,$m,$d-$w,$y);
				$title = "上周".$title_type."项目统计图";
			}
			if($status == 6){
				$s = mktime(0,0,0,$m,1,$y);
				$e = time();
				$title = "本月".$title_type."项目统计图";
			}
			if($status == 7){
				$s = mktime(0,0,0,$m-1,1,$y);
				$e = mktime(23,59,59,$m-1,$t,$y);
				$title = "上月".$title_type."项目统计图";
			}

			if($status == 8){
				$s = mktime(0,0,0,$m,$d-6,$y);
				$e = time();
				$title = "最近七天".$title_type."项目统计图";
			}
		   
			$map['b_time'] = $s;
			$map['e_time'] = $e;
			$b_time = date('Y-m-d',$s);
			$e_time = 	date('Y-m-d',$e);
			$this->assign('b_time',date('Y-m-d',$s));
			$this->assign('e_time',date('Y-m-d',$e));
	   }
	   $this->assign('tongji',D('Tongjiproject')->tongjiComm($map));
	   $this->assign('title',$title);
	   $this->assign('new',array('1'=>$b_time.'至'.$e_time,'2'=>$d_b_time.'至'.$d_e_time));
	   $this->assign('type_list',$type_list);
		
	  
       $this->display(); // 输出模板
    }

}
