<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class TongjimemberModel extends CommonModel{
    protected $pk   = 'uid';
    protected $tableName =  'member';

	 public function  tongjiComm($map){
		$where = " 1=1";
	
		if($bg_time = $map['b_time']){
			$where.= " AND dateline>={$map['b_time']}";
		}
		if($end_time = $map['e_time']){
			$where.= " AND dateline<={$map['e_time']}";
		}
        $data = $this->query("select uid,dateline,`from` FROM  ".$this->getTableName()." where".$where);
        $showdata = array();
        $days = $count = array();

		$num = ceil(($map['e_time'] - $map['b_time'])/86400);
		if($num>10 && $num<=20){
			$t = '86400'*2;
		}else if($num>20){
			$t = '86400'*ceil($num/10);
		}else if($num<=2){
			$t = '86400'*$num/8;
		}else{
			$t = '86400';
		}
		$from_list = D('Member')->_from_list();
		
        for($i=$map['b_time'];$i<=$map['e_time'];$i+=$t){
			if($num<=2){
				$days[date('dH',$i)] = '\''.date('d日H时',$i).'\'';
			}else{
				$days[date('md',$i)] = '\''.date('m月d日',$i).'\'';
			}
			$j=0;
			
			if($data){
				foreach($data as $k =>$v){
					if($v['dateline']>=$i && $v['dateline']<=$i+$t){
						$j++;
						if($num<=2){
							$count[date('dH',$i)] = $j;
						}else{
							$count[date('md',$i)] = $j;
						}
						
					}else{
						if($num<=2){
							if($count[date('dH',$i)] == ''){
								$count[date('dH',$i)] = 0;
							}
						}else{
							if($count[date('md',$i)] == ''){
								$count[date('md',$i)] = 0;
							}
						}

					}
				}
			}else{
				if($num<=2){
					$count[date('dH',$i)] = 0;
				}else{
					$count[date('md',$i)] = 0;
				}
			}
			
        }
		foreach($from_list as $key =>$val){
			$a[$key] = 0;
			foreach($data as $k =>$v){
				if($v['from'] == $key){
					$a[$key]++;
					$from_lists[$key] = $a[$key];
				}
			}
		}

	   $from_lists['all'] = count($data);
	   $days_str = implode(',',$days);
       $count_str = implode(',',$count);
	   $showdata['x'] = $days_str;
	   $showdata['y'] = $count_str;
	   $showdata['from_lists'] = $from_lists;
       return $showdata;
    }
}