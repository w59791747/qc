<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  MemberAction extends CommonAction {


	public function index()
	{
		$this->display(); 
	}

    public function info() 
	{
		$cate = D('Membercate')->fetchAll();
		$this->assign('cate', $cate); // 赋值数据集
		$Projectcate = D('Projectcate')->select();
		foreach($Projectcate as $k =>$v){
			$pcate[$v['cate_id']] = $v;
		}
		$this->assign('catelist', $pcate);
        $this->display(); 
    }

	public function mobile()
	{
		if ($this->isPost()) {
            $mobile = $this->_post('mobile');
            $yzm = $this->_post('yzm');
            if (empty($mobile) || empty($yzm))
                $this->chuangError('请填写正确的手机及手机收到的验证码！');
            $s_code = session($mobile);
            if ($yzm != $s_code)
                $this->chuangError('验证码不正确');
            $data = array(
                'uid' => $this->uid,
                'mobile' => $mobile
            );
            if (D('Member')->save($data)) {
				D('member')->updateCount($this->uid,'verify','2');
                $this->chuangSuccess('恭喜您通过手机认证', U('ucenter/member/mobile'));
            }
            $this->chuangError('更新数据失败！');
        } else {
			$this->display(); 
		}
	}

	 public function sendsms() {
        if (!$mobile = htmlspecialchars($_POST['mobile'])) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
        if (!isMobile($mobile)) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
        if ($user = D('Member')->where(array('mobile'=>$mobile))->find()) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'手机号码已经存在！'));
        }
        $randstring = session($_POST['mobile']);
        if (empty($randstring)) {
                $randstring = rand_string(6, 1);
                session($_POST['mobile'], $randstring);
        }
		$res = D('Sms')->sendSms('sms_code', $mobile, array('code' => $randstring));
        if(true===$res){
			 $this->ajaxReturn(array('status'=>'success','msg'=>'发送成功'));
		}else if(false===$res){
			 $this->ajaxReturn(array('status'=>'error','msg'=>'发送失败'));
		}else{
			 $this->ajaxReturn($res);
		}
    }


	public  function mail(){
		 $this->display();
	}

	public function sendemail() {
        $email = $this->_post('email');
        if (isEmail($email)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'];
            $uid = $this->uid;
            $local = array(
                'email' => $email,
                'uid' => $uid,
                'time' => NOW_TIME,
                'sig' => md5($uid . $email . NOW_TIME . C('AUTH_KEY'))
            );
            $link .=U('public/email', $local);
            D('Email')->sendMail('email_rz', $email, $this->_CONFIG['site']['sitename'] . '邮件认证', array('link' => $link));
        }
    }


	public function name()
	{
		
		if ($this->isPost()) {
           $data = $this->nameCheck();
		   $data['uid'] = $this->uid;
		   if(D('Memberdetail')->find($this->uid)){
				D('Memberdetail')->save($data);
		   }else{
			    $data['dateline'] = time();
				D('Memberdetail')->add($data);
		   }
		   $datas['uid'] = $this->uid;
		   $datas['audit'] = 0;
		   $datas['dateline'] = time();
		   D('Verify')->add($datas);
		   $obj = D('Verify');$Member = D('Member');
			
			$user = $Member->find($this->uid);
			if($user['verify']>=4){
					$obj->save(array('uid' => $this->uid, 'audit' => 1));
					$this->chuangSuccess('审核通过！', U('member/name'));
			}else{
				if(false !==$Member->updateCount($this->uid,'verify',4)){
					if($this->audit_comment($this->uid,'member','Verify','reg',$user['fenxiao_id'],'fenxiang_reg')){ 
						
						$obj->cleanCache();
						$this->chuangSuccess('审核通过！', U('member/name'));
					}
				}
			}
        } else {
			if(D('Verify')->find($this->uid)){
				$this->assign('renzheng', '1');
			}
			$this->display(); 
		}
	}

	 private function nameCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
        $data['realname'] = htmlspecialchars($data['realname']);
        if (!isAllChinese($data['realname'])) {
            $this->chuangError('真实姓名必须是汉字');
        }
        if (!isCreditNo($data['card'])) {
            $this->chuangError('身份证号码不正确');
        }
		
        return $data;
    }

	public function gold()
	{
	$Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   $map['from'] = 'gold';
	   $map['uid'] = $this->uid;
       $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberlog->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

	public  function price(){
       $Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
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
            $this->chuangSuccess('申请成功,等待审核', U('ucenter/member/price'));
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
		if($memebr['price']-$data['number']<0){
			 $this->chuangError('提现金额不能大于资金');
		}
		if ($memebr['xuni'] == 1) {
            $this->chuangError('该账号不能提现');
        }
		$data['number'] = -$data['number'];

        if (empty($data['card_id'])) {
            $this->chuangError('请选择取款银行');
        }

		$data['shouxufei'] = -$data['number']*$this->_CONFIG['integral']['shouxufei'];
		
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
		if ($data['number'] < 1) {
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
			$this->chuangSuccess('提交成功,确认支付信息', U('ucenter/member/pay',array('order_id'=>$order_id)));
		}

	}





	public  function goldpay()
	{
		$data = $this->_post('data', 'htmlspecialchars');
		if ($data['number'] < 0.01) {
            $this->chuangError('充值金额不能小于1元');
        }

		if (!D('Payment')->checkPayment($data['code'])) {
            $this->chuangError('支付方式不存在');
        }
		if($data['order_id']){
			$order_id = D('Order')->ordersave($data['order_id'],$this->uid,$data['number'],$data['code'],'gold');
		}else{
			$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'gold');
		}
		if(!$order_id){
			 $this->chuangError('支付失败');
		}else{
			$this->chuangSuccess('提交成功,确认支付信息', U('ucenter/member/pay',array('order_id'=>$order_id)));
		}

	}

	public  function pricepay()
	{
		$data = $this->_post('data', 'htmlspecialchars');
		if ($data['number'] < 0.01) {
            $this->chuangError('充值金额不能小于1元');
        }

		if (!D('Payment')->checkPayment($data['code'])) {
            $this->chuangError('支付方式不存在');
        }
		if($data['order_id']){
			$order_id = D('Order')->ordersave($data['order_id'],$this->uid,$data['number'],$data['code'],'price');
		}else{
			$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'price');
		}
		if(!$order_id){
			 $this->chuangError('支付失败');
		}else{
			$this->chuangSuccess('提交成功,确认支付信息', U('ucenter/member/pay',array('order_id'=>$order_id)));
		}

	}

	public function pay($order_id)
	{
		$order = D('Order')->find($order_id);
		$log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
			$this->assign('wx', '1');
		}
		$this->assign('payment', D('Payment')->getPayments());
		$this->assign('types', D('Payment')->getTypes());
		$this->assign('code', $log['payment']);
		$this->assign('number', $order['amount']/100);
		$this->assign('from', $order['type']);
		$this->assign('order_id', $order_id);
		$this->assign('log', $log);
		$this->display();	
	}

	
}
	