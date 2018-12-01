<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class BlockModel extends CommonModel{
    protected $pk   = 'block_id';
    protected $tableName =  'block';

	protected $from = array('Member'=>'用户','Project'=>'项目',  'Activity'=>'路演');
	protected $type = array('hot'=>'浏览量','new'=>'最新',  'orderby'=>'排序','no'=>'不补充');

	

    public function from()
    {
        return $this->from;
    }

	public function type()
    {
        return $this->type;
    }
}