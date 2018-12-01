<?php 

spl_autoload_register(function ($class) {
    if ($class && strpos($class, 'Facebook') !== false) {
		$file = BASE_PATH.'/Baocms/Lib/libs/'.$class.'.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
});

use \PayPal\Api\Payer;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Details;
use \PayPal\Api\Amount;
use \PayPal\Api\Transaction;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Payment;
use \PayPal\Exception\PayPalConnectionException;
use \PayPal\Api\PaymentExecution;




define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
define('USE_PROXY',FALSE);
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');
define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr&cmd=_express-checkout&token=');

$API_Endpoint =API_ENDPOINT;
class paypal
{
    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */

	
    function getCode($order, $payment)
    {
		require BASE_PATH."/Baocms/Lib/libs/vendor/paypal/rest-api-sdk-php/sample/bootstrap.php";
		define('SITE_URL', 'http://dz.welife.com'); //网站url自行定义
		//创建支付对象实例
        $token = '';
        $serverName = $_SERVER['SERVER_NAME'];
        $serverPort = $_SERVER['SERVER_PORT'];
        $url=__HOST__ . U( 'pchome/payment/respond');
        $paymentAmount=$order['logs_amount'];
        $currencyCodeType=$payment['paypal_ec_currency'];
        $paymentType='Sale';
        $data_order_id      = $order['logs_id'];
		$detial = D('Paymentlogs')->find($data_order_id);
		if(!$detial['order_ids']){
			$detial['order_ids'] = $detial['order_id'];
		}
		if($detial['type'] == 'tuan'){
			$tuan = D('Tuanorder')->where(array('order_id'=>$detial['order_ids']))->select();
			foreach($tuan as $k => $v){
				$tuan_ids= $v['tuan_id'];
			}
			$tuan_list = D('Tuan')->where(array('tuan_id' => $tuan_ids))->find();
			
		}else{
			$goods = D('Ordergoods')->where(array('order_id' => array('IN', $detial['order_ids'])))->select();
			foreach($goods as $k => $v){
				$goods_ids.= $v['goods_id'].',';
			}
			$goods_list = D('Goods')->where(array('goods_id' => array('IN', substr($goods_ids,0,-1))))->select();

			foreach($goods_list as $k => $v){
				$goods_lists[$v['goods_id']] = $v;
			}
		}


        $returnURL =$url.'?code=paypal&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount.'&invoice='.$data_order_id;

        $cancelURL =$url."?paymentType=".$paymentType;
		
		
		$shipping = 0;

		if (!isset($data_order_id, $paymentAmount)) {
			die("lose some params");
		}

		$payer = new Payer();
		$payer->setPaymentMethod('paypal');
		
		
		
		if($goods){
			foreach($goods as $k => $v){
				
				$item[$k] = new Item();
				$item[$k]->setName($goods_lists[$v['goods_id']]['title'])
					->setCurrency('USD')
					->setQuantity($v['num'])
					->setSku("123123") // Similar to `item_number` in Classic API
					->setPrice($v['price']/100);
			}
		}else{
			
			foreach($tuan as $k => $v){	
				$item[$k] = new Item();
				$item[$k]->setName($tuan_list['title'])
					->setCurrency('USD')
					->setQuantity($v['num'])
					->setSku("123123") // Similar to `item_number` in Classic API
					->setPrice($v['total_price']/100/$v['num']);
			}
		}
		
		//var_dump($item);echo "File:", __FILE__, ',Line:',__LINE__;exit;

		$itemList = new ItemList();
		$itemList->setItems($item);



		$details = new Details();
		$details->setShipping(0)
			->setTax(0)
			->setSubtotal($paymentAmount);

				$amount = new Amount();
		$amount->setCurrency("USD")
			->setTotal($paymentAmount)
			->setDetails($details);

		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setDescription("Payment description")
			->setInvoiceNumber(uniqid());

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($returnURL)
			->setCancelUrl($cancelURL);


		$payment = new Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions(array($transaction));

		

		try {
			$payment->create($apiContext);
		} catch (Exception $ex) {
			echo $ex->getData();
			ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
			die();
		}

		$approvalUrl = $payment->getApprovalLink();
		header("Location: {$approvalUrl}");
    }

    /**
     * 响应操作
     */
    function respond()
    {
		require BASE_PATH."/Baocms/Lib/libs/vendor/paypal/rest-api-sdk-php/sample/bootstrap.php";


		if(!isset($_GET['paymentId'], $_GET['PayerID'])){
			die();
		}

		if((bool)$_GET['success']=== 'false'){

			echo 'Transaction cancelled!';
			die();
		}
		$order_sn = $_REQUEST['invoice'];
		$paymentID = $_GET['paymentId'];
		$payerId = $_GET['PayerID'];

		$payment = Payment::get($paymentID, $apiContext);
		$execute = new PaymentExecution();
		$execute->setPayerId($payerId);

		try{
			$result = $payment->execute($execute, $apiContext);
		}catch(Exception $e){
			die($e);
		}
		D('Payment')->logsPaid($order_sn);
		return true;
    }

   

    function deformatNVP($nvpstr)
    {

        $intial=0;
        $nvpArray = array();

        while(strlen($nvpstr))
        {
            $keypos= strpos($nvpstr,'=');
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
            $keyval=substr($nvpstr,$intial,$keypos);
            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
        }

        return $nvpArray;
    }

}