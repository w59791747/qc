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
			$this->error('请验证后再发布项目',U('ucenter/member/name'));
		}
	}

	

	//已参与的众筹


	public function crowd_canyu()
	{
		$this->check();
		$Project = D('Project');
		import('ORG.Util.Page');// 导入分页类
	    $map['uid'] =  $this->uid;
		$map['is_pay'] = '1';

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
		$status_list = D('Project')->status2();
		$this->assign('status_list', $status_list);
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



}
			