<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class VerifyAction extends CommonAction {


    public function index() {
        $obj = D('Verify');
        import('ORG.Util.Page'); // 导入分页类
        $map = array();
	    if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('dateline' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
		$items = D('Member')->CallDataForMat($list);
		$this->itemsby($list,'uid','Member','memberlist');
		$this->assign('items', $items); 
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

	public function audit($uid = 0) {
        if (is_numeric($uid) && ($uid = (int) $uid)) {
            $obj = D('Verify');$Member = D('Member'); 
			$user = $Member->find($uid);
			if($user['verify']>=4){
					$obj->save(array('uid' => $uid, 'audit' => 1));
					$this->chuangSuccess('审核通过！', U('verify/index'));
			}else{

				if(false !==$Member->updateCount($uid,'verify',4)){
					if($this->audit_comment($uid,'member','Verify','reg',$user['fenxiao_id'],'fenxiang_reg')){
						
						$obj->cleanCache();
						$this->chuangSuccess('审核通过！', U('verify/index'));
					}
					   $this->chuangError('操作失败！');
					
				}
			}
        } else {
            $uid = $this->_post('uid', false);
            if (is_array($uid)) {
                $obj = D('Verify');$Member = D('Member');
                foreach ($uid as $id) {
					$user='';
					$user = $Member->find($id);
					if($user['verify']>=4){
							$obj->save(array('uid' => $id, 'audit' => 1));
					}else{
						if(false !==$Member->updateCount($uid,'verify',4)){
							if(false !==$Member->updateCount($id,'verify',4)){
								$this->audit_comment($id,'member','Verify','reg',$user['fenxiao_id'],'fenxiang_reg');
							}
						}
					}


                }
                $obj->cleanCache();
				$this->chuangSuccess('审核通过！', U('verify/index'));
            }
            $this->chuangError('请选择您要审核的用户');
        }
    }
	
	public function edit($uid = 0)
	{
		 if (is_numeric($uid) && ( $uid = (int) $uid)) {
			 $Memberdetail = D('Memberdetail');
			 if($detail = $Memberdetail->find($uid)){
				if ($this->isPost()) {
					 $data = $this->_post('data', 'htmlspecialchars');
						if (!isAllChinese($data['realname'])) {
							$this->chuangError('真实姓名必须是汉字');
						}
						if (!isCreditNo($data['card'])) {
							$this->chuangError('身份证号码不正确');
						} 
						$Memberdetail->where(array('uid'=>$uid))->save($data);
						$this->chuangSuccess('修改成功', U('verify/index'));
				}else{
					$this->assign('detail', $detail); 
					$this->display(); 
				}
			 }else{
				 $this->chuangError('用户不存在'); 
			 }
			
			 
		 }else{
			$this->chuangError('请选择要编辑的实名认证'); 
		 }
	}
    

    public function delete($uid = 0) {
        if (is_numeric($uid) && ( $uid = (int) $uid)) {
            $obj = D('Verify');$Member = D('Member');
			$detail = $obj->find($uid);
			if($detail['audit'] == 1){
				$Member->updateCount($uid,'verify',-4);
			}
			$obj->delete($uid);
			
            $obj->cleanCache();
            $this->chuangSuccess('删除成功！', U('verify/index'));
        } else {
            $uid = $this->_post('uid', false);
            if (is_array($uid)) {
                $obj = D('Verify');$Member = D('Member');
                foreach ($uid as $id) {
                    $detail = '';
					$detail = $obj->find($id);
					if($detail['audit'] == 1){
						$Member->updateCount($id,'verify',-4);
					}
					$obj->delete($id);
                }
                $obj->cleanCache();
                $this->chuangSuccess('删除成功！', U('verify/index'));
            }
            $this->error('请选择要删除的实名认证');
        }
    }

}
