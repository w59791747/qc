<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProjectAction extends CommonAction{

	public function index($cate=0,$status=0,$eid=0,$order=1)
	{
		$obj = D('Project');
		$obj->change_status();

		import('ORG.Util.Page');// 导入分页类
		$map['closed'] = 0;$map['audit'] = 1;

		if($cate){
			$map['cate_id'] = $cate;
		}
		if($status){
			$map['status'] = $status-1;
		}else{
			$map['status'] = array('gt',-1);
		}

		if($eid){
			if($eid == 1){
				$map['fund_raising'] = array('lt','5000000');
			}elseif($eid == 2){
				$map['fund_raising'] = array('between','5000000,10000000');
			}elseif($eid == 3){
				$map['fund_raising'] = array('between','10000000,20000000');
			}elseif($eid == 4){
				$map['fund_raising'] = array('between','20000000,30000000');
			}elseif($eid == 5){
				$map['fund_raising'] = array('between','30000000,50000000');
			}elseif($eid == 6){
				$map['fund_raising'] = array('gt','50000000');
			}
		}


		if($order == 1){
			$orderby = array('dateline'=>'desc');

		}elseif($order == 2){
			$orderby = array('views'=>'desc');
		}elseif($order == 3){
			$orderby = array('have_price'=>'desc');
		}elseif($order == 4){
			$orderby = array('have_num'=>'desc');
		}elseif($order == 5){
			$orderby = array('ltime'=>'desc');
			$map['status'] = 1;
		}else{
			$orderby = array('dateline'=>'desc');
		}
		$count      = $obj->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $obj->where($map)->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['shouyi'] = $this->shouyi($v);
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
		
		$status_list = D('Project')->status2();
		$catelist =  D('Projectcate')->order(array('orderby'=>'asc','cate_id'=>'asc'))->select();
		$this->assign('catelist',$catelist);
		$this->assign('status_list', D('Project')->status3());

		

		$this->assign('edu',D('Project')->edu());
		$this->assign('status', $status);
		$this->assign('order', $order);
		$this->assign('cate', $cate);
		$this->assign('eid', $eid);
		$this->assign('Projecttips', D('Projecttips')->fetchAll());
		$this->seodatas['cate1'] = $catelist[$cate]['cate_name'];
		$this->seodatas['cate2'] =$status_list[$status];

		$this->display();
	}

	public  function shouyi($v)
	{
		$shouyi['days']  =  floor(($v['etime']-$v['stime'])/3600/24);
		$Goodslist = D('Goodslist');
		
		
		$lists = $Goodslist->where(array('project_id'=>$v['project_id'],'is_pay'=>1))->order(array('shouyi'=>'desc'))->find();

		$shouyi['shouyi']  =  round((($lists['shouyi'])-$lists['price'])/$lists['price']*10000,0);

		return $shouyi;

	}

	
	public function follow($project=0)
	{	
		if(!$this->member){
			$this->chuangSuccess('请先登录再关注',U('passport/login'));
		}else{
			$obj = D('Projectfollow');
			if($project<=0){
				$this->chuangError('关注项目不能为空');
			}
			$is_follow = $obj->where(array('uid'=>$this->uid,'project'=>$project))->find();
			if($is_follow){
				$this->chuangError('您已经关注过了');
			}else{
				$data['dateline'] = NOW_TIME;
				$data['uid'] = $this->uid;
				$data['project'] = $project;
				if($follow_id = $obj->add($data)){
				   $obj->cleanCache();
				   D('Project')->updateCount($project,'follows',1);
				   $this->chuangSuccess('关注成功',$_SERVER['HTTP_REFERER']);
				   
				}
				$this->chuangError('操作失败！');
			}
			
		}
    }

	public function zan($project=0)
	{	
		if(!$this->member){
			$this->chuangSuccess('请先登录再赞',U('passport/login'));
		}else{
			$obj = D('Projectzan');
			if($project<=0){
				$this->chuangError('赞项目不能为空');
			}
			$is_zan = $obj->where(array('uid'=>$this->uid,'project'=>$project))->find();
			if($is_zan){
				$this->chuangError('您已经赞过了');
			}else{
				$data['dateline'] = NOW_TIME;
				$data['uid'] = $this->uid;
				$data['project'] = $project;
				if($zan_id = $obj->add($data)){
				   $obj->cleanCache();
				   D('Project')->updateCount($project,'zans',1);
				   $this->chuangSuccess('感谢您的支持',$_SERVER['HTTP_REFERER']);
				   
				}
				$this->chuangError('操作失败！');
			}
			
		}
    }


	public function tips($tip_id)
	{
		$obj = D('Project');

		import('ORG.Util.Page');// 导入分页类
		$map['closed'] = 0;$map['audit'] = 1;
		$orderby = array('dateline'=>'desc');
		if($tip_id){
			$map['tip_id'] = $tip_id;
		}

		$map['status'] = array('gt',0);

		
		$count      = $obj->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $obj->where($map)->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['shouyi'] = $this->shouyi($v);
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
		$this->assign('tip_id',$tip_id);
		$this->assign('Projecttips', D('Projecttips')->fetchAll());
		$this->display();
	}

	



	public function detail($project_id=0)
	{
		

		$obj = D('Project');
		$obj->change_status();
		$project = $obj->find($project_id);

		if(!$project){
			$this->chuangError('该项目不存在');
		}
		$project['shouyi'] = $this->shouyi($project);
		$project['project_photos'] = explode('||',$project['project_photo']);
		$project['content'] = htmlspecialchars_decode($project['content']);
		$project['hetong'] = htmlspecialchars_decode($project['hetong']);
		$project['canshu'] = htmlspecialchars_decode($project['canshu']);
		$this->assign('project',$project);


		$shen_time = $project['ltime']-time();
		if($shen_time<0){
			$shen_time = 0;
		}
		$this->assign('time',$shen_time);
		$obj->updateCount($project_id, 'views');
		$this->assign('catelist', D('Projectcate')->fetchAll());
		$this->assign('status_list', D('Project')->status2());

		
	    $Goodslist = D('Goodslist');
		

		//项目支持
		
		$lists_count = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>1))->count();
		$lists_select = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>1))->limit('0,8')->order('dateline desc')->select();
		$this->itemsby($lists_select,'uid','Member','memberlistcount');
		$this->assign('lists_count', $lists_count);	
		$this->assign('lists_select', $lists_select);

		$page_list = ceil($lists_count/12);
		if($page_list>1){
			for($i=1;$i<=$page_list;$i++){
				$list_arr[$i] = $i; 
			}
			$this->assign('list_arr', $list_arr);
		}


		//获取评论
		$Projectcomment = D('Projectcomment');
		$comment_count = $Projectcomment->where(array('audit'=>'1','project_id'=>$project_id))->count();
		$comment = $Projectcomment->where(array('audit'=>'1','project_id'=>$project_id))->order('dateline desc')->limit('5')->select();
		$this->itemsby($comment,'uid','Member','memberlist');
		$this->assign('comment', $comment);	
		$this->assign('comment_count', $comment_count);	

		$page_comment = ceil($comment_count/5);
		if($page_comment>1){
			for($i=1;$i<=$page_comment;$i++){
				$comment_arr[$i] = $i; 
			}
			$this->assign('comment_arr', $comment_arr);
		}
		$member = D('Member')->detail($project['uid']);
		$this->assign('member', $member);
		$this->assign('tips', D('Projecttips')->fetchAll());

		$this->assign('is_follow', D('Projectfollow')->where(array('uid'=>$this->uid,'project'=>$project_id))->find());
		$this->assign('is_zan', D('Projectzan')->where(array('uid'=>$this->uid,'project'=>$project_id))->find());

		

		//上一个 下一个
		$m['closed'] = 0;$m['audit'] = 1;
		$m['project_id'] = array('lt',$project_id);
		$up = $obj->where($m)->limit('1')->order('project_id desc')->find();
		
		$m2['closed'] = 0;$m2['audit'] = 1;
		$m2['project_id'] = array('gt',$project_id);
		$down = $obj->where($m2)->limit('1')->order('project_id asc')->find();

		$this->assign('up', $up);	
		$this->assign('down', $down);	
		$this->seodatas['title'] =$project['title'];

		$Projectchushou = D('Projectchushou');
		$chushou = $Projectchushou->where(array('project_id'=>$project_id))->find();
		$this->assign('chushou',$chushou);


		//投票计数
		$zancheng = D('Goodslist')->where(array('project_id'=>$project_id,'status'=>'1'))->count();
		$fandui = D('Goodslist')->where(array('project_id'=>$project_id,'status'=>'-1'))->count();
		$this->assign('zancheng', $zancheng);	$this->assign('fandui', $fandui);	

		$this->display('');
	}

	public function zhichi_list($project_id=0,$page=1)
	{

		$obj = D('Project'); $Goodslist = D('Goodslist');
		import('ORG.Util.Page');// 导入分页类 
		$project = $obj->find($project_id);
		$this->assign('project',$project);
		$this->assign('status_list', D('Project')->status2());
		$lists_count = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>1))->count();
		$Page       = new Page($lists_count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$lists_select = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>1))->order('dateline desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->itemsby($lists_select,'uid','Member','memberlistcount');
		$this->assign('lists_select', $lists_select);
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('lists_count',$lists_count);
		$this->display();
	}



	public function comment_list($project_id=0,$page=1)
	{
		$obj = D('Project'); $Projectcomment = D('Projectcomment');

		import('ORG.Util.Page');// 导入分页类 
		
		$lists_count = $Projectcomment->where(array('audit'=>'1','project_id'=>$project_id))->count();
		$Page       = new Page($lists_count,5);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$comment = $Projectcomment->where(array('audit'=>'1','project_id'=>$project_id))->order('dateline desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->itemsby($comment,'uid','Member','memberlist');


		$this->assign('comment', $comment);
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('lists_count',$lists_count);
		$this->display();
	}

	

	public function lists($tip_id=0,$project_id=0)
	{
		$obj = D('Project');
		$obj->change_status();
		import('ORG.Util.Page');// 导入分页类
		$map['closed'] = 0;$map['audit'] = 1;
		$title = $this->checkFields($this->_post('title', false));
		if($title){
			$map['title'] = array('LIKE','%'.$title.'%');
		}

		if($tip_id){
			$map['tip_id'] = $tip_id;
		}

		if($project_id){
			$d = $obj->find($project_id);
			$map['city'] = $d['city'];
		}
		$map['status'] = array('IN','1,2,3,4');
		$orderby = array('dateline'=>'desc');
		$count      = $obj->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $obj->where($map)->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
		$this->assign('title_soso',$title);
		$this->assign('type', D('Project')->type_list());
		$this->assign('catelist', D('Projectcate')->fetchAll());
		$this->assign('status_list', D('Project')->status_list());
		$this->assign('status', $status);
		$this->assign('order', $order);
		$this->assign('province', $province);
		$this->assign('city', $city);
		$this->assign('type_info', $type);
		$this->assign('huibao_list', D('Project')->huibao());
		$this->assign('status2_list', D('Project')->status2_list());
		$this->assign('cate_list', D('Membercate')->fetchAll());
		$this->assign('tips', D('Projecttips')->fetchAll());

		$type_listss = D('Project')->type_list();
		$this->seodatas['title'] = $type_listss[$type];
		$this->display();
	}

	public function comments($project_id=0)
	{
		if(!$this->member){
			$this->chuangSuccess('请先登录再评论',U('passport/login'));
		}else{
			if ($project_id = (int) $project_id) {
				$obj = D('Project');$Projectcomment = D('Projectcomment');
				if (!$detail = $obj->find($project_id)) {
					$this->chuangError('没有该项目');
				}else{
					$is_comment = $Projectcomment->where(array('project_id'=>$project_id,'uid'=>$this->uid))->find();
					if($is_comment){
						$this->chuangError('您已经评论过了');
					}else{
						$data = $this->_post('data', 'htmlspecialchars');

						if($data['content'] == ''){
							$this->chuangError('内容不能为空');
						}else{
							$data['content'] = D('Sensitive')->checkWords($data['content']);

							$data['dateline'] = NOW_TIME;
							$data['uid'] = $this->uid;
							$data['project_id'] = $project_id;
							$data['content'] = $data['content'];
							$data['score'] = $this->_post('rating', false);
							$power = $this->_CONFIG['power'];
							if($power['audit_comments'] != 'on'){
								$data['audit'] = 1;
								
							   $obj->updateCount($project_id,'comments',1);
							   $obj->updateCount($project_id,'score',$data['score']);
							}else{
								$data['audit'] = 0;
							}
							if($comment_id = $Projectcomment->add($data)){
							   $obj->cleanCache();
							   $this->chuangSuccess('评论成功',$_SERVER['HTTP_REFERER']);
							   
							}
						}
					}
				}
			}
		}
	}

	public function toupiao($project_id,$status)
	{
		$obj = D('Project');
		if(!$this->member){
			$this->chuangSuccess('请先登录再投票',U('passport/login'));
		}else if(!$project = $obj->find($project_id)){
			$this->chuangError('该项目不存在');
		}else if($project['status'] != 3){
			$this->chuangError('状态错误');
		}else{
			$Goodslist = D('Goodslist');
			$list = $Goodslist->where(array('project_id'=>$project_id,'uid'=>$this->uid,'is_pay'=>'1','status'=>'0'))->select();
			if(!$list){
				$this->chuangError('您未参与或已投票过,不能投票');
			}else{
				foreach($list as $k => $v){
					$lists[$v['list_id']] = $v['list_id'];
				}
				$Goodslist->where(array('list_id'=>array('IN',implode(',',$lists))))->save(array('status'=>$status));

				$count = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->count();
				$count1 = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'1'))->count();
				$count2 = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'-1'))->count();

				if($count1/$count*100>=$project['tpbfb']){
					$obj->where(array('project_id'=>$project_id))->save(array('status'=>'4'));
					$Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1','status'=>'0'))->save(array('status'=>1));

				}elseif($count2/$count*100>=$project['tpbfb']){
					$obj->where(array('project_id'=>$project_id))->save(array('status'=>'2'));
					$Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->save(array('status'=>0));
				}
				
				$this->chuangSuccess('投票成功',$_SERVER['HTTP_REFERER']);


			}
		}
	}


	

	public function zhichi($money,$project_id)
	{
		$member = $this->member;
		if(!$member){
			$this->error('请先登录再支持',U('passport/login'));
		}else if($member['verify']<6){
			$this->error('请先通过实名认证和手机认证');
		}else{
			$obj = D('Project');
			$project = $obj->find($project_id);
			if(!$project){
				$this->error('该项目不存在');
			}
			if(!$money){
				$this->error('金额不存在');
			}

			if($project['status'] != 1){
				$this->error('项目状态错误');
			}

			$shen = ($project['fund_raising']-$project['have_price'])/100;
			if($shen<$money){
				$this->error('该众筹剩余资金不足');
			}else{
			
				$this->assign('money', $money);
				$this->assign('project', $project);
				$this->assign('payment', D('Payment')->getPayments());
				$this->assign('payment2', D('Payment2')->getPayments());
				$this->display();
			}
		}

	}

	
			 
	public  function crowdpay()
	{
		$data = $this->_post('data', 'htmlspecialchars');
		if ($data['number'] < '0.01') {
            $this->chuangError('金额不能小于0.01');
        }
		if (!$data['contact']) {
           // $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
           // $this->chuangError('手机格式不正确');
        }

		if (!D('Payment')->checkPayment($data['code']) && !D('Payment2')->checkPayment($data['code'])) {
            $this->chuangError('支付方式不存在');
        }



		$Goodslist = D('Goodslist');
		$data['name'] = $data['contact'];
		$data['mobile'] = $data['phone'];
		$data['addr'] = $data['addr'];
		$data['price'] = $data['number']*100;
		$data['project_id'] = $data['project_id'];
		$data['type_id'] = $data['type_id'];
		$data['uid'] =$this->uid;
		$data['dateline'] = NOW_TIME;
		$list_id = $Goodslist->add($data);
		
		
		if($data['type'] == '2'){
			$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'crowd',$list_id,$data['jifen'],$data['actual_pay'],'2');
		}else{
			$order_id = D('Order')->orderadd($this->uid,$data['number'],$data['code'],'crowd',$list_id,$data['jifen'],$data['actual_pay']);
		}
		

		if($data['type'] == '2'){
			$this->chuangSuccess('提交成功,确认支付信息', U('project/pay2',array('order_id'=>$order_id)));

		}else{
			$this->chuangSuccess('提交成功,确认支付信息', U('project/pay',array('order_id'=>$order_id)));
		}
		

	}

	public function pay($order_id)
	{
		$order = D('Order')->find($order_id);
		if($order['uid'] != $this->uid){
			 $this->chuangSuccess('xxxx', U('index/index'));
		}
		$log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
		$this->assign('payment', D('Payment')->getPayments());
		$this->assign('types', D('Payment')->getTypes());
		$this->assign('code', $log['payment']);
		$this->assign('number', $order['amount']/100);
		$this->assign('from', $order['type']);
		$this->assign('order_id', $order_id);
		$this->assign('log', $log);
		if($log['payment'] == 'shuangqian' || $log['payment'] == 'zhifu' || $log['payment'] == 'huichao'){
			$payment_arr = D('Payment')->get_order($log['payment'], $order);
			$this->assign('payment_arr', $payment_arr);
		}
		$this->display();	
	}

	public function pay2($order_id)
	{
		$order = D('Order')->find($order_id);
		$log = D('Paymentlogs')->getLogsByOrderId($order['type'],$order['order_id']);
		$this->assign('payment', D('Payment2')->getPayments());
		$this->assign('types', D('Payment')->getTypes());
		$this->assign('code', $log['payment']);
		$this->assign('number', $order['amount']/100);
		$this->assign('from', $order['type']);
		$this->assign('order_id', $order_id);
		$this->assign('log', $log);
		$this->assign('order', $order);
		$this->display();	
	}
	

	
}
			