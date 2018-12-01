<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class MessageAction extends CommonAction {

   public function news()
   {
		$Membernew = D('Membernew');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['uid'] = $this->uid;
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
		$count      = $Membernew->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Membernew->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
   }

	public function follow()
	{
		$Memberfollow = D('Memberfollow');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['uid'] = $this->uid;
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
		$count      = $Memberfollow->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Memberfollow->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->itemsby($list,'follow_uid','Member','memberlist');
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	public function followed()
	{
		$Memberfollow = D('Memberfollow');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['follow_uid'] = $this->uid;
		$count      = $Memberfollow->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Memberfollow->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->itemsby($list,'uid','Member','memberlist');
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	public function follow_p()
	{
		$Projectfollow = D('Projectfollow');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['uid'] = $this->uid;
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
		$count      = $Projectfollow->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Projectfollow->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->itemsby($list,'project','Project','projectlist');
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	public function deletefollow_p($follow_id)
	{
		 if($follow_id = (int)$follow_id){
             $obj =D('Projectfollow');
             $obj->delete($follow_id);
             $this->chuangSuccess('取消成功！',U('message/follow_p'));
         }
         $this->chuangError('取消失败!');
	}

	

	

	

	public function comments_project()
	{
		$Projectcomment = D('Projectcomment');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$Project_list = D('Project')->where(array('uid'=>$this->uid))->select();
		$map['uid'] = $this->uid;
		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
		
		$count      = $Projectcomment->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Projectcomment->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('from',$from);
		$this->itemsby($list,'project_id','Project','projectlist');
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}

	

	public function commentsprojectdelete($comment_id = 0)
	{
		 if (is_numeric($comment_id) && ($comment_id = (int) $comment_id)) {
			$obj = D('Projectcomment');
			if ($this->audit_delete($comment_id,'Project','Projectcomment','delete')) {
				$this->chuangSuccess('删除成功！', U('message/comments_project'));
			}
        }
	}

	
}
