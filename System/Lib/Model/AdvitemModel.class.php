<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class AdvitemModel extends CommonModel{
    protected $pk   = 'item_id';
    protected $tableName =  'adv_item';

	public function _format_row($row){
		if($row['stime']){
			$row['stime'] = date('Y-m-d',$row['stime']);
		}
		if($row['ltime']){
			$row['ltime'] = date('Y-m-d',$row['ltime']);
		}
		return $row;
	}
}