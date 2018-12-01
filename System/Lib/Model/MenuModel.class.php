<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：风云
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com
 */

class MenuModel extends CommonModel {

    protected $pk = 'menu_id';
    protected $tableName = 'menu';
    protected $token = 'menu';
    protected $orderby = array('orderby'=>'asc');
   
    public function checkAuth($auth) {
        $data = $this->fetchAll();
        foreach ($data as $row) {
            if ($auth == $row['menu_action']) {
                return true;
            }
        }
        return false;
    }

	public function role_menu($role_id)
	{
		$menu_list = D('RoleMaps')->getMenuIdsByRoleId($role_id);
		$menu = $this->fetchAll();
		if ($role_id != 1) {
            if ($menu_list) {
                foreach ($menu as $k => $val) {
                    if (!empty($val['menu_action']) && !in_array($k, $menu_list)) {
                        unset($menu[$k]);
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = true;
                                foreach ($menu as $k3 => $v3) {
                                    if ($v3['parent_id'] == $v2['menu_id']) {
                                        $unset = false;
                                    }
                                }
                                if ($unset)
                                    unset($menu[$k2]);
                            }
                        }
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        $unset = true;
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = false;
                            }
                        }
                        if ($unset)
                            unset($menu[$k1]);
                    }
                }
            }else {
                $menu = array();
            }
        }
		return $menu;
	}

	public function top_menu($role_id)
	{
		$data = $this->role_menu($role_id);
		foreach($data as $k => $v){
			if($v['parent_id'] == 0 && $v['is_show'] == 1){
				$result[$v['menu_id']] = $v; 
			}
		}
		return $result;
	}

	public function child_menu($menu_id,$role_id)
	{
		$data = $this->role_menu($role_id);

		$result = array();
		foreach($data as $k => $v){
			if($v['parent_id'] == $menu_id && $v['is_show'] == 1){
				$result[$v['menu_id']] = $v;
			}
		}
		foreach($data as $k => $v){
			foreach($result as $kk => $vv){
				if($v['parent_id'] == $vv['menu_id'] && $v['is_show'] == 1){
					$result[$kk]['child'][$v['menu_id']] = $v;
				}
			}
		}

		return $result;
	}

    

}