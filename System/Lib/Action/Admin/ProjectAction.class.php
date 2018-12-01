<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProjectAction extends CommonAction{
    
	private $create_fields = array('title','cate_id','stime','cheshang_id','days','min_price','max_price','chi_days','toupiao','content','fenhong','tip_id','fund_raising','project_photo','crowd_photo','audit','huigou','canshu','hetong','status','tpbfb','desc','is_gold','xuni_num','xuni_price');
	private $edit_fields = array('title','cate_id','stime','cheshang_id','days','min_price','max_price','chi_days','toupiao','content','fenhong','tip_id','fund_raising','project_photo','crowd_photo','audit','huigou','canshu','hetong','status','tpbfb','desc','is_gold','xuni_num','xuni_price');
    
	public function crowd_yure()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '0';
		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display('crowd_yure');
	}

	public function create()
	{
		$obj = D('Project');
		
		if ($this->isPost()) {
			$data = $this->createCheck();
			$data['xuni_price']=$data['xuni_price']*100;
			if($project_id = $obj->add($data)){
				if($data['status'] == '1'){
					if($data['xuni_num'] > 0 && $data['xuni_price'] > 0){
					
						if($this->xuni($project_id,$data['xuni_num'],$data['xuni_price']/100)){
							if($data['xuni_price'] == $data['fund_raising']){
								$obj->where(array('project_id'=>$project_id))->save(array('status'=>2));
							}
						}
					}
					$this->chuangSuccess('添加成功', U('Project/crowd_zhongchou'));
				}else if($data['status'] == '0'){
					$this->chuangSuccess('添加成功', U('Project/crowd_yure'));
				}
			}

			$this->chuangError('操作失败！');
		} else {
			$this->assign('catelist', D('Projectcate')->fetchAll());
			$this->assign('tipslist', D('Projecttips')->fetchAll());
			$this->assign('cheshanglist', D('Cheshang')->fetchAll());
			$this->assign('other', D('Settingother')->find('1'));
			$this->display();
		}
	}

	

	public function xuni($project_id,$xuni_num,$xuni_price)
	{
		$obj = D('Project');$Goodslist = D('Goodslist');
		$member = D('Member')->where(array('xuni'=>'1'))->select();
		$project = $obj->find($project_id);
		if($project['status'] != '1'){
			$this->chuangError('该项目不在众筹中,不能添加虚拟信息');
		}else{
			$count = count($member);
			$price = 0;
			$arr_num = array();
			for($i=1;$i<=$xuni_num;$i++){
				$arr_num[$i] = '0';
			}
			for($j=1;$j<=$xuni_price/100;$j++){
				$rand = rand(1,rand(1,$xuni_num));
				$arr_num[$rand] += 100;
			}
			
			foreach($arr_num as $k => $v){
				if($v == 0){
					$arr_num[$k] = 100;
					$arr_num[1] = $arr_num[1]-100;
				}
			}
			shuffle($arr_num);
			for($i=1;$i<=$xuni_num;$i++){
				$data = '';
				$rand = rand(0,$count-1);
				$data['name'] = $member[$rand]['name'];
				$data['mobile'] = $member[$rand]['mobile'];
				$data['price'] = $arr_num[$i-1]*100;
				$data['is_xuni'] = 1;
				$data['is_pay'] = 1;
				$data['project_id'] = $project_id;
				$data['uid'] =$member[$rand]['uid'];
				$data['dateline'] = NOW_TIME;
				$list_id = $Goodslist->add($data);
				D('Member')->updateCount($member[$rand]['uid'], 'tou_price',$data['price']);
			}
			$obj->updateCount($project_id, 'have_num',$xuni_num);
			$obj->updateCount($project_id, 'have_price',$xuni_price*100);
			return true;

		}
	}




	private function createCheck() {
		
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }
		if (empty($data['cate_id'])) {
            $this->chuangError('分类不能为空');
        }
		if (empty($data['stime'])) {
			$this->chuangError('开始时间不能为空');
		}
		$data['stime'] = strtotime($data['stime']);
		$project_photo = $this->_post('project_photo', false);
		if (empty($project_photo)) {
            $this->chuangError('项目图片不能为空');
        }else{
			$data['project_photo'] = implode('||',$project_photo);
		}
		if (empty($data['days'])) {
            $this->chuangError('筹资期限不能为空');
        }
		if (empty($data['chi_days'])) {
            $this->chuangError('众筹最长持有期限不能为空');
        }
		if (empty($data['toupiao'])) {
            $this->chuangError('投票期限不能为空');
        }
		if (empty($data['fenhong'])) {
            $this->chuangError('分红不能为空');
        }
		if (empty($data['huigou'])) {
            $this->chuangError('溢价回购不能为空');
        }else{
			$data['huigou'] = $data['huigou']*100;
		}
		if (empty($data['fund_raising'])) {
            $this->chuangError('筹资不能为空');
        }else{
			$data['fund_raising'] = $data['fund_raising']*100;
		}
		
		
		if (empty($data['tpbfb'])) {
            $this->chuangError('投标百分比不能为空');
        }

		
	
		if (empty($data['content'])) {
            $this->chuangError('描述不能为空');
        }
		$data['clientip'] = get_client_ip();
		$data['ltime'] = $data['stime']+$data['days']*3600*24;
		$data['crowdltime'] = $data['stime']+$data['chi_days']*3600*24;
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	 private function editCheck() {
       $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }
		if (empty($data['cate_id'])) {
            $this->chuangError('分类不能为空');
        }

		$project_photo = $this->_post('project_photo', false);
		if (empty($project_photo)) {
            $this->chuangError('项目图片不能为空');
        }else{
			$data['project_photo'] = implode('||',$project_photo);
		}

		
		if (empty($data['stime'])) {
			$this->chuangError('开始时间不能为空');
		}
		$data['stime'] = strtotime($data['stime']);

		
		if (empty($data['days'])) {
            $this->chuangError('筹资期限不能为空');
        }
		if (empty($data['chi_days'])) {
            $this->chuangError('众筹最长持有期限不能为空');
        }
		if (empty($data['toupiao'])) {
            $this->chuangError('投票期限不能为空');
        }
		if (empty($data['fenhong'])) {
            $this->chuangError('分红不能为空');
        }
		if (empty($data['huigou'])) {
            $this->chuangError('溢价回购不能为空');
        }else{
			$data['huigou'] = $data['huigou']*100;
		}
		if (empty($data['fund_raising'])) {
            $this->chuangError('筹资不能为空');
        }else{
			$data['fund_raising'] = $data['fund_raising']*100;
		}
		
		if (empty($data['tpbfb'])) {
            $this->chuangError('投标百分比不能为空');
        }

		
	
		if (empty($data['content'])) {
            $this->chuangError('描述不能为空');
		}
		
		$data['ltime'] = $data['stime']+$data['days']*3600*24;
		$data['crowdltime'] = $data['stime']+$data['chi_days']*3600*24;

		//unset($data['status']);
		
        return $data;
    }



	public function crowd_edit($project_id = 0) {
		 $obj = D('Project');
		 if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择要编辑的项目');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
			$data['project_id'] = $project_id;
			if($detail['status'] != '0' && $detail['status'] != '1'){
				$this->chuangError('该项目不能修改');
			}
			if($obj->save($data)){
				if($detail['status'] == '1'){
					$this->chuangSuccess('修改成功', U('Project/crowd_zhongchou'));
				}else if($detail['status'] == '0'){
					$this->chuangSuccess('修改成功', U('Project/crowd_yure'));
				}
			}

            $this->chuangError('操作失败！');
        } else {
			
			$detail['project_photos']  = explode('||',$detail['project_photo']);
			$detail['canshu']  = htmlspecialchars_decode($detail['canshu']);
			$detail['hetong']  = htmlspecialchars_decode($detail['hetong']);
			$detail['content']  = htmlspecialchars_decode($detail['content']);
			$this->assign('detail', $detail);
				
			$this->assign('catelist', D('Projectcate')->fetchAll());
			$this->assign('cheshanglist', D('Cheshang')->fetchAll());
			$this->assign('tipslist', D('Projecttips')->fetchAll());
			$this->assign('other', D('Settingother')->find('1'));
            $this->display();
        }
    }


	public function crowd_zhongchou()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '1';

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	public function delete($project_id = 0)
	{
		$obj = D('Project');
		 if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择要编辑的项目');
		 }

		 if($detail['audit'] == 1){
			$data['audit'] = 0;
		 }else{
			$data['audit'] = 1;
		 }
		 $data['project_id'] = $project_id;
		 if($obj->save($data)){
			 if($detail['status'] == '-1'){
				$this->chuangSuccess('操作成功', U('Project/crowd_liubiao'));
			 }elseif($detail['status'] == '-3'){
				$this->chuangSuccess('操作成功', U('Project/crowd_huigou'));			
			 }else{
				$this->chuangSuccess('操作成功', U('Project/crowd_wancheng'));			

			 }
			
		 }
	}



	public function crowd_detail($project_id = 0)
	{
		$obj = D('Project');
		$Goodslist = D('Goodslist');
		if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择要查看的项目');
		}else if(!$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select()){
			$this->error('查看的项目暂时尚未有人支持');
		}else{
			$this->assign('detail', $detail);
			$this->assign('lists', $lists);
			$this->itemsby($lists,'uid','Member','memberlist');
			$this->display();
		}
	}

	public function crowd_chushou()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '2';

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	
	


	public function crowd_audit($project_id = 0) {
		$Projectchushou = D('Projectchushou');
		$obj = D('Project');$Goodslist = D('Goodslist');
		if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择项目');
		}
		$chushou = $Projectchushou->where(array('project_id'=>$project_id,'is_chenggong'=>array('IN','0','1')))->find();

		if ($this->isPost()) {
			$data = $this->_post('data', 'htmlspecialchars');
			$data['project_id'] = $project_id;
			if($detail['status'] != '2'){
				$this->chuangError('该项目不能修改');
			}
			if (empty($data['dingjin'])) {
				$this->chuangError('定金不能为空');
			}else{
				$data['dingjin'] = $data['dingjin']*100;
			}
			if (empty($data['amount'])) {
				$this->chuangError('实际金额不能为空');
			}else if($data['amount']*100<$detail['fund_raising']){
				$this->chuangError('实际金额不能小于筹资金额');
			}else{
				$data['amount'] = $data['amount']*100;
			}

			$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select();
			$toultime = $detail['toupiao']*60*60*24+time();
			if($chushou){
				$Projectchushou->save($data);
				$obj->where(array('project_id'=>$project_id))->save(array('status'=>'3','toultime'=>$toultime));
				foreach($lists as $k => $v){
					$member = D('Member')->find($v['uid']);
					D('Sms')->sendSms('sms_toupiao', $member['mobile'], array('title'=>$detail['title']));
				}
				$this->chuangSuccess('操作成功', U('Project/crowd_chushou'));
			}else{
				$Projectchushou->add($data);
				$obj->where(array('project_id'=>$project_id))->save(array('status'=>'3','toultime'=>$toultime));
				foreach($lists as $k => $v){
					$member = D('Member')->find($v['uid']);
					D('Sms')->sendSms('sms_toupiao', $member['mobile'], array('title'=>$detail['title']));
				}
				$this->chuangSuccess('操作成功', U('Project/crowd_chushou'));
			}
			
            $this->chuangError('操作失败！');
        } else {
			$this->assign('detail', $detail);
			$this->assign('chushou', $chushou);
			
            $this->display();
        }
		
    }

	public function crowd_toupiao()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '3';

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	public function crowd_huikuan()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '4';
		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	

	public  function buys($project_id = 0) {
		$Projectchushou = D('Projectchushou');
		$Goodslist = D('Goodslist');
		$obj = D('Project');
		$chushou = $Projectchushou->where(array('project_id'=>$project_id,'is_chenggong'=>array('IN','0,1')))->find();
		
		if (!$detail = $obj->detail($project_id)) {
			$this->chuangError('请选择项目');
		}elseif($detail['status'] != 4){
			$this->chuangError('状态错误');
		}elseif(!$chushou){
			$this->chuangError('操作失败');
		}else{
			$data['is_chenggong'] = 1;
			$Projectchushou->where(array('chushou_id'=>$chushou['chushou_id']))->save($data);
			$obj->where(array('project_id'=>$project_id))->save(array('status'=>'5','etime'=>time()));

			$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select();
			$listss = $obj->shouyi($lists,$detail);
			foreach($listss as $k => $v){
				$member = D('Member')->find($v['uid']);
				$shouyi = ($v['shouyi']+$v['price']/100)*100;
				D('Member')->updateCount($v['uid'],'price',$shouyi);
				D('Memberlog')->add_log($v['uid'],'price',$shouyi,'众筹成功,发放回报',1);
				$Goodslist->where(array('list_id'=>$v['list_id']))->save(array('shouyi'=>$shouyi,'shouyitime'=>time()));
				D('Sms')->sendSms('sms_huibao', $member['mobile'], array('huibao' => $shouyi/100,'title'=>$detail['title']));
			}

			$this->chuangSuccess('设置成功,该项目已完成', U('Project/crowd_huikuan'));
		}
		
		$this->chuangError('操作失败！');

	}

	public function crowd_wancheng()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = array('IN','-2,5');

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	public function crowd_detail2($project_id = 0)
	{
		$obj = D('Project');
		$Goodslist = D('Goodslist');
		if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择要查看的项目');
		}else if(!$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select()){
			$this->error('查看的项目暂时尚未有人支持');
		}else{
			$this->assign('detail', $detail);
			$this->assign('lists', $obj->shouyi($lists,$detail));
			
			$this->itemsby($lists,'uid','Member','memberlist');
			$this->display();
		}
	}

	

	public function crowd_liubiao()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '-1';

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	public function crowd_huigou()
	{
		   $Project = D('Project');
		   import('ORG.Util.Page');// 导入分页类
		   $map = array();
		   $map['closed'] =  '0';
		   $map['status'] = '-3';

		   $count      = $Project->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->itemsby($list,'uid','Member','memberlist');
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('status_list',$Project->status2());
		   $this->assign('type',$Project->type_list());
		   $this->assign('page',$show);
		   $this->assign('cheshanglist', D('Cheshang')->fetchAll());
		   $this->display();
	}

	public function crowd_detail3($project_id = 0)
	{
		$obj = D('Project');
		$Goodslist = D('Goodslist');
		if (!$detail = $obj->detail($project_id)) {
			$this->error('请选择要查看的项目');
		}else if(!$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select()){
			$this->error('查看的项目暂时尚未有人支持');
		}else{
			$this->assign('detail', $detail);
			$this->assign('lists', $obj->shouyi($lists,$detail));
			
			$this->itemsby($lists,'uid','Member','memberlist');
			$this->display();
		}
	}



	public function select($type='1'){
			$Project = D('Project');
			import('ORG.Util.Page'); // 导入分页类
			$map = array('ltime'=>array('gt',time()));
			$map['status'] = '1';$map['audit'] = '1';
			
			if($type != 1){
				$map['type'] =  $type;
				$this->assign('type',$type);
			}
			if($type = $this->_param('type','htmlspecialchars')){
				if($type != 1){
					$map['type'] =  $type;
				}
				$this->assign('type',$type);
			}
			
			$count = $Project->where($map)->count(); // 查询满足要求的总记录数 
			$Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
			$pager = $Page->show(); // 分页显示输出
			$list = $Project->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			$this->assign('list', $list); // 赋值数据集
			$this->assign('page', $pager); // 赋值分页输出
			$this->assign('typelist',$Project->type_list());
			$this->display(); // 输出模板
			
		}
    
    

    
    
   

	public function zijin($project_id = 0,$huigou=0)
	{
		$obj = D('Project');$Goodslist = D('Goodslist');
		$obj->where(array('project_id'=>$project_id))->save(array('huigou'=>$huigou));
		$detail = $obj->detail($project_id);
		$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select();
		$this->assign('lists', $obj->shouyi($lists,$detail));
		
		$this->itemsby($lists,'uid','Member','memberlist');

		$this->assign('detail', $detail);
		$this->display();
	}



	public function crowd_zijin($project_id = 0)
	{
		$obj = D('Project');$Goodslist = D('Goodslist');
		if (!$detail = $obj->detail($project_id)) {
			$this->chuangError('请选择项目');
		}else if($detail['status'] != -3){
			$this->chuangError('该项目状态错误');
		}else{
			$lists = $Goodslist->where(array('project_id'=>$project_id,'is_pay'=>'1'))->select();
			$listss = $obj->shouyi($lists,$detail);
			foreach($listss as $k => $v){
				$member = D('Member')->find($v['uid']);
				$shouyi = ($v['shouyi']+$v['price']/100)*100;
				D('Member')->updateCount($v['uid'],'price',$shouyi);
				D('Memberlog')->add_log($v['uid'],'price',$shouyi,'众筹回购成功,发放回报',1);
				D('Sms')->sendSms('sms_huibao', $member['mobile'], array('huibao' => $shouyi/100,'title'=>$detail['title']));
				$Goodslist->where(array('list_id'=>$v['list_id']))->save(array('shouyi'=>$shouyi,'shouyitime'=>time()));

			}
			$obj->where(array('project_id'=>$project_id))->save(array('status'=>'-2','etime'=>time()));
			
			$this->chuangSuccess('发放成功！',U('Project/crowd_wancheng'));
		}
	}
}
