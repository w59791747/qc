<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class ProductModel extends CommonModel{
    protected $pk   = 'product_id';
    protected $tableName =  'product';
	protected $token =  'product';

	protected $create_fields = array('product_id', 'title','cate_id','jifen','freight','photo','views','store','buys','orderby','audit','content','dateline');
    protected $edit_fields = array('product_id', 'title','cate_id','jifen','freight','photo','views','store','buys','orderby','audit','content','dateline');
	
	protected $zhifu = array('0'=>'未支付','1'=>'已支付');
	protected $status = array('0'=>'未发货','1'=>'已发货');

	protected $fanwei = array('1'=>'500积分以下','2'=>'500-1000积分',  '3'=>'1000-3000积分', '4'=>'3000-5000积分','5'=>'5000积分以上');

	
	
	public function fanwei()
    {
        return $this->fanwei;
    }


	public function create_fields()
    {
        return $this->create_fields;
    }
	
	public function edit_fields()
    {
        return $this->edit_fields;
    }

	public function zhifu()
    {
        return $this->zhifu;
    }

	public function status()
    {
        return $this->status;
    }


	 public function CallDataForMat($items) { 
        
        return $items;
    }
  
}