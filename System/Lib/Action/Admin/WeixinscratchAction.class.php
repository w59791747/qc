<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */




class WeixinscratchAction extends CommonAction 
{
    private $create_fields = array('scratch_id','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline');
    private $edit_fields = array('scratch_id','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline');
    private $goodscreate_fields = array('id','scratch_id','title','name','num','sort','photo');
	private $goodsedit_fields = array('id','scratch_id','title','name','num','sort','photo');
    public function _initialize() {
        parent::_initialize();
    }
    public function index($page=1)
    {
        import('ORG.Util.Page'); // 导入分页类
		$obj = D('Weixin_scratch');
		$count = $obj->where($map)->count();
		$Page = new Page($count, 25);
		$show = $Page->show();
		$list = $obj->where($map)->order(array('scratch_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $val) {
            $url = U('weixin/scratch/show', array('scratch_id' => $val['scratch_id']));
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
			$obj = D('Weixin_scratch');
			$objk = D('Shop_weixin_keyword');
			
			$map = array();
				
			if ($id = $obj->add($data)) {
				 $this->chuangSuccess('添加成功', U('weixinscratch/index'));
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
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }
		if (empty($data['stime'])) {
            $this->chuangError('开始时间不能为空');
        }
		if (empty($data['ltime'])) {
            $this->chuangError('结束时间不能为空');
        }
		if (empty($data['intro'])) {
            $this->chuangError('封面简介不能为空');
        }
        $data['type'] = news;
		$data['dateline'] = NOW_TIME;
        if (empty($data['type'])) {
            $data['type'] = news;
        }
        $data['create_ip'] = get_client_ip();
        return $data;
    }

     public function edit($scratch_id = null) {
        if ($scratch_id = (int) $scratch_id) {
            $obj = D('Weixin_scratch');
            if (!$detail = $obj->find($scratch_id)) {
                $this->chuangError('请选择要编辑的活动');
            }
           
            if ($this->isPost()) {
                $data = $this->editCheck();
				$obj = D('Weixin_scratch');
				$data['scratch_id'] = $scratch_id;			
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('weixinscratch/index'));
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
		if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }
		if (empty($data['stime'])) {
            $this->chuangError('开始时间不能为空');
        }
		if (empty($data['ltime'])) {
            $this->chuangError('结束时间不能为空');
        }
		if (empty($data['intro'])) {
            $this->chuangError('封面简介不能为空');
        }
        return $data;
    }
	


	 public function delete($scratch_id=null)
    {
		$obj = D('Weixin_scratch');
        if($scratch_id = (int)$scratch_id){
			if(!$detail = $obj->find($scratch_id)){
				$this->chuangError('你要删除的内容不存在');
			}elseif($obj->delete($scratch_id)){
				$this->chuangSuccess('删除成功！',U('weixinscratch/index'));
			}else{
                $this->chuangError('删除失败！');
            }
        }else{
            $this->chuangError('没有指定ID');
        }
    }  

	

	public function sn($scratch_id = 0) {
        if ($scratch_id = (int) $scratch_id) {
            $obj = D('Weixin_scratchsn');
			$obje = D('Weixin_scratch');
			if(!$detail = $obje->find($scratch_id)){
				$this->chuangError('该活动不存在');
			}else{
				$this->assign('detail', $detail);
			}
			$map = array();
			$map['scratch_id'] =$scratch_id;
			$map['prize_id'] =array('gt','0');
			import('ORG.Util.Page'); // 导入分页类
			$count = $obj->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$list = $obj->where($map)->order(array('sn_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){$this->assign('list', $list);
				$this->assign('page', $show); // 赋值分页输出
			}
			 
		}else{
			$this->chuangError('该刮刮卡不存在');
		}
		$this->display();
    }
	

	public function snedit($sn_id=null)
    {
		$obj = D('Weixin_scratchsn');
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
					$this->chuangSuccess('修改成功！',U('weixinscratch/sn',array('scratch_id'=>$detail['scratch_id'])));
                }
            }
        }
    } 
	public function sndelete($sn_id=null)
    {
		$obj = D('Weixin_scratchsn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}elseif($obj->delete($sn_id)){
				$this->chuangSuccess('删除成功！',U('coupon/sn',array('coupon_id'=>$detail['scratch_id'])));
			}
        }
    }
    

	public function goods($scratch_id)
	{
		
        $obj = D('Weixin_scratch');
        $objp = D('Weixin_prize'); 
        
        if(!($scratch_id = (int)$scratch_id)){
            $this->chuangError('没有指定刮刮卡ID');
        }else if(!$detail = $obj->find($scratch_id)){
            $this->chuangError('该刮刮卡不存在或已经删除');
        }else{
            import('ORG.Util.Page'); // 导入分页类
            $map = array('scratch_id' => $scratch_id);
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
		$this->assign('member_list', $member_list); // 赋值数据集 
		$this->assign('detail', $detail); // 赋值数据集
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
	
	public function goodscreate($scratch_id=null)
    {
		
		$obj = D('Weixin_scratch');
        if(!($scratch_id = (int)$scratch_id)){
            $this->chuangError('没有指定刮刮卡ID');
        }else if(!$detail = $obj->find($scratch_id)){
            $this->chuangError('该刮刮卡不存在或已经删除');
        }if ($this->isPost()) {
			$data = $this->goodscreateCheck($scratch_id);
			$objp = D('Weixin_prize');
			if ($id = $objp->add($data)) {
				$this->chuangSuccess('添加成功', U('weixinscratch/goods',array('scratch_id'=>$scratch_id)));
            }else{
				$this->chuangError('添加失败！');
			}	
		}else{
			$this->assign('scratch_id', $scratch_id); // 赋值数据集
			$this->display();
		}	
    }

    private function goodscreateCheck($scratch_id) {
        $data = $this->checkFields($this->_post('data', false), $this->goodscreate_fields);
        $data['title'] = htmlspecialchars($data['title']);
		$data['scratch_id'] = $scratch_id;
      
        return $data;
    }
    
    public function goodsedit($id=null)
    {
		
		$obj = D('Weixin_scratch');
		$objp = D('Weixin_prize');
        if (!$prize = $objp->find($id)) {
              $this->chuangError('未指定要修改的内容ID');
        }
     
        if(!$detail = $obj->find($prize['scratch_id'])){
            $this->chuangError('您要修改的内容不存在或已经删除');
        }if ($this->isPost()) {
			$data = $this->checkFields($this->_post('data', false), $this->goodsedit_fields);
			$data['id'] = $id;
			if (false !== $objp->save($data)) {
				$this->chuangSuccess('修改成功', U('weixinscratch/goods',array('scratch_id'=>$prize['scratch_id'])));
            }else{
				$this->chuangError('修改失败！');
			}	
		}else{
			  $this->assign('detail', $prize);
              $this->assign('detail2', $detail); // 赋值数据集
			  $this->display();
		}
      
    }

	public function goodsdelete($id=null)
    {
        if($id = (int)$id){
            $objp = D('Weixin_prize');
            if(!$detail = $objp->find($id)){
                $this->chuangError('你要删除的内容不存在或已经删除');
            }elseif($objp->delete($id)){
                $this->chuangSuccess('删除成功！',U('weixinscratch/goods',array('scratch_id'=>$detail['scratch_id'])));
            }else{
                $this->chuangError('删除失败！');
            }
        }else{
            $this->chuangError('没有指定ID');
        }
    }
      
}   
