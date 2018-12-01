<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CityAction extends CommonAction{
    private $create_fields = array('province_id','city_name','orderby');
    private $edit_fields = array('province_id','city_name','orderby');
    
    public  function index(){
       $Province = D('Province');
	   $City = D('City');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $City->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $City->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
	   $this->assign('province', $Province->fetchAll());
       $this->display(); // 输出模板
    }
    
   

    public function create() {
		$Province = D('Province');
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('City');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('city/index'));
            }
            $this->chuangError('操作失败！');
        } else {
			$this->assign('province', $Province->fetchAll());
            $this->display();
        }
    }



	public function edit($city_id = 0) {
		 $obj = D('City');
		 $Province = D('Province');
		 if (!$detail = $obj->find($city_id)) {
			$this->error('请选择要编辑的城市');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['city_id'] = $city_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('city/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('province', $Province->fetchAll());
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($city_id = 0){
         if($city_id = (int)$city_id){
             $obj =D('City');
             $obj->delete($city_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('city/index'));
         }
         $this->chuangError('删除失败!');
    }
    
    
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
       if (empty($data['city_name'])) {
            $this->chuangError('请输入城市名称');
        }
		 if (empty($data['province_id'])) {
            $this->chuangError('请选择省份');
        }
        $data['city_name'] = htmlspecialchars($data['city_name'], ENT_QUOTES, 'UTF-8');
        return $data;
    }

	private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if (empty($data['city_name'])) {
            $this->chuangError('请输入城市名称');
        }
		 if (empty($data['province_id'])) {
            $this->chuangError('请选择省份');
        }
		$data['dateline'] = time();
        $data['city_name'] = htmlspecialchars($data['city_name'], ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
   
}
