<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class MemberlogAction extends CommonAction{
    
    public  function index(){
       $Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   $map['from'] = 'gold';
	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
       $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberlog->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'uid','Member','memberlist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

	public  function price(){
       $Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   $map['from'] = 'price';
	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
       $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberlog->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('dateline'=>'desc'))->select();
	   $this->itemsby($list,'uid','Member','memberlist');
	   foreach($list as $k =>$v){
			$l[$k] = $Memberlog->_format_row($v);
	   }
       $this->assign('list',$l);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

	public function deltixian($log_id = null)
	{
		$Memberlog = D('Memberlog');
        if ($log_id = (int) $log_id) {
            if (!$detail = $Memberlog->find($log_id)) {
                $this->error('请选择要提现的日志');
            }else{
				 if ($this->isPost()) {
					$data = $this->delCheck($log_id);
					
					if (false !==$Memberlog->tixian_del($log_id,$data)) {
						$this->chuangSuccess('提现成功', U('memberlog/tixian'));
					}
					
					$this->chuangError('操作失败');
				} else {
					$detail = $Memberlog->tixian($detail);
					$this->assign('detail', $detail);

					$this->assign('Member', D('Member')->where(array('uid'=>$detail['uid']))->find());
					$this->display();
				}
			}
		}
		
	}

	private function delCheck($log_id) {
		if (empty($log_id)) {
            $this->chuangError('日志不存在');
        }
		$Memberlog = D('Memberlog');
		$obj = D('Member');
		$detail = $Memberlog->find($log_id);
		if (empty($detail)) {
            $this->chuangError('日志不存在');
        }

        $data = $this->_post('data', 'htmlspecialchars');
        if (empty($data['log'])) {
            $this->chuangError('日志不能为空');
        }
		$data['admin'] = $this->admin['admin_id'];
		if($detail['number']<0){
			$data['status'] = -1;
		}
        return $data;
    }

	public function tixian($log_id = null)
	{
		$Memberlog = D('Memberlog');
        if ($log_id = (int) $log_id) {
            if (!$detail = $Memberlog->find($log_id)) {
                $this->error('请选择要提现的日志');
            }
			$member = D('Member')->find($detail['uid']);
           
			$detail = $Memberlog->tixian($detail);
			$this->assign('detail', $detail);

			$this->assign('Member', D('Member')->where(array('uid'=>$detail['uid']))->find());
			$this->display('tixianadd');
            
        } else {
               import('ORG.Util.Page');// 导入分页类
			   $map = array();
			   $map['from'] = 'price';
			   $map['status'] = array('IN','-1,1,2,-3');
			   $map['card_id'] = array('gt','0');
			   $map['number'] = array('lt','0');
			   if($uid = $this->_param('uid','htmlspecialchars')){
					$map['uid'] =  $uid;
					$this->assign('uid',$uid);
				}
			   $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
			   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
			   $show       = $Page->show();// 分页显示输出
			   $list = $Memberlog->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('dateline'=>'desc'))->select();
			   $this->itemsby($list,'uid','Member','memberlist');
			   foreach($list as $k =>$v){
					$l[$k] = $Memberlog->tixian($v);
			   }
			    $this->itemsby($list,'card_id','Membercard','cardlist');

			   $this->assign('list',$l);// 赋值数据集
			   $this->assign('page',$show);// 赋值分页输出
			   $this->display(); // 输出模板
        }
	}
	
	public function queren($log_id = null)
	{
		$Memberlog = D('Memberlog');
       
		if (!$detail = $Memberlog->find($log_id)) {
			$this->error('请选择要提现的编号');
		}
		if ($detail['status'] != '1') {
			$this->error('提现内容错误');
		}
		$member = D('Member')->find($detail['uid']);

		if ($this->isPost()) {
			$data = $this->priceCheck($log_id);
			if($member['dong_price']+$detail['number']<0){
				$this->chuangError('提现失败');
			}else if($detail['status'] == 2){
				$this->chuangError('资金已被提现');
			}else{
				$number = -$detail['number']-$detail['shouxufei']*100;
				$result = $this->add_lianlian($detail,$number);
				$re = json_decode($result,true);
				
				if($re['ret_code']=='0000'){
					$data['code'] = $re['no_order'];
					$data['status'] = -3;
					$data['type']='lianlian';
					$data['log'] = '系统处理中，请稍后再次查询';
					D('Memberlog')->where(array('log_id'=>$log_id))->save($data);
					$this->chuangSuccess('系统处理中，请稍后再次查询',U('memberlog/tixian'));
				}else{
					 $data['code'] = $re['no_order'];
					 $data['status'] = -2;
					 $data['type']='lianlian';
					 $data['log'] = $re['ret_msg'];
					 D('Memberlog')->where(array('log_id'=>$log_id))->save($data);
					 D('Memberlog')->tixian_del2($log_id);
					 $this->chuangSuccess('提现失败'.$re['ret_msg'],U('memberlog/tixian'));
				}
			}
			$this->chuangError('操作失败');
		} else {
			$detail = $Memberlog->tixian($detail);
			$this->assign('detail', $detail);

			$this->assign('Member', D('Member')->where(array('uid'=>$detail['uid']))->find());
			$this->display('tixianadd');
		}
	}
	
	public function add_lianlian($detail,$number)
	{
		$file = APP_PATH . "Libs/payments/lianlian/daifu.php";
		include($file);
		$payment = D('Payment')->checkPayment('lianlian');
		$config = $payment['setting'];
		$Setting = D('Setting')->fetchAll();
		$config['notify_url'] = $Setting['site']['host'].U('home/verify/daifu');

		$daifu = new daifu($config);
		
		$Membercard = D('Membercard');
		$card = $Membercard->find($detail['card_id']);
		$city = explode(',',$card['city']);
		$detail = D('Memberdetail')->find($detail['uid']);
		$AgentPay = array(
			'orderCode' => substr(time(),-6).rand(100000,999999),
			'accName' => $card['name'],
			'ic_card' =>  $detail['card'],
			'accNo' =>  $card['card'],
			'bank' =>  $card['bank'],
			'code' =>  $card['code'],
			'city' =>  $card['city'],
			'zhihang' =>  $card['zhihang'],
			'tranAmt' => $number/100,
			'shuxing' =>  'C',
			'leixing' =>  'DEBIT',
			'zhaiyao' =>  'text',
			'laiyuan' => $Setting['site']['host'],
		);
		
		$datas = $daifu->daifu_r($AgentPay);
		return $datas;
	}

	private function priceCheck($log_id) {
		if (empty($log_id)) {
            $this->chuangError('日志不存在');
        }
		$Memberlog = D('Memberlog');
		$obj = D('Member');
		$detail = $Memberlog->find($log_id);
		if (empty($detail)) {
            $this->chuangError('日志不存在');
        }

        $data = $this->_post('data', 'htmlspecialchars');
        if (empty($data['log'])) {
            $this->chuangError('日志不能为空');
        }
		$data['admin'] = $this->admin['admin_id'];
		if($detail['number']<0){
			$data['status'] = 2;
		}
        return $data;
    }

	public function sms()
	{
		$Smslog = D('Smslog');
        import('ORG.Util.Page'); // 导入分页类
        $map = array();
        $count = $Smslog->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Smslog->where($map)->order(array('log_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
	}



}
