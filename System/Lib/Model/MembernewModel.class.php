<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class MembernewModel extends CommonModel{
    protected $pk   = 'new_id';
    protected $tableName =  'member_news';

	public function addnew($data,$admin)
	{
		$map['closed'] = array('IN','0,1'); 
		if($data['from'] != '1'){
			$map['from'] = $data['from']; 
		}

		$member = D('member')->where($map)->select();
		$datas = array();
		$datas['title'] = $data['title'];
		$datas['content'] = $data['content'];
		$datas['admin'] = $admin;
		$datas['dateline'] = time();
		foreach($member as $k => $v){
			$datas['uid'] = $v['uid'];
			$this->add($datas);
		}
		return count($member);
	}
  
}