<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class HuobanModel extends CommonModel{
    protected $pk   = 'huoban_id';
    protected $tableName =  'huoban';
    protected $token = 'chuang_huoban';
    
    public function CallDataForMat($items)
	{
		return $items;
	}

   
}