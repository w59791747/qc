<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class BlockitemModel extends CommonModel{
    protected $pk   = 'item_id';
    protected $tableName =  'block_item';
	

	public function getfrom($row,$detail){
		$from = D($detail['from']);
		$arr = $from->find($row['itemId']);
		
		if($detail['from'] == 'Member'){
			$arr['title'] = $arr['name'];
			$id = 'uid';
			$con = $arr['uid'];
		}elseif($detail['from'] == 'Project'){
			if($arr['type'] == 'crowd'){
				$ctl = 'crowd';
			}
			$id = 'project_id';
			$con = $arr['project_id'];
		}elseif($detail['from'] == 'Activity'){
			$id = 'activity_id';
			$con = $arr['activity_id'];
		}elseif($detail['from'] == 'Hatch'){
			$id = 'hatch_id';
			$con = $arr['hatch_id'];
		}elseif($detail['from'] == 'Article'){
			$id = 'article_id';
			$con = $arr['article_id'];
		}
		if(!$ctl){
			$ctl = 'index';
		}
		$arrs['title'] = $arr['title'];
		$arrs['link'] =  U($detail['from'].'/'.$ctl,array($id=>$con));
		return $arrs;

	}
	public function _format_row($row){
		if($row['stime']){
			$row['stime'] = date('Y-m-d',$row['stime']);
		}
		if($row['ltime']){
			$row['ltime'] = date('Y-m-d',$row['ltime']);
		}
		return $row;
	}

	public function CallDataForMat($items,$block_id,$from,$limit)
	{

		$l = explode(',',$limit);
		$data = array();
		foreach($items as $k => $v){
			
			$d[] = $v['itemId'];
			$t[] = $v['target'];
		}
		$block  = D('Block')->find($block_id);
		if($block['from'] == 'Member'){
			$data['closed']= 0;$data['uid'] = array('IN',implode(',',$d));
		}elseif($block['from'] == 'Project'){
			$data['status']= 1;$data['closed']= 0;$data['project_id'] = array('IN',implode(',',$d));
		}elseif($block['from'] == 'Hatch'){
			$data['audit']= 1;$data['hatch_id'] = array('IN',implode(',',$d));
		}elseif($block['from'] == 'Article'){
			$data['audit']= 1;$data['closed']= 0;$data['article_id'] = array('IN',implode(',',$d));
		}elseif($block['from'] == 'Activity'){
			$data['audit']= 1;$data['activity_id'] = array('IN',implode(',',$d));
		}
		$result = D($block['from'])->where($data)->limit('0,'.$l[1])->select();
		
		foreach($result as $k =>$v){
			if($block['from'] == 'Member'){
				$result[$k]['id'] = $v['uid'];
			}elseif($block['from'] == 'Project'){
				$result[$k]['id'] = $v['project_id'];
			}elseif($block['from'] == 'Hatch'){
				$result[$k]['id'] = $v['hatch_id'];
			}elseif($block['from'] == 'Article'){
				$result[$k]['id'] = $v['article_id'];
			}elseif($block['from'] == 'Activity'){
				$result[$k]['id'] = $v['activity_id'];
			}
		}

		foreach($d as $kk =>$vv){
			foreach($result as $k =>$v){
				if($v['id'] == $vv){
					$last[$kk] = $v;
					$last[$kk]['target'] = $t[$kk];
				}
			}
		}

		
		if($result){
			$count = count($result);
		}else{
			$count = 0 ;
		}
		if($block['type'] !='no' ){
			if($count<$l[1]){
				if($block['type'] == 'orderby'){
					$order['orderby'] = 'asc';
				}
				if($block['type'] == 'new'){
					$order['dateline'] = 'desc';
				}
				if($block['type'] == 'hot'){
					$order['views'] = 'desc';
				}
				if($block['from'] == 'Member'){
					$f['closed']= array('IN','0,1');
					if($d){
						$f['uid'] = array('NOT IN',implode(',',$d));
					}
					
					$f['from'] = $from;
					
				}elseif($block['from'] == 'Project'){
					$f['status']= 1;$f['closed']= 0;$f['audit']= 1;
					if($d){
						$f['project_id'] = array('NOT IN',implode(',',$d));
						}
					if(is_numeric($from)){
						$f['status'] = array('EGT',$from);
					}else if($from){
						$f['type'] = $from;
					}
				}elseif($block['from'] == 'Activity'){
					$f['audit']= 1;
					if($d){
					$f['activity_id'] = array('NOT IN',implode(',',$d));
					}
				}
				$itemsss = D($block['from'])->where($f)->order($order)->limit('0,'.($l[1]-$count))->select();
				if(!$result){
					$results = $itemsss ;
				}else{
					$results = array_merge_recursive($last,$itemsss) ;
				}
			}else{
				$results = $last;

			}

		}else{
			$results = $last;
		}
				

		if (is_mobile()) {
			$ctl_f = 'mobile';
        }else{
			$ctl_f = 'home';
		}


		$config = D('Setting')->fetchAll();
		foreach($results as $k =>$v){

			if($block['from'] == 'Member'){
				$results[$k]['id'] = $v['uid'];
				$results[$k]['title'] = $v['name'];
				if($v['from'] == 'member'){
					$results[$k]['img'] = !empty($v['face']) ? $v['face'] : $config['attachs']['member_face'];
					$results[$k]['url'] = U($ctl_f.'/user/detail',array('uid'=>$v['uid']));
				}else if($v['from'] == 'partners'){
					$results[$k]['img'] = !empty($v['face']) ? $v['face'] : $config['attachs']['partners_face'];
					$results[$k]['url'] = U($ctl_f.'/partners/detail',array('uid'=>$v['uid']));
				}else if($v['from'] == 'investors'){
					$results[$k]['img'] = !empty($v['face']) ? $v['face'] : $config['attachs']['investors_face'];
					$results[$k]['url'] = U($ctl_f.'/investors/detail',array('uid'=>$v['uid']));
				}else if($v['from'] == 'hatch'){
					$results[$k]['img'] = !empty($v['face']) ? $v['face'] : $config['attachs']['hatch_face'];
				}
				$results[$k]['detail'] = D('Memberdetail')->find($v['uid']);
			}elseif($block['from'] == 'Project'){
				$results[$k]['id'] = $v['project_id'];

				if($v['type'] == 'partners'){
					$results[$k]['img'] = !empty($v['project_photo']) ? $v['project_photo'] : $config['attachs']['project_img'];
				}else if($v['type'] == 'investors'){
					$results[$k]['img'] = !empty($v['project_photo']) ? $v['project_photo'] : $config['attachs']['project_img'];
				}else if($v['type'] == 'crowd'){
					$results[$k]['img'] = !empty($v['project_photo']) ? $v['project_photo'] : $config['attachs']['crowd_img'];
				}
				$results[$k]['url'] = U($ctl_f.'/project/detail',array('project_id'=>$v['project_id']));
			}elseif($block['from'] == 'Hatch'){
				$results[$k]['id'] = $v['hatch_id'];
				$results[$k]['img'] = !empty($v['thumb']) ? $v['thumb'] : $config['attachs']['hatch_img'];
				$results[$k]['url'] = U($ctl_f.'/hatch/detail',array('hatch_id'=>$v['hatch_id']));
			}elseif($block['from'] == 'Article'){
				$results[$k]['id'] = $v['article_id'];
				$results[$k]['img'] = $v['thumb'];
				if($v['link']){
					$results[$k]['url'] = $v['link'];
				}else{
					$results[$k]['url'] = U($ctl_f.'/article/detail',array('article_id'=>$v['article_id']));
				}
			}elseif($block['from'] == 'Activity'){
				$results[$k]['id'] = $v['activity_id'];
				$results[$k]['img'] = $v['thumb'];
				$results[$k]['url'] = U($ctl_f.'/activity/detail',array('activity_id'=>$v['activity_id']));
			}
		}

		return $results;
	}
}