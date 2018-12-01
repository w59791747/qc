<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PaymentlogsModel extends CommonModel{
    protected $pk   = 'log_id';
    protected $tableName =  'payment_logs';
    
    public function getLogsByOrderId($type,$order_id){
        $order_id = (int)$order_id;
        $type = addslashes($type);
        return $this->find(array('where'=>array('type'=>$type, 'order_id'=>$order_id)));
    }

	public function getLogsByOrderno($order_no){
		$order = D('Order')->where(array('order_no'=>$order_no))->find();
		return $this->getLogsByOrderId($order['type'],$order['order_id']);
	}



	public function logadd($uid,$price,$code,$from,$product_id=0,$order_id,$jifen=0,$actual_pay=0)
	{
		if($from == 'dingjin' || $from == 'weikuan' || $from == 'crowd'){
			$log = $this->where(array('uid'=>$uid,'from'=>$from,'product_id'=>$product_id))->find();
			$this->where(array('log_id'=>$log['log_id']))->delete();
			D('Order')->where(array('order_id'=>$log['order_id']))->delete();
		}
		$data['uid'] = $uid;
		$data['from'] = $from;
		$data['payment'] = $code;
		$data['amount'] = $price*100;
		$data['jifen'] = $jifen;
		if($actual_pay ==0){
			$data['actual_pay'] = $price*100;
		}else{
			$data['actual_pay'] =$actual_pay*100;
		}
		$data['product_id'] = $product_id;
		$data['order_id'] = $order_id;
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
		if($logs_id = $this->add($data)){
			return $logs_id;
		}else{
			return false;
		}
	}

	 public function set_payed($log_id, $pay_trade_no='', $extra='')
    {
        if(!is_numeric($log_id)){
            return false;
        }
		$log = $this->find($log_id);
		//if($log['payed'] == 1){
			//return  false;
		//}else{
			$data['payed'] = '1';
			$data['pay_trade_no'] = $pay_trade_no;
			$data['payedip'] = get_client_ip();
			$data['payedtime'] = NOW_TIME;
			$data['log_id'] = $log_id;
			$this->save($data);
			return $log_id;
		//}
    }
    
}