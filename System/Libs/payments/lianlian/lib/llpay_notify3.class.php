﻿<?php

/* *
 * 类名：LLpayNotify
 * 功能：连连支付通知处理类
 * 详细：处理连连支付各接口通知返回
 * 版本：1.1
 * 日期：2014-04-16
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************注意*************************
 * 调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
 */

require_once APP_PATH."Libs/payments/lianlian/lib/llpay_core.function.php";

require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_md5.function.php";
require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_rsa.function.php";
require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_cls_json.php";

class LLpayNotify3 {
	var $llpay_config;
    var $notifyResp = array();
    var $result = false;
	function __construct($llpay_config) {
		$this->llpay_config = $llpay_config;
	}
	function LLpayNotify3($llpay_config) {
		$this->__construct($llpay_config);
	}
	

	/**
	 * 针对notify_url验证消息是否是连连支付发出的合法消息
	 * @return 验证结果
	 */
	function verifyNotify() {
		//生成签名结果
			$is_notify = true;
			require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_cls_json.php";
			$json = new JSON;
			$str = file_get_contents("php://input");
			//$str = '{"dt_order":"20181130112543","info_order":"提现","money_order":"0.2","no_order":"548343625474","oid_partner":"201811280002298007","oid_paybill":"2018113085524645","result_pay":"SUCCESS","settle_date":"20181130","sign":"gKS2rncSJ6FlDbgfI2b7RybitQ/O5ZkOkvHd/GYGfXiaNfw33QY1Si0kPWzfWTR54A7cRGQcDuXnPcbgyCpyD7gUK6hkpnMGovOTAB1iOVh+a1IhFEABcjUgnWiIrHawf+ETSH4tOafxKvx3NDkTIyzvp4vn4VfpF+eoUiN3Y4U=","sign_type":"RSA"}';
			$val = $json->decode($str);
			$oid_partner = $this->getJsonVal($val,'oid_partner' );
			$sign_type = $this->getJsonVal($val,'sign_type' );
			$sign = $this->getJsonVal($val,'sign' );
			$dt_order = $this->getJsonVal($val,'dt_order' );
			$no_order = $this->getJsonVal($val,'no_order' );
			$oid_paybill = $this->getJsonVal($val,'oid_paybill' );
			$money_order = $this->getJsonVal($val,'money_order' );
			$result_pay = $this->getJsonVal($val,'result_pay' );
			$settle_date = $this->getJsonVal($val,'settle_date' );
			$info_order = $this->getJsonVal($val,'info_order');
			$pay_type = $this->getJsonVal($val,'pay_type' );
			$bank_code = $this->getJsonVal($val,'bank_code' );
			$no_agree = $this->getJsonVal($val,'no_agree' );
			$id_type = $this->getJsonVal($val,'id_type' );
			$id_no = $this->getJsonVal($val,'id_no' );
			$acct_name = $this->getJsonVal($val,'acct_name' );
		//首先对获得的商户号进行比对
		if ($oid_partner != $this->llpay_config['oid_partner']) {
			//商户号错误
			return;
		}
		
		$parameter = array (
			'oid_partner' => $oid_partner,
			'sign_type' => $sign_type,
			'dt_order' => $dt_order,
			'no_order' => $no_order,
			'oid_paybill' => $oid_paybill,
			'money_order' => $money_order,
			'result_pay' => $result_pay,
			'settle_date' => $settle_date,
			'info_order' => $info_order,
			'pay_type' => $pay_type,
			'bank_code' => $bank_code,
			'no_agree' => $no_agree,
			'id_type' => $id_type,
			'id_no' => $id_no,
			'acct_name' => $acct_name
		);
		if (!$this->getSignVeryfy($parameter, $sign)) {
			return;
		}
		$this->notifyResp = $parameter;
		$this->result = true;
		return true;
	}

	/**
	 * 针对return_url验证消息是否是连连支付发出的合法消息
	 * @return 验证结果
	 */
	function verifyReturn() {
		if (empty ($_POST)) { //判断POST来的数组是否为空
			return false;
		} else {
			//首先对获得的商户号进行比对
			if (trim($_POST['oid_partner' ]) != $this->llpay_config['oid_partner']) {
				//商户号错误
				return false;
			}

			//生成签名结果
			$parameter = array (
				'oid_partner' => $_POST['oid_partner' ],
				'sign_type' => $_POST['sign_type'],
				'dt_order' => $_POST['dt_order' ],
				'no_order' =>  $_POST['no_order' ],
				'oid_paybill' => $_POST['oid_paybill' ],
				'money_order' => $_POST['money_order' ],
				'result_pay' =>  $_POST['result_pay'],
				'settle_date' => $_POST['settle_date'],
				'info_order' =>$_POST['info_order'],
				'pay_type'=>$_POST['pay_type'],
				'bank_code'=>$_POST['bank_code'],
			);

			if (!$this->getSignVeryfy($parameter, trim($_POST['sign' ]))) {
				return false;
			}
			return true;

		}
	}

	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = argSort($para_filter);

		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);

		//file_put_contents("log.txt", "原串:" . $prestr . "\n", FILE_APPEND);
		//file_put_contents("log.txt", "sign:" . $sign . "\n", FILE_APPEND);
		$isSgin = false;
		switch (strtoupper(trim($this->llpay_config['sign_type']))) {
			case "MD5" :
				$isSgin = $this->md5Verify($prestr, $sign, $this->llpay_config['key']);
				break;
			case "RSA" :
				$isSgin = $this->Rsaverify($prestr, $sign);
				break;
			default :
				$isSgin = false;
		}
		return $isSgin;
	}
	
	
	function Rsasign($data,$priKey) {
		//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
		$res = openssl_get_privatekey($priKey);

		//调用openssl内置签名方法，生成签名$sign
		openssl_sign($data, $sign, $res,OPENSSL_ALGO_MD5);

		//释放资源
		openssl_free_key($res);
		
		//base64编码
		$sign = base64_encode($sign);
		//file_put_contents("log.txt","签名原串:".$data."\n", FILE_APPEND);
		return $sign;
	}

	/********************************************************************************/
	
	
	/**RSA验签
	 * $data待签名数据(需要先排序，然后拼接)
	 * $sign需要验签的签名,需要base64_decode解码
	 * 验签用连连支付公钥
	 * return 验签是否通过 bool值
	 */
	function Rsaverify($data, $sign)  {
		//读取连连支付公钥文件
		$pubKey = file_get_contents(APP_PATH."Libs/payments/lianlian/key/llpay_public_key.pem");

		//转换为openssl格式密钥
		$res = openssl_get_publickey($pubKey);

		//调用openssl内置方法验签，返回bool值
		$result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_MD5);
		
		//释放资源
		openssl_free_key($res);

		//返回资源是否成功
		return $result;
	}
	
	function getJsonVal($json, $k){
		if(isset($json->{$k})){
			return trim($json->{$k});
		}
		return "";	
	}

}
?>
