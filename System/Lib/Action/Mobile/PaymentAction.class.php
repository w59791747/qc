<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PaymentAction extends CommonAction {

    public function index()
    {
        $from = $this->_get('from');
		$code = $this->_get('code');
		if($from == 'return'){
			$this->return_verify($code);
		}elseif($from == 'notify'){
			$this->notify_verify($code);
		}
		
    }

    public function return_verify($code)
    {
		$Setting = D('Setting')->fetchAll();
        $forward =  $Setting['site']['host'].U('ucenter/order/lists');
		
        if($obj = D('Payment')->loadPayment($code)){
			  if($trade = $obj->return_verify()){
			
                 if(!$log = D('Paymentlogs')->getLogsByOrderno($trade['trade_no'])){
					 $this->error('支付的订单不存在', $forward);
                }else if(D('Paymentlogs')->set_payed($log['log_id'])){
					if($log['payed'] == '1'){
							
							$this->success('该订单支付成功', $forward);
						}else{
							D('Paymentlogs')->set_payed($log['log_id']);
							D('Order')->set_payed($log, $trade);
							$order = D('Order')->find($log['order_id']);
							D('Memberlog')->change_status($order,$code);
							
							if($log['from'] == 'crowd'){
								$Goodslist = D('Goodslist');
								$where['uid'] = $log['uid'];
								$where['list_id'] = $log['product_id'];
								$data['is_pay'] = 1;
								$data['pay_time'] = NOW_TIME;
								$Goodslist->where($where)->save($data);
								$lists = $Goodslist->find($log['product_id']);

								D('Project')->updateCount($lists['project_id'],'have_num',1);
								D('Project')->updateCount($lists['project_id'],'have_price',$log['amount']);
								$Project_detail = D('Project')->find($lists['project_id']);
								if($Project_detail['have_price'] >=  $Project_detail['fund_raising']){
									D('Project')->where(array('project_id'=>$lists['project_id']))->save(array('status'=>'2'));
									$lists_goods = $Goodslist->where(array('project_id'=>$lists['project_id'],'is_pay'=>'1'))->select();
									foreach($lists_goods as $k => $v){
										$member = D('Member')->find($v['uid']);
										D('Sms')->sendSms('sms_chouzi', $member['mobile'], array('title'=>$Project_detail['title']));
									}
								}

								
							}
						$this->success('该订单支付成功', $forward);
					}
                }
            }else{
				$this->error('支付签名失败', $forward);	
            }
            $this->error('支付失败', $forward);
        }
    }

	

    public function notify_verify($code)
    {
		$success = false;
				
		  if($obj = D('Payment')->loadPayment($code)){
			 
			if($trade = $obj->notify_verify2()){
				
				if(!$log = D('Paymentlogs')->getLogsByOrderno($trade['trade_no'])){
					 $success = false;
                }else if(D('Paymentlogs')->set_payed($log['log_id'])){
					if($log['payed'] == '1'){
							
							$this->success('该订单支付成功', $forward);
						}else{
							D('Paymentlogs')->set_payed($log['log_id']);
							D('Order')->set_payed($log, $trade);
							$order = D('Order')->find($log['order_id']);
							D('Memberlog')->change_status($order,$code);
							if($log['from'] == 'crowd'){
								$Goodslist = D('Goodslist');
								$where['uid'] = $log['uid'];
								$where['list_id'] = $log['product_id'];
								$data['is_pay'] = 1;
								$data['pay_time'] = NOW_TIME;
								$Goodslist->where($where)->save($data);
								$lists = $Goodslist->find($log['product_id']);

								D('Project')->updateCount($lists['project_id'],'have_num',1);
								D('Project')->updateCount($lists['project_id'],'have_price',$log['amount']);
								
								$Project_detail = D('Project')->find($lists['project_id']);
								if($Project_detail['have_price'] >=  $Project_detail['fund_raising']){
									D('Project')->where(array('project_id'=>$lists['project_id']))->save(array('status'=>'2'));
									$lists_goods = $Goodslist->where(array('project_id'=>$lists['project_id'],'is_pay'=>'1'))->select();
									foreach($lists_goods as $k => $v){
										$member = D('Member')->find($v['uid']);
										D('Sms')->sendSms('sms_chouzi', $member['mobile'], array('title'=>$Project_detail['title']));
									}
								}

								

							}
							$success = true;
					}
                }else{
                     $success = false;             
                }
			}
			$obj->notify_success($success);
		}
	}

	

	public function wxqrcode()
    {
		$forward = U('index');
        if(!$codeurl = base64_decode($this->_get('codeurl'))){
            $this->error('失败', $forward);
        }
        if(!$amount = $this->_get('amount')){
             $this->error('失败', $forward);
        }
		if(!$order_id = $this->_get('order_id')){
            $this->error('失败', $forward);
        }
        $amount = sprintf("%.2f", $amount/100);
		$this->assign('codeurl', $codeurl);
		$this->assign('amount', $amount);
		$this->assign('order_id', $order_id);
        $this->display(); 
    }

	public function paycheck($order_id=0)
	{
			$order = D('Paymentlogs')->where(array('order_id'=>$order_id))->find();
			if($order['payed'] == 1){
				$this->ajaxReturn(array('status' => 'success', 'msg' => '支付成功'));
			}else{
				$this->ajaxReturn(array('status' => 'error', 'msg' => '请尽快支付'));
			}

	}
}
