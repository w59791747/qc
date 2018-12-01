<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class PaymentlogsAction extends CommonAction{


    
    public  function index(){
       $Paymentlogs = D('Paymentlogs');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       if(($bg_date = $this->_param('bg_date',  'htmlspecialchars') )&& ($end_date=$this->_param('end_date','htmlspecialchars'))){
           $bg_time = strtotime($bg_date);
           $end_time = strtotime($end_date);
           $map['create_time|pay_time'] = array(array('ELT',$end_time),array('EGT',$bg_time));
           $this->assign('bg_date',$bg_date);
           $this->assign('end_date',$end_date);
       }else{
           if($bg_date = $this->_param('bg_date',  'htmlspecialchars')){
               $bg_time = strtotime($bg_date);
               $this->assign('bg_date',$bg_date);
               $map['create_time|pay_time'] = array('EGT',$bg_time);
           }
           if($end_date = $this->_param('end_date',  'htmlspecialchars')){
               $end_time = strtotime($end_date);
               $this->assign('end_date',$end_date);
               $map['create_time|pay_time'] = array('ELT',$end_time);
           }
       }
       
       $count      = $Paymentlogs->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Paymentlogs->where($map)->order(array('log_id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   foreach($list as $k =>$v){
			$members[$v['uid']] = $v['uid'];
	   }
	   $member = D('Member')->itemsByIds($members);
	   $this->assign('member',$member);// 用户集合
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }




    
   
}
