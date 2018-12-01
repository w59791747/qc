<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProductAction extends CommonAction {

  
	public function lists() {
        $obj = D('Productbuy');$Product = D('Product');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('uid'=>$this->uid);
        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->itemsby($list,'product_id','Product','productlist');
		
	    $this->assign('zhifu_list',$Product->zhifu());
	    $this->assign('status_list',$Product->status());

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

	public function orderdetail($order_id = 0)
	{
		$obj = D('Productbuy');$Product = D('Product');
        if ($order_id = (int) $order_id) {
            if (!$detail = $obj->find($order_id)) {
                $this->error('请选择要查看的订单',U('product/lists'));
            }elseif($detail['uid'] != $this->uid){
				 $this->error('您没有权限查看该订单',U('product/lists'));
			}
			if ($this->isPost()) {
			
			}else{
				$this->assign('detail', $detail);
				$this->assign('product', $Product->find($detail['product_id']));
				$this->assign('zhifu_list',$Product->zhifu());
				$this->assign('status_list',$Product->status());
				$this->display();
			}

			
            
        } else {
            $this->error('请选择要查看的订单');
        }
	}

    public function orderdelete($order_id = 0) {
		$obj = D('Productbuy');
        if(!$detail = $obj->find($order_id)){
			 $this->chuangError('请选择要取消的订单');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限查看该订单');
		}else{
            $obj->delete($order_id);
            $this->chuangSuccess('取消成功！', U('Product/lists'));
        }
    }

	
	public  function shouhuo($buy_id = 0)
	{
		$obj = D('Productbuy');
		if (!$detail = $obj->find($buy_id)) {
			$this->chuangError('请选择要查看的订单');
		}elseif($detail['status'] != 1 || $detail['payed'] != 1){
			$this->chuangError('该订单状态不正确');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限操作该订单');
		}else{
			$data['buy_id'] = $buy_id;
			$data['status'] = 2;
			$obj->save($data);
			$this->chuangSuccess('收货成功！',U('Product/lists'));
		}
	}

}
