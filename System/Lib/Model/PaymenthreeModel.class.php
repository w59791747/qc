<?php


/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class PaymenthreeModel extends CommonModel {

    protected $pk = 'payment_id';
    protected $tableName = 'paymenthree';
    protected $token = 'paymenthree';
    protected $types = array(
        'crowd' => '众筹充值',
        'gold' => '积分充值',
        'price' => '资金充值',
    );
    protected $type = null;
    protected $log_id = null;

    public function getType() {
        return $this->type;
    }

    public function getLogId() {
        return $this->log_id;
    }

    public function getTypes() {
        return $this->types;
    }

    public function getPayments($mobile = false) {
        $datas = $this->fetchAll();
        $return = array();
        foreach ($datas as $val) {
            if ($val['is_open']) {
                if ($mobile == false) {
                    if (!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }
            }
        }

        if (is_weixin()) {
            unset($return['alipay']);
        }

        return $return;
    }


  
    public function checkMoney($logs_id, $money) {
        $money = (int) ($money );
        $logs = D('Paymentlogs')->find($logs_id);
        if ($logs['need_pay'] == $money)
            return true;
        return false;
    }

	public function _format($data) {
        $data['setting'] = unserialize($data['setting']);
        return $data;
    }



   
    public function checkPayment($code) {
        $datas = $this->fetchAll();
        foreach ($datas as $val) {
            if ($val['code'] == $code){
                return $val;
			}
        }
        return false;
    }

    public function getPayment($code) {
        $datas = $this->fetchAll();
        foreach ($datas as $val) {
            if ($val['code'] == $code)
                return $val['setting'];
        }
        return false;
    }

	 public function get_order($code, $order, $form=false)
    {
        if(!$oPayApi = $this->loadPayment($code)){
            return false;
        }
        $log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
        $a = array();
        if($log['amount'] != $order['amount']){
            $a['amount'] = $order['amount'];
        }
        if($log['payment'] != $code){
            $a['payment'] = $code;
        }
        if($a){
			$a['log_id'] = $log['log_id'];
            D('Paymentlogs')->save($a);
        }
        $params = array();
        $params['trade_no'] = $order['order_no'];
        $Setting = D('Setting')->fetchAll();
		$types = D('Payment')->getTypes();
        $params['title'] = $Setting['site']['title'].'-'.$types[$order['type']];
		$params['order_id'] = $order['order_id'];
        $params['body'] = "text";
        $params['amount'] = $order['amount'];
        $params['contact'] = $order['contact'];
        $params['mobile'] = $order['mobile'];
        $params['addr'] = $order['addr'];
        if($form){
            return $oPayApi->build_form($params);
        }
		
		if (strpos($_SERVER["HTTP_USER_AGENT"], 'MicroMessenger') && $code == 'wxpay') {
			return $oPayApi->build_url($params);
		}else{
			return $oPayApi->build_url($params);
		}
    }

	

	public function loadPayment($code)
    {  
        static $_PayApiObj = array();
        if(!is_object($_PayApiObj[$code])){
            $file = APP_PATH . "Libs/payments/{$code}/{$code}.php";
            if(!file_exists($file)){
				$this->chuangError('您选择的支付接口不存在');
                return false;
            }else if(!$payment = $this->checkPayment($code)){
				$this->chuangError('您选择的支付接口不存在');

                return false;
            }else if(empty($payment['is_open'])){
				$this->chuangError('您选择的支付接口不可用');

                return false;
            }
			if (strpos($_SERVER["HTTP_USER_AGENT"], 'MicroMessenger') && $code == 'wxpay') {
				$file = APP_PATH . "Libs/payments/{$code}/jsapi.php";
				include($file);
				$clsName = "Weixin_".ucfirst('jsapi');
			}else{
				include($file);
				$clsName = "Payment_".ucfirst($code);
			}
            $config = $payment['setting'];
            $Setting = D('Setting')->fetchAll();
			if(is_mobile()){
				define("IN_MOBILE","1");
			}
			if(defined('IN_MOBILE')){
				$config['return_url'] = $Setting['site']['host'].U('mobile/payment/index',array('from'=>'return','code'=>$code));
				$config['notify_url'] = $Setting['site']['host'].U('mobile/payment/index',array('from'=>'notify','code'=>$code));
			}else{
				$config['return_url'] = $Setting['site']['host'].U('home/payment/index',array('from'=>'return','code'=>$code));
				$config['notify_url'] = $Setting['site']['host'].U('home/payment/index',array('from'=>'notify','code'=>$code));
			}
            $_PayApiObj[$code] = new $clsName($config);
        }
        return $_PayApiObj[$code];
    }

}
