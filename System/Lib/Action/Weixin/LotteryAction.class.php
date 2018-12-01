<?php
/**
 * Copy Right IJH.CC
 * Each engineer has a duty to keep the code elegant
 * $Id$
 */

class LotteryAction extends CommonAction {
	public function index()
    {
        exit();
    }
	
	public function show($lottery_id)
	{
		$obj = D('Weixin_lottery');
		$objsn = D('Weixin_lotterysn');
		if(!$lottery_id = (int)$lottery_id){
			$this->Error('没有指定大转盘ID');
        }else if(!$detail = $obj->find($lottery_id)){
			$this->Error('该大转盘不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$aa = $obj->find($lottery_id);
			$bb['views'] = $aa['views'] +1;
			$bb['id'] = $aa['id'];
			$obj->save($bb);

			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			$objm =  D('Member_weixin');
			$obju =  D('Member');
			$map = array();
			if($uid = $objm->where($map)->getField('uid')){
				$member = $obju->find($uid);
			}else{
				$this->Error('微信参数错误');
			}
			$filter['lottery_id'] = $lottery_id;
			$filter['openid'] = $openid;
			$list = $objsn->where($filter)->select();
			$now = time();
			$year=date('Y',$now);
			$month=date('m',$now);
			$day=date('d',$now);
			$firstSecond=mktime(0,0,0,$month,$day,$year);
			$lastSecond=mktime(23,59,59,$month,$day,$year);
			$filter['dateline']=array('between',array($firstSecond,$lastSecond));
			if($record =$objsn->where($filter)->order(array('dateline'=>'desc'))->select()){
				$this->assign('record',$record);
			}


			
			
			$Lottery = $detail;
			$data=$Lottery;

			// 1. 中过奖金	
			if ($record['islottery'] == 1) {				
				$data['end'] = 5;
				$data['sn']	 	 = $record['sn'];
				$data['uname']	 = $record['nick_name'];
				$data['prize']	 = $record['prize'];
				$data['openid'] 	 = $record['openid'];
			}
			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
			if (!empty ( $stime ) && $stime >= time ()) {
				$error = '该活动还没开始';
			}else if (!empty ( $ltime ) && $ltime <= time ()) {
				$error = '您来晚啦';
			}else if ($detail ['max_num'] <= count($list)  && $detail ['daynums'] <= count($record)) {
				$error = '您的领取名额已用完啦';
			}
			$this->assign('error',$error);
		
			$data['On'] 		= 1;
			$data['lid']		= $Lottery['id'];
			$data['rid']		= intval($record['sn_id']);
			$data['usenums']    =$objsn->where(array('openid'=>$openid,'lottery_id'=>$lottery_id))->count();
			$data['info']=str_replace('&lt;br&gt;','<br>',$data['info']);
			$data['endinfo']=str_replace('&lt;br&gt;','<br>',$data['end_tips']);

			$this->assign('Dazpan',$data);
			
			foreach($record as $k => $v){
			    switch($v['prize']){
			        case 1:$record[$k]['award'] = $data['fist'];break;
			        case 2:$record[$k]['award'] = $data['second'];break;
			        case 3:$record[$k]['award'] = $data['third'];break;
			        case 4:$record[$k]['award'] = $data['four'];break;
			        case 5:$record[$k]['award'] = $data['five'];break;
			        case 6:$record[$k]['award'] = $data['six'];break;
			    }
			}
			$this->assign('record',$record);
			$this->assign('openid',$openid);
			$this->display();
		}
		
	}

	public function getajax($id,$rid)
	{	
		
		$id 		=	(int)$id;
		$rid 		= 	(int)$rid;
		
		$Lottery= D('Weixin_lottery')->find($id);
		$data=$this->prizeHandle($Lottery);
		 if ($data['end']==3){
			$sn	 	 = $data['sn'];
			$uname	 = $data['wecha_name'];
			$prize	 = $data['prize'];
			$tel 	 = $data['phone'];
			$msg = "您已经中过了";
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		} 
		if ($data['end']==-1){
			$msg = $data['winprize'];
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		}
		if ($data['end']==-2){
			$msg = $data['winprize'];
			echo '{"error":1,"msg":"'.$msg.'"}';
			exit;
		}
		
		if ($data['prizetype'] >= 1 && $data['prizetype'] <= 6) {
			echo '{"success":1,"sn":"'.$data['sncode'].'","prizetype":"'.$data['prizetype'].'","usenums":"'.$data['usenums'].'"}';
		}else{
			echo '{"success":0,"prizetype":"","usenums":"'.$data['usenums'].'"}';
		}
		exit();
	}

	public function getPrizeName($Lottery,$prize)
	{
		$array = array('1'=>'frist','2'=>'second','3'=>'third','4'=>'four','5'=>'five','6'=>'six');
		return $Lottery[$array[$prize]];
	}

	public function prizeHandle($Lottery)
	{	
		if(empty($openid)){
			$openid = $this->access_openid();
		}
		
		$client = $this->wechat_client();
		$wx_info = $client->getUserInfoById($openid);
		$objm =  D('Member_weixin');
		$obju =  D('Member');
		$map = array();
		$map['openid'] = $openid;
		$uid = $objm->where($map)->getField('uid');
		$member = $obju->find($uid);
		$now=time();
		$id 		= $Lottery['id'];
		$isday = 0;
		$where 		= array('openid'=>$openid,'lottery_id'=>$Lottery['id']);
		$count 	= D('Weixin_lotterysn')->where($where)->count();
		$record = D('Weixin_lotterysn')->where($where)->select();
		if($record){
			$record['usenums'] = $count;
		}else{
			$record['usenums'] = 0;
		}
		$stime = strtotime($Lottery['stime']);
		$ltime = strtotime($Lottery['ltime']);
		if ($ltime < $now) {
			$data['end'] = 2;
			$data['end_tips'] = $Lottery['end_tips'];
			$data['end_photo']  = empty($Lottery['end_photo']) ? 1 : $Lottery['end_photo'];
		}else{
				//最多抽奖次数
			$LotteryedRecordWhere=array('lottery_id'=>$id,'islottery'=>1);
			$prizedCount = D('Weixin_lotterysn')->where($LotteryedRecordWhere)->count();
			if (0){
				$data['end'] = 3;
				$data['msg'] = "您已经中过奖了，不能再领取了，谢谢";
				$data['wecha_name']=$record['nickname'];
				$data['sn']  = $record['sn'];
				$data['myprize'] 	= $this->getPrizeName($Lottery,$record['prize']);
				$data['prize'] 	= $record['prize'];
			}else {
					//是否已经够次数
				if ($record['usenums'] >= $Lottery['max_num'] ) {
					$data['end'] = -1;
					$data['prizetype'] = 4; //啥都没了
					$data['zjl']	  = 0;
					$data['usenums']  = $record['usenums'];
					$data['winprize']	   = "抽奖次数已经用完";
						//exit;
				}else{
						//当天的次数
					$year=date('Y',$now);
					$month=date('m',$now);
					$day=date('d',$now);
					$firstSecond=mktime(0,0,0,$month,$day,$year);
					$lastSecond=mktime(23,59,59,$month,$day,$year);
					foreach($record as $k => $v){
						if($v['dateline']>=$firstSecond && $v['dateline']<=$lastSecond){
							$thisDayNums++;
						}
					}
						if ($thisDayNums>=$Lottery['daynums']){
							$data['end'] = -2; //
							$data['zjl']	  = 0;
							$data['winprize']	   = "今天已经抽了".$thisDayNums."次了，没名额了，明天再来吧";
						}else {
							//3.没有领过,次数没达到,开始随机发放优惠券
							if($Lottery['fistlucknums']  == $Lottery['fistnums'] &&
							$Lottery['secondlucknums'] == $Lottery['secondnums'] &&
							$Lottery['thirdlucknums']  == $Lottery['thirdnums'] &&
							$Lottery['fourlucknums']  == $Lottery['fournums'] &&
							$Lottery['fivelucknums']  == $Lottery['fivenums'] &&
							$Lottery['sixlucknums']  == $Lottery['sixnums']
							){
								$prizeType=7;
							}else{
								$prizeType=intval($this->get_prize($Lottery));
							}
							//排除没有设置的优惠券
							//奖品数 != 已经领取该奖品数 => 还有奖品

							switch ($prizeType){
								default:
									$data['prizetype'] = 7; //啥都没了
									$data['zjl']	  = 0;
									$data['winprize']	   = "谢谢参与";
									$isLottery=0;
									$data['sncode']    = '';
									break;
								case 1:
									$data['prizetype'] = 1;
									$data['sncode'] = uniqid();
									$data['winprize']	   = $Lottery['fist'];
									$data['zjl']	   = 1;
									$fistlucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('fistlucknums');
									$data1['id']->$id;
									$data1['fistlucknums'] = $fistlucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'fistlucknums', 1);
									$isLottery=1;
									break;
								case 2:
									$data['prizetype'] = 2;
									$data['winprize']  = $Lottery['second'];
									$data['zjl']	   = 1;
									$data['sncode']    = uniqid();
									$secondlucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('secondlucknums');
									$data1['id']->$id;
									$data1['secondlucknums'] = $secondlucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'secondlucknums', 1);
									$isLottery=1;
									break;
								case 3:
									$data['prizetype'] = 3;
									$data['winprize']	   = $Lottery['third'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$thirdlucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('thirdlucknums');
									$data1['id']->$id;
									$data1['thirdlucknums'] = $thirdlucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'thirdlucknums', 1);
									$isLottery=1;
									break;
								case 4:
									$data['prizetype'] = 4;
									$data['winprize']	   = $Lottery['four'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$fourlucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('fourlucknums');
									$data1['id']->$id;
									$data1['fourlucknums'] = $fourlucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'fourlucknums', 1);
									$isLottery=1;
									break;
								case 5:
									$data['prizetype'] = 5;
									$data['winprize']	   = $Lottery['five'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$fivelucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('fivelucknums');
									$data1['id']->$id;
									$data1['fivelucknums'] = $fivelucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'fivelucknums', 1);
									$isLottery=1;
									break;
								case 6:
									$data['prizetype'] = 6;
									$data['winprize']	   = $Lottery['six'];
									$data['zjl']	   = 1;
									$data['sncode'] = uniqid();
									$sixlucknums = D('Weixin_lottery')->where(array('id'=>$id))->getField('sixlucknums');
									$data1['id']->$id;
									$data1['sixlucknums'] = $sixlucknums + 1;
									D('Weixin_lottery')->save($data1);
									//K::M('weixin/lottery')->update_count($id, 'sixlucknums', 1);
									$isLottery=1;
									break;
							}
						

							$data_sn = array('uid'=>$member['uid'],'openid'=>$openid,'lottery_id'=>$Lottery['id'],'nickname'=>$wx_info['nickname'],'islottery'=>$isLottery,'sn'=>$data['sncode'],'prize'=>intval($data['prizetype']),'dateline'=>$now,'photo'=>$wx_info['headimgurl']);
							$sn_id = D('Weixin_lotterysn')->add($data_sn);
						
						}

					}
				}
			}
		$record =D('Weixin_lotterysn')->find($sn_id);
		$count =D('Weixin_lotterysn')->where(array('openid'=>$openid))->count();
		$data['rid']		= intval($record['sn_id']);
		$data['sn']		= $record['sn'];
		$data['dateline']	= $record['dateline'];
		$data['usenums']	= $count;
		return $data;
		
	}

	protected function get_prize($Lottery){
		$id=intval($Lottery['id']);

		$joinNum=$Lottery['joinnum'];
		//
		$firstNum=intval($Lottery['fistnums'])-intval($Lottery['fistlucknums']);
		$secondNum=intval($Lottery['secondnums'])-intval($Lottery['secondlucknums']);
		$thirdNum=intval($Lottery['thirdnums'])-intval($Lottery['thirdlucknums']);
		$fourthNum=intval($Lottery['fournums'])-intval($Lottery['fourlucknums']);
		$fifthNum=intval($Lottery['fivenums'])-intval($Lottery['fivelucknums']);
		$sixthNum=intval($Lottery['sixnums'])-intval($Lottery['sixlucknums']);
		$multi=intval($Lottery['max_num']);//最多抽奖次数
		$prize_arr = array(
			'0' => array('id'=>1,'prize'=>'一等奖','v'=>$firstNum,'start'=>0,'end'=>$firstNum), 
			'1' => array('id'=>2,'prize'=>'二等奖','v'=>$secondNum,'start'=>$firstNum,'end'=>$firstNum+$secondNum), 
			'2' => array('id'=>3,'prize'=>'三等奖','v'=>$thirdNum,'start'=>$firstNum+$secondNum,'end'=>$firstNum+$secondNum+$thirdNum),
			'3' => array('id'=>4,'prize'=>'四等奖','v'=>$fourthNum,'start'=>$firstNum+$secondNum+$thirdNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum),
			'4' => array('id'=>5,'prize'=>'五等奖','v'=>$fifthNum,'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum),
			'5' => array('id'=>6,'prize'=>'六等奖','v'=>$sixthNum,'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum,'end'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum),
			'6' => array('id'=>7,'prize'=>'谢谢参与','v'=>(intval($Lottery['predict_num']))*$multi-($firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum),'start'=>$firstNum+$secondNum+$thirdNum+$fourthNum+$fifthNum+$sixthNum,'end'=>intval($Lottery['predict_num'])*$multi)
		);
		//
		foreach ($prize_arr as $key => $val) { 
			$arr[$val['id']] = $val; 
		} 
		//-------------------------------	 
		//随机抽奖[如果预计活动的人数为1为各个奖项100%中奖]
		//-------------------------------	 
		if ($Lottery['predict_num'] == 1) {
	 
			if ($Lottery['fistlucknums'] <= $Lottery['fistnums']) {
				$prizetype = 1;	
			}else{
				$prizetype = 7;	
			}			
			 
		}else{
			$prizetype = $this->get_rand($arr,(intval($Lottery['predict_num'])*$multi)-$joinNum); 
		}
		//$winprize = $prize_arr[$rid-1]['prize'];
		switch($prizetype){
			case 1:
					 
				if ($Lottery['fistlucknums'] >= $Lottery['fistnums']) {
					 $prizetype = ''; 
					 //$winprize = '谢谢参与'; 
				}else{
					 
					$prizetype = 1; 					
				    //$lottery_db->where(array('id'=>$id))->setInc('fistlucknums');
				}
			break;
				
			case 2:
				if ($Lottery['secondlucknums'] >= $Lottery['secondnums']) {
						$prizetype = ''; 
						//$winprize = '谢谢参与';
				}else{
					//判断是否设置了2等奖&&数量
					if(empty($Lottery['second']) && empty($Lottery['secondnums'])){
						$prizetype = ''; 
						//$winprize = '谢谢参与';
					}else{ //输出中了二等奖
						$prizetype = 2; 					
						//$lottery_db->where(array('id'=>$id))->setInc('secondlucknums');
					}	 
					
				}
				break;
							
			case 3:
				if ($Lottery['thirdlucknums'] >= $Lottery['thirdnums']) {
					 $prizetype = ''; 
					// $winprize = '谢谢参与';
				}else{
					if(empty($Lottery['third']) && empty($Lottery['thirdnums'])){
						 $prizetype = ''; 
						// $winprize = '谢谢参与';
					}else{
						$prizetype = 3; 					
						//$lottery_db->where(array('id'=>$id))->setInc('thirdlucknums');
					} 
					
				}
				break;
						
			case 4:
				if ($Lottery['fourlucknums'] >= $Lottery['fournums']) {
					  $prizetype =  ''; 
					// $winprize = '谢谢参与';
				}else{
					 if(empty($Lottery['four']) && empty($Lottery['fournums'])){
					   	$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					 }else{
					 	$prizetype = 4; 					
						//$lottery_db->where(array('id'=>$id))->setInc('fourlucknums');
					 }					
				}
			break;
			
			case 5:
				if ($Lottery['fivelucknums'] >= $Lottery['fivenums']) {
					 $prizetype =  ''; 
					 //$winprize = '谢谢参与';
				}else{
					if(empty($Lottery['five']) && empty($Lottery['fivenums'])){
						$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					}else{
						$prizetype = 5; 					
						//$lottery_db->where(array('id'=>$id))->setInc('fivelucknums');
					} 
				}
			break;
			
			case 6:
				if ($Lottery['sixlucknums'] >= $Lottery['sixnums']) {
					 $prizetype =  ''; 
					// $winprize = '谢谢参与';
				}else{
					 if(empty($Lottery['six']) && empty($Lottery['sixnums'])){
					 	$prizetype =  ''; 
					 	//$winprize = '谢谢参与';
					 }else{
					 	$prizetype = 6; 					
						//$lottery_db->where(array('id'=>$id))->setInc('sixlucknums');
					 }
					
				}
			break;
							
			default:
					$prizetype =  ''; 
					//$winprize = '谢谢参与';
					
					break;
		}
		if (intval($prizetype)&&$prizetype<7){
			//M('Lottery_record')->where(array('lid'=> $id,'wecha_id'=>$this->_get('wecha_id')))->save(array('islottery'=>1));
		}
		
		return $prizetype;
	}

	protected function get_rand($proArr,$total) {

		    $result = 7; 
		    $randNum = mt_rand(1, $total); 
		    foreach ($proArr as $k => $v) {
		    	
		    	if ($v['v']>0){//奖项存在或者奖项之外
		    		if ($randNum>$v['start']&&$randNum<=$v['end']){
		    			$result=$k;
		    			break;
		    		}
		    	}
		    }

		    return $result; 
	}
}