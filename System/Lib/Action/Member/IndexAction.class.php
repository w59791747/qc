<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class IndexAction extends CommonAction {

    public function index() 
	{
		//公告
		$map['uid'] =  $this->uid;
		$map['is_pay'] = '1';
		$list = D('Goodslist')->where($map)->order(array('dateline'=>'desc'))->limit('0,5')->select();
		foreach($list as $k => $v){
			$arr[$v['list_id']] = $v['list_id'];
			$arr2[$v['project_id']] = $v['project_id'];
		}
		$m2['project_id'] = array('IN',implode(',',$arr2));
		
		$p = D('Project')->where($m2)->select();
		$this->assign('list',$list);
		$this->assign('project',$this->arrkey($p,'project_id'));
        $this->display(); 
    }

   
}
