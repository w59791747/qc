<?php


/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class WeixincouponAction extends CommonAction 
{
   private $create_fields = array('coupon_id','title','intro','photo','stime','ltime','use_tips','end_tips','end_photo','num','max_count','down_count','use_count','views','follower_condtion','clientip','dateline');
    private $edit_fields = array('coupon_id','title','intro','photo','stime','ltime','use_tips','end_tips','end_photo','num','max_count','down_count','use_count','views','follower_condtion','clientip','dateline');
    
    public function _initialize() {
        parent::_initialize();
    }
    public function index($page=1)
    {
		
		import('ORG.Util.Page'); // 导入分页类
		$obj = D('Weixin_coupon');
		$count = $obj->where($map)->count();
		$Page = new Page($count, 25);
		$show = $Page->show();
		$list = $obj->where($map)->order(array('coupon_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
		foreach ($list as $k => $val) {
            $url = U('weixin/coupon/detail', array('coupon_id' => $val['coupon_id']));
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
			$obj = D('Weixin_coupon');
			$map = array();
			if ($id = $obj->add($data)) {
				 $this->chuangSuccess('添加成功', U('weixincoupon/index'));
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
        $data['dateline'] = NOW_TIME;
        $data['clientip'] = get_client_ip();
        return $data;
    }

	
	 public function edit($coupon_id = null) {
        if ($coupon_id = (int) $coupon_id) {
            $obj = D('Weixin_coupon');
            if (!$detail = $obj->find($coupon_id)) {
                $this->chuangError('请选择要编辑的活动');
            }
           
            if ($this->isPost()) {
                $data = $this->editCheck();
				$obj = D('Weixin_coupon');
				$data['coupon_id'] = $coupon_id;			
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('weixincoupon/index'));
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
	
	public function delete($coupon_id=null)
    {
		$obj = D('Weixin_coupon');
        if($coupon_id = (int)$coupon_id){
			if(!$detail = $obj->find($coupon_id)){
				$this->chuangError('你要删除的内容不存在');
			}elseif($obj->delete($coupon_id)){
				$this->chuangSuccess('删除成功！',U('weixincoupon/index'));
			}
        }
    } 

	public function sn($coupon_id = 0) {
        if ($coupon_id = (int) $coupon_id) {
            $obj = D('Weixin_couponsn');
			$obje = D('Weixin_coupon');
			if(!$detail = $obje->find($coupon_id)){
				$this->chuangError('该活动不存在');
			}else{
				$this->assign('detail', $detail);
			}
			$map = array();
			$map['coupon_id'] =$coupon_id;
			import('ORG.Util.Page'); // 导入分页类
			$count = $obj->where($map)->count();
			$Page = new Page($count, 15);
			$show = $Page->show();
			$list = $obj->where($map)->order(array('sn_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
			if($list){$this->assign('list', $list);
				$this->assign('page', $show); // 赋值分页输出
			}
			 
		}else{
			$this->chuangError('该优惠券不存在');
		}
		$this->display();
    }
	
	public function snedit($sn_id=null)
    {
		$obj = D('Weixin_couponsn');
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
					$this->chuangSuccess('修改成功！',U('weixincoupon/sn',array('coupon_id'=>$detail['coupon_id'])));
                }
            }
        }
    } 

	public function sndelete($sn_id=null)
    {
		$obj = D('Weixin_couponsn');
        if($sn_id = (int)$sn_id){
			if(!$detail = $obj->find($sn_id)){
				$this->chuangError('你要修改的内容不存在或已经删除');
			}elseif($obj->delete($sn_id)){
				$this->chuangSuccess('删除成功！',U('weixincoupon/sn',array('id'=>$detail['egg_id'])));
			}
        }
    }  
}   
