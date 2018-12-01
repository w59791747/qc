<?php


require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_apipost_submit.class.php";
require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_security.function.php";

class daifu
{
    
    public function __construct($cfg)
    {
        $this->config = $cfg;
        $this->_parameter = array();
		$this->_parameter['oid_partner'] = $cfg['appid'];
		$this->_parameter['notify_url'] = $cfg['notify_url'];
		$this->_parameter['llpay_payment_url'] = 'https://instantpay.lianlianpay.com/paymentapi/payment.htm';
        if (!extension_loaded('openssl')){
            $this->transport = 'http';
        }
    }
	
	public function config()
	{
		$llpay_config['oid_partner'] =$this->_parameter['oid_partner'];
		//秘钥格式注意不能修改（左对齐，右边有回车符）

//秘钥格式注意不能修改（左对齐，右边有回车符）  商户私钥，通过openssl工具生成,私钥需要商户自己生成替换，对应的公钥通过商户站上传
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

//连连银通公钥
		$llpay_config['LIANLIAN_PUBLICK_KEY'] ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCSS/DiwdCf/aZsxxcacDnooGph3d2JOj5GXWi+
q3gznZauZjkNP8SKl3J2liP0O6rU/Y/29+IUe+GTMhMOFJuZm1htAtKiu5ekW0GlBMWxf4FPkYlQ
kPE0FtaoMP3gYfh+OwI+fIRrpW3ySn3mScnc6Z700nU/VYrRkfcSCbSnRwIDAQAB
-----END PUBLIC KEY-----';

	

		//安全检验码，以数字和字母组成的字符
		$llpay_config['key'] = '201408071000001539_sahdisa_20141205';

		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


		//签名方式 不需修改
		$llpay_config['sign_type'] = strtoupper('RSA');


		//字符编码格式 目前支持 gbk 或 utf-8
		$llpay_config['input_charset'] = strtolower('utf-8');
		return $llpay_config;
	}

	

	 public function daifu_r($params)
    {
		$llpay_config = $this->config();
       $parameter = array (
			"oid_partner" => trim($this->_parameter['oid_partner']),
			"sign_type" => trim($llpay_config['sign_type']),
			"no_order" => $params['orderCode'],
			"dt_order" => date('YmdHis', time()),
			"money_order" => $params['tranAmt'],
			"acct_name" => $params['accName'],
			"card_no" => $params['accNo'],
			"info_order" => '提现',
			"memo" => "代付",
			"notify_url" => $this->_parameter['notify_url'],
			"platform" => $params['laiyuan'],
			"api_version" => '1.0',
		);
		
		if($params['code']){
			$parameter['flag_card'] = '1';
			$parameter['bank_code'] = $params['code'];
			$parameter['city_code'] = $params['city'];
			$parameter['brabank_name'] = $params['zhihang'];
		}else{
			$parameter['flag_card'] = '0';
		}
		
		$llpaySubmit = new LLpaySubmit($llpay_config);
		//对参数排序加签名
		$sortPara = $llpaySubmit->buildRequestPara($parameter);
		//传json字符串
		$json = json_encode($sortPara); 
		
		$parameterRequest = array (
			"oid_partner" => trim($llpay_config['oid_partner']),
			"pay_load" => $this->ll_encrypt($json,$llpay_config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
		);
		$html_text = $llpaySubmit->buildRequestJSON($parameterRequest,$this->_parameter['llpay_payment_url']);
		
		//调用付款申请接口，同步返回0000，是指创建连连支付单成功，订单处于付款处理中状态，最终的付款状态由异步通知告知
		//出现1002，2005，4006，4007，4009，9999这6个返回码时或者没返回码，抛exception（或者对除了0000之后的code都查询一遍查询接口）调用付款结果查询接口，明确订单状态，不能私自设置订单为失败状态，以免造成这笔订单在连连付款成功了，
		//而商户设置为失败,用户重新发起付款请求,造成重复付款，商户资金损失

		//对连连响应报文内容需要用连连公钥验签
		return $html_text;
    }
	
	function ll_encrypt($plaintext, $public_key) {
		
		$pu_key = openssl_pkey_get_public ( $public_key );
		$hmack_key = genLetterDigitRandom(32);
		$version = "lianpay1_0_1";
		$aes_key = genLetterDigitRandom(32);
		$nonce = genLetterDigitRandom(8);
		return lianlianpayEncrypt($plaintext, $pu_key, $hmack_key, $version, $aes_key, $nonce);
	}


	function chaxun($out_batch_no){
        $testTools = new TestTools();
		
        /**************基本参数****************/
        $service = 'query_b2c_batch_fundout_order';//服务名称
        $version = '1.0';//接口版本
        $request_time = date('YmdHis');//请求时间
        $partner_id = $this->_parameter['appid'];//合作者身份ID
        $_input_charset = 'UTF-8';//参数编码字符集
        $sign_type = 'RSA';//签名类型
		$notify_url = $this->_parameter['notify_url'];//异步通知地址
		$encrypt_version = '1.0';//加密版本
		$sign_version = '1.0';//签名版本
		$memo = '';//备注
        /****************业务参数***********************/
		$out_batch_no = $out_batch_no;//批次号
		
		
		/****************组织基本参数***********************/
        $param=array();
        $param['service']=$service;
        $param['version']=$version;
        $param['request_time']=$request_time;
        $param['partner_id']=$partner_id;
        $param['_input_charset']=$_input_charset;
        $param['sign_type']=$sign_type;
		if($notify_url != null){
			$param['notify_url']=$notify_url;
		}
		if($encrypt_version != null){
			$param['encrypt_version']=$encrypt_version;
		}
		if($sign_version != null){
			$param['sign_version']=$sign_version;
		}
		if($memo != null){
			$param['memo']=$memo;
		}
		/****************组织业务参数***********************/
		$param['out_batch_no']=$out_batch_no;
		
		
        ksort($param);//对签名参数据排序
        //进行签名
        $sign=$testTools->getSignMsg($param,$sign_type,$_input_charset);
        //将签名结果存入请求数组中
        $param['sign']=$sign;
        $testTools->write_log("出款查询请求参数".json_encode($param));
        $data = $testTools->createcurl_data($param); 
		// 调用createcurl_data创建模拟表单需要的数据
        $result = $testTools->curlPost(masRequestUrl,$data,$_input_charset); 
		// 使用模拟表单提交进行数据提交
        $splitdata = json_decode($result,true);
        $sign_type = $splitdata ['sign_type'];//签名方式
		ksort($splitdata); // 对签名参数据排序
        if ($testTools->checkSignMsg ($splitdata,$sign_type,$_input_charset)) {
            return $splitdata;
        } else {
			return $splitdata;
        }
    }
	


	

	

}
