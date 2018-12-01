<?php


/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class WeixinpacketAction extends CommonAction 
{
	 private $create_fields = array('id','shop_id','title','title','keyword','msg_pic','desc','info','start_time','end_time','ext_total','get_number','value_count','is_open','item_num','item_sum','item_max','item_unit','packet_type','deci','people','password');
    private $edit_fields = array('id','shop_id','title','title','keyword','msg_pic','desc','info','start_time','end_time','ext_total','get_number','value_count','is_open','item_num','item_sum','item_max','item_unit','packet_type','deci','people','password');

	 public function index($page=1)
    {
       
        import('ORG.Util.Page'); // 导入分页类
		$obj = D('Weixin_packet');
		$count = $obj->where($map)->count();
		$Page = new Page($count, 25);
		$show = $Page->show();
		$list = $obj->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $val) {
            $url = U('weixin/packet/index', array('packet_id' => $val['id']));
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
			$obj = D('Weixin_packet');
			if ($id = $obj->add($data)) {
				 $this->chuangSuccess('添加成功', U('weixinpacket/index'));
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
		if (empty($data['start_time'])) {
            $this->chuangError('开始时间不能为空');
        }
		if (empty($data['end_time'])) {
            $this->chuangError('结束时间不能为空');
        }
		if (empty($data['info'])) {
            $this->chuangError('活动规则不能为空');
        }
        $data['type'] = news;
		$data['dateline'] = NOW_TIME;
        if (empty($data['type'])) {
            $data['type'] = news;
        }
        $data['create_ip'] = get_client_ip();
        return $data;
    }


	public function edit($id = null) {
        if ($id = (int) $id) {
            $obj = D('Weixin_packet');
            if (!$detail = $obj->find($id)) {
                $this->chuangError('请选择要编辑的活动');
            }
           
            if ($this->isPost()) {
                $data = $this->editCheck();
				$obj = D('Weixin_packet');
				$data['id'] = $id;			
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('weixinpacket/index'));
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
		if (empty($data['start_time'])) {
            $this->chuangError('开始时间不能为空');
        }
		if (empty($data['end_time'])) {
            $this->chuangError('结束时间不能为空');
        }
		if (empty($data['info'])) {
            $this->chuangError('活动规则不能为空');
        }
        return $data;
    }
	public function delete($id=null)
    {
		$obj = D('Weixin_packet');
        if($id = (int)$id){
			if(!$detail = $obj->find($id)){
				$this->chuangError('你要删除的内容不存在');
			}elseif($obj->delete($id)){
				$this->chuangSuccess('删除成功！',U('weixinpacket/index'));
			}else{
                $this->chuangError('删除失败！');
            }
        }else{
            $this->chuangError('没有指定ID');
        }
    }


	public function sn($packet_id = 0) {
        if ($packet_id = (int) $packet_id) {
            $obj = D('Weixin_packetsn');
			$obje = D('Weixin_packet');
			if(!$detail = $obje->find($packet_id)){echo "File:", __FILE__, ',Line:',__LINE__;exit;
				$this->chuangError('该活动不存在');
			}else{
				$this->assign('detail', $detail);
			}
			$map = array();
			$map['packet_id'] =$packet_id;
			import('ORG.Util.Page'); // 导入分页类
			$count = $obj->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$list = $obj->where($map)->order(array('sn_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){
				$this->assign('list', $list);
				$this->assign('page', $show); // 赋值分页输出
			}
			 
		}else{
			$this->chuangError('该红包不存在');
		}
		$this->display();
    }
	public function sndelete($sn_id=null)
    {
		$obj = D('Weixin_packetsn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}elseif($obj->delete($sn_id)){
				$this->chuangSuccess('删除成功！',U('weixinpacket/sn',array('packet_id'=>$detail['packet_id'])));
			}
        }
    }

	public function snedit($sn_id=null)
    {
		$obj = D('Weixin_packetsn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}else{
				if($detail['is_reward'] == '1'){
					$data['is_reward'] = '0';
				}else{
					$data['is_reward'] = '1';
				}
				$data['sn_id'] = $sn_id;
                if($obj->save($data)){
					$this->chuangSuccess('修改成功！',U('weixinpacket/sn',array('packet_id'=>$detail['packet_id'])));
                }
            }
        }else{
			$this->chuangError('没有指定红包ID');
		}
    } 

	public function logs($id)
	{
		$obj = D('Weixin_packetsn');
        if($sn_id = (int)$id){
			$data['packet_id'] = $sn_id;
			$detail = $obj->where($data)->select();
			$objl = D('Weixin_packetling');
			$map['packet_id'] = $sn_id;
			import('ORG.Util.Page'); // 导入分页类
			$count = $objl->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$list = $objl->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){
				$this->assign('page', $show); // 赋值分页输出
				$this->assign('list', $list); 
			}
			
		}else{
			$this->error('没有指定红包ID');
		}
		$this->assign('packet', D('Weixin_packet')->find($id)); 
		$this->assign('detail', $detail); 
		$this->display();
	}
	
	public function logsdelete($id=null)
    {
		$objl = D('Weixin_packetling');
        if($id = (int)$id){
			if(!$detail = $objl->find($id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}elseif($objl->delete($id)){
				$this->chuangSuccess('删除成功！',U('weixinpacket/logs',array('id'=>$detail['id'])));
			}
        }else{
			$this->chuangError('没有指定ID');
		}
			
	}

}   
