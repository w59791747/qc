<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class OrderAction extends CommonAction {

   private $create_adress = array('addr','contact','default','phone','city');
    private $edit_adress = array('addr','contact','default','phone','city','citys');
    

	public function index()
	{
		$this->display(); 
	}
    public  function adress(){
       $Memberaddr = D('Memberaddr');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   $map['uid'] = $this->uid;
       $count      = $Memberaddr->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberaddr->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
   

    public function adresscreate() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Memberaddr');
			$data['uid'] = $this->uid;
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('order/adress'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

	private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_adress);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		
        if (empty($data['city']) || $data['city'] == '城市') {
            $this->chuangError('请选择城市');
        }
		$is_default = D('Memberaddr')->where(array('default'=>'1','uid'=>$this->uid))->find();
		if(!$is_default){
			$data['default'] = 1;
		}
		$data['dateline'] = time();
        return $data;
    }



	public function adressedit($addr_id = 0) {
		 $obj = D('Memberaddr');
		 if (!$detail = $obj->find($addr_id)) {
			$this->error('请选择要编辑的地址');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['addr_id'] = $addr_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('order/adress'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('member', D('member')->find($detail['uid']));
			$this->assign('detail', $detail);
            $this->display();
        }
    }

	private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_adress);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		if ($data['city'] == '城市' && $data['citys'] == '') {
            $this->chuangError('城市不能为空');
        }elseif($data['city'] == '城市' && $data['citys']){
			unset($data['city']);
		}else{
			unset($data['citys']);
		}
		
        return $data;
    }

	public function set_default($addr_id = 0)
	{
		 $obj = D('Memberaddr');
		 if (!$detail = $obj->find($addr_id)) {
			$this->chuangError('请选择要编辑的地址');
		 }else{
			$is_default = $obj->where(array('default'=>'1','uid'=>$this->uid))->find();
			$data['addr_id'] = $addr_id;
			$data['default'] = 1;
			if ($obj->save($data)) {
				$datas = array('default'=>'0','addr_id'=>$is_default['addr_id']);
				$obj->save($datas);
				$this->chuangSuccess('设置成功', U('order/adress'));
			}
		 }
	}
    
    public function adressdelete($addr_id = 0){
		 $obj =D('Memberaddr');
		 $addr = $obj->find($addr_id);
		 if(!$detail = $obj->find($addr_id)){
			 $this->chuangError('请选择要删除的收货地址');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限删除该收货地址');
		}else{
			 if($detail['default'] == 1){
				$this->chuangError('默认地址不能删除,请先重新设置默认地址!');
			 }else{
				 $obj->delete($addr_id);
				 $obj->cleanCache();
				 $this->chuangSuccess('删除成功！',U('order/adress'));
			 }
         }
    }


	public function lists() {
        $obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'),'uid'=>$this->uid);
        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
			$lists[$k] = $obj->_format_row($val);
        }
        $this->assign('list', $lists); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('typelist', $obj->getType());
		$this->assign('statuslist', $obj->status());
        $this->display(); // 输出模板
    }

	public function orderdetail($order_id = 0)
	{
		$obj = D('Order');
        if ($order_id = (int) $order_id) {
            if (!$detail = $obj->find($order_id)) {
                $this->error('请选择要查看的订单',U('order/lists'));
            }elseif($detail['uid'] != $this->uid){
				 $this->error('您没有权限查看该订单',U('order/lists'));
			}
			if ($this->isPost()) {
			
			}else{
				$this->assign('detail', $obj->_format_row($detail));
				$this->assign('typelist', $obj->getType());
				$this->assign('statuslist', $obj->status());
				if($detail['addr_id']>0){
					$this->assign('addr', D('Memberaddr')->find($detail['addr_id']));
				}
				if($detail['type'] == 'crowd'){
					$logs = D('Paymentlogs')->where(array('order_id'=>$order_id))->find();
					$list = D('Goodslist')->find($logs['product_id']);
					$type = D('Goodstype')->find($list['type_id']);
					$project = D('Project')->find($list['project_id']);

					//设置自动收货(20天)
						D('Order')->check($type['project_id']);

					$this->assign('list', $list);
					$this->assign('project', $project);
					$this->assign('type', $type);
				}
				$this->display();
			}
            
        } else {
            $this->error('请选择要查看的订单');
        }
	}

    public function orderdelete($order_id = 0) {
		$obj = D('Order');
        if(!$detail = $obj->find($order_id)){
			 $this->chuangError('请选择要取消的订单');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限查看该订单');
		}else{
			$data['order_id'] = $order_id;
			$data['closed'] = '-1';
            $obj->save($data);
            $this->chuangSuccess('取消成功！', U('order/lists'));
        }
    }

	public function tousu_c($log_id=0)
	{
		$this->assign('log_id', $log_id);
		$this->display();
	}


	public  function tousu()
	{
		$obj =D('Paymentlogs');
		$data = $this->_post('data', 'htmlspecialchars');
		$log = $obj->find($data['log_id']);
		if(!$log){
			 $this->chuangError('该订单不存在');
		}elseif($log['uid'] != $this->uid){
			 $this->chuangError('您没有权限投诉');
		}elseif($log['status']>2 || $log['status']<1){
			 $this->chuangError('该订单状态不正确');
		}elseif(!$data['content']){
			 $this->chuangError('内容不能为空');
		}else{
			D('Paymentlogs')->where(array('log_id'=>$data['log_id']))->save(array('status'=>'-1','content'=>$data['content']));
			 $this->chuangSuccess('投诉成功', U('order/orderdetail',array('order_id'=>$log['order_id'])));
        }
	}

	public  function shouhuo($log_id = 0)
	{
		$obj =D('Paymentlogs');
		$log = $obj->find($log_id);
		if(!$log){
			 $this->chuangError('该订单不存在');
		}elseif($log['uid'] != $this->uid){
			 $this->chuangError('您没有权限设置');
		}elseif($log['status'] != 2 && $log['status'] != '-1'){
			 $this->chuangError('该订单状态不正确');
		}else{
			D('Paymentlogs')->where(array('log_id'=>$log_id))->save(array('status'=>'3'));
			 $this->chuangSuccess('确认收货成功', U('order/orderdetail',array('order_id'=>$log['order_id'])));
        }
	}

}
