<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class AdvAction extends CommonAction {

	private $create_fields = array('title' ,'audit' ,'orderby');
    private $edit_fields = array('title' ,'audit' ,'orderby');


    public function index($uid = null) {
        $obj = D('Adv');
        import('ORG.Util.Page'); // 导入分页类
		$map['closed'] = 0;
        if($title = $this->_param('title','htmlspecialchars')){
            $map['title'] = array('LIKE','%'.$title.'%');
            $this->assign('title',$title);
        }
		if($adv_id = $this->_param('adv_id','htmlspecialchars')){
			if($from != 1){
				$map['adv_id'] =  $adv_id;
			}
            $this->assign('adv_id',$adv_id);
        }
        
        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('orderby'=>'asc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

	 public function create() {
		$obj = D('Adv');
        if ($this->isPost()) {
            $data = $this->createCheck();
            if ($obj->add($data)) {
                $this->chuangSuccess('添加成功', U('adv/index'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		$data['closed'] = 0;
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	 private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		
        return $data;
    }


	public function edit($adv_id = 0) {
		 $obj = D('Adv');
		 if (!$detail = $obj->find($adv_id)) {
			$this->error('请选择要编辑的广告位');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
           
			$data['adv_id'] = $adv_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('adv/index'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('detail', $detail);
            $this->display();
        }
    }
    
    public function delete($adv_id = 0){
         if($adv_id = (int)$adv_id){
             $obj =D('Adv');
			 $obj->save(array('adv_id'=>$adv_id,'closed'=>1));
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('adv/index'));
         }
         $this->chuangError('删除失败!');
    }

	public function item($adv_id = 0)
	{
		$Adv =D('Adv');
		$obj =D('Advitem');
		if (!$detail = $Adv->find($adv_id)) { 
			$this->error('请选择广告位');
		}else{
			$lists = $obj->where(array('adv_id'=>$adv_id))->order(array('orderby'=>'asc'))->select();
			$this->assign('detail', $detail);
			$this->assign('lists', $lists);
			$this->display();
		}
	}

	public function itemcreate($adv_id = 0) {
        if ($this->isPost()) {
            $data = $this->itemcreateCheck();
            $obj = D('Advitem');
			$data['adv_id'] = $adv_id;
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('adv/item',array('adv_id'=>$adv_id)));
            }
            $this->chuangError('操作失败！');
        } else {
			$Adv =D('Adv');
			if (!$detail = $Adv->find($adv_id)) { 
				$this->error('请选择广告位');
			}
			$this->assign('detail', $detail);
            $this->display();
        }
    }

	 private function itemcreateCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		if (empty($data['link'])) {
            $this->chuangError('链接地址不能为空');
        } 
		$data['stime'] = strtotime($data['stime']);
		$data['ltime'] = strtotime($data['ltime']);
		
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	 private function itemeditCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
       // $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		$data['stime'] = strtotime($data['stime']);
		$data['ltime'] = strtotime($data['ltime']);
		if (empty($data['link'])) {
            $this->chuangError('链接地址不能为空');
        } 
		
        return $data;
    }

	public function itemedit($item_id = 0) {
		 $obj = D('Advitem');
		 if (!$detail = $obj->find($item_id)) {
			$this->error('请选择要编辑的广告');
		 }
        if ($this->isPost()) {
            $data = $this->itemeditCheck();
           
			$data['item_id'] = $item_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('adv/item',array('adv_id'=>$detail['adv_id'])));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('detail', $obj->_format_row($detail));
            $this->display();
        }
    }
    
    public function itemdelete($item_id = 0){
		$obj =D('Advitem');
		 if (!$detail = $obj->find($item_id)) {
			$this->error('请选择要删除的广告');
		 }
         if($item_id = (int)$item_id){
             $obj->delete($item_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！', U('adv/item',array('adv_id'=>$detail['adv_id'])));
         }
         $this->chuangError('删除失败!');
    }

}


