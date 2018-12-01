<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class HelpcateAction extends CommonAction {

    private $create_fields = array('title', 'orderby');
    private $edit_fields = array('title', 'orderby');

    public function index() {
        $Articlecate = D('Articlecate');
		$map=array('hidden'=>'0','from'=>'help');
        $list = $Articlecate->where($map)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    public function create($parent_id=0) {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Articlecate');
            $data['parent_id'] = $parent_id;
			$data['from'] = 'help';
            if ($obj->add($data)) {
                $obj->cleanCache();
                $this->chuangSuccess('添加成功', U('helpcate/index'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->assign('parent_id',$parent_id);
            $this->display();
        }
    }
    
    
    public function child($parent_id=0){
        $datas = D('Articlecate')->fetchAll();
        $str = '';
        foreach($datas as $var){
            if($var['parent_id'] == $parent_id && $var['hidden'] == 0){
				$str.='<option value="'.$var['cate_id'].'">'.$var['title'].'</option>'."\n\r";	
            }           
        }
        echo $str;die;
    }
    
    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        $data['cate_name'] = htmlspecialchars($data['cate_name']);
        if (empty($data['title'])) {
            $this->chuangError('分类不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
        return $data;
    }

    public function edit($cate_id = 0) {
        if ($cate_id = (int) $cate_id) {
            $obj = D('Articlecate');
            if (!$detail = $obj->find($cate_id)) {
                $this->error('请选择要编辑的分类');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['cate_id'] = $cate_id;
                if (false !== $obj->save($data)) {
                    $obj->cleanCache();
                    $this->chuangSuccess('操作成功', U('helpcate/index'));
                }
                $this->chuangError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的分类');
        }
    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        $data['cate_name'] = htmlspecialchars($data['cate_name']);
        if (empty($data['title'])) {
            $this->chuangError('分类不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
        return $data;
    }

    public function delete($cate_id = 0) {
        if (is_numeric($cate_id) && ($cate_id = (int) $cate_id)) {
            $obj = D('Articlecate');
            $obj->delete($cate_id);
            $obj->cleanCache();
            $this->chuangSuccess('删除成功！', U('helpcate/index'));
        } else {
            $cate_id = $this->_post('cate_id', false);
            if (is_array($cate_id)) {
                $obj = D('Articlecate');
                foreach ($cate_id as $id) {
                    $obj->delete($id);
                }
                $obj->cleanCache();
                $this->chuangSuccess('删除成功！', U('helpcate/index'));
            }
            $this->error('请选择要删除的分类');
        }
    }
    
    public function update() {
        $orderby = $this->_post('orderby', false);
        $obj = D('Articlecate');
        foreach ($orderby as $key => $val) {
            $data = array(
                'cate_id' => (int) $key,
                'orderby' => (int) $val
            );
            $obj->save($data);
        }
        $obj->cleanCache();
        $this->chuangSuccess('更新成功', U('helpcate/index'));
    }

}
