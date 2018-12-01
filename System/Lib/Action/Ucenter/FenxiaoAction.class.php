<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class FenxiaoAction extends CommonAction {

  
	public function lists()
	{
		$obj = D('Member');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['fenxiao_id'] = $this->uid;
		$count      = $obj->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $obj->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

}
