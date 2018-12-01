<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class JifenAction extends CommonAction {
	
	public function gold()
	{
	$Memberlog = D('Memberlog');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	    $stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
	   $status = $_POST['status'];
	   if($status == 1){
		 $map['number'] = array('GT','0');
	   }elseif($status == 2){
	     $map['number'] = array('LT','0');
	   }
	   $map['from'] = 'gold';
	   $map['uid'] = $this->uid;
       $count      = $Memberlog->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberlog->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
	   //echo $Memberlog->getLastSql();echo "File:", __FILE__, ',Line:',__LINE__;exit;
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
	   $this->assign('status',$status);
       $this->display(); // 输出模板
    }
  
	public function lists() {
        $obj = D('Productbuy');$Product = D('Product');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('uid'=>$this->uid);

		$stime = strtotime($_POST['stime']);
		$ltime = strtotime($_POST['ltime']);
		if($stime && !$ltime){
			$map['dateline'] = array('gt',$stime);
			$this->assign('stime', $_POST['stime']);
		}elseif(!$stime && $ltime){
			$map['dateline'] = array('lt',$ltime);
			$this->assign('ltime', $_POST['ltime']);
		}elseif($stime && $ltime){
			$map['dateline'] = array('between',array($stime,$ltime));
			$this->assign('ltime', $_POST['ltime']);
			$this->assign('stime', $_POST['stime']);
		}
	   $status = $_POST['status'];
	   if($status == 1){
		 $map['payed'] = 1;
	   }elseif($status == 2){
	     $map['payed'] = 0;
	   }

        $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->itemsby($list,'product_id','Product','productlist');
		
	    $this->assign('zhifu_list',$Product->zhifu());
	    $this->assign('status_list',$Product->status());

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('status',$status);
        $this->display(); // 输出模板
    }



    public function orderdelete($order_id = 0) {
		$obj = D('Productbuy');
        if(!$detail = $obj->find($order_id)){
			 $this->chuangError('请选择要取消的订单');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限查看该订单');
		}else{
            $obj->delete($order_id);
            $this->chuangSuccess('取消成功！', U('Jifen/lists'));
        }
    }

	
	
}
