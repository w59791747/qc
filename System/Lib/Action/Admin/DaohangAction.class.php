<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class DaohangAction extends CommonAction{
    private $create_fields = array('title','orderby','is_hidden','parent_id','link');
    private $edit_fields = array('title','orderby','is_hidden','parent_id','link');
    
    public  function index(){
       $Daohang = D('Daohang');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $Daohang->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Daohang->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'parent_id','Daohang','dh_cate');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
	   
       $this->display(); // 输出模板
    }

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Daohang');
        if($obj->add($data)){
            $obj->cleanCache();
            $this->chuangSuccess('添加成功',U('daohang/index'));
        }
            $this->chuangError('操作失败！');
        } else {
			$this->assign('dhs',D('Daohang')->where('parent_id=0 AND is_hidden=1')->select()); 
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])){
            $this->chuangError('标题不能为空');
        }
		if(empty($data['link'])){
            $this->chuangError('链接不能为空');
        }
		$data['orderby'] = htmlspecialchars($data['orderby']);
        if(empty($data['orderby'])){
            $this->chuangError('排序不能为空');
        }
		$data['dateline'] = NOW_TIME;
        return $data;
    }
    public function edit($daohang_id = 0){
        if($daohang_id =(int) $daohang_id){
            $obj = D('Daohang');
            if(!$detail = $obj->find($daohang_id)){
                $this->error('请选择要编辑的导航');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['daohang_id'] = $daohang_id;
                if(false!==$obj->save($data)){
                    $obj->cleanCache();
                    $this->chuangSuccess('编辑成功',U('daohang/index'));
                }
                $this->chuangError('操作失败');
                
            }else{
				$this->assign('dhs',D('Daohang')->where('parent_id=0 AND is_hidden=1')->select()); 
                $this->assign('detail',$detail);         
                $this->display();
            }
        }else{
            $this->error('请选择要编辑的导航');
        }
    }
     private function editCheck(){
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
		$data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])){
            $this->chuangError('标题不能为空');
        }	
		if(empty($data['link'])){
            $this->chuangError('链接不能为空');
        }
        $data['orderby'] = htmlspecialchars($data['orderby']);
        if(empty($data['orderby'])){
            $this->chuangError('排序不能为空');
        }
		
        return $data;  
    }

    public function delete($daohang_id = 0){
         if(is_numeric($daohang_id) && ($daohang_id = (int)$daohang_id)){
             $obj =D('Daohang');
			 $daohang = $obj->find($daohang_id);
			 if($daohang['is_hidden'] == 1){
				$data['is_hidden'] = 0;
			 }else{
				$data['is_hidden'] = 1;
			 }
			 $data['daohang_id'] = $daohang_id;
             $obj->save($data);
             $obj->cleanCache();
             $this->chuangSuccess('操作成功！',U('daohang/index'));
         }
         
    }

    
   
}
