<?php

class  VerifyAction extends CommonAction{
    
    public function index(){
        import('ORG.Util.Image');
        Image::buildImageVerify(4,3,'png',60,30);
    }
	
	public  function daifu(){
		
		require_once  APP_PATH."Libs/payments/lianlian/lib/llpay_notify3.class.php";
		$llpay_config = $this->config();
		$llpayNotify = new LLpayNotify3($llpay_config);
		$verify_result = $llpayNotify->verifyNotify();
		if ($verify_result) {
			$no_order = $llpayNotify->notifyResp['no_order'];//商户订单号
			$oid_paybill = $llpayNotify->notifyResp['oid_paybill'];//连连支付单号
			$result_pay = $llpayNotify->notifyResp['result_pay'];//支付结果，SUCCESS：为支付成功
			$money_order = $llpayNotify->notifyResp['money_order'];// 支付金额
			if($result_pay == "SUCCESS"){
				$log = D('Memberlog')->where(array('code'=>$no_order))->find();
				$data['status'] = 2;
				$data['log'] = '提现成功';
				D('Memberlog')->tixian_log($log['log_id'],$data);
			}
			file_put_contents("log.txt", "异步通知 验证成功\n", FILE_APPEND);
			die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			file_put_contents("log.txt", "异步通知 验证失败\n".file_get_contents("php://input"), FILE_APPEND);
			//验证失败
			die("{'ret_code':'9999','ret_msg':'验签失败'}");
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
	
	public function config()
	{
		//$lianlian = D('Payment')->checkPayment('lianlian');
		$llpay_config['oid_partner'] = '201811280002298007';

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
		$llpay_config['key'] = '201408071000001539_sahdisa_20141205';

		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

		//版本号
		$llpay_config['version'] = '1.1';

		//请求应用标识 为wap版本，不需修改
		$llpay_config['app_request'] = '3';


		//签名方式 不需修改
		$llpay_config['sign_type'] = strtoupper('RSA');

		//订单有效时间  分钟为单位，默认为10080分钟（7天） 
		$llpay_config['valid_order'] ="30";

		//字符编码格式 目前支持 gbk 或 utf-8
		$llpay_config['input_charset'] = strtolower('utf-8');

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$llpay_config['transport'] = 'http';
		
		return $llpay_config;
	}
}