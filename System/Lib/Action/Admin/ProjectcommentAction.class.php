<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProjectcommentAction extends CommonAction{

    public  function index(){
		
       $Projectcomment = D('Projectcomment');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
		if($project_id = $this->_param('project_id','htmlspecialchars')){
			$map['project_id'] =  $project_id;
            $this->assign('project_id',$project_id);
        }
       $count      = $Projectcomment->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Projectcomment->where($map)->order(array('comment_id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'uid','Member','memberlist');
	   $this->itemsby($list,'project_id','Project','projectlist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }

	public function audit($comment_id = 0) {
		$comment_id = $this->_post('comment_id', false);
		if (is_array($comment_id)) {
			$obj = D('Projectcomment');
			if ($this->audit_comment($comment_id,'Project','Projectcomment','comment')) {
				$this->chuangSuccess('审核成功！', U('Projectcomment/index'));
			}else{
				$this->chuangError('审核失败');
			}
			
		}
		$this->error('请选择要审核的评论');
	}

   public function delete($comment_id = 0) {
        if (is_numeric($comment_id) && ($comment_id = (int) $comment_id)) {
            $obj = D('Projectcomment');
			if ($this->audit_delete($comment_id,'Project','Projectcomment','delete')) {
				$this->chuangSuccess('删除成功！', U('Projectcomment/index'));
			}
        } else {
            $comment_id = $this->_post('comment_id', false);
            if (is_array($comment_id)) {
                $obj = D('Projectcomment');
				if ($this->audit_delete($comment_id,'Project','Projectcomment','comment')) {
					$this->chuangSuccess('删除成功！', U('Projectcomment/index'));
				}
                $this->chuangSuccess('删除成功！', U('Projectcomment/index'));
            }
            $this->error('请选择要删除的评论');
        }
    }

}
