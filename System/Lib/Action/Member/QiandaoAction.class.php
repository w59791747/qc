<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class QiandaoAction extends CommonAction {
	public  function qiandao(){
		$month_date = date('Y-m-').'1';
		$start_time = strtotime($month_date);
		$start_week = date('w', $start_time);
		$total_month_day = date('t', $start_time);
		
		$weeks_in_month = ceil(($start_week+$total_month_day)/7);
		 
		$month_day_arr = array();
		$start_month_day = 1;
		for($i=0;$i<$weeks_in_month;$i++) {
		 
			for($j=0;$j<7;$j++){
				if($i ==0 && $j >= $start_week) {
					$month_day_arr[$i][$j] = $start_month_day;
					$start_month_day++;
				} elseif($i == 0) {
					$month_day_arr[$i][$j] = '';
				} else {
					$month_day_arr[$i][$j] = $start_month_day;
					$start_month_day++;
				}
		 
				if($start_month_day > $total_month_day){
					break;
				}
			}
		}
		$obj = D('qiandao');
		$date = date('Y-m-d');
		$is_qiandao = $obj->where(array('uid'=>$this->uid,'time'=>$date))->find();
		$this->assign('is_qiandao', $is_qiandao);
		$this->assign('jifen', $this->_CONFIG['integral']['qiandao']);

		$qiandao = $obj->where(array('uid'=>$this->uid,'time'=>array('LIKE','%'.DATE('Y-m').'%')))->select();
		
		foreach($qiandao as $k => $v){
			$arr[] = substr($v['time'],-2)+0;
		}
		$this->assign('arr', $arr);
		$this->assign('month_day_arr', $month_day_arr);
		$this->display();
	}


	public  function qiandao_create()
	{
		if(!$this->member){
			$this->chuangSuccess('登录失效,请重新登录',U('passport/login'));
		}else{
			$obj = D('qiandao');
			$date = date('Y-m-d');
			$is_qiandao = $obj->where(array('uid'=>$this->uid,'time'=>$date))->find();
			if($is_qiandao){
				$this->chuangError('您已经签到过了,请明天再签到');
			}else{
				$data['dateline'] = NOW_TIME;
				$data['uid'] = $this->uid;
				$data['jifen'] = $this->_CONFIG['integral']['qiandao'];
				$data['time'] = $date;
				if($qiandao_id = $obj->add($data)){
					if(false !==D('member')->updateCount($this->uid,'gold',$this->_CONFIG['integral']['qiandao'])){
						$data2['uid'] = $this->uid;
						$data2['from'] = 'gold';
						$data2['number'] = $this->_CONFIG['integral']['qiandao'];
						$data2['log'] = '签到送积分';
						$data2['status'] = '1';
						$data2['clientip'] = get_client_ip();
						$data2['dateline'] = NOW_TIME;
						D('Memberlog')->add($data2);
					}

				   $obj->cleanCache();
				   $this->chuangSuccess('签到成功',$_SERVER['HTTP_REFERER']);
				   
				}
				$this->chuangError('操作失败！');
			}
			
		}
	}
}
