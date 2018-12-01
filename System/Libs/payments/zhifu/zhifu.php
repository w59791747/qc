<?php
/**
 * Copy Right IJH.CC
 * Each engineer has a duty to keep the code elegant
 * $Id: alipay.php 5379 2014-05-30 10:17:21Z youyi $
 */

class Payment_Zhifu
{

    //zhifu
    private $gateway = 'https://pay.dinpay.com/gateway?input_charset=UTF-8';

    
    public function __construct($cfg)
    {
        $this->config = $cfg;
        $this->_parameter = array();
		$this->_parameter['merchant_code'] = $cfg['zhifu_code'];
        $this->_parameter['return_url'] = $cfg['return_url'];
        $this->_parameter['notify_url'] = $cfg['notify_url'];
		include_once(APP_PATH."Libs/payments/zhifu/merchant.php");

		$this->_key['merchant_private_key'] = $merchant_private_key;
		$this->_key['merchant_public_key'] = $merchant_public_key;
		$this->_key['dinpay_public_key'] = $dinpay_public_key;


		

    }

	 public function build_url($params)
    {
		$params_list['merchant_code'] = $this->_parameter['merchant_code'];

		$params_list['service_type'] = 'direct_pay';

		$params_list['interface_version'] ="V3.0";

		$params_list['sign_type'] ="RSA-S";

		$params_list['input_charset'] ="UTF-8";

		$params_list['notify_url'] = $this->_parameter['notify_url'];

		$params_list['order_no'] = $params['trade_no'];

		$params_list['order_time'] = date( 'Y-m-d H:i:s' );


		$params_list['order_amount'] = $params['amount']/100;

		$params_list['product_name'] = $params['title'];
		
		$params_list['return_url'] = $this->_parameter['return_url'];
		$params_list['sign'] =  $this->getSignature($params_list);
		
		return $params_list;
    }

	

	function getSignature($params_list){
		$signStr= "";
		$signStr = $signStr."input_charset=".$params_list['input_charset']."&";	
		$signStr = $signStr."interface_version=".$params_list['interface_version']."&";	
		$signStr = $signStr."merchant_code=".$params_list['merchant_code']."&";	
		$signStr = $signStr."notify_url=".$params_list['notify_url']."&";		
		$signStr = $signStr."order_amount=".$params_list['order_amount']."&";		
		$signStr = $signStr."order_no=".$params_list['order_no']."&";		
		$signStr = $signStr."order_time=".$params_list['order_time']."&";	
		$signStr = $signStr."product_name=".$params_list['product_name']."&";
		$signStr = $signStr."return_url=".$params_list['return_url']."&";
		$signStr = $signStr."service_type=".$params_list['service_type'];
		$merchant_private_key=  $this->_key['merchant_private_key'];
		$merchant_private_key= openssl_get_privatekey($this->_key['merchant_private_key']);
		openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
		$sign = base64_encode($sign_info);
		return  $sign;
		
	}
	       

	public  function return_verify(){
		$merchant_code	= $_POST["merchant_code"];	

		$interface_version = $_POST["interface_version"];

		$sign_type = $_POST["sign_type"];

		$dinpaySign = base64_decode($_POST["sign"]);

		$notify_type = $_POST["notify_type"];

		$notify_id = $_POST["notify_id"];

		$order_no = $_POST["order_no"];

		$order_time = $_POST["order_time"];	

		$order_amount = $_POST["order_amount"];

		$trade_status = $_POST["trade_status"];

		$trade_time = $_POST["trade_time"];

		$trade_no = $_POST["trade_no"];

		$bank_seq_no = $_POST["bank_seq_no"];

		$extra_return_param = $_POST["extra_return_param"];

		
		$signStr = "";

		if($bank_seq_no != ""){
			$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
		}

		if($extra_return_param != ""){
			$signStr = $signStr."extra_return_param=".$extra_return_param."&";
		}	

		$signStr = $signStr."interface_version=".$interface_version."&";	

		$signStr = $signStr."merchant_code=".$merchant_code."&";

		$signStr = $signStr."notify_id=".$notify_id."&";

		$signStr = $signStr."notify_type=".$notify_type."&";

		$signStr = $signStr."order_amount=".$order_amount."&";	

		$signStr = $signStr."order_no=".$order_no."&";	

		$signStr = $signStr."order_time=".$order_time."&";	

		$signStr = $signStr."trade_no=".$trade_no."&";	

		$signStr = $signStr."trade_status=".$trade_status."&";

		$signStr = $signStr."trade_time=".$trade_time;

		$dinpay_public_key=  $this->_key['dinpay_public_key'];

			
		$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
		
		$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);	
		
		$result="";
		
		if($flag==true){		
			
			$trade['trade_no'] = $order_no;
			$trade['amount'] = $order_amount;
			return $trade;
			
		}
	}

	public  function notify_verify(){
		$merchant_code	= $_POST["merchant_code"];	

		$interface_version = $_POST["interface_version"];

		$sign_type = $_POST["sign_type"];

		$dinpaySign = base64_decode($_POST["sign"]);

		$notify_type = $_POST["notify_type"];

		$notify_id = $_POST["notify_id"];

		$order_no = $_POST["order_no"];

		$order_time = $_POST["order_time"];	

		$order_amount = $_POST["order_amount"];

		$trade_status = $_POST["trade_status"];

		$trade_time = $_POST["trade_time"];

		$trade_no = $_POST["trade_no"];

		$bank_seq_no = $_POST["bank_seq_no"];

		$extra_return_param = $_POST["extra_return_param"];


	/////////////////////////////   参数组装  /////////////////////////////////
	/**	
	除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母	
	*/

		
		$signStr = "";
		
		if($bank_seq_no != ""){
			$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
		}

		if($extra_return_param != ""){
			$signStr = $signStr."extra_return_param=".$extra_return_param."&";
		}	

		$signStr = $signStr."interface_version=".$interface_version."&";	

		$signStr = $signStr."merchant_code=".$merchant_code."&";

		$signStr = $signStr."notify_id=".$notify_id."&";

		$signStr = $signStr."notify_type=".$notify_type."&";

		$signStr = $signStr."order_amount=".$order_amount."&";	

		$signStr = $signStr."order_no=".$order_no."&";	

		$signStr = $signStr."order_time=".$order_time."&";	

		$signStr = $signStr."trade_no=".$trade_no."&";	

		$signStr = $signStr."trade_status=".$trade_status."&";

		$signStr = $signStr."trade_time=".$trade_time;
		
		//echo $signStr;
		
	/////////////////////////////   RSA-S验证  /////////////////////////////////
		$dinpay_public_key=  $this->_key['dinpay_public_key'];

		
		$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
		
		$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);	
		
		
	///////////////////////////   响应“SUCCESS” /////////////////////////////

		
		if($flag){		
		
			$trade['trade_no'] = $BillNo;
				$trade['amount'] = $Amount;
				return $trade;
		

		}
	}

}