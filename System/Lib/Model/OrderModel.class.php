<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class OrderModel extends CommonModel {

    protected $pk = 'order_id';
    protected $tableName = 'order';
    protected $types = array(
        'gold' => '积分充值',
        'price' => '资金充值',
        'crowd' => '众筹',
    );
	protected $pay_status = array(
        '0' => '未支付',
        '1' => '已支付',
        '-1' => '已取消',
    );

	

    public function getType() {
        return $this->types;
    }
	public function status() {
        return $this->pay_status;
    }

	public function create_order_no()
    {
        $i = rand(0, 9999);
        do {
            if (9999 == $i) {
                $i = 0;
            } 
            ++$i;
            $no = date("ymd") . str_pad($i, 4, "0", STR_PAD_LEFT);
            $order_no = $this->query("SELECT order_no FROM ".$this->getTableName()." WHERE order_no='{$no}'");
        } while ($order_no);
        return $no;
    }

	public function orderadd($uid,$price,$code,$from,$product_id=0,$jifen=0,$actual_pay=0,$type=1)
	{
		
		$data['uid'] = $uid;
		$data['type'] = $from;
		$data['product_amount'] = $price*100;
		$data['amount'] = $price*100;
		$data['order_no'] = $this->create_order_no();
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
		if($type == '2'){
			$data['note'] = substr(time(),-6).'-'.rand(1,99);
		}
		if($order_id = $this->add($data)){
			D('Paymentlogs')->logadd($uid,$price,$code,$from,$product_id,$order_id,$jifen,$actual_pay);
			return $order_id;
		}else{
			return false;
		}
	}



	public function ordersave($order_id,$uid,$price,$code,$from,$product_id=0,$jifen=0,$actual_pay=0)
	{
		$logs = D('Paymentlogs')->where(array('order_id'=>$order_id))->find();
		D('Paymentlogs')->where(array('order_id'=>$order_id))->delete();

		D('Paymentlogs')->logadd($uid,$price,$code,$from,$logs['product_id'],$order_id,$jifen,$actual_pay);
		return $order_id;
			
	}

	public function set_payed($log, $trade=array())
    {
        if(!$order = $this->find($log['order_id'])){
            return false;
        }
        $a = array('order_id'=>$order['order_id'],'pay_status'=>1, 'pay_time'=>time(), 'audit'=>1);
        if($ret = $this->save($a)){
			if($member = D('Member')->find($order['uid'])){
				D('Sms')->sendSms('sms_'.$order['from'], $member['mobile'], array('price' => $order['amount']/100));
			}
        }
        return $ret;
    }

	public function check($project_id,$fahuo=20)
	{
		$Project = D('Project'); $Goodstype = D('Goodstype'); 
		$Goodslist = D('Goodslist'); $Paymentlogs = D('Paymentlogs');
		$detail = $Project->find($project_id);
		if($detail['status'] == 4){
			if(time()>=$detail['ltime']+3600*24*$fahuo){
				
				$lists_2 = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'1','is_zhong'=>array('IN','0,1')))->select();

				foreach($lists_2 as $k => $v){
					$arr[] = $v['list_id'];
				}
				if($arr){
					$map['product_id'] = array('IN',implode(',',$arr));
				}
				$map['status'] = '2';
				$Paymentlogs->where($map)->save(array('status'=>'3'));
			}
		}
	}

	public function _format_row($row)
	{
		$logs = D('Paymentlogs')->getLogsByOrderId($row['type'],$row['order_id']);
		$row['amount'] = $row['amount']/100;
		$row['product_amount'] = $row['product_amount']/100;
		$row['log'] = $logs;
		return $row;
	}

	
    
    

}
