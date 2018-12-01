<?php

require_once APP_PATH."Libs/payments/wxpay/lib/WxPay.Notify.php";
require_once APP_PATH."Libs/payments/wxpay/lib/WxPay.Api.php";
require_once APP_PATH."Libs/payments/wxpay/lib/WxPay.Config.php";

class Payment_Wxpay extends WxPayNotify
{
     public function __construct($cfg)
    {
        $this->config = $cfg;
        WxPayConfig::$_CONFIG = $cfg;
        $this->_parameter = array();
        $this->_parameter['APPID'] = $cfg['appid'];
        $this->_parameter['MCHID'] = $cfg['mchid'];
        $this->_parameter['KEY'] = $cfg['appkey']; 
        $this->_parameter['APPSECRET'] = $cfg['appsecret'];
        require_once APP_PATH."Libs/payments/wxpay/WxPay.NativePay.php";
    }

    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        //K::M('system/logs')->log('wxpay', "query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    public function build_url($input)
    {
        $inputObj = new WxPayUnifiedOrder();
        //echo $input['trade_no']= $input['trade_no'].'0';
        $inputObj->SetBody($input['title']);
        $inputObj->SetOut_trade_no($input['trade_no']);
        $inputObj->SetTotal_fee($input['amount']);
        $inputObj->SetNotify_url($this->config['notify_url']);
        $inputObj->SetTrade_type("NATIVE");
        $inputObj->SetProduct_id($input['trade_no']);
        if($inputObj->GetTrade_type() == "NATIVE"){
            $result = WxPayApi::unifiedOrder($inputObj);
            return $result["code_url"];
        }
    }

    public function NotifyProcess($trade, &$msg)
    {
        $success = false;
		$Setting = D('Setting')->fetchAll();
        $forward =  $Setting['site']['host'].U('ucenter/order/lists');
		
		
        if(!array_key_exists("transaction_id", $trade)){
            $msg = "输入参数不正确";
        }else if(!$this->Queryorder($trade["transaction_id"])){//查询订单，判断订单真实性
            $msg = "订单查询失败";
        }else if($trade['return_code'] == 'SUCCESS' && $trade['result_code'] == 'SUCCESS'){


            $amount = $trade['total_fee'];
            $trade = array('trade_no'=>$trade['out_trade_no'], 'pay_trade_no'=>$trade['transaction_id'], 'trade_status'=>$trade['return_code'], 'amount'=>$amount, 'trade_type'=>$trade['trade_type']);

			    if(!$log = D('Paymentlogs')->getLogsByOrderno($trade['trade_no'])){
					 $this->error('支付的订单不存在', $forward);
                }else if($trade['amount'] != $log['amount']){
					$this->error('支付金额非法', $forward);
				}else if(D('Paymentlogs')->set_payed($log['log_id'])){
					if($log['payed'] == '1'){
							if($log['from'] == 'dingjin'){
								$Projectlook = D('Projectlook');
								$data['city'] = $this->member['city'];
								$data['content'] = cookie('project'.$log['product_id']);
								$data['clientip'] = get_client_ip();
								$data['dateline'] = NOW_TIME;
								$Projectlook->where(array('uid'=>$log['uid'],'project_id'=>$log['product_id']))->save($data);
							}
							$this->success('该订单支付成功', $forward);
					}else{
						D('Order')->set_payed($log, $trade);
						$order = D('Order')->find($log['order_id']);
						D('Memberlog')->change_status($order,$code);
						if($log['from'] == 'dingjin'){
							$Projectlook = D('Projectlook');
							$data['project_id'] = $log['product_id'];
							$data['city'] = $this->member['city'];
							$data['uid'] = $log['uid'];
							$data['content'] = cookie('project'.$log['product_id']);
							$data['clientip'] = get_client_ip();
							$data['dateline'] = NOW_TIME;
							$Projectlook->add($data);
							D('Member')->updateCount($log['uid'],'tou_projects',1);
						}
						if($log['from'] == 'weikuan'){
							$Projectweikuan = D('Projectweikuan');
							$where['uid'] = $log['uid'];
							$where['project_id'] = $log['product_id'];
							$data['is_pay'] = 1;
							$data['pay_time'] = NOW_TIME;
							$Projectweikuan->where($where)->save($data);
							D('Project')->where(array('project_id'=>$log['product_id']))->save(array('status'=>3));
						}
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
							D('Goodstype')->updateCount($lists['type_id'],'have_num',1);

						}
						$this->success('该订单支付成功', $forward);
					}
                }else{
					$this->error('该订单已经支付过了', $forward);
                                  
                }
        }
        return $success;
    }

    public function notify_verify()
    {
        $handle = $this->Handle(true);
    }

    public function notify_success()
    {
        if($success){
            echo "success";exit;
        }else{
            echo "fail";exit;
        }
    }

   
}
