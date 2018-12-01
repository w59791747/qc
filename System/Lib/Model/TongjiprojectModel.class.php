<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class TongjiprojectModel extends CommonModel{
    protected $pk   = 'project_id';
    protected $tableName =  'project';

	 public function  tongjiComm($map){
		$where = " 1=1";
		if($map['type']){
			$where.= " AND `type`='{$map['type']}'";
		}
		if($bg_time = $map['b_time']){
			$where.= " AND dateline>={$map['b_time']}";
		}
		if($end_time = $map['e_time']){
			$where.= " AND dateline<={$map['e_time']}";
		}
        $data = $this->query("select project_id,dateline FROM  ".$this->getTableName()." where".$where);

		$where2 = " 1=1";
		if($map['type']){
			$where2.= " AND `type`='{$map['type']}'";
		}

		if($d_bg_time = $map['d_b_time']){
			$where2.= " AND dateline>={$map['d_b_time']}";
		}
		if($d_end_time = $map['d_e_time']){
			$where2.= " AND dateline<={$map['d_e_time']}";
		}
		$data2 = $this->query("select project_id,dateline FROM  ".$this->getTableName()." where".$where2);


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
        for($i=$map['b_time'];$i<=$map['e_time'];$i+=$t){
			if($num<=2){
				$days[] = '\''.date('d日H时',$i).'\'';
			}else{
				$days[] = '\''.date('m月d日',$i).'\'';
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
							$count[date('dH',$i)] = 0;
						}else{
							$count[date('md',$i)] = 0;
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

		 for($i=$map['d_b_time'];$i<=$map['d_e_time'];$i+=$t){
			if($num<=2){
				$days2[] = '\''.date('d日H时',$i).'\'';
			}else{
				$days2[] = '\''.date('m月d日',$i).'\'';
			}
			$j=0;
			if($data2){
				foreach($data2 as $k =>$v){
					if($v['dateline']>=$i && $v['dateline']<=$i+$t){
						$j++;
						if($num<=2){
							$count2[date('dH',$i)] = $j;
						}else{
							$count2[date('md',$i)] = $j;
						}
					}else{
						if($num<=2){
							if($count2[date('dH',$i)] == ''){
								$count2[date('dH',$i)] = 0;
							}
						}else{
							if($count2[date('md',$i)] == ''){
								$count2[date('md',$i)] = 0;
							}
						}

					}
				}
			}else{
				if($num<=2){
					$count2[date('dH',$i)] = 0;
				}else{
					$count2[date('md',$i)] = 0;
				}
			}
			
        }

	   $days_str = implode(',',$days);
       $count_str = implode(',',$count);
	   $count2_str = implode(',',$count2);
		foreach($days as $k =>$v){
			$days3[$v] = $days2[$k];
		}

	   $showdata['x'] = $days_str;
	   $showdata['x1'] = $days3;
	   $showdata['y1'] = $count_str;
	   $showdata['y2'] = $count2_str;
       return $showdata;
    }



	 public function  tongjiindex($map){
		$type_list = D('Project')->type_list();
		foreach($type_list as $k =>$v){
			$where = " closed=0";
			if($bg_time = $map['b_time']){
				$where.= " AND dateline>={$map['b_time']}";
			}
			if($end_time = $map['e_time']){
				$where.= " AND dateline<={$map['e_time']}";
			}
			$datas[$k] = $this->query("select project_id,dateline FROM  ".$this->getTableName()." where".$where);
		}
        
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
        for($i=$map['b_time'];$i<=$map['e_time'];$i+=$t){
			if($num<=2){
				$days[date('dH',$i)] = '\''.date('d日H时',$i).'\'';
			}else{
				$days[date('md',$i)] = '\''.date('m月d日',$i).'\'';
			}
			$j=0;
			if($datas){
				foreach($datas as $kk => $data){
					if($data){
						$j=0;
						foreach($data as $k =>$v){
							if($v['dateline']>=$i && $v['dateline']<=$i+$t){
								$j++;
								if($num<=2){
									$count[$kk][date('dH',$i)] = $j;
								}else{
									$count[$kk][date('md',$i)] = $j;
								}
							}else{
								if($num<=2){
									$count[$kk][date('dH',$i)] = 0;
								}else{
									$count[$kk][date('md',$i)] = 0;
								}

							}
						}

					}else{
						if($num<=2){
							$count[$kk][date('dH',$i)] = 0;
						}else{
							$count[$kk][date('md',$i)] = 0;
						}
					}

				}
			}
			
        }
	   $days_str = implode(',',$days);
	   foreach($count as $k => $v){
			 $count_str[$k] = implode(',',$v);
			 foreach($v as $kk => $vv){
				$tongji[$k] += $vv;
			 }
	   }
	   $showdata['x'] = $days_str;
	   $showdata['y'] = $count_str;
	   $showdata['tongji'] = $tongji;
       return $showdata;
    }
}