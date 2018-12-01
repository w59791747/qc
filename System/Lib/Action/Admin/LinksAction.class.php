<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class LinksAction extends CommonAction{
    private $create_fields = array('link_name','link_url','orderby');
    private $edit_fields = array('link_name','link_url','orderby');
    
    public  function index(){
       $Links = D('Links');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $Links->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Links->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
   

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Links');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('Links/index'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }



	public function edit($link_id = 0) {
		 $obj = D('Links');
		 if (!$detail = $obj->find($link_id)) {
			$this->error('请选择要编辑的友情链接');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['link_id'] = $link_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('Links/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($link_id = 0){
         if($link_id = (int)$link_id){
             $obj =D('Links');
             $obj->delete($link_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('Links/index'));
         }
         $this->chuangError('删除失败!');
    }
    
    
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
       if (empty($data['link_name'])) {
            $this->chuangError('友情链接名称');
        }
		 if (empty($data['link_url'])) {
            $this->chuangError('链接地址');
        }

        return $data;
    }

	private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if (empty($data['link_name'])) {
            $this->chuangError('友情链接名称');
        }
		 if (empty($data['link_url'])) {
            $this->chuangError('链接地址');
        }
        return $data;
    }
    
   
}
