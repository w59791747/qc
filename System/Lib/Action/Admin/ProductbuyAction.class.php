<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProductbuyAction extends CommonAction{
   
    public  function index(){
       $Product = D('Product');$Productbuy = D('Productbuy');
       import('ORG.Util.Page');// 导入分页类
	   if($payed = $this->_param('payed','htmlspecialchars')){
			$map['payed'] =  $payed-2;
            $this->assign('payed',$payed);
       }

	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
       }
	  
	   if($status = $this->_param('status','htmlspecialchars')){
			$map['status'] =  $status-2;
            $this->assign('status',$status);
       }
       $count      = $Productbuy->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Productbuy->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'product_id','Product','productlist');
	   $this->itemsby($list,'uid','Member','memberlist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);
	   $this->assign('zhifu_list',$Product->zhifu());
	   $this->assign('status_list',$Product->status());
       $this->display('index'); // 输出模板
    }

	public function detail($buy_id = 0)
	{
		$obj = D('Productbuy');$Product = D('Product');$Member = D('Member');
		if (!$detail = $obj->find($buy_id)) {
			$this->error('请选择要查看的订单');
		}else{
			$this->assign('product', $Product->find($detail['product_id']));
			$this->assign('member', $Member->find($detail['uid']));
			$this->assign('zhifu_list',$Product->zhifu());
			$this->assign('status_list',$Product->status());
			$this->assign('detail', $detail);
			$this->display();
		}
	}

	public function fahuo($buy_id = 0)
	{
		$obj = D('Productbuy');
		if (!$detail = $obj->find($buy_id)) {
			$this->error('请选择要查看的订单');
		}elseif($detail['status'] != 0 || $detail['payed'] != 1){
			$this->error('该订单状态不正确');
		}else{
			$data['buy_id'] = $buy_id;
			$data['status'] = 1;
			$obj->save($data);
			$this->chuangSuccess('发货成功！',U('Productbuy/index'));
		}
	}


	public function delete($buy_id=0){
		 $obj =D('Productbuy');
        if(is_numeric($buy_id) && ($buy_id = (int)$buy_id)){
			 $obj->delete($buy_id);
			 $obj->cleanCache();
			 $this->chuangSuccess('删除成功！',U('Productbuy/index'));
			
         }
    }
}
