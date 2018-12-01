<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class MemberModel extends CommonModel{
    protected $pk   = 'uid';
    protected $tableName =  'member';

	protected $create_fields = array('name', 'passwd','from','mail','mobile','cate_id','gold','price','gender','city_id','realname','face','Y','M','D','verify_mail','verify_mobile','is_company','fenxiao_id','views','closed','city','xuni');
    protected $edit_fields = array('name', 'passwd','from','mail','mobile','cate_id','gold','price','gender','city_id','realname','face','Y','M','D','verify_mail','verify_mobile','is_company','fenxiao_id','views','closed','city','xuni');

	protected $_from_list = array('member'=>'发布者','crowd'=>'众筹者');

	protected $closed = array('0'=>'正常','1'=>'待审核',  '2'=>'锁定', '3'=>'删除');



    public function create_fields()
    {
        return $this->create_fields;
    }

	public function edit_fields()
    {
        return $this->edit_fields;
    }

	public function _from_list()
    {
		$config = D('Setting')->fetchAll();
		if($config['power']['hatch'] != 'on'){
			return $this->_from_list2;
		}else{
			return $this->_from_list;
		}
    }

	public function closed()
    {
        return $this->closed;
    }

	//经验

	public function detail($uid)
	{
		$detail = $this->find($uid);
		$obj = D('Memberdetail');
		$map = array('uid'=>$uid);

		$detail['detail'] = $obj->where($map)->find();
		return $detail;
	}

	public function parseWhere($where)
	{
		return $this->parseWhere($where);
	}
    
    
   
 
   
    public function CallDataForMat($items) { 
        if (empty($items))
            return array();
        $obj = D('Memberdetail');
       
        foreach ($items as $k => $val) {
			$map = array();
			$map = array('uid'=>$val['uid']);
            $val['detail'] = $obj->where($map)->find();
            $items1[$val['uid']] = $val;
        }
        return $items1;
    }

	 public function _format_row($row)
    {
        static $gender = array('man'=>'男','woman'=>'女');
		$config = D('Setting')->fetchAll();
        $row['format_regdate'] = date("Y-m-d",$row['dateline']);          
        $row['format_gender'] = $gender[$row['gender']] ? $gender[$row['gender']] : '保密';
		if($row['from'] == 'member'){
			 $row['face'] = !empty($row['face']) ? $row['face'] : $config['attachs']['member_face'];
		}
		if($row['from'] == 'partners'){
			 $row['face'] = !empty($row['face']) ? $row['face'] : $config['attachs']['partners_face'];
		}
		if($row['from'] == 'investors'){
			 $row['face'] = !empty($row['face']) ? $row['face'] : $config['attachs']['investors_face'];
		}
		if($row['from'] == 'hatch'){
			 $row['face'] = !empty($row['face']) ? $row['face'] : $config['attachs']['hatch_face'];
		}
		if($row['face'] == ''){
			$row['face'] = 'default.jpg';
		}
		
		$row['price'] = sprintf("%.2f", $row['price']/100);

		$obj = D('Memberdetail');
		$row['detail'] = $obj->find($row['uid']);
        return $row;
    }
  
}