<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class NewsAction extends CommonAction{


	public function index()
	{
		$obj = D('Goodsnews');
		import('ORG.Util.Page');// 导入分页类
		$count      = $obj->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $obj->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		$projectlist = $this->itemsby($list,'project_id','Project','projectlist');
		$this->itemsby($projectlist,'uid','Member','memberlist');
		$this->assign('list', $list);
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('count',$count);
		$this->display();
	}

	public function mine()
	{
		$obj = D('Goodsnews');$Projectfollow = D('Projectfollow');
		import('ORG.Util.Page');// 导入分页类

		$follows = $Projectfollow->where(array('uid'=>$this->uid))->select();
		foreach($follows as $k => $v){
			$t[] = $v['project'];
		}
		if($t){
			$m['project_id'] = array('IN',implode(',',$t));
			$count      = $obj->where($m)->count();// 查询满足要求的总记录数 
			$Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
			$show       = $Page->show();// 分页显示输出
			$list = $obj->where($m)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
			$projectlist = $this->itemsby($list,'project_id','Project','projectlist');
			$this->itemsby($projectlist,'uid','Member','memberlist');
			$this->assign('list', $list);
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('count',$count);
		}
		$this->display();
	}

	

	
}
			