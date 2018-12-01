<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class MemberlogModel extends CommonModel{
    protected $pk   = 'log_id';
    protected $tableName =  'member_log';

	public function insert($data){
		if($log_id = $this->add($data)){
			if(false !==D('member')->updateCount($data['uid'],$data['from'],$data['number'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	

	public function change_status($order,$code)
	{
		$Setting = D('Setting')->fetchAll();

		$member = D('Member')->find($order['uid']);
		$log = D('Paymentlogs')->where(array('order_id'=>$order['order_id']))->find();
		if(!$log['actual_pay']){
			$log['actual_pay'] = $order['amount'];
		}
		$mobile = $member['mobile'];
		if($code == 'money'){
			if($member['price'] < $log['actual_pay']){
				return "101";
			}
			if(false !==D('member')->updateCount($order['uid'],'price',-$log['actual_pay'])){
				$data['uid'] = $order['uid'];
				$data['from'] = 'price';
				$data['number'] = -$log['actual_pay'];
				$data['log'] = '余额支付';
				$data['status'] = '2';
				$data['clientip'] = get_client_ip();
				$data['dateline'] = NOW_TIME;
				$this->add($data);
			 }

		 }

		  if($order['type'] == 'gold'){
				D('member')->updateCount($order['uid'],'gold',$order['amount']/100*$Setting['integral']['bili']);
				$datas['uid'] = $order['uid'];
				$datas['from'] = 'gold';
				$datas['number'] = $order['amount']/100*$Setting['integral']['bili'];
				$datas['log'] = '用户充值积分';
				$datas['status'] = '1';
				$datas['clientip'] = get_client_ip();
				$datas['dateline'] = NOW_TIME;
				$log_id = $this->add($datas);
				if($code != 'money'){
					D('member')->updateCount($order['uid'],'gold',$order['amount']/100*$Setting['integral']['xiaofei']);
					$data2['uid'] = $order['uid'];
					$data2['from'] = 'gold';
					$data2['number'] = $order['amount']/100*$Setting['integral']['xiaofei'];
					$data2['log'] = '用户充值积分额外送积分';
					$data2['status'] = '1';
					$data2['clientip'] = get_client_ip();
					$data2['dateline'] = NOW_TIME;
					$log_id = $this->add($data2);
					
					if($member['fenxiao_id']){
						$this->add_log($member['fenxiao_id'],'gold',$order['amount']/100*$Setting['integral']['fenxiang_xiaofei'],'分销用户消费返积分',1);
					}
				}
				D('Sms')->sendSms('sms_gold', $mobile, array('price' => $order['amount']/100));
				return '100';
			}

			if($order['type'] == 'price'){
				D('member')->updateCount($order['uid'],'price',$order['amount']);
				$datas['uid'] = $order['uid'];
				$datas['from'] = 'price';
				$datas['number'] = $order['amount'];
				$datas['log'] = '用户充值资金';
				$datas['status'] = '1';
				$datas['clientip'] = get_client_ip();
				$datas['dateline'] = NOW_TIME;
				$log_id = $this->add($datas);
				if($code != 'money'){
					D('member')->updateCount($order['uid'],'gold',$order['amount']/100*$Setting['integral']['xiaofei']);
					$data2['uid'] = $order['uid'];
					$data2['from'] = 'gold';
					$data2['number'] = $order['amount']/100*$Setting['integral']['xiaofei'];
					$data2['log'] = '用户充值资金额外送积分';
					$data2['status'] = '1';
					$data2['clientip'] = get_client_ip();
					$data2['dateline'] = NOW_TIME;
					$log_id = $this->add($data2);
					if($member['fenxiao_id']){
						$this->add_log($member['fenxiao_id'],'gold',$order['amount']/100*$Setting['integral']['fenxiang_xiaofei'],'分销用户消费返积分',1);
					}
				}
				D('Sms')->sendSms('sms_price', $mobile, array('price' => $order['amount']/100));
				return '100';
			}

			if($order['type'] == 'crowd'){
				D('member')->updateCount($order['uid'],'tou_price',$order['amount']);
				if($code != 'money'){
					D('member')->updateCount($order['uid'],'gold',$log['actual_pay']/100*$Setting['integral']['xiaofei']);
					$data2['uid'] = $order['uid'];
					$data2['from'] = 'gold';
					$data2['number'] = $log['actual_pay']/100*$Setting['integral']['xiaofei'];
					$data2['log'] = '众筹成功额外赠送积分';
					$data2['status'] = '1';
					$data2['clientip'] = get_client_ip();
					$data2['dateline'] = NOW_TIME;
					$log_id = $this->add($data2);
					if($member['fenxiao_id']){
						$this->add_log($member['fenxiao_id'],'gold',$log['actual_pay']/100*$Setting['integral']['fenxiang_xiaofei'],'分销用户消费返积分',1);
					}
				}
				if($log['jifen']>0){
					if(false !==D('member')->updateCount($order['uid'],'gold',-$log['jifen'])){
						$data['uid'] = $order['uid'];
						$data['from'] = 'gold';
						$data['number'] = -$log['jifen'];
						$data['log'] = '众筹积分支付';
						$data['status'] = '1';
						$data['clientip'] = get_client_ip();
						$data['dateline'] = NOW_TIME;
						$this->add($data);
					 }
				 }
				D('Sms')->sendSms('sms_crowd', $mobile, array('price' => $order['amount']/100));
				return '100';
			}
	}

	public function add_log($uid,$from,$num,$log,$status){
		$data['uid'] = $uid;
		$data['from'] = $from;
		$data['number'] = $num;
		$data['log'] = $log;
		$data['status'] = $status;
		$data['clientip'] = get_client_ip();
		$data['dateline'] = NOW_TIME;
		$log_id = $this->add($data);
		return $log_id;
	}

	public function update_log($log_id,$data){
		$Memberlog = D('Memberlog');
		$detail = $Memberlog->find($log_id);
		if(false !== $this->where(array('log_id'=>$log_id))->save($data)){
			if(false !==D('member')->updateCount($detail['uid'],$detail['from'],$detail['number'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	

	public function tixian_del($log_id,$data){
		$Memberlog = D('Memberlog');
		$detail = $Memberlog->find($log_id);
		if(false !== $this->where(array('log_id'=>$log_id))->save($data)){
			if(false !==D('member')->updateCount($detail['uid'],'dong_price',$detail['number'])){
				D('member')->updateCount($detail['uid'],'price',$detail['number']*-1);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function tixian_log($log_id,$data){
		$Memberlog = D('Memberlog');
		$detail = $Memberlog->find($log_id);
		if(false !== $this->where(array('log_id'=>$log_id))->save($data)){
			if(false !==D('member')->updateCount($detail['uid'],'dong_price',$detail['number'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function tixian($row)
    {
		$config = D('Setting')->fetchAll();
		
		if($row['from'] == 'price'){
			$row['number'] = sprintf("%.2f", $row['number']/100*-1);
			$row['last'] = $row['number']-$row['shouxufei'];
		}
		return $row;
	}
	
	public function tixian_del2($log_id){
		$Memberlog = D('Memberlog');
		$detail = $Memberlog->find($log_id);
		if(false !==D('member')->updateCount($detail['uid'],'dong_price',$detail['number'])){
			D('member')->updateCount($detail['uid'],'price',$detail['number']*-1);
			return true;
		}else{
			return false;
		}
	}

    public function _format_row($row)
    {
		if($row['from'] == 'price'){
			$row['number'] = sprintf("%.2f", $row['number']/100);
		}
		return $row;
	}
}