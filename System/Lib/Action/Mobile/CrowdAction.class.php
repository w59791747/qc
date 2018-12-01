<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CrowdAction extends CommonAction {

	public function index()
	{
		$this->assign('catelist', D('Projectcate')->fetchAll());
		$this->assign('huibao', D('Project')->huibao());
		$this->assign('cate', D('Membercate')->fetchAll());
		$this->assign('status_list',D('Project')->status2());
		$this->assign('type', D('Project')->type_list());

		$Project = D('Project');
		$map['audit'] = 1;$map['type'] = 'crowd';$map['closed'] = 0;
		$project_list = $Project->where($map)->order(array('dateline'=>'desc'))->limit('10')->select();
		foreach($project_list as $k => $v){
			$uids[$v['uid']] =  $v['uid'];
		}
		$member_list = D('Member')->itemsByIds($uids);
		
		$this->assign('project_list', $project_list);
		$this->assign('member_list', $member_list);

		$this->display();
	}

	
}