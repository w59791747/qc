<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ProductAction extends CommonAction {
    
  
   public  function index($cate=0,$fanwei=0,$order=1){

	   $CONFIG = D('Setting')->fetchAll();
	   if($CONFIG['power']['product'] != 'on'){
			$this->error('管理员未开启积分商城模块',U('home/index'));
	   }else{
		   $Product = D('Product');
		   import('ORG.Util.Page');// 导入分页类
			
		   $fanwei_arr = $Product->fanwei();

		   $map['audit'] = 1;$map['store'] = array('gt',0);
			if($cate){
				$map['cate_id'] = $cate;
				 $this->assign('cate',$cate);// 赋值数据集
			}

			if($fanwei){
			
				$arr = explode('-',$fanwei_arr[$fanwei]);
				if(count($arr) == 2){
					$map['jifen'] = array('between',($arr[0]).','.($arr[1]));
				}elseif(strpos($fanwei_arr[$fanwei],'以下') !== false){
					preg_match('/[0-9]+/',$fanwei_arr[$fanwei],$match);
					 $map['jifen'] = array('ELT',$match[0]);
				}else{
					 preg_match('/[0-9]+/',$fanwei_arr[$fanwei],$match);
					 $map['jifen'] = array('EGT',$match[0]);
					 
				}
				$this->assign('fanwei',$fanwei);
			}

			if($order == 1){
				$orderby = array('buys'=>'desc');
			}elseif($order == 2){
				$orderby = array('dateline'=>'desc');
			}elseif($order == 3){
				$orderby = array('jifen'=>'desc');
			}
		   $count      = $Product->where($map)->count();// 查询满足要求的总记录数 
		   $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		   $show       = $Page->show();// 分页显示输出
		   $list = $Product->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		   $this->assign('list',$list);// 赋值数据集
		   $this->assign('catelist', D('Productcate')->fetchAll());
		   $this->assign('fanwei_arr',$fanwei_arr);
		   $this->assign('page',$show);
		   $this->assign('order',$order);
		   $this->display();
		 }
    }
    
    public function detail($product_id=0) {
	   
				
	   $CONFIG = D('Setting')->fetchAll();
	   if($CONFIG['power']['product'] != 'on'){
			$this->error('管理员未开启积分商城模块',U('home/index'));
	   }else{
		    $Product = D('Product');
			$Product->updateCount($product_id,'views',1);
		    if(!$detail = $Product->find($product_id)){
				$this->error('该商品不存在',U('product/index'));
			}elseif($detail['audit'] != '1'){
				$this->error('该商品未审核',U('product/index'));
			}else{$detail['content'] = htmlspecialchars_decode($detail['content']);
				$this->assign('detail',$detail);
				$this->assign('catelist', D('Productcate')->fetchAll());
				$this->seodatas['title'] =$detail['title'];
				$this->display();
			}
			
	   }
    }

	public function buys($product_id=0,$buys=1)
	{
	   $member = $this->member;
		
	   $CONFIG = D('Setting')->fetchAll();
	   if($CONFIG['power']['product'] != 'on'){
			$this->error('管理员未开启积分商城模块',U('home/index'));
	   }elseif(!$member){
			$this->error('请先登录再购买',U('passport/login'));
	   }else{
		    $Product = D('Product');$Memberaddr = D('Memberaddr');
		    if(!$detail = $Product->find($product_id)){
				$this->error('该商品不存在',U('product/index'));
			}elseif($detail['audit'] != '1'){
				$this->error('该商品未审核',U('product/index'));
			}elseif($detail['store'] < $buys){
				$this->error('库存不足',U('product/detail',array('product_id'=>$product_id)));
			}else{
				$addr = $Memberaddr->where(array('uid'=>$this->uid,'default'=>'1'))->find();
				$this->assign('addr', $addr);
				$this->assign('buys', $buys);
				$this->assign('detail', $detail);
				$this->display();
			}
	   }
	}

	public  function pay()
	{

	   $member = $this->member;
		
	   $CONFIG = D('Setting')->fetchAll();
	   if($CONFIG['power']['product'] != 'on'){
			$this->error('管理员未开启积分商城模块',U('home/index'));
	   }elseif(!$member){
			$this->error('请先登录再购买',U('passport/login'));
	   }else{
			$data = $this->_post('data', 'htmlspecialchars');
			if ($data['amount'] < '1') {
				$this->chuangError('不能小于1');
			}
			if (!$data['contact']) {
				$this->chuangError('联系人不能为空');
			}
			if (!isMobile($data['phone'])) {
				$this->chuangError('手机格式不正确');
			}
			if (!$data['city']) {
				$this->chuangError('城市不能为空');
			}
			if (!$data['addr']) {
				$this->chuangError('地址不能为空');
			}

			$Product = D('Product');$Productbuy = D('Productbuy');
			
			if(!$detail = $Product->find($data['product_id'])){
				$this->error('该商品不存在',U('product/index'));
			}elseif($detail['audit'] != '1'){
				$this->error('该商品未审核',U('product/index'));
			}elseif($detail['store'] < $data['buys']){
				$this->error('库存不足',U('product/detail',array('product_id'=>$data['product_id'])));
			}else{
				
				$data['uid'] =$this->uid;
				$data['product_id'] =$data['product_id'];
				$data['num'] = $data['buys'];
				$data['danjia'] = $detail['jifen'];
				$data['yunfei'] = $detail['freight'];
				$data['name'] = $data['contact'];
				$data['mobile'] = $data['phone'];
				$data['addr'] = $data['city'].$data['addr'];
				$data['amount'] = $data['amount'];
				$data['dateline'] = NOW_TIME;
				$order_id = $Productbuy->add($data);
				
				$Product->updateCount($data['product_id'],'store',-$data['buys']);
				$Product->updateCount($data['product_id'],'buys',$data['buys']);

			}
			
			$this->chuangSuccess('提交成功,确认支付信息', U('product/pay2',array('order_id'=>$order_id)));
		}
	}

	public function pay2($order_id)
	{

	   $member = $this->member;
		
	   $CONFIG = D('Setting')->fetchAll();
	   if($CONFIG['power']['product'] != 'on'){
			$this->error('管理员未开启积分商城模块',U('home/index'));
	   }elseif(!$member){
			$this->error('请先登录再购买',U('passport/login'));
	   }else{
		    $Product = D('Product');$Productbuy = D('Productbuy');
		    $detail = $Productbuy->find($order_id);
			$this->assign('order_id', $order_id);
			$this->assign('detail', $detail);
			$this->assign('product', $Product->find($detail['product']));
			$this->display();	
		}
	}

	public function  payment()
	{
		$Product = D('Product');$Productbuy = D('Productbuy');
		$data = $this->_post('data', 'htmlspecialchars');
		$order_id = $data['order_id'];
        if(!is_numeric($order_id)){
			$this->chuangError('该订单不存在');
        }else if($this->uid){
            if(!$order = $Productbuy->find($order_id)){
				$this->chuangError('您的订单不存在或已经删除');
            }else if($order['payed'] == 1){
				$this->chuangError('该订单已经支付过了,不需要重复支付');
            }else if($this->member['gold']<$order['amount']){
				$this->chuangError('积分不足 请先充值');
            }else{

				D('member')->updateCount($order['uid'],'gold',-$order['amount']);
				D('Memberlog')->add_log($order['uid'],'gold',-$order['amount'],'积分商城购买商品',1);
				$data['buy_id'] = $order_id;
				$data['payed'] = 1;
				$data['payedtime'] = NOW_TIME;
				$Productbuy->save($data);

				$this->chuangSuccess('支付成功', U('member/jifen/lists'));
			}
				
        }
	}
		

}

	