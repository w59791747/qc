<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class FxAction extends CommonAction{
    private $create_fields = array('name','mobile');
    private $edit_fields = array('name','mobile');
    
    public  function index(){
       $Fx = D('Fx');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
       $count      = $Fx->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Fx->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('dateline'=>'desc'))->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
	
	public  function reg($fx_id = 0){
		if($fx_id =(int) $fx_id){
            $obj = D('Fx');$Member = D('Member');
            if(!$detail = $obj->find($fx_id)){
                $this->error('请选择要查看的内容');
            }else{
				import('ORG.Util.Page');// 导入分页类
				$map = array();
				$map['fx_id'] = $detail['mobile'];
				$count      = $Member->where($map)->count();// 查询满足要求的总记录数 
				$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
				$show       = $Page->show();// 分页显示输出
				$list = $Member->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('dateline'=>'desc'))->select();
				$this->assign('list',$list);// 赋值数据集
				$this->assign('page',$show);// 赋值分页输出
				$this->assign('detail',$detail);         
				$this->display();
			}
        }else{
            $this->error('请选择要查看的内容');
        }
    }
	
	public  function buy($fx_id = 0){
		if($fx_id =(int) $fx_id){
            $obj = D('Fx');$Member = D('Member');$Order = D('Order');
            if(!$detail = $obj->find($fx_id)){
                $this->error('请选择要查看的内容');
            }else{
				import('ORG.Util.Page');// 导入分页类
				$map = array();
				$map['fx_id'] = $detail['mobile'];
				$memberlist = $Member->where($map)->select();
				foreach($memberlist as $k => $v){
					$uids[] = $v['uid'];
				}
				$map2['pay_status'] = '1';
				$map2['uid'] = array('IN',implode(',',$uids));
				$count      = $Order->where($map2)->count();// 查询满足要求的总记录数 
				$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
				$show       = $Page->show();// 分页显示输出
				$list = $Order->where($map2)->limit($Page->firstRow.','.$Page->listRows)->order(array('dateline'=>'desc'))->select();
				foreach($list as $k=>$val){
					$lists[$k] = D('Order')->_format_row($val);
				}
				$this->assign('list',$lists);// 赋值数据集
				$this->assign('page',$show);// 赋值分页输出
				$this->assign('detail',$detail);  

			$this->itemsby($list,'uid','Member','memberlist');
			$this->assign('typelist', D('Order')->getType());
			$this->assign('statuslist', D('Order')->status());
			$this->assign('payment', D('Payment')->getPayments());				
				$this->display();
			}
        }else{
            $this->error('请选择要查看的内容');
        }
    }

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Fx');
        if($obj->add($data)){
            $obj->cleanCache();
            $this->chuangSuccess('添加成功',U('Fx/index'));
        }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        if(empty($data['name'])){
            $this->chuangError('名称不能为空');
        }
		if (!isMobile($data['mobile'])) {
            $this->chuangError('手机格式不正确');
        }
		$data['link'] = $this->CONFIG['site']['host'].U('home/passport/reg',array('fx'=>$data['mobile']));
		
		$data['dateline'] = NOW_TIME;
        return $data;
    }
    public function edit($fx_id = 0){
        if($fx_id =(int) $fx_id){
            $obj = D('Fx');
            if(!$detail = $obj->find($fx_id)){
                $this->error('请选择要编辑的内容');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['fx_id'] = $fx_id;
                if(false!==$obj->save($data)){
                    $obj->cleanCache();
                    $this->chuangSuccess('编辑成功',U('Fx/index'));
                }
                $this->chuangError('操作失败');
                
            }else{
                $this->assign('detail',$detail);         
                $this->display();
            }
        }else{
            $this->error('请选择要编辑的内容');
        }
    }
     private function editCheck(){
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
		if(empty($data['name'])){
            $this->chuangError('名称不能为空');
        }
		if (!isMobile($data['mobile'])) {
            $this->chuangError('手机格式不正确');
        }
		$data['link'] = $this->CONFIG['site']['host'].U('home/passport/reg',array('fx'=>$data['mobile']));
		
		
        return $data;  
    }

    public function delete($fx_id = 0){
         if(is_numeric($fx_id) && ($fx_id = (int)$fx_id)){
             $obj =D('Fx');
             $obj->delete($fx_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('Fx/index'));
         }
         
    }

    
   
}
