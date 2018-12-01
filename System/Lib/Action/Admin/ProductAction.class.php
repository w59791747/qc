<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProductAction extends CommonAction{
   
    public  function index(){
       $Product = D('Product');
       import('ORG.Util.Page');// 导入分页类
		if ($title = $this->_param('title', 'htmlspecialchars')) {
			$map['title'] = array('LIKE', '%' . $title . '%');
			$this->assign('title', $title);
		}
       $count      = $Product->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Product->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'cate_id','Productcate','catelist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);
       $this->display('index'); // 输出模板
    }


	public function create() {
		 $obj = D('Product');
		
        if ($this->isPost()) {
            $data = $this->createCheck();
			if ($obj->add($data)) {
                $this->chuangSuccess('添加成功', U('Product/index'));
            }
			
            
        } else {
			$this->assign('catelist', D('Productcate')->fetchAll());
            $this->display();
        }
    }

	public function createCheck()
	{
		$data = $this->checkFields($this->_post('data', 'htmlspecialchars'), D('Product')->create_fields());
		if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		if (empty($data['photo'])) {
            $this->chuangError('图片不能为空');
        } 
		if (empty($data['cate_id'])) {
            $this->chuangError('分类不能为空');
        } 
		if (empty($data['jifen'])) {
            $this->chuangError('积分不能为空');
        } 
		if (empty($data['store'])) {
            $this->chuangError('库存不能为空');
        } 
		if (empty($data['content'])) {
            $this->chuangError('内容不能为空');
        } 

		$data['dateline'] = NOW_TIME;
        return $data;
	}

	public function edit($product_id = 0) {
		 $obj = D('Product');
		 if (!$detail = $obj->find($product_id)) {
			$this->error('请选择要编辑的商品');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck();
			$data['product_id'] = $product_id;
			$data['product_id'] = $product_id;
			if (false !== $obj->save($data)) {
				$this->chuangSuccess('修改成功', U('Product/index'));
			}
            
        } else {
			$this->assign('detail', $detail);
			$this->assign('catelist', D('Productcate')->fetchAll());
            $this->display();
        }
    }

	public function editCheck()
	{
		$data = $this->checkFields($this->_post('data', 'htmlspecialchars'), D('Product')->edit_fields());
		if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        } 
		if (empty($data['jifen'])) {
            $this->chuangError('积分不能为空');
        } 
		if (empty($data['store'])) {
            $this->chuangError('库存不能为空');
        } 
		if (empty($data['content'])) {
            $this->chuangError('内容不能为空');
        } 
        return $data;
	}


	public function delete($product_id=0){
		 $obj =D('Product');
        if(is_numeric($product_id) && ($product_id = (int)$product_id)){
			 $obj->delete($product_id);
			 $obj->cleanCache();
			 $this->chuangSuccess('删除成功！',U('Product/index'));
			
         }else {
            $product_id = $this->_post('product_id', false);
			$product = $obj->itemsByIds($product_id);
            if (is_array($product)) {
                foreach ($product as $k => $v) {
					 $obj->delete($v['project_id']);
                }
				 $obj->cleanCache();
				 $this->chuangSuccess('删除成功！',U('Product/index'));
            }
            $this->chuangError('请选择删除项目');
        }
    }

	
}
