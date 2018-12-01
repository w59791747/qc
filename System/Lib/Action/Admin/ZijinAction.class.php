<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ZijinAction extends CommonAction{  
    public  function index(){
		$Projectchushou = D('Projectchushou');
		$obj = D('Project');$Paymentlogs = D('Paymentlogs');$Member = D('Member');
		$map['closed'] =  '0';
		$map['status'] = '5';
		$project = $obj->where($map)->select();
		$num1= $num2= $num3= $num4= $num5=0;
		foreach($project as $k => $v){
			$chushou = '';
			$chushou = $Projectchushou->where(array('project_id'=>$v['project_id'],'is_chenggong'=>1))->find();
			$num1 += ($chushou['amount']-$v['fund_raising'])/100*(100-$v['fenhong'])/100;
		}

		$plogs = D('Paymentlogs')->where(array('from'=>'gold','payed'=>'1'))->select();

		foreach($plogs as $k => $v){
			
			$num2 += $v['amount']/100;
		}

		$map2['closed'] =  '0';
		$map2['status'] = '-2';
		$project2 = $obj->where($map2)->select();
		foreach($project2 as $k => $v){
			$num3 += ($v['huigou']-$v['fund_raising'])/100*$v['fenhong']/100;
		}

		$plogs2 = D('Paymentlogs')->where(array('from'=>'crowd','payed'=>'1'))->select();
		foreach($plogs2 as $k => $v){
			
			$num4 += $v['jifen']/100;
		}

		$member = $Member->where(array('gold'=>array('gt',0)))->select();
		foreach($member as $k => $v){
			
			$num5 += $v['gold'];
		}
		$this->assign('num1',$num1);
		$this->assign('num2',$num2);
		$this->assign('num3',$num3);
		$this->assign('num4',$num4);
		$this->assign('num5',$num5);



      
       $this->display(); // 输出模板
    }
}

