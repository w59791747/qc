<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class AdminModel extends CommonModel{
    protected $pk   = 'admin_id';
    protected $tableName =  'admin';
    
     public function getAdminByUsername($username){
        $data = $this->find(array('where'=>array('admin_name'=>$username)));
        return $this->_format($data);
    }
    
    public  function _format($data){
        static  $roles;
        if(empty($roles)) $roles = D('Role')->fetchAll();
        if(!empty($data)) $data['role_name'] = $roles[$data['role_id']]['role_name'];    
        return $data;
    }
}