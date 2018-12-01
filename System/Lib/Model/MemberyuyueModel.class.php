<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class MemberyuyueModel extends CommonModel{
    protected $pk   = 'yuyue_id';
    protected $tableName =  'member_yuyue';


	public function  tongjiComm($map){
		$where = " 1=1";
		
		if($bg_time = $map['b_time']){
			$where.= " AND dateline>={$map['b_time']}";
		}
		if($end_time = $map['e_time']){
			$where.= " AND dateline<={$map['e_time']}";
		}
        $data = $this->query("select yuyue_uid,dateline FROM  ".$this->getTableName()." where".$where);
        $showdata = '';
		$result = array();
		$type_list = D('Member')->_from_list();
		foreach($data as $k => $v){
			$t='';
			$t = D('Member')->find($v['yuyue_uid']);
			$data[$k]['from'] = $t['from'];
		}

		
		foreach($type_list as $k => $v){
			$a[$k] = 0;
			foreach($data as $kk => $vv){
				if($vv['from'] == $k){
					$a[$k]++;
					$result[$v] = $a[$k];
				}
			}
		}
		foreach($result as $k => $v){
			$showdata['tongji'].= '[\'预约'.$k.'\','.$v.'],';
			$showdata['shuju'][$k] = $v;
		}
		$showdata['shuju']['all'] = count($data);
		$showdata['tongji'] = substr($showdata['tongji'],0,-1);
		

       return $showdata;
    }

	public function check_yuyue_count($data)
    {
        $config = D('Setting')->fetchAll();
        if($yuyue_count = (int)$config['power']['fabu_yuyue']){
			$map['uid'] = $data['uid'];
			$map['dateline'] = array('gt',$data['dateline']-86400);
            if($yuyue_count < $this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一用户24小时只能预约'.$yuyue_count.'次');
                return $error;
            }
        }
        if($yuyue_time = (int)$config['power']['yuyue_jiange']){
            $time = $data['dateline'] - $yuyue_time*60;
			$map['uid'] = $data['uid'];
			$map['dateline'] = array('gt',$time);
            if($this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一用户两个预约的间隔'.$yuyue_time.'分钟');
                return $error;
            }
        }
        return true;
    }
  
}