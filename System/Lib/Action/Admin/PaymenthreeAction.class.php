<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class  PaymenthreeAction extends CommonAction{
    
    public function index(){
       $Paymenthree = D('Paymenthree');
       import('ORG.Util.Page');// 导入分页类
       $count      = $Paymenthree->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Paymenthree->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
    public function uninstall(){
        $payment_id = (int)  $this->_get('payment_id');
        $payments = D('Paymenthree')->fetchAll();
        if(!$payments[$payment_id]){
            $this->chuangError('没有该托管方式！');
        }
        $datas = array(
                'payment_id'=>$payment_id,
                'is_open' => 0
            );
        D('Paymenthree')->save($datas);
        D('Paymenthree')->cleanCache();
        $this->chuangSuccess('卸载托管方式成功！',U('paymenthree/index'));
    }
    
    public function install(){
        $payment_id = (int)  $this->_get('payment_id');
        $payments = D('Paymenthree')->fetchAll();
		
        if(!$payments[$payment_id]){
            $this->error('没有该托管方式！');die;
        }
        if($payments[$payment_id]['code'] == 'money'){
            D('Paymenthree')->save(array(
                'payment_id'=>$payment_id,
                'is_open' => 1
            ));
            $this->success("安装成功", U('paymenthree/index'));die;
        }
        if($this->isPost()){
            $data = $this->_post('data',false);
            $datas = array(
                'payment_id'=>$payment_id,
                'setting' => serialize($data),
                'is_open' => 1
            );
            D('Paymenthree')->save($datas);
            D('Paymenthree')->cleanCache();
            $this->chuangSuccess('恭喜您安装托管方式成功！',U('paymenthree/index'));
        }else{

            $this->assign('detail',$payments[$payment_id]);
            $this->display($payments[$payment_id]['code']);
        }
        
    }
    
}
