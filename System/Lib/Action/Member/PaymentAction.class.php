<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PaymentAction extends CommonAction {

    public function order()
    {
		$data = $this->_post('data', 'htmlspecialchars');
		$order_id = $data['order_id'];
		$code = $data['code'];
		$pwd = $data['pwd'];
        if(!is_numeric($order_id)){
			$this->chuangError('该订单不存在');
        }else if(!D('Payment')->checkPayment($code)){
            $this->chuangError('支付方式不存在');
        }else if($this->uid){
			$log = D('Paymentlogs')->where(array('order_id'=>$order_id))->find();
            if(!$order = D('Order')->find($order_id)){
				$this->chuangError('您的订单不存在或已经删除');
            }else if($order['order_status'] < 0){
				$this->chuangError('订单已经取消不可支付');
            }else if($order['order_status'] == 2){
				$this->chuangError('订单已经完成不可支付');
            }else if($this->check_pay($log) == false && $log['from'] == 'crowd'){
				$this->chuangError('该众筹剩余资金不足 支付失败');
			}else if($order['pay_status']){
				$this->chuangError('该订单已经支付过了,不需要重复支付');
            }else if($code == 'money'){
				if($pwd == ''){
					$this->chuangError('请输入支付密码');
					exit;
				}
				if($this->member['pwd'] != $pwd){
					$this->chuangError('支付密码错误');
					exit;
				}
				$status = D('Memberlog')->change_status($order,$code);
				
				if($status == '100')
				{
					D('Paymentlogs')->set_payed($log['log_id']);
					D('Order')->set_payed($log, $order);
					
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
					$this->chuangSuccess('支付成功', U('project/order'));
				}else if($status == '101'){
					$this->chuangError('账号余额不足');
				}else{
					$this->chuangError('支付失败');
				}
			}else if($url = D('Payment')->get_order($code, $order)){
                if(strpos($_SERVER["HTTP_USER_AGENT"], 'MicroMessenger') === false && strpos($url,'wxpay')){
					$u = base64_encode($url);
					$qrurl = U('home/payment/wxqrcode', array('amount'=>$order['amount'],'order_id'=>$order['order_id'],'codeurl'=>$u));
					$this->chuangSuccess('立即支付',$qrurl);
                }else{
					$this->chuangSuccess('立即支付',$url);
                }
                exit;
            }
        }
    }

	public function check_pay($log)
	{
		$Goodslist = D('Goodslist');
		$obj = D('Project');
		$list = $Goodslist->find($log['product_id']);
		$project = $obj->find($list['project_id']);
		
		if($log['amount']+$project['have_price']>$project['fund_raising']){
			return false;
		}else{
			return true;
		}
	}
}
