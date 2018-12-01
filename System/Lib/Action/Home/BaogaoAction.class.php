<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class BaogaoAction extends CommonAction {

    public function index($cate_id=0) {
       
		//交易额 回款额
		$heng = array();
		$num = 0;
		for($i=12;$i>0;$i--){
			$num++;
			$heng[$num] = date('m-d',time()-$i*3600*24*7);
			$up[$num] = time()-$i*3600*24*7;
		}
		$where['dateline']=array(array('gt', $up[1]));
		$where['shouyitime']=array(array('gt', $up[1]));
		$where['_logic'] = 'or';
		$map['_complex'] = $where;

		
		$map['is_pay']=1;
		$lists = D('Goodslist')->where($map)->select();
		$chushou = array('1'=>'0','2'=>'0','3'=>'0','4'=>'0','5'=>'0','6'=>'0','7'=>'0','8'=>'0','9'=>'0','10'=>'0','11'=>'0','12'=>'0');
		$shouyi = array('1'=>'0','2'=>'0','3'=>'0','4'=>'0','5'=>'0','6'=>'0','7'=>'0','8'=>'0','9'=>'0','10'=>'0','11'=>'0','12'=>'0');
		foreach($lists as $k => $v){
			foreach($up as $kk => $vv){
				if($v['dateline']>$up[$kk] && $v['dateline']<$up[$kk+1]){
					$chushou[$kk] += ceil($v['price']/100);
				}
				if($v['shouyitime']>$up[$kk] && $v['shouyitime']<$up[$kk+1]){
					$shouyi[$kk] += ceil($v['shouyi']/100);
				}
			}
		}
		if(max($chushou)>max($shouyi)){
			$result = max($chushou);
		}else{
			$result = max($shouyi);
		}
		$len = strlen($result);
		$int =1;
		for($i=1;$i<$len;$i++){
			$int.='0';
		}
		$chushou1 = array('1'=>'12周','2'=>'11周','3'=>'10周','4'=>'9周','5'=>'8周','6'=>'7周','7'=>'6周','8'=>'5周','9'=>'4周','10'=>'3周','11'=>'2周','12'=>'1周');
		$this->assign('x1',implode(',',$chushou1));
		$this->assign('max',ceil($result/$int)*$int);
		$this->assign('x',implode(',',$heng));
		$this->assign('y1',implode(',',$chushou));
		$this->assign('y2',implode(',',$shouyi));



		//近30期回款周期和万元收益趋势图

		$Projectchushou = D('Projectchushou');$Project=D('Project');
		
		$where = " and b.is_chenggong =1";
		$orderby = "b.chushou_id desc";

		$lists_2  = $Project->query("select a.*,b.* from ".$Project->getTableName()." as a,".D('Projectchushou')->getTableName()." as b where a.project_id=b.project_id ".$where." ORDER BY ".$orderby." limit 0,30");
		$count = count($lists_2);
		for($i=0;$i<$count;$i++){
			$arr1[$i]='';
			$arr2[$i]='';
			$arr3[$i]='';
			$arr4[$i]='';
		}
		foreach($lists_2 as $k => $v){
			$arr1[$k] = "'第".$v['project_id']."期'";
			$arr2[$k] = "' '";
			$arr3[$k] = floor(($v['etime']-$v['stime'])/3600/24);
			$arr4[$k] = round((($v['amount'])-$v['fund_raising'])/$v['fund_raising']*10000,0);
		}
		//var_dump($arr3);var_dump($arr4);echo "File:", __FILE__, ',Line:',__LINE__;exit;
		$len2 = strlen(max($arr3));
		$int2 =1;
		for($i=1;$i<$len2;$i++){
			$int2.='0';
		}
		$this->assign('max2',ceil(max($arr3)/$int2)*$int2);
		$this->assign('arr1',implode(',',$arr1));
		$this->assign('arr2',implode(',',$arr2));
		$this->assign('arr3',implode(',',$arr3));
		$this->assign('arr4',implode(',',$arr4));


		//其他统计
		
		$items['count1'] = D('Project')->count();//众筹总项目数

		$arr = D('Project')->field('have_price,have_num')->where(array('type'=>'crowd'))->select();
		foreach($arr as $k => $v){
			$items['count2'] += $v['have_num'];//项目参与人数
			$items['count3'] += $v['have_price']/100;//众筹总金额数
		}
		$p = D('Project')->field('project_id,status')->select();
		$items['count4'] = $items['count5']= 0;
		foreach($p as $k => $v){
			if($v['status']<=1){
				$items['count4']++;
			}
			if($v['status'] == 5 || $v['status'] == -2){
				$items['count5']++;
			}
		}
		$this->assign('items',$items);
        $this->display(); // 输出模板
    }
    
}	