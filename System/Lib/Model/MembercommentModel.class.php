<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class MembercommentModel extends CommonModel{
    protected $pk   = 'comment_id';
    protected $tableName =  'member_comment';
	protected $token =  'member_comment';

	public function update_member($uid,$comment,$scores){
		$Member = D('Member');
		if(false !==$Member->updateCount($uid,'scores',$scores)){
			$Member->updateCount($uid,'comments',$comment);
			return true;
		}else{
			return false;
		}
	}

	public function  tongjiComm($map){
		$where = " 1=1";
		
		if($bg_time = $map['b_time']){
			$where.= " AND dateline>={$map['b_time']}";
		}
		if($end_time = $map['e_time']){
			$where.= " AND dateline<={$map['e_time']}";
		}
        $data1 = $this->query("select dateline FROM  ".$this->getTableName()." where".$where);

		$data2 = D('Projectcomment')->query("select dateline FROM  ".D('Projectcomment')->getTableName()." where".$where);

		$data3 = D('Articlecomment')->query("select dateline FROM  ".D('Articlecomment')->getTableName()." where".$where);
		$showdata['tongji'] = '[\'用户评论\','.count($data1).'],'.'[\'项目评论\','.count($data2).'],'.'[\'文章评论\','.count($data3).']';

		$showdata['shuju']['用户评论'] = count($data1);
		$showdata['shuju']['项目评论'] = count($data2);
		$showdata['shuju']['文章评论'] = count($data3);
		$showdata['shuju']['all'] = count($data3)+count($data1)+count($data2);
		
        return $showdata;
    }
}