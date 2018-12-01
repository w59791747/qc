<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class FeedbackAction extends CommonAction{
    private $edit_fields =array('name','title','content','mobile','status');
    
    public  function index(){
       $obj = D('Feedback');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $obj->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $obj->where($map)->order('dateline desc')->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    

	public function edit($feedback_id = 0) {
		 $obj = D('Feedback');
		 if (!$detail = $obj->find($feedback_id)) {
			$this->error('请选择要查看的反馈信息');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['feedback_id'] = $feedback_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('Feedback/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($feedback_id = 0){
         if($feedback_id = (int)$feedback_id){
             $obj =D('Feedback');
             $obj->delete($feedback_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('Feedback/index'));
         }
         $this->chuangError('删除失败!');
    }
    
    
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
      
        return $data;
    }

	
   
}
