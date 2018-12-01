<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class WeixinshakeAction extends CommonAction 
{
	private $create_fields = array('scratch_id','collect_count','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline');
	private $edit_fields = array('scratch_id','collect_count','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline');
	private $goodscreate_fields = array('id','shake_id','title','name','num','sort','photo');
	private $goodsedit_fields = array('id','shake_id','title','name','num','sort','photo');

	public function _initialize() {
        parent::_initialize();
        //$this->assign('types', D('Award')->getCfg());
    }
	
    public function index($page=1)
    {
		
		import('ORG.Util.Page'); // 导入分页类
		$obj = D('Weixin_shake');
		$pager = array();
		$count = $obj->where($map)->count();
		$Page = new Page($count, 25);
		$show = $Page->show();
		$list = $obj->where($map)->order(array('shake_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
		foreach ($list as $k => $val) {
            $url = U('weixin/shake/preview', array('shake_id' => $val['shake_id']));
            $url = __HOST__ . $url;
            $list[$k]['url'] = $url;
        }
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
       
    }

	
	public function create()
	{
		
		if ($this->isPost()) {
			$data = $this->createCheck();
			$obj = D('Weixin_shake');
			if ($id = $obj->add($data)) {
				 $this->chuangSuccess('添加成功', U('weixinshake/index'));
            }else{
				$this->chuangError('添加失败！');
			}	
		}else{
			$this->display();
		}
	}
	
	
	private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['title'] = htmlspecialchars($data['title']);
       
        $data['type'] = news;
		$data['dateline'] = NOW_TIME;
        if (empty($data['type'])) {
            $data['type'] = news;
        }
        return $data;
    }
	
	public function edit($shake_id = null) {
        if ($shake_id = (int) $shake_id) {
            $obj = D('Weixin_shake');
            if (!$detail = $obj->find($shake_id)) {
                $this->chuangError('请选择要编辑的活动');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
				$obj = D('Weixin_shake');
				$data['shake_id'] = $shake_id;			
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('weixinshake/index'));
                }
                $this->chuangError('修改失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->chuangError('请选择要编辑活动');
        }
    }

	private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['title'] = htmlspecialchars($data['title']);
		
        return $data;
    }
	
	public function delete($shake_id=null)
    {
		$obj = D('Weixin_shake');
        if($coupon_id = (int)$shake_id){
			if(!$detail = $obj->find($shake_id)){
				$this->chuangError('你要删除的内容不存在');
			}elseif($obj->delete($shake_id)){
				$this->chuangSuccess('删除成功！',U('weixinshake/index'));
			}
        }else{
			$this->chuangError('非法操作！');
		}
    } 

	public function sn($shake_id = 0) {
        if ($shake_id = (int) $shake_id) {
            $obj = D('Weixin_shakesn');
			$obje = D('Weixin_shake');
			if(!$detail = $obje->find($shake_id)){
				$this->chuangError('该活动不存在');
			}else{
				$this->assign('detail', $detail);
			}
			$map = array();
			$map['shake_id'] =$shake_id;
			import('ORG.Util.Page'); // 导入分页类
			$map['sn'] = array('gt','100');
			$count = $obj->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();

			$list = $obj->where($map)->order(array('sn_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){
				$uids = '';
				foreach($items as $k => $v){
					$uids[$v['uid']] = $v['uid'];
				}
				$data = array();
				$data['uid']=array('in',$uids);
				$member_list = D('Member')->where($data)->select();
				$this->assign('member_list', $member_list);
				$this->assign('list', $list);
				$this->assign('page', $show); // 赋值分页输出
			}
			 
		}else{
			$this->chuangError('该活动不存在');
		}
		$this->display();
    }
	
	


 
	public function sndelete($sn_id=null)
    {
		$obj = D('Weixin_shake');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}elseif($obj->delete($sn_id)){
				$this->chuangSuccess('删除成功！',U('weixinshake/sn',array('shake_id'=>$detail['shake_id'])));
			}
        }else{
			$this->chuangError('非法操作！');
		}
    }  

	public function snedit($sn_id=null)
    {
		$obj = D('Weixin_shakesn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}else{
				if($detail['is_use'] == '1'){
					$data['is_use'] = 0;
					$data['use_time'] = '';
				}else{
					$data['is_use'] = 1;
					$data['use_time'] = time();
				}
				$data['sn_id'] = $sn_id;
                if($obj->save($data)){
					$this->chuangSuccess('修改成功！',U('weixinshake/sn',array('shake_id'=>$detail['shake_id'])));
                }
            }
        }
    }


	public function goods($shake_id)
	{
        $obj = D('Weixin_shake');
        $objp = D('Weixin_shakeprize'); 
        
        if(!($shake_id = (int)$shake_id)){
            $this->chuangError('没有指定摇一摇ID');
        }else if(!$detail = $obj->find($shake_id)){
            $this->chuangError('该摇一摇不存在或已经删除');
        }else{
            import('ORG.Util.Page'); // 导入分页类
            $map = array('shake_id' => $shake_id);
            $count = $objp->where($map)->count();
            $Page = new Page($count, 25);
            $show = $Page->show();
            $list = $objp->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
            if($list){
                $map = array();
                $uids = '';
                $obju =  D('Member');
                foreach($list as $k => $v){
					$uids[$v['uid']] = $v['uid'];
				}
                 $map['uid']=array('in',$uids);
			     $member_list = $obju->where($map)->select();
            }
        }
		$this->assign('shake_id', $shake_id); // 赋值数据集
		$this->assign('member_list', $member_list); // 赋值数据集 
		$this->assign('detail', $detail); // 赋值数据集
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


	public function goodscreate($shake_id=null)
    {
		$obj = D('Weixin_shake');
        if(!($shake_id = (int)$shake_id)){
            $this->chuangError('没有指定摇一摇ID');
        }else if(!$detail = $obj->find($shake_id)){
            $this->chuangError('未指定内容ID');
        }if ($this->isPost()) {
			$data = $this->goodscreateCheck($shake_id);
			$objp = D('Weixin_shakeprize');
			if ($id = $objp->add($data)) {
				$this->chuangSuccess('添加成功', U('weixinshake/goods',array('shake_id'=>$shake_id)));
            }else{
				$this->chuangError('添加失败！');
			}	
		}else{
			$this->assign('shake_id', $shake_id); // 赋值数据集
			$this->display();
		}	
    }
	
	 private function goodscreateCheck($shake_id) {
        $data = $this->checkFields($this->_post('data', false), $this->goodscreate_fields);
        $data['title'] = htmlspecialchars($data['title']);
		
		$data['shake_id'] = $shake_id;
      
        return $data;
    }
	
	 public function goodsedit($id=null)
    {
		$obj = D('Weixin_shake');
		$objp = D('Weixin_shakeprize');
        if (!$detail = $objp->find($id)) {
              $this->chuangError('未指定要修改的内容ID');
        }
        if(!$detail['shake_id']){
            $this->chuangError('没有指定摇一摇ID');
        }else if(!$details = $obj->find($shake_id)){
            $this->chuangError('您要修改的内容不存在或已经删除');
        }if ($this->isPost()) {
			$data = $this->checkFields($this->_post('data', false), $this->goodsedit_fields);
			$data['id'] = $id;
			if (false !== $objp->save($data)) {
				$this->chuangSuccess('修改成功', U('weixinshake/goods',array('shake_id'=>$detail['shake_id'])));
            }else{
				$this->chuangError('修改失败失败！');
			}	
		}else{
			$this->assign('detail', $detail); // 赋值数据集
			$this->display();
		}
        
    }
	

	public function goodsdelete($id=null)
    {
        if($id = (int)$id){
            $objp = D('Weixin_shakeprize');
            if(!$detail = $objp->find($id)){
                $this->chuangError('你要删除的内容不存在或已经删除');
            }elseif($objp->delete($id)){
                $this->chuangSuccess('删除成功！',U('weixinshake/goods',array('shake_id'=>$detail['shake_id'])));
            }else{
                $this->chuangError('删除失败！');
            }
        }else{
            $this->chuangError('没有指定ID');
        }
    }
	

}   
