<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class ProjectcommentModel extends CommonModel{
    protected $pk   = 'comment_id';
    protected $tableName =  'project_comment';

	public function update_project($project_id,$comment,$scores){
		$Project = D('Project');
		if(false !==$Project->updateCount($project_id,'scores',$scores)){
			$Project->updateCount($project_id,'comments',$comment);
			return true;
		}else{
			return false;
		}
	}
}