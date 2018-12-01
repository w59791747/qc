<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ScratchAction extends CommonAction {

	public function index()
    {
        exit();
    }
	
	public function show($scratch_id) {
		$obj = D('Weixin_scratch');
		$objsn = D('Weixin_scratchsn');
		if(!($scratch_id = (int)$scratch_id) && !($scratch_id = $this->_post('scratch_id'))){
			$this->Error('没有指定刮刮乐ID');
        }else if(!$detail = $obj->find($scratch_id) ){
			$this->Error('该刮刮乐不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);	
			$objm =  D('Member_weixin');
			$obju =  D('Member');
			$map = array();
			$map['openid'] = $openid;

			$aa = $obj->find($scratch_id);
			$bb['views'] = $aa['views'] +1;
			$bb['scratch_id'] = $aa['scratch_id'];
			$obj->save($bb);

			$objp =  D('Weixin_prize');
			$filter['scratch_id'] = $scratch_id;
			import('ORG.Util.Page'); // 导入分页类
			$count = $objp->where($filter)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$prizes = $objp->where($filter)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($prizes){
				foreach($prizes as $k => $v){
					$prizess[$v['id']] = $v;
				}
				$this->assign('prizes',$prizess);
			}
			$all_prizes = $objsn->where(array('scratch_id'=>$scratch_id))->order(array('sn_id' => 'desc'))->select();
			foreach ($all_prizes as $all) {
				if ($all['prize_id'] > 0) {
					$has[$all['prize_id']] += 1; // 每个奖项已经中过的次数
					$new_scratch[] = $all; // 最新中奖记录
					if($all['openid'] == $openid){
						$scratchsn[] = $all;
					} // 我的中奖记录
				} else {
					$no_count += 1; // 没有中奖的次数
				}
				// 记录我已抽奖的次数
				if($all['openid'] == $openid){
					$my_count += 1;
				} 
			}
			$detail['count'] = $detail['max_num'] - $my_count;
			$error = '';
			$stime = strtotime($detail['stime']);
			$ltime = strtotime($detail['ltime']);
			if ($ltime <= time ()) {
				$error = '活动已结束';
			} else if($stime >= time ()){
				$error = '活动还未开始';
			}else if ($detail['count']<=0) {
				$error = '您的刮卡机会已用完啦';
			}
			$this->assign('error',$error);

			

			//抽奖详情
			if(!$sdetail = $obj->find($scratch_id)) {
				$this->Error('该刮刮乐不存在或已经删除');
			}else{
				$this->assign('sdetail',$sdetail);
			}
		

			//中奖详情
			$filter = $pager = array();
			$pager['page'] = max(intval($page), 1);
			$pager['limit'] = $limit = 50;
					if($SO = $this->_post('SO')){
				$pager['SO'] = $SO;
				
			}
			$filter['scratch_id'] = $scratch_id;
			$filter['prize_id'] = array('gt','0');
			$zitems = $objsn->where($filter)->order(array('sn_id' => 'desc'))->select();
			if($zitems){
				$uids = '';
				foreach($zitems as $k => $v){
					$uids[$v['uid']] = $v['uid'];
					$zitems[$k]['title'] = $prizess[$v['prize_id']]['title'];
					//$zitems[$k]['photo'] = $prizess[$v['prize_id']]['photo'];
					$zitems[$k]['name'] = $prizess[$v['prize_id']]['name'];
				}
				$data = array();
				$data['uid']=array('in',$uids);
				$member_list = D('Member')->where($data)->select();
				$this->assign('member_list',$member_list);
			}
			
			$this->assign('zitems',$zitems);
			$this->assign('zdetail',$zdetail);

			//我的中奖信息
			
			$myzitems = '';
			foreach($zitems as $k => $v){
				if($v['openid'] == $openid){
					$myzitems[$k] = $v;
				}
			}
			$this->assign('myzitems',$myzitems);
			$link =  U('scratch/lottery', array('scratch_id' => $scratch_id));
			$link1 = U('scratch/set_sn_code');
			
			$this->assign('link',$link);
			$this->assign('link1',$link1);
			$this->assign('scratchsn',$scratchsn);
			$this->assign('new_scratch',$new_scratch);
			$this->assign('detail',$detail);
			$this->display();
		}
		
	}

	// 抽奖算法 中奖概率 = 奖品总数/(预估活动人数*每人抽奖次数)
	function lottery($scratch_id) {
		
		$prize = array ();
		if(!($scratch_id) && !($scratch_id = $this->_post('id'))){
           
            $prize ['id'] = 0;
			$prize ['title'] = '刮刮乐不存在';
        }elseif(!$data = D('Weixin_scratch')->find($scratch_id)){
 			$prize ['id'] = 0;
			$prize ['title'] = '刮刮乐不存在';

        }else{
			$filter['scratch_id'] = $scratch_id;
			$prizes = D('Weixin_prize')->where(array('scratch_id'=>$scratch_id))->select();
			$all_prizes = D('Weixin_scratchsn')->where(array('scratch_id'=>$scratch_id))->order(array('sn_id' => 'desc'))->select();
			
			if(empty($openid)){
							$openid = $this->access_openid();
						}
			foreach ( $all_prizes as $all ) {
				if ($all ['prize_id'] > 0) {
					$has[$all ['prize_id']] += 1; // 每个奖项已经中过的次数
					$new_prizes [] = $all; // 最新中奖记录
					if($all ['openid'] == $openid){
						$scratchsn [] = $all;
					} // 我的中奖记录
				} else {
					$no_count += 1; // 没有中奖的次数
				}
				
				// 记录我已抽奖的次数
				if($all ['openid'] == $openid){
					$my_count += 1;
				} 
			}

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
			echo $prize['id'].'|'.$prize['title'];
			$this->assign('prize',$prize);
			exit;
		}
	}

	function set_sn_code() {
		if(empty($openid)){
			$openid = $this->access_openid();
		}
		$client = $this->wechat_client();
		$wx_info = $client->getUserInfoById($openid);
		$member = D('Member_weixin')->where(array('openid'=>$openid))->find();

		if(!$_POST['id'] || !$_POST['prize_id']){
			$data ['uid'] = $member['uid'];
			$data['scratch_id'] = $_POST['id'];
			$data['openid'] = $openid;
			$data['nickname'] = $wx_info['nickname'];
			$data ['prize_id'] = 0;
			$data ['dateline'] =time();
			$data['uid'] = $member['uid'];

			D('weixin_scratchsn')->add($data);
		}else{
			$data ['sn'] = uniqid ();
			$data ['uid'] = $member['uid'];
			$data['scratch_id'] = $_POST['id'];
			$data['openid'] = $openid;
			$data['nickname'] = $wx_info['nickname'];
			$data ['prize_id'] = $_POST['prize_id'];
			$data['photo'] = $wx_info['headimgurl'];//修改
			$data['uid'] = $member['uid'];
			$data ['dateline'] =time();
			if (! empty ( $data ['scratch_id'] )) {
				$scratch = D('weixin_scratch')->find($data ['scratch_id']);

			}
			$title = '';
			if (! empty ( $data ['prize_id'] )) {
				$items = D('weixin_prize')->find($data ['prize_id']);
			}
			$data ['prize_title'] = $items['name'];
			D('weixin_scratchsn')->add($data);
			echo $res;
		}
	}
}