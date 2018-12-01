<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class  PaymentAction extends CommonAction{
    
    public function index(){
       $Payment = D('Payment');
       import('ORG.Util.Page');// 导入分页类
       $count      = $Payment->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Payment->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
    public function uninstall(){
        $payment_id = (int)  $this->_get('payment_id');
        $payments = D('Payment')->fetchAll();
        if(!$payments[$payment_id]){
            $this->chuangError('没有该支付方式！');
        }
        $datas = array(
                'payment_id'=>$payment_id,
                'is_open' => 0
            );
        D('Payment')->save($datas);
        D('Payment')->cleanCache();
        $this->chuangSuccess('卸载支付方式成功！',U('payment/index'));
    }
    
    public function install(){
        $payment_id = (int)  $this->_get('payment_id');
        $payments = D('Payment')->fetchAll();
        if(!$payments[$payment_id]){
            $this->error('没有该支付方式！');die;
        }
        if($payments[$payment_id]['code'] == 'money'){
            D('Payment')->save(array(
                'payment_id'=>$payment_id,
                'is_open' => 1
            ));
            $this->success("安装成功", U('payment/index'));die;
        }
        if($this->isPost()){
            $data = $this->_post('data',false);
			if($data['sqepay_gateway']){
				foreach($data['sqepay_gateway'] as $k => $v){
					$data['gateway'] .= $k.','.$v.'|';
				}
				unset($data['sqepay_gateway']);
			}
            $datas = array(
                'payment_id'=>$payment_id,
                'setting' => serialize($data),
                'is_open' => 1
            );
            D('Payment')->save($datas);
            D('Payment')->cleanCache();
            $this->chuangSuccess('恭喜您安装支付方式成功！',U('payment/index'));
        }else{
			if($payments[$payment_id]["setting"]["gateway"]){
				$arr = explode('|',$payments[$payment_id]["setting"]["gateway"]);
				foreach($arr as $k =>$v){
					$t = '';
					$t = explode(',',$v);
					if($t[1] =='1'){
						$attall[$t[0]] = $t[1];
					}

				}
				$this->assign('attall',$attall);
			}
            $this->assign('detail',$payments[$payment_id]);
            $this->display($payments[$payment_id]['code']);
        }
        
    }

	public function closemobile($payment_id)
	{
		if(is_numeric($payment_id) && ($payment_id = (int)$payment_id)){
             $obj =D('Payment');
			 $Payment = $obj->find($payment_id);
			 if($Payment['is_mobile_only'] == 1){
				$data['is_mobile_only'] = 0;
			 }else{
				$data['is_mobile_only'] = 1;
			 }
			 $data['payment_id'] = $payment_id;
             $obj->save($data);
             $obj->cleanCache();
             $this->chuangSuccess('操作成功！',U('payment/index'));
         }
	}
    
}
