<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class MembercardAction extends CommonAction{
    private $create_fields = array('bank','card','zhihang','name');
    private $edit_fields = array('bank','card','zhihang','name');
    
    public  function index(){
       $Membercard = D('Membercard');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   if($uid = $this->_param('uid','htmlspecialchars')){
			$map['uid'] =  $uid;
            $this->assign('uid',$uid);
        }
       $count      = $Membercard->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Membercard->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
	   $this->itemsby($list,'uid','Member','memberlist');
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
   
    
    public function delete($card_id = 0){
         if($card_id = (int)$card_id){
             $obj =D('Membercard');
             $obj->delete($card_id);
             $obj->cleanCache();
             $this->chuangSuccess('删除成功！',U('Membercard/index'));
         }
         $this->chuangError('删除失败!');
    }

    
    
  
    
   
}
