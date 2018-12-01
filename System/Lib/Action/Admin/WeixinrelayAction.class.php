<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class WeixinrelayAction extends CommonAction 
{
	private $create_fields = array('relay_id','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','relay_num','min_gold','max_gold','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline','time');
	private $edit_fields = array('relay_id','title','intro','photo','stime','ltime','use_tips','end_tips','predict_num','relay_num','min_gold','max_gold','max_num','follower_condtion','member_condtion','collect_count','views','end_photo','clientip','dateline','time');
    private $goodscreate_fields = array('id','relay_id','title','name','num','sort','photo');
	private $goodsedit_fields = array('id','relay_id','title','name','num','sort','photo');
	
	public function index($page=1)
    {
        
		import('ORG.Util.Page'); // 导入分页类
		$obj = D('Weixin_relay');
		$pager = array();
		$count = $obj->where($map)->count();
		$Page = new Page($count, 25);
		$show = $Page->show();
		$list = $obj->where($map)->order(array('relay_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $val) {
            $url = U('weixin/relay/preview', array('relay_id' => $val['relay_id']));
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
			$obj = D('Weixin_relay');
			
			$map = array();
					
			if ($id = $obj->add($data)) {
				 $this->chuangSuccess('添加成功', U('weixinrelay/index'));
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
        return $data;
    }
	public function edit($relay_id = null) {
        if ($relay_id = (int) $relay_id) {
            $obj = D('Weixin_relay');
            if (!$detail = $obj->find($relay_id)) {
                $this->chuangError('请选择要编辑的活动');
            }
            
            if ($this->isPost()) {
                $data = $this->editCheck();
				$obj = D('Weixin_relay');
				$data['relay_id'] = $relay_id;			
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('weixinrelay/index'));
                }
                $this->chuangError('修改失败');
            } else {
				$this->assign('relay_id', $relay_id);
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
	public function delete($relay_id=null)
    {
		$obj = D('Weixin_relay');
        if($relay_id = (int)$relay_id){
			if(!$detail = $obj->find($relay_id)){
				$this->chuangError('你要删除的内容不存在');
			}elseif($obj->delete($relay_id)){
				$this->chuangSuccess('删除成功！',U('weixinrelay/index'));
			}
        }else{
			$this->chuangError('非法操作！');
		}
    }
	
	public function sn($relay_id = 0) {
        if ($relay_id = (int) $relay_id) {
            $obj = D('Weixin_relaysn');
			$obje = D('Weixin_relay');
			if(!$detail = $obje->find($relay_id)){
				$this->chuangError('该活动不存在');
			}else{
				$this->assign('detail', $detail);
			}
			$map = array();
			$map['relay_id'] =$relay_id;
			import('ORG.Util.Page'); // 导入分页类
			$count = $obj->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$list = $obj->where($map)->order(array('sn_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){
				$uids = '';
				foreach($list as $k => $v){
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
		$weixin = $this->ucenter_weixin();
        if($sn_id = (int)$sn_id){
            if(!$detail = K::M('weixin/relaysn')->detail($sn_id)){
                $this->err->add('你要删除的内容不存在或已经删除', 211);
            }else{
                if(K::M('weixin/relaysn')->delete($sn_id)){
                    $this->err->add('删除内容成功');
                }
            }
        }else if($ids = $this->GP('sn_id')){
            if(K::M('weixin/relaysn')->delete($ids)){
                $this->err->add('批量删除内容成功');
            }
        }else{
            $this->err->add('未指定要删除的内容ID', 401);
        }
    }  

	public function snedit($sn_id=null)
    {
		$obj = D('Weixin_relaysn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}else{
				if($detail['is_use'] == '1'){
					$data['is_use'] = 0;
					$data['use_time'] = '';
				}else{
					$data['is_use'] = 1;
					$data['use_time'] = __TIME;
				}
				$data['sn_id'] = $sn_id;
                if($obj->save($data)){
					$this->chuangSuccess('修改成功！',U('weixinrelay/sn',array('relay_id'=>$detail['relay_id'])));
                }
            }
        }
    }
	
	public function goods($relay_id)
	{
		
        $obj = D('Weixin_relay');
        $objp = D('Weixin_relayprize'); 
        
        if(!($relay_id = (int)$relay_id)){
            $this->chuangError('没有指定微接力ID');
        }else if(!$detail = $obj->find($relay_id)){
            $this->chuangError('该微接力不存在或已经删除');
        }else{
            import('ORG.Util.Page'); // 导入分页类
			$map['relay_id'] = $relay_id;
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
		$this->assign('relay_id', $relay_id); // 赋值数据集
		$this->assign('member_list', $member_list); // 赋值数据集 
		$this->assign('detail', $detail); // 赋值数据集
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

	public function goodscreate($relay_id=null)
    {
		
		$obj = D('Weixin_relay');
        if(!($relay_id = (int)$relay_id)){
            $this->chuangError('没有指定微接力ID');
        }else if(!$detail = $obj->find($relay_id)){
            $this->chuangError('未指定内容ID');
        }if ($this->isPost()) {
			$data = $this->goodscreateCheck($relay_id);
			$objp = D('Weixin_relayprize');
			if ($id = $objp->add($data)) {
				$this->chuangSuccess('添加成功', U('weixinrelay/goods',array('relay_id'=>$relay_id)));
            }else{
				$this->chuangError('添加失败！');
			}	
		}else{
			$this->assign('relay_id', $relay_id); // 赋值数据集
			$this->display();
		}	
    }
	private function goodscreateCheck($relay_id) {
        $data = $this->checkFields($this->_post('data', false), $this->goodscreate_fields);
        $data['title'] = htmlspecialchars($data['title']);
		$data['relay_id'] = $relay_id;
        return $data;
    }

	public function goodsedit($id=null)
    {
		
		$obj = D('Weixin_relay');
		$objp = D('Weixin_relayprize');
        if (!$detail = $objp->find($id)) {
              $this->chuangError('未指定要修改的内容ID');
        }
       
        if(!$detail['relay_id']){
            $this->chuangError('没有指定微接力ID');
        }else if(!$details = $obj->find($relay_id)){
            $this->chuangError('您要修改的内容不存在或已经删除');
        }if ($this->isPost()) {
			$data = $this->checkFields($this->_post('data', false), $this->goodsedit_fields);
			$data['id'] = $id;
			if (false !== $objp->save($data)) {
				$this->chuangSuccess('修改成功', U('weixinrelay/goods',array('relay_id'=>$detail['relay_id'])));
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
            $objp = D('Weixin_relayprize');
            if(!$detail = $objp->find($id)){
                $this->chuangError('你要删除的内容不存在或已经删除');
            }elseif($objp->delete($id)){
                $this->chuangSuccess('删除成功！',U('weixinrelay/goods',array('relay_id'=>$detail['relay_id'])));
            }else{
                $this->chuangError('删除失败！');
            }
        }else{
            $this->chuangError('没有指定ID');
        }
    }
	
	public function snlist($sn_id){
		
		
		$objU = D('Member_weixin');
		
		if(!$sn_id){
			$this->chuangError('你要查看的内容不存在或已经删除');
		}elseif(!$detail = D('Weixin_relaysn')->find($sn_id)){
			$this->chuangError('你要查看的内容不存在或已经删除');
		}else{
			$objl = D('Weixin_relaylist');
			import('ORG.Util.Page'); // 导入分页类
            $map['relay_id'] = $detail['relay_id'];
			$map['openid'] = $detail['openid'];
            $count = $objl->where($map)->count();
            $Page = new Page($count, 25);
            $show = $Page->show();
            $list = $objl->where($map)->order(array('list_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $this->assign('detail',$detail);
			$this->assign('page', $show);
			$this->assign('list', $list);		
		}
		$this->display();
	}

}   
