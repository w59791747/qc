<?php
/**
 * Copy Right IJH.CC
 * Each engineer has a duty to keep the code elegant
 * $Id$
 */

require_once APP_PATH."Libs/payments/wxpay/WxPay.JsApiPay.php";
require_once APP_PATH."Libs/payments/wxpay/lib/WxPay.Api.php";
require_once APP_PATH."Libs/payments/wxpay/lib/WxPay.Config.php";

class Weixin_Jsapi
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
		WxPayConfig::$_CONFIG = $cfg;

	}

	public function build_url($input)
	{
		$tools = new JsApiPay();
		$openid = cookie('openid');
		$inputObj = new WxPayUnifiedOrder();
		$inputObj->SetBody($input['title']);
		$inputObj->SetOut_trade_no($input['trade_no']);
		$inputObj->SetTotal_fee($input['amount']);
		$inputObj->SetNotify_url($this->config['notify_url']);
		$inputObj->SetTrade_type("JSAPI");
		//$inputObj->SetProduct_id($input['trade_no']);
		$inputObj->SetTime_start(date("YmdHis"));
		$inputObj->SetTime_expire(date("YmdHis", time() + 600));
		$inputObj->SetOpenid($openid);
		$order = WxPayApi::unifiedOrder($inputObj);
		//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		//$this->printf_info($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}

	public function printf_info($data)
	{
		$arr = array('trade_no'=>'订单号','contact'=>'联系人','mobile'=>'电话','addr'=>'地址');
		foreach($arr as $key=>$value){
			echo "<font color='#00ff55;'>".$value."</font> : ".$data[$key]." <br/>";
		}
	}

}