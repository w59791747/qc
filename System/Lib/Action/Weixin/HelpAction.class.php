<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class HelpAction extends CommonAction {

	public function index()
    {
        exit();
    }
	
	public function preview($help_id=null)
	{
		$obj = D('Weixin_help');
		$objsn = D('Weixin_helpsn');
        if(!($help_id = (int)$help_id) && !($help_id = $this->_post('help_id'))){
			$this->assign('error','未指定内容ID');
			$this->display("public:404");
        }else if(!$detail = $obj->find($help_id)){
			header("HTTP/1.0 404 NOT FOUND");
			$this->assign('error','该助力不存在或已经删除');
			$this->display("public:404");
        }else{
			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);

			$list = $my_sn_list =$objsn->where(array('help_id'=>$help_id,'openid'=>$openid))->find();
			$this->assign('my_sn_list',$list);
			$aa = $obj->find($help_id);
			$bb['views'] = $aa['views'] +1;
			$bb['help_id'] = $aa['help_id'];
			$obj->save($bb);
			include_once "System/Lib/Action/Weixin/jssdk.php";
			$weixin = $this->_CONFIG['weixin'];
			$jsSdk = new WeixinJSSDK($weixin['appid'], $weixin['appsecret']);
			$jsSdk1 = $jsSdk->getSignPackage();
			$this->assign('wxjscfg',$jsSdk1);
			$this->assign('detail',$detail);

			$filter['help_id'] = $help_id;
			$prizes = D('Weixin_helpprize')->where($filter)->select();
			if($prizes){
				$this->assign('prizes',$prizes);
			}
			import('ORG.Util.Page'); // 导入分页类
			$count = $objsn->where($filter)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$sn_list = $objsn->where($filter)->order(array('zhuli' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($sn_list){
				$this->assign('sn_list',$sn_list);
			}
			$Page = array();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$filter['openid'] = $openid;
			$list_sn = D('Weixin_helplist')->where($filter)->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list_sn){
				$this->assign('list_sn',$list_sn);
			}
			if($my_sn_list){
				$url = __HOST__.U('help/show', array('sn_id'=>$my_sn_list['sn_id']));
				$this->assign('url',$url);
			}
				
			$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
			$member = D('Member')->find($weixin['uid']);

			if (!empty ( $ltime ) && $ltime <= time ()) {
				$error = '您来晚啦';
			}
			$this->assign('error',$error);
			$this->display();
		}
	}

	public function sign($help_id=null)
	{
		$obj = D('Weixin_help');
		$objsn = D('Weixin_helpsn');
		if(!($help_id = (int)$help_id) && !($help_id = $this->_post('help_id'))){
			$this->Error('未指定该助力ID');
        }else if(!$detail = $obj->find($help_id)){
			$this->Error('该助力不存在或已经删除');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);

			$stime = strtotime($detail ['stime']);
			$ltime = strtotime($detail ['ltime']);
		
			$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
			$member = D('Member')->find($weixin['uid']);
			$list =$objsn->where(array('help_id'=>$help_id,'openid'=>$openid))->select();
			
			if (! empty ( $ltime ) && $ltime <= time ()) {
				$this->Error('您来晚啦');
			}else{
				$data['zhuli'] = 1;
				$data['uid'] = $member['uid'];
				$data['help_id'] = $help_id;
				$data['openid'] = $openid;
				$data['nickname'] = $wx_info['nickname'];
				$data['img'] = $wx_info['headimgurl'];//修改
				$data['dateline'] = TIME();
				if($sn = $objsn->add($data)){
					$msg = $data['nickname'].'|'.$data['img'].'|'.$data['zhuli'];
					$this->chuangreturn($msg);
				}else {
					$this->Errors('您已经参加过了');
				}
			}
			
		}
	}

	public function fenxiang($sn_id)
	{
		$obj = D('Weixin_help');
		$objsn = D('Weixin_helpsn');
		if(!($sn_id = (int)$sn_id) && !($sn_id = $this->_post('sn_id'))){
			$this->Error('该用户不存在');
        }else if(!$detail = $objsn->find($sn_id)){
            $this->Error('该用户不存在');
        }else{
			$data['zhuanfa'] = $detail['zhuanfa'] +1;
			$data['sn_id'] = $detail['sn_id'];
			$objsn->save($data);
			$this->Error('分享成功');
		}
	}

	public function zhuli($sn_id)
	{
		$obj = D('Weixin_help');
		$objsn = D('Weixin_helpsn');
		if(!($sn_id = (int)$sn_id) && !($sn_id = $this->_post('sn_id'))){
			$this->Error('该用户不存在');
        }else if(!$detail = $objsn->find($sn_id)){
            $this->Error('该用户不存在');
        }else{
			if(empty($openid)){
				$openid = $this->access_openid();
			}
			$client = $this->wechat_client();
			$wx_info = $client->getUserInfoById($openid);
	
			$data['openid'] = $detail['openid'];
			$data['help_id'] = $detail['help_id'];

			$data['zhuliid'] = $openid;
			$data['nickname'] = $wx_info['nickname'];
			$data['img'] = $wx_info['headimgurl'];//修改
			$data['dateline'] = time();
			if($list = D('Weixin_helplist')->add($data)){
				$zhuli = $detail['zhuli'] +1;
				$objsn->save(array('sn_id'=>$sn_id,'zhuli'=>$zhuli));
				$this->chuangreturn('助力成功');
			}else {
				$this->Errors('助力失败');
			}
		}
	}

	public function show($sn_id)
	{
		$obj = D('Weixin_help');
		$objsn = D('Weixin_helpsn');
		if(!($sn_id = (int)$sn_id) && !($sn_id = $this->_post('sn_id'))){
			$this->Error('该用户不存在');
        }else if(!$helpsn = $objsn->find($sn_id)){
			$this->Error('该用户不存在');
        }else if(!$detail =$obj->find($helpsn['help_id'])){
			header("HTTP/1.0 404 该助力不存在11或已经删除");
			$this->display("public:404"); 
        }else{

			if(empty($openid)){
				$openid = $this->access_openid();
				
			}
			if($openid == $helpsn['openid']){
				$this->Error('不能自己给自己助力');
			}else{
				$client = $this->wechat_client();
				$wx_info = $client->getUserInfoById($openid);
				$list = $objsn->where(array('help_id'=>$helpsn['help_id'],'openid'=>$helpsn['openid']))->select();
				foreach($list as $k => $v){
					$my_sn_list = $v;
					$this->assign('my_sn_list',$v);
				}

				$filter['help_id'] = $helpsn['help_id'];
				$prizes = D('Weixin_helpprize')->where($filter)->select();
				if($prizes){
					$this->assign('prizes',$prizes);
				}
				import('ORG.Util.Page'); // 导入分页类
				$count = $objsn->where($filter)->count();
				$Page = new Page($count, 15);
				$show = $Page->show();
				$sn_list = $objsn->where($filter)->order(array('zhuli' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			
				if($sn_list){
					
					$this->assign('sn_list',$sn_list);
				}

				$filter['openid'] = $helpsn['openid'];
				$list_sn = D('Weixin_helplist')->where($filter)->limit($Page->firstRow . ',' . $Page->listRows)->select();
				if($list_sn){
					$this->assign('list_sn',$list_sn);
				}
				$aa = $obj->find($helpsn['help_id']);
				$bb['views'] = $aa['views'] +1;
				$bb['help_id'] = $aa['help_id'];
				$obj->save($bb);
				include_once "System/Lib/Action/Weixin/jssdk.php";
				$jsSdk = new WeixinJSSDK($weixin['appid'], $weixin['appsecret']);
				$jsSdk1 = $jsSdk->getSignPackage();
				$this->assign('wxjscfg',$jsSdk1);
				$condition = array ();

				$this->assign('condition',$condition);
				if($my_sn_list){
					$url = U('help/show', array('sn_id'=>$my_sn_list['sn_id']));
					$this->assign('url',$url);
				}
				$url2 = __HOST__.U('help/preview', array('help_id'=>$helpsn['help_id']));
				$this->assign('url2',$url2);
			
				$weixin =D('Member_weixin')->where(array('openid',$openid))->select();
				$member = D('Member')->find($weixin['uid']);
				$stime = strtotime($detail ['s_time']);
				$ltime = strtotime($detail ['l_time']);
				if (!empty ( $detail ['ltime'] ) && $detail ['ltime'] <= time ()) {
					$error = '您来晚啦';
				}
				$this->assign('error',$error);
				$this->assign('helpsn',$helpsn);
				$this->assign('detail',$detail);
				$list_sn = D('Weixin_helplist')->where(array('openid'=>$helpsn['openid'],'zhuliid'=>$openid))->select();
				if($list_sn){
					$this->assign('iszhuli',1);
				}
				$this->display();
			}
		}
	}
}