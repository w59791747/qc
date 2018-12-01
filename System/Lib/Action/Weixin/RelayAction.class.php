<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class RelayAction extends CommonAction {

	
	
	public function preview($relay_id=null)
	{
		$obj = D('Weixin_relay');
		$objsn = D('Weixin_relaysn');
        if(!($relay_id = (int)$relay_id) && !($relay_id = $this->GP('relay_id'))){
            $this->Error('未指定要修改的内容ID');
        }else if(!$detail = $obj->find($relay_id)){
			$this->Error('该接力不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			
			$stime = strtotime($detail ['s_time']);
			$ltime = strtotime($detail ['l_time']);
			$list = $my_sn_list =$objsn->where(array('relay_id'=>$relay_id,'openid'=>$openid))->find();
			$this->assign('my_sn_list',$list);
			$aa = $obj->find($relay_id);
			$bb['views'] = $aa['views'] +1;
			$bb['relay_id'] = $aa['relay_id'];
			$obj->save($bb);
			include_once "System/Lib/Action/Weixin/jssdk.php";
			$weixin = $this->_CONFIG['weixin'];
			$jsSdk = new WeixinJSSDK($weixin['appid'], $weixin['appsecret']);
			$jsSdk1 = $jsSdk->getSignPackage();

			$this->assign('wxjscfg',$jsSdk1);
			$this->assign('detail',$detail);
			$condition = array ();
			$filter['relay_id'] = $relay_id;
			$prizes = D('Weixin_relayprize')->where($filter)->select();
			if($prizes){
				$this->assign('prizes',$prizes);
			}
			$sn_list = $objsn->where($filter)->order(array('gold' => 'desc'))->select();
			if($sn_list){
				$this->assign('sn_list',$sn_list);
			}
			$count = $objsn->where($filter)->count();
			$filter['openid'] = $openid;
			$filter['type'] = 1;
			$list_sn = D('Weixin_relaylist')->where($filter)->select();
			if($list_sn){
				$this->assign('list_sn',$list_sn);
			}

			$filter['openid'] = $openid;
			$filter['type'] = 2;
			$list_sn2 = D('Weixin_relaylist')->where($filter)->select();

			if($list_sn2){
				$list_sn3 = $arr3 = array();
				foreach($list_sn2 as $k => $v){
					if(in_array($v['jieliid'],$arr3)){
						$list_sn3[$v['jieliid']]['gold'] += $v['gold'];
						$list_sn3[$v['jieliid']]['cishu'] += 1;
					}else{
						$arr3[] = $v['jieliid'];
						$list_sn3[$v['jieliid']] = $v;
						$list_sn3[$v['jieliid']]['cishu'] = 1;
					}
				}
				$this->assign('list_sn3',$list_sn3);
			}

		
			
			if($my_sn_list){
				$url = __HOST__.U('relay/show', array('sn_id'=>$my_sn_list['sn_id']));
				$this->assign('url',$url);
			}
			$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
			$member = D('Member')->find($weixin['uid']);

			$stime = strtotime($detail ['stime']);
				$ltime = strtotime($detail ['ltime']);
				if (! empty ( $ltime ) && $ltime <= time ()) {
				$error = '您来晚啦';
			}
			$this->assign('time',time());
			$this->assign('error',$error);
			$this->display();
		}
	}

	public function sign($relay_id,$qian,$sn_id=null)
	{
		$obj = D('Weixin_relay');
		$objsn = D('Weixin_relaysn');
		if(!($relay_id = (int)$relay_id)){
			$this->Error('未指定该接力ID');
        }else if(!$detail = $obj->find($relay_id)){
			$this->Error('该接力不存在或已经删除');
        }else{
			if($sn_id<=0){
				if(empty($openid)){
					$openid = $this->access_openid();
				}
				$client = $this->wechat_client();
				$wx_info = $client->getUserInfoById($openid);
				$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
				$member = D('Member')->find($weixin['uid']);
				$stime = strtotime($detail ['stime']);
				$ltime = strtotime($detail ['ltime']);
				if (! empty ( $ltime ) && $ltime <= time ()) {
					$this->Errors('您来晚了');
				}else{
					$data ['cishu'] = 1;
					$data ['uid'] = $member['uid'];
					$data['relay_id'] = $relay_id;
					$data['openid'] = $openid;
					$data['nickname'] = $wx_info['nickname'];
					$data['gold'] = $qian;
					$data['img'] = $wx_info['headimgurl'];//修改
					$data['dateline'] = time();
					if($sn = $objsn->add($data)){
						$datas['openid'] = $openid;
						$datas['relay_id'] = $detail['relay_id'];
						$datas['nickname'] = $wx_info['nickname'];
						$datas['img'] = $wx_info['headimgurl'];//修改
						$datas['type'] = 1;
						$datas['gold'] = $qian;
						$datas['dateline'] = time();
						if($list = D('Weixin_relaylist')->add($datas)){
							$this->chuangreturn('0',$qian);
						}
					}
				}
			}else{
				if(empty($openid)){
					$openid = $this->access_openid();
				}
				$client = $this->wechat_client();
				$wx_info = $client->getUserInfoById($openid);
				$list =$objsn->where(array('relay_id'=>$relay_id,'openid'=>$openid))->select();
				foreach($list as $k => $v){
					if($v['cishu']>= $detail['max_num']){
						$this->Errors('您已经没有接力次数了');
					}else if($v['openid'] != $openid){
						$this->Errors('用户错误');
					}else{
						$aa = $objsn->find($sn_id);
						$bb['cishu'] = $aa['cishu'] +1;
						$bb['sn_id'] = $aa['sn_id'];
						$objsn->save($bb);
						$a = $objsn->find($sn_id);
						$b['gold'] = $a['gold'] + $qian;
						$b['sn_id'] = $a['sn_id'];
						$objsn->save($b);
						$datas['openid'] = $openid;
						$datas['relay_id'] = $detail['relay_id'];
						$datas['nickname'] = $wx_info['nickname'];
						$datas['img'] = $wx_info['headimgurl'];//修改
						$datas['type'] = 1;
						$datas['gold'] = $qian;
						$datas['dateline'] = time();

						if($sn1 = D('Weixin_relaylist')->add($datas)){
							$this->chuangreturn('0',$qian);
						}
					}
				}
			}
		}
	}

	public function fenxiang($sn_id)
	{
		$obj = D('Weixin_relay');
		$objsn = D('Weixin_relaysn');
		if(!($sn_id = (int)$sn_id) && !($sn_id = $this->_post('sn_id'))){
			$this->Error('该用户不存在');
        }else if(!$detail = $objsn->find($sn_id)){
             $this->Error('该用户不存在');
        }else{
			 $this->Error('分享成功');
		}
	}

	public function show($sn_id=null)
	{
		$obj = D('Weixin_relay');
		$objsn = D('Weixin_relaysn');
        if(!($sn_id = (int)$sn_id)){
            $this->Error('该用户不存在');
        }else if(!$relaysn = $objsn->find($sn_id)){
           $this->Error('该用户不存在');
        }else if(!$detail =$obj->find($relaysn['relay_id'])){
			$this->Error('该接力不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			if($openid == $relaysn['openid']){
				$this->Error('不能自己给自己接力');
			}else{
				$relay_id = $relaysn['relay_id'];
				$list = $objsn->where(array('relay_id'=>$relaysn['relay_id'],'openid'=>$relaysn['openid']))->select();
				foreach($list as $k => $v){
					$my_sn_list = $v;
					$this->assign('my_sn_list',$v);
				}
				$aa = $obj->find($relaysn['relay_id']);
				$bb['views'] = $aa['views'] +1;
				$bb['relay_id'] = $aa['relay_id'];
				$obj->save($bb);

				include_once "System/Lib/Action/Weixin/jssdk.php";
				$weixin = $this->_CONFIG['weixin'];
				$jsSdk = new WeixinJSSDK($weixin['appid'], $weixin['appsecret']);
				$jsSdk2 = $jsSdk->getSignPackage();
				$this->assign('wxjscfg',$jsSdk2);

				import('ORG.Util.Page'); // 导入分页类

				$my_count = D('Weixin_relaylist')->where(array('relay_id'=>$relay_id,'openid'=>$relaysn['openid'],'jieliid'=>$openid))->count();
				$Page = array();
				$Page = new Page($count, 100);
				$show = $Page->show();
				$my_list  = D('Weixin_relaylist')->where(array('relay_id'=>$relay_id,'openid'=>$relaysn['openid'],'jieliid'=>$openid))->limit($Page->firstRow . ',' . $Page->listRows)->select();
				
				foreach($my_list as $k => $v){
					$gold_all += $v['gold'];
				}
				$this->assign('my_count',$my_count);
				$this->assign('gold_all',$gold_all);
				$this->assign('detail',$detail);



				$filter['relay_id'] = $relay_id;
				$prizes = D('Weixin_relayprize')->where($filter)->select();
				if($prizes){
					$this->assign('prizes',$prizes);
				}
				$Page = array();
				$Page = new Page($count, 100);
				$show = $Page->show();
				$sn_list = $objsn->where($filter)->order( array('gold'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
				if($sn_list){
					$this->assign('sn_list',$sn_list);
				}

				$filter['openid'] = $relaysn['openid'];
				$filter['type'] = 1;
				$Page = array();
				$Page = new Page($count, 100);
				$show = $Page->show();
				$list_sn = D('Weixin_relaylist')->where($filter)->limit($Page->firstRow . ',' . $Page->listRows)->select();
				if($list_sn){
					$this->assign('list_sn',$list_sn);
				}

				$filter['openid'] = $relaysn['openid'];
				$filter['type'] = 2;
				$filter['openid'] = $openid;
				$Page = new Page($count, 1000);
				$show = $Page->show();
				$list_sn2 = D('Weixin_relaylist')->where($filter)->limit($Page->firstRow . ',' . $Page->listRows)->select();

				if($list_sn2){
					$list_sn3 = $arr3 = array();
					foreach($list_sn2 as $k => $v){
						
						if(in_array($v['jieliid'],$arr3)){
							$list_sn3[$v['jieliid']]['gold'] += $v['gold'];
							$list_sn3[$v['jieliid']]['cishu'] += 1;
						}else{
							$arr3[] = $v['jieliid'];
							$list_sn3[$v['jieliid']] = $v;
							$list_sn3[$v['jieliid']]['cishu'] = 1;
						}
					}
					$this->assign('list_sn3',$list_sn3);
				}

				
				$this->assign('condition',$condition);
				
				if($my_sn_list){
					$url = __HOST__.U('relay/show', array('sn_id'=>$my_sn_list['sn_id']));
					$this->assign('url',$url);
				}
				$url2 = __HOST__.U('relay/preview', array('relay_id'=>$detail['relay_id']));
				$this->assign('url2',$url2);
				
				$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
				$member = D('Member')->find($weixin['uid']);
				$stime = strtotime($detail ['stime']);
				$ltime = strtotime($detail ['ltime']);
				if (! empty ( $ltime ) && $ltime <= time ()) {
					$error = '您来晚啦';
				}
				$this->assign('time',time());
				$this->assign('error',$error);
				$this->display();
			}
		}
	}

	public function sign2($relay_id,$qian,$sn_id)
	{
		$obj = D('Weixin_relay');
		$objsn = D('Weixin_relaysn');
		if(!($relay_id = (int)$relay_id)){
			$this->Error('未指定该接力ID');
        }else if(!$detail = $obj->find($relay_id)){
			$this->Error('该接力不存在或已经删除');
        }else if(!$relaysn = $objsn->find($sn_id)){
			$this->Error('该接力玩家不存在');
		}else{
			
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
			$my_list = D('Weixin_relaylist')->where(array('relay_id'=>$relay_id,'openid'=>$relaysn['openid'],'jieliid'=>$openid))->select();
			

			if($my_count>= $detail['relay_num']){
				$this->Error('您已经没有接力次数了');
			}else{
				$aa = $objsn->find($sn_id);
				$bb['gold'] = $aa['gold'] + $qian;
				$bb['sn_id'] = $aa['sn_id'];
				$objsn->save($bb);
				$datas['openid'] = $relaysn['openid'];
				$datas['jieliid'] = $openid;
				$datas['relay_id'] = $detail['relay_id'];
				$datas['nickname'] = $wx_info['nickname'];
				$datas['img'] = $wx_info['headimgurl'];//修改
				$datas['type'] = 2;
				$datas['gold'] = $qian;
				if($list = D('Weixin_relaylist')->add($datas)){
							$this->chuangreturn('0',$qian);
				}
			}
		}
	}
}