<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class UserAction extends CommonAction {





	
	public function detail($uid=0)
	{
		$obj = D('Member');
		$detail = $obj->find($uid);
		if($detail['from'] != 'member'){
			$this->display("Public:404"); 
		}else{
			$detail = $obj->_format_row($detail);

			//查看是否关注
			$Memberfollow = D('Memberfollow');
			$follow = $Memberfollow->where(array('uid'=>$this->uid,'follow_uid'=>$uid))->find();
			$this->assign('follow', $follow);

			//他的项目
			$Project = D('Project');
			$project_list = $Project->where(array('uid'=>$uid))->limit('0,5')->select();
			foreach($project_list as $k => $v){
				$project_list[$k] = $Project->_format_row($v);
			}
			$this->assign('project_list', $project_list);

			//他被预约

			$Memberyuyue = D('Memberyuyue');
			$yuyue = $Memberyuyue->where(array('yuyue_uid'=>$uid))->limit('0,5')->select();
			foreach($yuyue as $k => $v){
				$uids[$v['uid']] = $v['uid'];
				$yuyues[$v['uid']] = $v;
			}
			$yuyue_member = D('Member')->itemsByIds($uids);
			foreach($yuyue_member as $k =>$v){
				$yuyue_member[$k] = $obj->_format_row($v);
			}
			$this->assign('yuyue_member', $yuyue_member);
			$this->assign('yuyue', $yuyues);

			//他被关注

			$Memberfollow = D('Memberfollow');
			$followc = $Memberfollow->where(array('uid'=>$uid))->count();
			$this->assign('followc', $followc);
			$follow = $Memberfollow->where(array('uid'=>$uid))->limit('0,5')->select();
			foreach($follow as $k => $v){
				$uidss[$v['follow_uid']] = $v['follow_uid'];
			}
			$follow_member = D('Member')->itemsByIds($uidss);
			foreach($follow_member as $k =>$v){
				$follow_member[$k] = $obj->_format_row($v);
			}
			$this->assign('follow_member', $follow_member);

			//关注的人

			$follow2 = $Memberfollow->where(array('follow_uid'=>$uid))->limit('0,20')->select();
			foreach($follow2 as $k => $v){
				$uidsss[$v['uid']] = $v['uid'];
			}
			$follow2_member = D('Member')->itemsByIds($uidsss);
			foreach($follow2_member as $k =>$v){
				$follow2_member[$k] = $obj->_format_row($v);
			}
			$this->assign('follow2_member', $follow2_member);

			//关注项目

			$Projectfollow = D('Projectfollow');
			$p_follow = $Projectfollow->where(array('uid'=>$uid))->limit('0,5')->select();
			foreach($p_follow as $k => $v){
				$projects[$v['project']] = $v['project'];
			}
			$follow_project = D('Project')->itemsByIds($projects);
			foreach($follow_project as $k =>$v){
				$follow_project[$k] = $Project->_format_row($v);
			}
			$this->assign('follow_project', $follow_project);

			//获取评论
			$Membercomment = D('Membercomment');
			$comment = $Membercomment->where(array('audit'=>'1','comment_uid'=>$detail['uid']))->order('dateline desc')->limit('5')->select();
			$this->itemsby($comment,'uid','Member','memberlist');
			$this->assign('comment', $comment);	

			$this->assign('type_list', D('Member')->_from_list());
			$this->assign('detail', $detail);
			$this->assign('type', D('Project')->type_list());
			$this->assign('cate_list', D('Membercate')->fetchAll());
			$this->assign('catelist', D('Projectcate')->fetchAll());

			$this->seodatas['title'] = $detail['name'];
			$this->seodatas['cate'] = '创客列表';
			$this->display();
		}
		
	}

	public function follow($uid=0)
	{	
		if(!$this->member){
			$this->chuangSuccess('请先登录再关注',U('passport/login'));
		}else{
			$obj = D('Memberfollow');
			$is_follow = $obj->where(array('uid'=>$this->uid,'follow_uid'=>$uid))->find();
			if($is_follow){
				$this->chuangError('您已经关注过了');
			}else{
				$data['dateline'] = NOW_TIME;
				$data['uid'] = $this->uid;
				$data['follow_uid'] = $uid;
				if($follow_id = $obj->add($data)){
				   $obj->cleanCache();
				   D('Member')->updateCount($uid,'follow_num',1);
				   $this->chuangSuccess('关注成功',$_SERVER['HTTP_REFERER']);
				   
				}
				$this->chuangError('操作失败！');
			}
		}
    }

	

	public function comments($uid=0)
	{
		if(!$this->member){
			$this->chuangSuccess('请先登录再评论',U('passport/login'));
		}else{
			if ($uid = (int) $uid) {
				$obj = D('Member');$membercomment = D('Membercomment');
				if (!$detail = $obj->find($uid)) {
					$this->chuangError('没有该用户');
				}else{
					$is_comment = D('Membercomment')->where(array('uid'=>$this->uid,'comment_uid'=>$uid))->find();
					if($is_comment){
						$this->chuangError('您已经评论过了');
					}else{
						$data = $this->_post('data', 'htmlspecialchars');
						if($data['content'] == ''){
							$this->chuangError('内容不能为空');

						}else{
							$data['content'] = D('Sensitive')->checkWords($data['content']);

							$data['dateline'] = NOW_TIME;
							$data['uid'] = $this->uid;
							$data['comment_uid'] = $uid;
							$data['content'] = $data['content'];
							$data['score'] = $this->_post('rating', false);
							$power = $this->_CONFIG['power'];
							if($power['audit_comments'] != 'on'){
								$data['audit'] = 1;
								
							   $obj->updateCount($uid,'comments',1);
							   $obj->updateCount($uid,'scores',$data['score']);
							}else{
								$data['audit'] = 0;
							}
							if($comment_id = $membercomment->add($data)){
							   $obj->cleanCache();
							   $this->chuangSuccess('评论成功',$_SERVER['HTTP_REFERER']);
							   
							}
						}
					}
				}
			}
		}
	}


    public function yuyue()
	{	
		if(!$this->member){
			$this->chuangSuccess('请先登录再预约',U('passport/login'));
		}else{
			$data = $this->_post('data', 'htmlspecialchars');
			$uid = $data['uid'];
			$member = D('Member')->find($uid);
			if($member['from'] == $this->member['from']){
				$this->chuangError('不能预约和你同样的角色');
			}else{
				
				$obj = D('Memberyuyue');
				$data = $this->yuyueCheck();
				$return = $obj->check_yuyue_count($data);
				if($return === true){
					$is_yuyue = $obj->where(array('uid'=>$this->uid,'yuyue_uid'=>$data['yuyue_uid']))->find();
					if($is_yuyue){
						$this->chuangError('您已经预约过了');
					}elseif($yuyue_id = $obj->add($data)){
					   $obj->cleanCache();
					   D('Member')->updateCount($data['yuyue_uid'],'yuyue_count',1);
					   $this->chuangSuccess('预约成功',$_SERVER['HTTP_REFERER']);
					   
					}
					$this->chuangError('操作失败！');
				}else{
					$this->chuangError($return['msg']);
				}
				
				$this->chuangError('操作失败！');
			}

		}
    }

	private function yuyueCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
		if (empty($data['uid'])) {
            $this->chuangError('预约用户不能为空');
        }
		$data['yuyue_uid'] = $data['uid'];
		$data['uid'] = $this->uid;
		if (empty($data['name'])) {
            $this->chuangError('姓名不能为空');
        }
		if (empty($data['mobile'])) {
            $this->chuangError('电话不能为空');
        }

		if (empty($data['desc'])) {
            $this->chuangError('备注不能为空');
        }

		$power = $this->_CONFIG['power'];
		if($power['audit_yuyue'] != 'on'){
			$data['audit'] = 1;
		}else{
			$data['audit'] = 0;
		}
        $data['dateline'] = NOW_TIME;
		
        return $data;
    }

	
}