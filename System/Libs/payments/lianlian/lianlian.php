<?php


require_once APP_PATH."Libs/payments/lianlian/lib/llpay_submit.class.php";
require_once APP_PATH."Libs/payments/lianlian/lib/llpay_submit2.class.php";
require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_notify.class.php";
require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_notify2.class.php";
require_once   APP_PATH."Libs/payments/lianlian/lib/llpay_cls_json.php";
class Payment_Lianlian
{
    public function __construct($cfg)
    {
		
        $this->config = $cfg;
        $this->_parameter = array();
		$this->_parameter['oid_partner'] = $cfg['appid'];
        $this->_parameter['return_url'] = $cfg['return_url'];
        $this->_parameter['notify_url'] = $cfg['notify_url'];
        if (!extension_loaded('openssl')){
            $this->transport = 'http';
        }
    }
	
	
	public function config()
	{
		$llpay_config['oid_partner'] =$this->_parameter['oid_partner'];
		//秘钥格式注意不能修改（左对齐，右边有回车符）
		
$llpay_config['RSA_PRIVATE_KEY'] ='-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDdIM1t55+RrG59lowWHYxsIBW3M0WnCZxh4Ae9p1CeJvqx2Rxn
BPV0c9cisLwKsC2AmPp6TXPLkFUgyis4q3z6s4cg3FDu42zNGfLn9lSkX4B+b5qi
y13B7vert9Kb1pYMh0SCdDNalz3rrMTJe20jgNRMdwmwJGyTBn8jV0pswQIDAQAB
AoGAcjydBDdR308GcckRq+cuATn+HfvV8vprGMFFHnxOkLWwc6qnGq2cJMInlVta
eecUSsg9NmvG85Yn9F2dQJSPZnrJtAFfSAU+Tp1PO9IYJveNyedd2X+R/phDONnH
LNF5V/xJQOeM69mUDA4U0dzT8FWkwyyyot+SbkOVc7KwZcECQQD3xnRz+Z/1xIsg
d1SQLHjBLOGTkJoVv8JQrGhBRXPhcWanJCF/y95K0mQ2qDfdeAoEuKonRnU21TC8
CKJEYDXpAkEA5Hfn6exGwC6AJU8EPXhhF/VIe5cY14aG+ivzob2oIWAB76H5+uKx
RrJtBNKoi5e9cf90a0lv+dp0AbYICG1BGQJAWk80PN08R2j2yMOVx+LdtJM2OQHY
l5rIKX1dloTJAt/BaaRKrXjSMfVTX3SawBczl5rzMdOFf5He7Ho9IqzqmQJBAMtz
v9E/OE4B2tnegL8gyQ3lvvNYPaYIRYDYzO+WjfeSoIa+LndtkkKLt6uY0MDmkqzG
uOyljBLqp22ypXqjmFkCQEC3ECVkd8Z685/apOYDwezt+DI1CKkPpg30e5YKl56W
pkhX18XwYZUsFLc/XvY1mboQPSJ9x0Z3QP0WmwEJdlc=
-----END RSA PRIVATE KEY-----';	
		//安全检验码，以数字和字母组成的字符
		$llpay_config['key'] ='201408071000001539_sahdisa_20141205';

		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

		//版本号
		$llpay_config['version'] = '1.0';

		//证件类型
		$llpay_config['id_type'] = '0';
		
		$llpay_config['app_request'] = '3';

		//签名方式 不需修改
		$llpay_config['sign_type'] = strtoupper('RSA');

		//订单有效时间  分钟为单位，默认为10080分钟（7天） 
		$llpay_config['valid_order'] ="10080";

		//字符编码格式 目前支持 gbk 或 utf-8
		$llpay_config['input_charset'] = strtolower('utf-8');

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$llpay_config['transport'] = 'http';
		return $llpay_config;
	}

	 public function build_url($params)
    {
		
		
		$llpay_config = $this->config();
		if(is_mobile()){
			$parameter = array (
				"oid_partner" => trim($this->_parameter['oid_partner']),
				"app_request" => trim($llpay_config['app_request']),
				"sign_type" => trim($llpay_config['sign_type']),
				"valid_order" => trim($llpay_config['valid_order']),
				"user_id" => $params['uid'].rand('000000','999999'),
				"busi_partner" => '101001',
				"no_order" => $params['trade_no'],
				"dt_order" => date('YmdHis', time()),
				"name_goods" => $params['title'],
				"money_order" =>$params['amount']/100,
				"notify_url" => $this->_parameter['notify_url'],
				"url_return" => $this->_parameter['return_url'],
				"id_type" => '0',
				"id_no" => $params['card'],
				"acct_name" => $params['name'],
				"risk_item" => '{\"user_info_bind_phone\":\"13958069593\",\"user_info_dt_register\":\"20131030122130\",\"risk_state\":\"1\",\"frms_ware_category\":\"1009\"}'
			);
			//建立请求
			$llpaySubmit2 = new LLpaySubmit2($llpay_config);
			$html_text = $llpaySubmit2->buildRequestForm($parameter, "post", "确认");
			echo $html_text;
		}else{
			$parameter = array (
				"version" => trim($llpay_config['version']),
				"oid_partner" => trim($this->_parameter['oid_partner']),
				"sign_type" => trim($llpay_config['sign_type']),
				"valid_order" => trim($llpay_config['valid_order']),
				"user_id" => $params['uid'],
				"timestamp" => date('YmdHis', time()),
				"busi_partner" => '101001',
				"no_order" => $params['trade_no'],
				"dt_order" => date('YmdHis', time()),
				"name_goods" => $params['title'],
				"money_order" => $params['amount']/100,
				"notify_url" => $this->_parameter['notify_url'],
				"url_return" => $this->_parameter['return_url'],
				"pay_type" => '1',
				"risk_item" => '{"user_info_bind_phone":"13958069593","user_info_dt_register":"20131030122130","risk_state":"1","frms_ware_category":"1009"}',
				"id_no" => '',
				"acct_name" => '',
				"flag_modify" => 1,
				"card_no" => '',
				"back_url" => ''
			);
			$llpaySubmit = new LLpaySubmit($llpay_config);
			$html_text = $llpaySubmit->buildRequestForm($parameter, "post", "确认");
			echo $html_text;
		}
    }

	

	



	public  function return_verify(){
		
		$llpay_config = $this->config();
		if(is_mobile()){
			$llpayNotify = new LLpayNotify2($llpay_config);
			$verify_result = $llpayNotify->verifyReturn();
		}else{
			$llpayNotify = new LLpayNotify($llpay_config);
			$verify_result = $llpayNotify->verifyReturn();
		}
		if($verify_result) {
			
			if(is_mobile()){
				$json = new JSON;
				$res_data = $_POST["res_data"];

				//商户编号
				$oid_partner = $json->decode($res_data)-> {'oid_partner' };

				//商户订单号
				$no_order = $json->decode($res_data)-> {'no_order' };

				//支付结果
				$result_pay =  $json->decode($res_data)-> {'result_pay' };

				
			}else{
				//商户编号
				$oid_partner = $_POST['oid_partner' ];
				//签名方式
				$sign_type = $_POST['sign_type' ];
				//签名
				$sign= $_POST['sign' ];
				//商户订单时间
				$dt_order= $_POST['dt_order' ];
				//商户订单号
				$no_order = $_POST['no_order' ];
				//支付单号
				$oid_paybill = $_POST['oid_paybill' ];
				//交易金额
				$money_order = $_POST['money_order' ];
				//支付结果
				$result_pay =  $_POST['result_pay'];
				//清算日期
				$settle_date =  $_POST['settle_date'];
				//订单描述
				$info_order =  $_POST['info_order'];
				//支付方式
				$pay_type =  $_POST['pay_type'];
				//银行编号
				$bank_code =  $_POST['bank_code'];
			}

			if($result_pay == 'SUCCESS') {
				$trade['trade_no'] = $no_order;
				$trade['amount'] = $money_order;
				return $trade;
			}else {
			   echo "result_pay=".$result_pay;
			}
			file_put_contents("log.txt","同步通知:成功\n", FILE_APPEND);
			echo "验证成功<br />";
			
		}
		else {
			//验证失败
			//如要调试，请看llpay_notify.php页面的verifyReturn函数
			
			file_put_contents("log.txt","同步通知 验证失败\n", FILE_APPEND);
			echo "验证失败";
		}
	}

	public  function notify_verify(){
		$llpay_config = $this->config();
		
		$llpayNotify = new LLpayNotify($llpay_config);
		$verify_result = $llpayNotify->verifyNotify();
		
		
		if ($verify_result) {
			
			$is_notify = true;
			$json = new JSON;
			$str = file_get_contents("php://input");
			$val = $json->decode($str);
			
			
				
			$oid_partner = trim($val-> {
				'oid_partner' });
			$dt_order = trim($val-> {
				'dt_order' });
			$no_order = trim($val-> {
				'no_order' });
			$oid_paybill = trim($val-> {
				'oid_paybill' });
			$money_order = trim($val-> {
				'money_order' });
			$result_pay = trim($val-> {
				'result_pay' });
			$settle_date = trim($val-> {
				'settle_date' });
			$info_order = trim($val-> {
				'info_order' });
			$pay_type = trim($val-> {
				'pay_type' });
			$bank_code = trim($val-> {
				'bank_code' });
			$sign_type = trim($val-> {
				'sign_type' });
			$sign = trim($val-> {
				'sign' });
			$trade['trade_no'] = $no_order;
			$trade['amount'] = $money_order;
			return $trade;
			file_put_contents("log.txt", "异步通知1 验证成功\n", FILE_APPEND);
			die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			file_put_contents("log.txt", "异步通知 验证失败\n", FILE_APPEND);
			//验证失败
			die("{'ret_code':'9999','ret_msg':'验签失败'}");
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
		
		
	public  function notify_verify2(){
		$llpay_config = $this->config();
		$llpayNotify = new LLpayNotify2($llpay_config);
		$verify_result = $llpayNotify->verifyNotify();
		if ($llpayNotify->result) {
			$no_order = $llpayNotify->notifyResp['no_order'];//商户订单号
			$oid_paybill = $llpayNotify->notifyResp['oid_paybill'];//连连支付单号
			$result_pay = $llpayNotify->notifyResp['result_pay'];//支付结果，SUCCESS：为支付成功
			$money_order = $llpayNotify->notifyResp['money_order'];// 支付金额
			if($result_pay == "SUCCESS"){
				$trade['trade_no'] = $no_order;
				$trade['amount'] = $money_order;
				return $trade;
			}
			file_put_contents("log.txt", '异步通知2 验证成功\n'.json_decode($llpayNotify->notifyResp), FILE_APPEND);
			die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			file_put_contents("log.txt", "异步通知 验证失败\n", FILE_APPEND);
			//验证失败
			die("{'ret_code':'9999','ret_msg':'验签失败'}");
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}

}