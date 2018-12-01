<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CommonModel extends Model{
    protected $pk   = '';
    protected $tableName =  '';
    protected $token = '';
    protected $cacheTime = 86400;
    protected $orderby = array(); //针对全部查询出的数据的排序

    public function updateCount($id,$col,$num = 1){
        $id = (int)$id;
        
        return $this->execute(" update ".$this->getTableName()." set {$col} = ({$col} + '{$num}') where ".$this->pk." = '{$id}' ");
    }

	



	

	public function chuangSuccess($message, $jumpUrl = '', $time = 3000) {
        $str = '<script>';
        $str .='parent.success("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
        $str.='</script>';
        exit($str);
    }

	public function chuangError($message, $time = 3000, $yzm = false) {
        $str = '<script>';
        if ($yzm) {
            $str .='parent.error("' . $message . '",' . $time . ',"yzmCode()");';
        } else {
            $str .='parent.error("' . $message . '",' . $time . ');';
        }
        $str.='</script>';
        exit($str);
    }
    
    public  function  itemsByIds($ids = array()){
        if(empty($ids)) return array();
        $data = $this->where(array($this->pk=>array('IN',$ids)))->select();
        $return = array();
        foreach($data as $val){
            $return[$val[$this->pk]] = $val;
        }
        return $return;
    }
   
    public function fetchAll($field = '*', $where = array()){
        $cache = cache(array('type'=>'File','expire'=>  $this->cacheTime));
        if(!$data = $cache->get($this->token)){
			
            $result = $this->field($field);
            if ( ! empty($where))
            {
                $result = $result->where($where);
            }
            $result = $result->order($this->orderby)->select();
            $data = array();
            foreach($result  as $row){
                $data[$row[$this->pk]] = $this->_format($row);
            }
            $cache->set($this->token,$data);
        }   
        return $data;
     }

	
     public function cleanCache(){
         $cache = cache(array('type'=>'File','expire'=>$this->cacheTime));
         $cache->rm($this->token);
     }
    
    public  function _format($data){
        return $data;
    }
}