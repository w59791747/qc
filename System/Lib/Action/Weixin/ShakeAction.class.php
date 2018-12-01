<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class ShakeAction extends CommonAction {

	public function index()
    {
        exit();
    }
	

	public function preview($shake_id=null)
	{
		$obj = D('Weixin_shake');
		$objsn = D('Weixin_shakesn');
        if(!($shake_id = (int)$shake_id) && !($shake_id = $this->_post('shake_id'))){
			$this->Error('没有指定摇一摇ID');
        }else if(!$detail = $obj->find($shake_id)){
			$this->Error('没有指定摇一摇ID');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}

			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);

			import('ORG.Util.Page'); // 导入分页类
			$filter['shake_id'] = $shake_id;
			$prizes = D('Weixin_shakeprize')->where($filter)->select();
			if($prizes){
				$this->assign('prizes',$prizes);
			}

			$aa = $obj->find($shake_id);
			$bb['views'] = $aa['views'] +1;
			$bb['shake_id'] = $aa['shake_id'];
			$obj->save($bb);

			
			
			$all_prizes = D('Weixin_shakesn')->where($filter)->order(array('sn_id' => 'desc'))->select();

			$filter['openid'] = $openid;

			foreach ($all_prizes as $all ) {
				if ($all['prize_id'] > 0) {
					$has[$all ['prize_id']] += 1; // 每个奖项已经中过的次数
					$new_shake[] = $all; // 最新中奖记录
					if($all['openid'] == $openid){
						$shakesn[] = $all;
					} // 我的中奖记录
				} else {
					$no_count += 1; // 没有中奖的次数
				}
				
				// 记录我已抽奖的次数
				if($all['openid'] == $openid){
					$my_count += 1;
				} 
			}
			include_once "System/Lib/Action/Weixin/jssdk.php";
			$weixin = $this->_CONFIG['weixin'];
			$jsSdk = new WeixinJSSDK($weixin['appid'], $weixin['appsecret']);
			$jsSdk1 = $jsSdk->getSignPackage();
			$this->assign('wxjscfg',$jsSdk1);
			$detail['wxjscfg'] = $jsSdk->getCardSignPackage($detail['id']);
			
			$detail['count'] = $detail['max_num'] - $my_count;
			$error = '';
			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
			if ($ltime <= time ()) {
				$error = '活动已结束';
			} else if($stime >= time ()){
				$error = '活动还未开始';
			}else if ($detail['count']<=0) {
				$error = '您的摇一摇机会已用完啦';
			} 
			$this->assign('error',$error);
			// 抽奖算法
			if(!$error){
				 $this->_lottery ( $detail, $prizes, $new_shake, $my_count, $has, $no_count );
			}
			$prizes_list = D('Weixin_shakeprize')->where(array('shake_id'=>$shake_id))->select();
			foreach($prizes_list as $k =>$v){
				$prizes_lists[$v['id']] = $v;
			}

			foreach($shakesn as $k =>$v){
				if($v['prize_id']>0){
					$shakesns[$v['sn_id']] = $v;
				}
			}

			foreach($all_prizes as $k =>$v){
				if($v['prize_id']>0){
					$all_prizess[$v['sn_id']] = $v;
				}
			}
			$this->assign('prizes_list',$prizes_lists);
			$this->assign('shakesn',$shakesns);
			
			$this->assign('all_prizes',$all_prizess);
			$this->assign('new_shake',$new_shake);
			$this->assign('detail',$detail);
			$this->display();
		}
	}

	// 抽奖算法 中奖概率 = 奖品总数/(预估活动人数*每人抽奖次数)
	function _lottery($data, $prizes, $new_prizes, $my_count = 0, $has = array(), $no_count = 0) {
		$max_num = empty ( $data ['max_num'] ) ? 1 : $data ['max_num'];
		$count = $data ['predict_num'] * $max_num; // 总基数
		                                                    // 获取已经中过的奖
		foreach ( $prizes as $p ) {
			$prizesArr [$p ['id']] = $p;
			
			$prize_num = $p ['num'] - $has [$p ['id']];
			for($i = 0; $i < $prize_num; $i ++) {
				$rand [] = $p ['id']; // 中奖的记录，同时通过ID可以知道中的是哪个奖
			}
		}
		
		if ($data ['predict_num'] != 1) {
			$remain = $count - count ( $rand ) - $no_count;
			$remain > 5000 && $remain = 5000; // 防止数组过大导致内存溢出
			for($i = 0; $i < $remain; $i ++) {
				$rand [] = 0; // 不中奖的记录
			}
		}
		if (empty ( $rand )) {
			$rand [] = - 1;
		}
		
		shuffle ( $rand ); // 所有记录随机排序
		$prize_id = $rand [0]; // 第一个记录作为当前用户的中奖记录
		$prize = array ();
		
		if ($prize_id > 0) {
			$prize = $prizesArr [$prize_id];
		} elseif ($prize_id == - 1) {
			$prize ['id'] = 0;
			$prize ['title'] = '奖品已抽完';
		} else {
			$prize ['id'] = 0;
			$prize ['title'] = '谢谢参与';
		}

		// 获取我的抽奖机会
		if (empty ( $data ['max_num'] )) {
			$prize ['count'] = 1;
		} else {
			$prize ['count'] = $max_num - $my_count - 1;
			$prize ['count'] < 0 && $prize ['count'] = 0;
		}
		if($max_num <= $my_count){
			$prize ['id'] = 0;
			$prize ['title'] = '您抽奖机会已经用完了';
		}
		$this->assign('prize',$prize);
	}

	function set_sn_code() {
		if(empty($openid)){
			$openid = $this->access_openid();
		}
		$client = $this->wechat_client();
		$wx_info = $client->getUserInfoById($openid);
		$weixin =D('Member_weixin')->where(array('openid'=>$openid))->find();
		$member = D('Member')->find($weixin['uid']);

		if(!$_POST['id'] || !$_POST['prize_id']){
			$data ['uid'] = $member['uid'];
			$data['shake_id'] = $_POST['id'];
			$data['openid'] = $openid;
			$data['nickname'] = $wx_info['nickname'];
			$data ['prize_id'] = 0;
			$data ['photo'] = $wx_info['headimgurl'];
			$data ['dateline'] =time();
			D('Weixin_shakesn')->add($data);
		}else{
			$data ['sn'] = uniqid ();
			$data ['uid'] = $member['uid'];
			$data['shake_id'] = $_POST['id'];
			$data['openid'] = $openid;
			$data['nickname'] = $wx_info['nickname'];
			$data ['prize_id'] = $_POST['prize_id'];
			$data ['photo'] = $wx_info['headimgurl'];
			
			if (! empty ( $data ['prize_id'] )) {
				$prize =D('Weixin_shakeprize')->find($data['prize_id']);
			}
			$data ['prize_title'] = $prize['title'];
			$data ['dateline'] =time();

			D('Weixin_shakesn')->add($data);
			echo $res;
		}
	}
}