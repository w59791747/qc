<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class YuyueAction extends CommonAction {


	
	//我的预约
    public function lists2() {
           $Memberyuyue = D('Memberyuyue');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['uid'] = $this->uid;
		   $count      = $Memberyuyue->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Memberyuyue->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'yuyue_uid','Member','memberlist');
		   $this->itemsby($list,'hatch_id','Hatch','hatchlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('page',$show);// 赋值分页输出
		   $this->display(); // 输出模板
    }


	// 我被预约
	public function lists() {
           $Memberyuyue = D('Memberyuyue');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['yuyue_uid'] = $this->uid;
		   $map['audit'] = 1;
		   $count      = $Memberyuyue->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Memberyuyue->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('page',$show);// 赋值分页输出
		   if($this->member['from'] == 'hatch'){
				$this->itemsby($list,'hatch_id','Hatch','hatchlist');
		   }
		   $this->display(); // 输出模板
    }

	public function detail($yuyue_id)
	{
		 $obj = D('Memberyuyue');
		 if (!$detail = $obj->find($yuyue_id)) {
			$this->error('请选择要查看的预约信息',U('yuyue/lists'));
		 }else if($detail['yuyue_uid'] != $this->uid){
			$this->error('您没有权限查看该预约',U('yuyue/lists'));
		 }else if($detail['audit'] == '0'){
			$this->error('该项目正在审核中',U('yuyue/lists'));
		 }else{
			if ($this->isPost()) {
				if($detail['gold']>0 && $detail['is_pay'] == 0){
					if($detail['gold']>$this->member['gold']){
						$this->chuangError('积分不足,请先充值');
					}else{
						if(false !==D('Member')->updateCount($this->uid,'gold',-$detail['gold'])){
							$datas['uid'] = $this->uid;
							$datas['from'] = 'gold';
							$datas['number'] = -$detail['gold'];
							$datas['log'] = '查看预约扣除积分';
							$datas['status'] = '1';
							$datas['clientip'] = get_client_ip();
							$datas['dateline'] = NOW_TIME;
							$log_id = D('Memberlog')->add($datas);

							$data['yuyue_id'] = $yuyue_id;
							$data['is_pay'] = 1;
							$obj->save($data);
							$this->chuangSuccess('支付成功',U('yuyue/detail',array('yuyue_id'=>$yuyue_id)));
						}else{
							$this->chuangError('支付失败');
						}
					}
				}else{
					$this->chuangError('该预约不需要支付积分或已经支付过了');
				}
				
			} else {
				$this->assign('detail', $detail);
				$this->assign('member', D('Member')->find($detail['uid']));
				$this->display();
			}
		 }
       
	}

	public function yuyuedelete($yuyue_id)
	{
		$obj =D('Memberyuyue');
		if(!$detail = $obj->find($yuyue_id)){
			 $this->chuangError('请选择要删除的预约');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限删除该预约');
		}else{
             $obj->delete($yuyue_id);
             $this->chuangSuccess('删除成功！',U('yuyue/lists2'));
         }
         $this->chuangError('删除失败!');
	}
}
