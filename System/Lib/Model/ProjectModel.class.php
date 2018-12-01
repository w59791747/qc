<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class ProjectModel extends CommonModel{
    protected $pk   = 'project_id';
    protected $tableName =  'project';

	protected $_type_list = array('crowd'=>'众筹项目');

	protected $status = array('0'=>'预热中','1'=>'众筹中','2'=>'出售中','3'=>'投票中','4'=>'待回款','5'=>'已完成','-1'=>'流标','-2'=>'回购','-3'=>'需要回购');

	protected $status2 = array('0'=>'预热中','1'=>'众筹中','2'=>'出售中','3'=>'投票中','4'=>'待回款','5'=>'已完成','-2'=>'溢价回购','-1'=>'流标');
	
	protected $status3 = array('0'=>'预热中','1'=>'众筹中','2'=>'出售中','3'=>'投票中','4'=>'待回款','5'=>'已完成','-2'=>'溢价回购');
	protected $edu = array('1'=>'5万以下','2'=>'5万-10万','3'=>'10万-20万','4'=>'20万-30万','5'=>'30万-50万','6'=>'50万以上');
	


    public function create_fields()
    {
        return $this->create_fields;
    }
	
	public function edit_fields()
    {
        return $this->edit_fields;
    }

	public function edu()
    {
        return $this->edu;
    }

	 public function type_list()
    {
        return $this->_type_list;
    }

	public function huibao()
    {
        return $this->huibao;
    }

	public function status()
    {
        return $this->status;
    }
	public function status2()
    {
        return $this->status2;
    }
	
	public function status3()
    {
        return $this->status3;
    }

	

	public function change_status()
	{

		//预热自动转到众筹中
		$data['closed']  = 0; 
		$data['audit']  = 1; 
		$data['status'] = 0;
		$data['stime']  = array('lt',time());

		$project = $this->where($data)->select();
		foreach($project as $k => $v){
			$project[$v['project_id']] = $v;
			$m[] = $v['project_id'];
		}	

		if($m){
			if(count($m)>=2){
				$map['project'] =  array('IN',implode(',',$m));
				$map['project_id'] =  array('IN',implode(',',$m));
			}else{
				$map['project'] =  $m[0];
				$map['project_id'] =  $m[0];

			}
			$lists = D('Projectfollow')->where($map)->select();
			foreach($lists as $k => $v){
				$member = D('Member')->find($v['uid']);
				D('Sms')->sendSms('sms_startcrowd', $member['mobile'], array('title'=>$project[$v['project']]['title']));
			}
			$this->xuni($map);
			$this->where($map)->save(array('status'=>'1'));
		}



		//众筹失败


		$data1['closed']  = 0; 
		$data1['audit']  = 1; 
		$data1['status'] = array('IN','0,1');
		$data1['ltime']  = array('lt',time());
		$project2 = $this->where($data1)->select();
		foreach($project2 as $k => $v){
			$m1[] = $v['project_id'];
		}
		if($m1){
			if(count($m1)>=2){
				$map1['project_id'] =  array('IN',implode(',',$m1));
			}else{
				$map1['project_id'] =  $m1[0];

			}
			$map1['is_pay']=1;
			$lists = D('Goodslist')->where($map1)->select();
			foreach($lists as $k => $v){
				D('Member')->updateCount($v['uid'],'price',$v['price']);
				D('Memberlog')->add_log($v['uid'],'price',$v['price'],'众筹失败,筹金返还',1);
				D('Paymentlogs')->where(array('product_id'=>$v['list_id']))->save(array('status'=>'-2'));

				$member = D('Member')->find($v['uid']);
				D('Sms')->sendSms('sms_falsecrowd', $member['mobile'], array('title'=>$project[$v['project_id']]['title']));
			}

			$this->where($map1)->save(array('status'=>'-1'));
		}

		// 投票结束  转出售或者转待回款
		
		
		$data2['closed']  = 0; 
		$data2['audit']  = 1; 
		$data2['status'] = 3;
		$data2['toultime']  = array('lt',time());
		$project3 = $this->where($data2)->select();
		foreach($project3 as $k => $v){
			$m2[] = $v['project_id'];
		}
		if($m2){
			if(count($m2)>=2){
				$map2['project_id'] =  array('IN',implode(',',$m2));
			}else{
				$map2['project_id'] =  $m1[0];

			}
			$map2['is_pay']=1;
			$map2['status']=0;
			$lists = D('Goodslist')->where($map2)->save(array('status'=>'1'));
			
		}

		foreach($project3 as $k => $v){
			$map3=array();$count=0;$num=0;$tpbfb=0;
			D('Goodslist')->where(array('project_id'=>$v['project_id'],'status'=>'0'))->save(array('status'=>'1'));
			$map3['project_id'] =  $v['project_id'];
			$map3['is_pay']=1;
			$tou_out = D('Goodslist')->where($map3)->select();
			$count = count($tou_out);
			foreach($tou_out as $k =>$v){
				if($v['status'] == '1'){
					$num++;
				}
			}
			$tpbfb = $num/$count*100;
			if($tpbfb > $v['tpbfb']){
				$this->where(array('project_id'=>$v['project_id']))->save(array('status'=>'4'));
			}else{
				$lists = D('Goodslist')->where(array('project_id'=>$v['project_id']))->save(array('status'=>'0'));
				$this->where(array('project_id'=>$v['project_id']))->save(array('status'=>'2'));
			}

		}


		//回购

		$data3['closed']  = 0; 
		$data3['audit']  = 1; 
		$data3['status'] = array('IN','2,3,4');
		$data3['crowdltime']  = array('lt',time());
		$project3 = $this->where($data3)->save(array('status'=>'-3'));
		

	}
	
	
	public function xuni($map)
	{
		$obj = D('Project');$Goodslist = D('Goodslist');
		$map['xuni_num'] = array('gt','0');
		$map['xuni_price'] = array('gt','0');
		$projectlist = $obj->where($map)->select();
		$member = D('Member')->where(array('xuni'=>'1'))->select();
		foreach($projectlist as $k => $project){
			$count = count($member);
			$price = 0;
			$arr_num = array();
			for($i=1;$i<=$project['xuni_num'];$i++){
				$arr_num[$i] = '0';
			}
			for($j=1;$j<=$project['xuni_price']/10000;$j++){
				$rand = rand(1,rand(1,$project['xuni_num']));
				$arr_num[$rand] += 100;
			}
			
			foreach($arr_num as $k => $v){
				if($v == 0){
					$arr_num[$k] = 100;
					$arr_num[1] = $arr_num[1]-100;
				}
			}
			shuffle($arr_num);
			for($i=1;$i<=$project['xuni_num'];$i++){
				$data = '';
				$rand = rand(0,$count-1);
				$data['name'] = $member[$rand]['name'];
				$data['mobile'] = $member[$rand]['mobile'];
				$data['price'] = $arr_num[$i-1]*100;
				$data['is_xuni'] = 1;
				$data['is_pay'] = 1;
				$data['project_id'] = $project['project_id'];
				$data['uid'] =$member[$rand]['uid'];
				$data['dateline'] = NOW_TIME;
				$list_id = $Goodslist->add($data);
				D('Member')->updateCount($member[$rand]['uid'], 'tou_price',$data['price']);
			}
			
			$obj->updateCount($project['project_id'], 'have_num',$project['xuni_num']);
			$obj->updateCount($project['project_id'], 'have_price',$project['xuni_price']);
		}
	}

	public function detail($project_id)
	{
		$detail = $this->find($project_id);
		return $detail;
	}

	

	public function check_project_count($data)
    {
        $config = D('Setting')->fetchAll();
        if($project_count = (int)$config['power']['fabu_project']){
			$map['uid'] = $data['uid'];
			$map['dateline'] = array('gt',$data['dateline']-86400);
            if($project_count < $this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一用户24小时只能发布'.$project_count.'项目');
                return $error;
            }
        }
        if($project_time = (int)$config['power']['project_jiange']){
            $time = $data['dateline'] - $project_time*60;
			$map['uid'] = $data['uid'];
			$map['dateline'] = array('gt',$time);
            if($this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一用户两个项目的间隔'.$project_time.'分钟');
                return $error;
            }
        }
        return true;
    }

	public function  shouyi($list,$detail)
	{

		if($detail['status'] == '-2' || $detail['status'] == '-3'){
			foreach($list as $k =>$v)
			{
				$list[$k]['shouyi'] = round(($v['price']/$detail['fund_raising']*$detail['huigou']/100-$v['price']/100),2);

			}
		}else{
			$Projectchushou = D('Projectchushou');
			$chushou = $Projectchushou->where(array('project_id'=>$detail['project_id'],'is_chenggong'=>'1'))->find();

			foreach($list as $k =>$v)
			{
				$list[$k]['shouyi'] = round(($v['price']/$detail['fund_raising']*$chushou['amount']/100-$v['price']/100)*$detail['fenhong']/100,2);
			}
		}
		return $list;
		
	}



	
	public function _format_row($v){
		$config = D('Setting')->fetchAll();
		$v['project_photo'] = !empty($v['project_photo']) ? $v['project_photo'] : $config['attachs']['crowd_img'];
		return $v;
	}

	public function CallDataForMat($items)
	{
		$Goodslist = D('Goodslist');
		foreach($items as $k => $v){
			$items[$k]['fund_raising'] = $v['fund_raising']/100;
			$items[$k]['have_price'] = $v['have_price']/100;
			$items[$k]['shouyi']['days']  =  floor(($v['etime']-$v['stime'])/3600/24);
			if($items[$k]['shouyi']['days'] == 0){
				$items[$k]['shouyi']['days'] = 1;
			}
			$lists = $Goodslist->where(array('project_id'=>$v['project_id'],'is_pay'=>1))->order(array('shouyi'=>'desc'))->find();
			$items[$k]['shouyi']['shouyi']  =  round((($lists['shouyi'])-$lists['price'])/$lists['price']*10000,0);
		}

		
		return $items;
	}
}