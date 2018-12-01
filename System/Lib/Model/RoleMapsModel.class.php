<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class  RoleMapsModel extends CommonModel{
    
    protected $tableName =  'role_maps';
    
    public function getMenuIdsByRoleId($role_id){
        $role_id = (int) $role_id;
        $datas = $this->where(" role_id = '{$role_id}' ")->select();
        $return = array();
        foreach($datas as $val){
            $return[$val['menu_id']] = $val['menu_id'];
        }
        return $return;
    }
    
}
