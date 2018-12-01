<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  MemberAction extends CommonAction {

   
	

	public  function price($status=0){
       $Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   if($status == 1){
		 $map['number'] = array('GT','0');
	   }elseif($status == 2){
	    $map['number'] = array('LT','0');
	   }
	   $map['from'] = 'price';
	   $map['uid'] = $this->uid;
       $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberlog->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   foreach($list as $k =>$v){
			$l[$k] = $Memberlog->_format_row($v);
	   }
       $this->assign('list',$l);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
	   $this->assign('status',$status);
       $this->display(); // 输出模板
    }

	public  function pricedown()
	{
        if ($this->isPost()) {
			$data = $this->downCheck();
		    $data['uid'] = $this->uid;
			D('member')->updateCount($data['uid'],'dong_price',-$data['number']);
			D('member')->updateCount($data['uid'],'price',$data['number']);
            D('Memberlog')->add($data);

			$member = D('member')->find($this->uid);
			$mobile = $member['mobile'];
			$res = D('Sms')->sendSms('sms_tixian', $mobile, array('price' => -$data['number']/100));
            $this->chuangSuccess('申请成功,等待审核', U('member/price'));
        } else {
			$Membercard = D('Membercard');
			$map['uid'] = $this->uid;
			$card = $Membercard->where($map)->select();
			$this->assign('card',$card);// 赋值分页输出
            $this->display();
        }
	}

	private function downCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
        $data['number'] = abs($data['number']*100);
		$keyu = $this->_CONFIG['integral']['min']*100;

        if ($data['number'] < $keyu) {
            $this->chuangError('提现金额不能小于'.$this->_CONFIG['integral']['min'].'元');
        }
		$memebr = D('member')->find($this->uid);
		
		if ($memebr['xuni'] == 1) {
            $this->chuangError('该账号不能提现');
        }
		if($memebr['price']-$data['number']<0){
			 $this->chuangError('提现金额不能大于资金');
		}
		$data['number'] = -$data['number'];

        if (empty($data['card_id'])) {
            $this->chuangError('请选择取款银行');
        }
		

		$data['shouxufei'] = $this->_CONFIG['integral']['shouxufei'];
		
		$data['from'] = 'price';
		$data['status'] = 1;
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	

	public  function priceup($order_id=0)
	{
		if($order_id){
			$this->assign('order', D('Order')->find($order_id));
		}
		$this->assign('payment', D('Payment')->getPayments());
        $this->display('priceup');	
	}

	public  function priceup2($order_id=0)
	{
		if($order_id){
			$this->assign('order', D('Order')->find($order_id));
		}
		$this->assign('payment2', D('Payment2')->getPayments());

        $this->display();	
	}

	public  function goldup($order_id=0)
	{
		if($order_id){
			$this->assign('order', D('Order')->find($order_id));
		}
		$this->assign('payment', D('Payment')->getPayments());
        $this->display('goldup');	
	}

	public function orderpay($order_id=0)
	{
		if(!$order_id){
			$this->error('请选择要支付的订单');
		}else{
			$order = D('Order')->find($order_id);
		}
		if($order['uid'] != $this->uid){
			$this->error('您没有权限支付该订单');
		}else{
			if($order['type'] == 'gold'){
				$this->goldup($order_id);
			}else if($order['type'] == 'price'){
				$this->priceup($order_id);
			}else if($order['type'] == 'crowd'){
				$this->crowdup($order_id);
			}
		}

	}

	
	public function crowdup($order_id=0,$project_id=0)
	{
		if($order_id){
			$order = D('Order')->find($order_id);
			$this->assign('order', $order);
		}
		$log = D('Paymentlogs')->where(array('order_id'=>$order_id))->find();
		$this->assign('log', $log);
		$this->assign('payment', D('Payment')->getPayments());
		$Goodslist = D('Goodslist');
		$lists = $Goodslist->find($log['product_id']);
		$project = D('Project')->find($lists['project_id']);
		$shen = ($project['fund_raising']-$project['have_price']);
		if($shen<$order['amount']){
			$this->error('该众筹剩余资金不足');
		}else{
			$this->assign('project',$project);
			$this->display('crowdup');
		}
	}

	public  function crowdpay()
	{
		$data = $this->_post('data', 'htmlspecialchars');
		if ($data['number'] < 0.01) {
            $this->chuangError('充值金额不能小于1元');
        }

		if (!D('Payment')->checkPayment($data['code'])) {
            $this->chuangError('支付方式不存在');
        }
		if($data['order_id']){
			$order_id = D('Order')->ordersave($data['order_id'],$this->uid,$data['number'],$data['code'],'crowd',$data['project_id'],$data['jifen'],$data['actual_pay']);
		}else{
			$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'crowd',$data['project_id'],$data['jifen'],$data['actual_pay']);
		}
		if(!$order_id){
			 $this->chuangError('支付失败');
		}else{
			$this->chuangSuccess('提交成功,确认支付信息', U('member/pay',array('order_id'=>$order_id)));
		}

	}


	

	

	public  function pricepay()
	{
		$data = $this->_post('data', 'htmlspecialchars');
		if ($data['number'] < 0.01) {
            $this->chuangError('充值金额不能小于1元');
        }

		if (!D('Payment')->checkPayment($data['code']) && !D('Payment2')->checkPayment($data['code'])) {
            $this->chuangError('支付方式不存在');
        }
		if($data['type'] == '2'){
			if($data['order_id']){
				$order_id = D('Order')->ordersave($data['order_id'],$this->uid,$data['number'],$data['code'],'price','','','',2);
			}else{
				$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'price','','','',2);
			}
		}else{
			if($data['order_id']){
				$order_id = D('Order')->ordersave($data['order_id'],$this->uid,$data['number'],$data['code'],'price');
			}else{
				$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'price');
			}
		}
		if(!$order_id){
			 $this->chuangError('支付失败');
		}else{
			if($data['type'] == '2'){
				$this->chuangSuccess('提交成功,确认支付信息', U('member/pay2',array('order_id'=>$order_id)));

			}else{
				$this->chuangSuccess('提交成功,确认支付信息', U('member/pay',array('order_id'=>$order_id)));
			}
		}

	}

	public function pay2($order_id)
	{
		$order = D('Order')->find($order_id);
		$log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
		$this->assign('payment', D('Payment2')->getPayments());
		$this->assign('types', D('Payment')->getTypes());
		$this->assign('code', $log['payment']);
		$this->assign('number', $order['amount']/100);
		$this->assign('from', $order['type']);
		$this->assign('order_id', $order_id);
		$this->assign('log', $log);
		$this->assign('order', $order);
		$this->display();	
	}

	public function pay($order_id)
	{
		$order = D('Order')->find($order_id);
		$log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
		$this->assign('payment', D('Payment')->getPayments());
		$this->assign('types', D('Payment')->getTypes());
		$this->assign('code', $log['payment']);
		$this->assign('number', $order['amount']/100);
		$this->assign('from', $order['type']);
		$this->assign('order_id', $order_id);
		$this->assign('log', $log);
		if($log['payment'] == 'shuangqian' || $log['payment'] == 'zhifu'){
			$payment_arr = D('Payment')->get_order($log['payment'], $order);
			$this->assign('payment_arr', $payment_arr);
		}
		$this->display();	
	}




	public function order() {
        $obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'),'uid'=>$this->uid);

		$status = $_POST['status'];
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		$map['type'] = 'price';
		
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
			
			if(false !== strpos($_SERVER['HTTP_REFERER'],'project')){
				 $this->chuangSuccess('取消成功！', U('project/order'));
			}else{
				$this->chuangSuccess('取消成功！', U('member/order'));
			}
        }
    }

	

	
}
	