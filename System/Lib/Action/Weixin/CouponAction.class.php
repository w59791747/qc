<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CouponAction extends CommonAction 
{

	public function detail($coupon_id=null)
    {
		$obj = D('Weixin_coupon');
		if(!($coupon_id = (int)$coupon_id) && !($coupon_id = $this->_post('coupon_id'))){
			$this->Error('未指定要修改的内容ID');
        }else if(!$detail = $obj->where(array('coupon_id'=>$coupon_id))->select()){
			$this->Error('该优惠券不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			 
			$objsn = D('Weixin_couponsn');
			$list = $objsn->where(array('coupon_id'=>$coupon_id,'openid'=>$openid))->select();
			$aa = $obj->find($coupon_id);
			$bb['views'] = $aa['views'] +1;
			$bb['coupon_id'] = $aa['coupon_id'];
			$obj->save($bb);

			$this->assign('my_sn_list',$list);
			$this->assign('detail',$detail);
			
			
			
			$map = array();
			$map['openid'] = $openid;
			
			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
			if (!empty ( $ltime ) && $ltime <= time ()) {
				$error = '您来晚啦';
			} else if ($detail ['num']<=$detail['down_count']) {
				$error = '优惠券已经领取光啦';
			}else if ($detail ['max_count'] > 0 && $detail ['max_count'] <= count($list)) {
				$error = '您的领取名额已用完啦';
			}
			$this->assign('error',$error);
			$this->display();
		}
    }

	public function preview()
	{
		$obj = D('Weixin_coupon');
		$objsn = D('Weixin_couponsn');
		$list = $obj->select();
		foreach($list as $k => $v){
			$items[$v['coupon_id']] = $v;
		}
		$obj = D('Weixin_coupon');
		$this->assign('items',$items);
		$this->assign('time',time());
		$stime = strtotime($items['stime']);
		$ltime = strtotime($items['ltime']);
		$this->assign('ltime',$ltime);
		$this->assign('stime',$stime);
		
		if(empty($openid)){
			$openid = $this->access_openid();
		}
		$map =array();
		$map['openid'] = $openid;$map['is_use'] = 1;
		$list1 = $objsn->where($map)->select();
		$map =array();
		$map['openid'] = $openid;$map['is_use'] = 0;
		$list2 = $objsn->where($map)->select();
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		$this->display();
		
	}

	public function sign($coupon_id=null)
	{
		$obj = D('Weixin_coupon');
		$objsn = D('Weixin_couponsn');
		if(!($coupon_id = (int)$coupon_id) && !($coupon_id = $this->_post('coupon_id'))){
			$this->Error('未指定要修改的内容ID');
        }else if(!$detail = $obj->find($coupon_id)){
			$this->Error('该优惠券不存在或已经删除');
        }else{
			
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			$member = D('Member_weixin')->where(array('open_id'=>$openid))->find();
			
			$list = $objsn->where(array('coupon_id'=>$coupon_id,'openid'=>$openid))->select();
			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
			if (! empty ( $ltime ) && $ltime <= time ()) {
				$error = '您来晚啦';
			} else if ($detail ['max_count'] > 0 && $detail ['max_count'] <= count($list)) {
				$error = '您的领取名额已用完啦';
			} else if ($detail ['num']<=$detail['down_count']) {
				$error = '优惠券已经领取光啦';
			}else{
				$data ['sn'] = uniqid ();

				$data ['uid'] = $member['uid'];
				$data['coupon_id'] = $coupon_id;
				$data['openid'] = $openid;
				$data['nickname'] = $wx_info['nickname'];
				$data['dateline'] = time();
				if($sn = $objsn->add($data)){
					$data1['down_count'] = $detail['down_count'] + 1;
					$data1['coupon_id'] = $coupon_id;
					$obj->save($data1);
					$qrurl =  U('coupon/show', array('sn' => $sn));
					 header("Location:{$qrurl}");
				}
			}
			if($error){
				$this->assign('error',$error);
				$this->display('over');
			}
		}
	}

	public function show($sn)
	{
		$obj = D('Weixin_coupon');
		$objsn = D('Weixin_couponsn');
		if(!($sn = (int)$sn) && !($sn = $this->_post('sn'))){
			$this->Error('非法1访问');
        }else if(!$detail = $objsn->find($sn)){
            $this->Error('非法2访问');
        }else if(!$coupon = $obj->where(array('coupon_id'=>$detail['coupon_id']))->select()){
            $this->Error('非法3访问');
        }else{
        	$this->assign('detail',$detail);
			
			if($coupon){
				foreach($coupon as $k => $v){
					$coupon = $v;
				}
			}
			$this->assign('coupon',$coupon);
			$this->display();
		}
	}
}