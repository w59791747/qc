<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProvinceAction extends CommonAction{
    private $create_fields = array('region_id','province_name','orderby');
    private $edit_fields = array('region_id','province_name','orderby');
    
    public  function index(){
       $Province = D('Province');
	   $Region = D('Region');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $Province->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Province->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
	   $this->assign('region', $Region->fetchAll());
       $this->display(); // 输出模板
    }
    
   

    public function create() {
		$Region = D('Region');
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Province');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('province/index'));
            }
            $this->chuangError('操作失败！');
        } else {
			$this->assign('region', $Region->fetchAll());
            $this->display();
        }
    }



	public function edit($province_id = 0) {
		 $obj = D('Province');
		 $Region = D('Region');
		 if (!$detail = $obj->find($province_id)) {
			$this->error('请选择要编辑的省份');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['province_id'] = $province_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('province/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('region', $Region->fetchAll());
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($province_id = 0){
         if($province_id = (int)$province_id){
             $obj =D('Province');
             $obj->delete($province_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('province/index'));
         }
         $this->chuangError('删除失败!');
    }
    
    
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
       if (empty($data['province_name'])) {
            $this->chuangError('请输入省份名称');
        }
		 if (empty($data['region_id'])) {
            $this->chuangError('请选择区域');
        }
        $data['province_name'] = htmlspecialchars($data['province_name'], ENT_QUOTES, 'UTF-8');
        return $data;
    }

	private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if (empty($data['province_name'])) {
            $this->chuangError('请输入省份名称');
        }
		 if (empty($data['region_id'])) {
            $this->chuangError('请选择区域');
        }
		$data['dateline'] = time();
        $data['province_name'] = htmlspecialchars($data['province_name'], ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
   
}
