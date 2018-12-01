<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProjectAction extends CommonAction{

	private $create_fields = array('title','name','mobile','huibao','type','city');


	

 
	public function check()
	{  
		$obj = D('Project');
		$obj->change_status();
		if($this->member['verify'] <6){
			$this->error('请通过实名认证和手机认证后，查看参与项目！',U('set/renzheng'));
		}
	}


	public  function order()
	{
		 $obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'),'uid'=>$this->uid);

		$status = $_POST['status'];
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		$map['type'] = 'crowd';
		
		if($status){
			$map['pay_status'] = $status-2;
			$this->assign('status', $status-2);
		}else{
			$this->assign('status', 0);
		}
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
			$lists[$k] = $obj->_format_row($val);
        }
        $this->assign('list', $lists); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('typelist', $obj->getType());
		$this->assign('statuslist', $obj->status());
		//var_dump($obj->getType());
		//var_dump($obj->status());echo "File:", __FILE__, ',Line:',__LINE__;exit;
        $this->display(); // 输出模板
	}

	public function hetong($list_id)
	{
		$obj = D('Goodslist');
		if(!$list = $obj->find($list_id)){
			$this->error('该订单不存在');
		}else if($list['is_pay'] != 1){
			$this->error('该订单状态错误');
		}else{
			$project = D('Project')->find($list['project_id']);
			$member = D('Memberdetail')->find($list['uid']);
			$cheshang = D('Cheshang')->find($project['cheshang_id']);
			$this->assign('canshu', htmlspecialchars_decode($project['canshu']));
			$this->assign('member', $member);
			$d['y'] = date('Y',$list['dateline']);
			$d['m'] = date('m',$list['dateline']);
			$d['d'] = date('d',$list['dateline']);
			$this->assign('cheshang', $cheshang);
			$this->assign('d', $d);
			
		}
		$this->display();
	}
	

	//已参与的众筹


	public function crowd_canyu()
	{
		$this->check();
		$Project = D('Project');
		import('ORG.Util.Page');// 导入分页类
	    $map['uid'] =  $this->uid;
		$map['is_pay'] = '1';

		$status = $_POST['status'];
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		
		if($status){
			$arr_s= $Project->where(array('status'=>$status-3))->select();
			foreach($arr_s as $k => $v){
				$arr_p[$v['project_id']] = $v['project_id'];
			}
			$map['project_id'] = array('IN',implode(',',$arr_p));
		}
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}

		$count      = D('Goodslist')->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = D('Goodslist')->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		foreach($list as $k => $v){
			$arr[$v['list_id']] = $v['list_id'];
			$arr2[$v['project_id']] = $v['project_id'];
		}
		$m1['product_id']= array('IN',implode(',',$arr));
		$m1['payed']= 1;
		$m1['from']= 'crowd';
		$m2['project_id'] = array('IN',implode(',',$arr2));
		
		$p = $Project->where($m2)->select();
		$o = D('Paymentlogs')->where($m1)->select();
		$this->assign('order',$this->arrkey($o,'product_id'));
		$this->assign('project',$this->arrkey($p,'project_id'));
		$this->assign('page',$show);
		$this->assign('status',$status);
		$status_list = D('Project')->status3();
		$this->assign('status_list', $status_list);


		//统计

		 $map2['uid'] =  $this->uid;
		 $map2['is_pay'] = '1';

		 $tongji = D('Goodslist')->where($map2)->select();
		 $count1 = count($tongji);

	  
		$m=date("m");$d=date("d");$y=date("Y");
		
		$s = mktime(0,0,0,$m,$d-6,$y);
		$e = time();
		$s1 = mktime(0,0,0,$m,$d-29,$y);
		$count2 = $count3 = 0;
		foreach($tongji as $k => $v){
			if($v['dateline'] > $s){
				$count2++;
			}
			if($v['dateline'] > $s1){
				$count3++;
			}
			$count4 += $v['price'];
		}
		$this->assign('count1', $count1);
		$this->assign('count2', $count2);
		$this->assign('count3', $count3);
		$this->assign('count4', $count4);
		



		$this->display();
	}

	public function  toupiao($project_id,$status=0)
	{
		$obj = D('Project');$Projectchushou = D('Projectchushou');
		if(!$project = $obj->find($project_id)){
			$this->error('该项目不存在');
		}else if($project['status'] != 3){
			$this->error('状态错误');
		}else{
			if ($status) {
				$Goodslist = D('Goodslist');
				$list = $Goodslist->where(array('project_id'=>$project_id,'uid'=>$this->uid,'is_pay'=>'1','status'=>'0'))->select();
				if(!$list){
					$this->chuangError('您未参与或已投票过,不能投票');
				}else{
					foreach($list as $k => $v){
						$lists[$v['list_id']] = $v['list_id'];
					}
					$Goodslist->where(array('list_id'=>array('IN',implode(',',$lists))))->save(array('status'=>$status));

					$count = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->count();
					$count1 = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'1'))->count();
					$count2 = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'-1'))->count();

					if($count1/$count*100>=$project['tpbfb']){
						$obj->where(array('project_id'=>$project_id))->save(array('status'=>'4'));
						$Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'0'))->save(array('status'=>1));

					}elseif($count2/$count*100>=$project['tpbfb']){
						$obj->where(array('project_id'=>$project_id))->save(array('status'=>'2'));
						$Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->save(array('status'=>0));
					}
					
					$this->chuangSuccess('投票成功',U('project/crowd_canyu'));
				}
			}else{
					$chushou = $Projectchushou->where(array('project_id'=>$project_id))->find();
					$this->assign('chushou',$chushou);
					$this->assign('project',$project);
					//投票计数
					$zancheng = D('Goodslist')->where(array('project_id'=>$project_id,'status'=>'1'))->count();
					$fandui = D('Goodslist')->where(array('project_id'=>$project_id,'status'=>'-1'))->count();
					$this->assign('zancheng', $zancheng);	$this->assign('fandui', $fandui);	
					$this->display();
				}
		}
	}

	public  function shouyi()
	{
		
		$this->check();
		$Project = D('Project');
		import('ORG.Util.Page');// 导入分页类
	    $map['uid'] =  $this->uid;
		$map['is_pay'] = '1';
		$map['shouyi'] = array('gt','0');

		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		
		
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}

		$count      = D('Goodslist')->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = D('Goodslist')->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集

		foreach($list as $k => $v){
			$arr[$v['list_id']] = $v['list_id'];
			$arr2[$v['project_id']] = $v['project_id'];
		}
		$m1['product_id']= array('IN',implode(',',$arr));
		$m1['payed']= 1;
		$m1['from']= 'crowd';
		$m2['project_id'] = array('IN',implode(',',$arr2));
		
		$p = $Project->where($m2)->select();
		$o = D('Paymentlogs')->where($m1)->select();
		$this->assign('order',$this->arrkey($o,'product_id'));
		$this->assign('project',$this->arrkey($p,'project_id'));
		$this->assign('page',$show);


		
		//统计

		 $map2['uid'] =  $this->uid;
		 $map2['is_pay'] = '1';

		 $tongji = D('Goodslist')->where($map2)->select();
		

	  
		$m=date("m");$d=date("d");$y=date("Y");
		
		$s = mktime(0,0,0,$m,$d-29,$y);
		$e = time();
		$s1 = mktime(0,0,0,$m,$d-89,$y);
		$count1 = $count2 = $count3 = $num1 = $num2 = 0;
		foreach($tongji as $k => $v){
			if($v['dateline'] > $s){
				$count2 += $v['price'];
			}
			if($v['dateline'] > $s1){
				$count3 += $v['price'];
			}
			$count1 += $v['shouyi'];

			if($v['shouyi']){
				$num1 += $v['shouyi'];
				$num2 += $v['price'];
			}
		}
		//var_dump($tongji);echo "File:", __FILE__, ',Line:',__LINE__;exit;
		$this->assign('count1', $count1/100);
		$this->assign('count2', $count2/100);
		$this->assign('count3', $count3/100);
		$this->assign('count4', ceil(($num1-$num2)/$num2*100));
		$this->display();
	}

}
			