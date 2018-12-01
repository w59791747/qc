<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class OrderAction extends CommonAction {

  


	public function lists() {
        $obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'),'uid'=>$this->uid);
		$type = $_POST['type'];
		$status = $_POST['status'];
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);

		if($type){
			$map['type'] = $type;
			$this->assign('type', $type);
		}else{
			$this->assign('type', 0);
		}
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
		//var_dump($obj->getType());
		//var_dump($obj->status());echo "File:", __FILE__, ',Line:',__LINE__;exit;
        $this->display(); // 输出模板
    }

	public function orderdetail($order_id = 0)
	{
		$obj = D('Order');$Paymentlogs = D('Paymentlogs');
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
						D('Order')->check($type['project_id'],$type['fahuo']);
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
