<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ActivityAction extends CommonAction {
	
	public function index($order=1)
	{
		$obj = D('Activity');
		import('ORG.Util.Page');// 导入分页类
		$map['audit'] = 1;
		//$map['end_sign'] = array('gt',time());
		
		if($order == 1){
			$orderby = array('dateline'=>'desc');
		}elseif($order == 2){
			$orderby = array('views'=>'desc');
		}
		$count      = $obj->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show2();// 分页显示输出
		$list = $obj->where($map)->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
		$this->assign('order',$order);
		$this->display();
	}

	public  function detail($activity_id=0)
	{
		$obj = D('Activity');
		$activity = $obj->find($activity_id);
		$this->assign('detail',$activity);
		$this->assign('time',$activity['end_sign']-time());
		$this->assign('nowtime',time());
		$obj->updateCount($activity_id, 'views');
		
		$Activitysign = D('Activitysign');
		$sign = $Activitysign->where(array('activity_id'=>$activity_id))->select();
		$this->itemsby($sign,'uid','Member','memberlist');
		$this->assign('sign', $sign);	
		$map['audit'] = 1;
		$map['activity_id'] = array('notin',$activity_id);
		$orderby = array('views'=>'desc');
		$list = $obj->where($map)->order($orderby)->limit('0,3')->select();
		$this->assign('list',$list);
		$this->assign('type', D('Project')->type_list());
		$this->seodatas['title'] = $activity['title'];
		$this->display();
	}

	public function sign($activity_id=0)
	{	
		if(!$this->member){
			$this->chuangSuccess('请先登录再报名',U('passport/login'));
		}else{
			$obj = D('Activitysign');
			if($_POST['activity_id']){
				$activity_id = $_POST['activity_id'];
			}
			$detail = D('Activity')->find($activity_id);
			if($detail['end_sign']<time()){
				$this->chuangError('该活动已过期');
			}
			$is_sign = $obj->where(array('uid'=>$this->uid,'activity_id'=>$activity_id))->find();
			if($is_sign){
				$this->chuangError('您已经报名过了');
			}else{
				$data = $this->_post('data', 'htmlspecialchars');
				if($data['contact'] == '' || $data['mobile'] == '' ){
					$this->chuangError('请填写完整信息');
				}else{
					$data['dateline'] = NOW_TIME;
					$data['uid'] = $this->uid;
					$data['activity_id'] = $activity_id;
					


					if($sign_id = $obj->add($data)){
					   $obj->cleanCache();
					   D('Activity')->updateCount($activity_id,'sign_num',1);
					   $this->chuangSuccess('报名成功',$_SERVER['HTTP_REFERER']);
					   
					}
				}
				$this->chuangError('操作失败！');
			}
		}
    }
}

?>	