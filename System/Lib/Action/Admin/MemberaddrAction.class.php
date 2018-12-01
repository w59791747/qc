<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class MemberaddrAction extends CommonAction{
    private $create_fields = array('addr','contact','default','phone','uid','city');
    private $edit_fields = array('addr','contact','default','phone','uid','city');
    
    public  function index(){
       $Memberaddr = D('Memberaddr');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
       $count      = $Memberaddr->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberaddr->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'uid','Member','memberlist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
   

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Memberaddr');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('Memberaddr/index'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }



	public function edit($addr_id = 0) {
		 $obj = D('Memberaddr');
		 if (!$detail = $obj->find($addr_id)) {
			$this->error('请选择要编辑的地址');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['addr_id'] = $addr_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('Memberaddr/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('member', D('member')->find($detail['uid']));
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($addr_id = 0){
         if($addr_id = (int)$addr_id){
             $obj =D('Memberaddr');
             $obj->delete($addr_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('Memberaddr/index'));
         }
         $this->chuangError('删除失败!');
    }

    
    
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		
        return $data;
    }

	private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		if (empty($data['uid'])) {
            $this->chuangError('用户不能为空');
        }
        if (empty($data['city']) || $data['city'] == '城市') {
            $this->chuangError('请选择城市');
        }
		$data['dateline'] = time();
        return $data;
    }
    
   
}
