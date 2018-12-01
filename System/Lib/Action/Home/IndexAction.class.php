<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class IndexAction extends CommonAction {
    
     public function _initialize() {
        parent::_initialize();
    }

    public function index() {
		
        if (is_mobile()) {
            header("Location:" . U('mobile/index/index'));
            die;
        }

		//统计
			$items['count1'] = D('Project')->count();
			$arr = D('Project')->field('have_price,have_num')->where(array('type'=>'crowd'))->select();
			foreach($arr as $k => $v){
				$items['count2'] += $v['have_num'];
				$items['count3'] += $v['have_price']/100;
			}
		$this->assign('count',$items);
		D('Project')->change_status();
		$this->assign('count',$items);
		$this->assign('catelist', D('Projectcate')->fetchAll());
		$this->assign('status_list',D('Project')->status());
		$this->assign('tips', D('Projecttips')->fetchAll());
		$this->assign('articlelist',D('Articlecate')->where(array('hidden'=>'0','parent_id'=>'144','from'=>'article'))->order(array('orderby'=>'asc'))->select());
		$map['closed'] = 0;$map['audit'] = 1;
		$orderby = array('dateline'=>'desc');
		$map['status'] = array('IN','0,1,2,3,4');

		//众筹
		$lists = D('Project')->where($map)->order($orderby)->limit('0,8')->select();
		$Projecttips = D('Projecttips')->fetchAll();

		foreach($lists as $k => $v){
			if($v['status'] == 0){
				$list1[] = $v;
			}
		}

		foreach($lists as $k => $v){
			if($v['status'] == 1){
				$list1[] = $v;
			}
		}

		foreach($lists as $k => $v){
			if($v['status'] == 2){
				$list1[] = $v;
			}
		}

		foreach($lists as $k => $v){
			if($v['status'] == 3){
				$list1[] = $v;
			}
		}


		foreach($lists as $k => $v){
			if($v['status'] == 4){
				$list1[] = $v;
			}
		}


		$this->assign('list1',$list1);$this->assign('Projecttips',$Projecttips);
        $this->display();
    }
    
    public function get_arr(){
        
         if(IS_AJAX){
            
            $cate_id = I('val',0,'intval,trim');
            
            $today = date('Y-m-d');

            $t = D('Tuan');
            $map = array(
                'cate_id'=>$cate_id,
                'city_id'=>$this->city_id,
                'closed'=>0,
                'audit'=>1,
                'bg_date' => array('elt',$today),
                'end_date'=>array('egt',$today)
                
            );
            $r = $t->where($map)->limit(8)->select();
            
            if($r){
                $this->ajaxReturn(array('status'=>'success','arr'=>$r));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
            
        }
        
    }
    

}

