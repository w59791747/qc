<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class ProjectcateAction extends CommonAction {


	public function index()
	{
		$obj = D('Projectcate');
		import('ORG.Util.Page'); // 导入分页类
		$count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->order(array('orderby'=>'asc','cate_id'=>'asc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
	}

	public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Projectcate');
        if($obj->add($data)){
            $obj->cleanCache();
            $this->chuangSuccess('添加成功',U('projectcate/index'));
        }

            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), array('cate_name','orderby'));
        $data['cate_name'] = htmlspecialchars($data['cate_name']);
        if(empty($data['cate_name'])){
            $this->chuangError('分类名不能为空');
        }
		$data['dateline'] = time();
        return $data;
    }
    public function edit($cate_id = 0){
        if($cate_id =(int) $cate_id){
            $obj = D('Projectcate');
            if(!$detail = $obj->find($cate_id)){
                $this->error('请选择要编辑的分类');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['cate_id'] = $cate_id;
                if(false!==$obj->save($data)){
                    $obj->cleanCache();
                    $this->chuangSuccess('编辑成功',U('projectcate/index'));
                }
                $this->chuangError('操作失败');
                
            }else{
                $this->assign('detail',$detail);         
                $this->display();
            }
        }else{
            $this->error('请选择要编辑的分类');
        }
    }
     private function editCheck(){
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), array('cate_name','orderby'));
        $data['cate_name'] = htmlspecialchars($data['cate_name']);
        if(empty($data['cate_name'])){
            $this->chuangError('分类名不能为空');
        }
        return $data;  
    }

    public function delete($cate_id = 0){
         if(is_numeric($cate_id) && ($cate_id = (int)$cate_id)){
             $obj =D('Projectcate');
             $obj->delete($cate_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('projectcate/index'));
         }else{
            $cate_id = $this->_post('cate_id',false);
            if(is_array($cate_id)){     
                $obj = D('Projectcate');
                foreach($cate_id as $id){
                    $obj->delete($id);
                }                
                $obj->cleanCache();
                $this->chuangSuccess('删除成功！',U('projectcate/index'));
            }
            $this->error('请选择要删除的分类');
         }
         
    }

}