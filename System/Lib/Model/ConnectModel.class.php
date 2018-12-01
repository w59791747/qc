<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class  ConnectModel extends Model{
    protected $pk   = 'connect_id';
    protected $tableName =  'connect';
    
    public function getConnectByOpenid($type,$open_id){
        
        return $this->find(array("where"=>array(
            'type' => $type,
            'open_id' => $open_id
        )));     
    }
    
    
}