<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：风云
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com
 */

class IndexAction extends CommonAction{
    
     public function index($menu_id=1){
		
		$this->assign('menu_id',$menu_id);
        $this->display();
     }

	public function menu($menu_id=1){
        $role_id = $this->_admin['role_id'];
		$menu = D('Menu')->child_menu($menu_id,$role_id);
		$this->assign('menu_id',$menu_id);
        $this->assign('menuList',$menu);
		$this->display();
	}

	 public function middle()
    {
         $this->display();
    }
	


	public function top()
    {
		$role_id = $this->_admin['role_id'];
		$menu = D('Menu')->top_menu($role_id);
        $this->assign('menuList',$menu);
        $this->display();
    }
     
     public function main()
	{
		$projectcount = D('Project')->count();
		$membercount = D('Member')->count();
		$yuyuecount = D('Memberyuyue')->count();
		$c1 = D('Membercomment')->count();
		$c2 = D('Projectcomment')->count();
		$c3 = D('Articlecomment')->count();
		$commentcount = $c1+$c2+$c3;
		$this->assign('count',array('projectcount'=>$projectcount,'membercount'=>$membercount,'yuyuecount'=>$yuyuecount,'commentcount'=>$commentcount));
		$this->assign('admin',$this->admin);


		$obj = D('Project');
		$map = array();
		$title = "最近七天项目统计图";
		$m=date("m");$d=date("d");$y=date("Y");$w=date("w");
		$t = date("t", strtotime($y.'-'.($m-1)));
		$s = mktime(0,0,0,$m,$d-6,$y);
		$e = time();
		$map['b_time'] = $s;
		$map['e_time'] = $e;
		$b_time = date('Y-m-d',$s);
		$e_time = 	date('Y-m-d',$e);
		$this->assign('tongji',D('Tongjiproject')->tongjiindex($map));
		$this->assign('type_list',D('Project')->type_list());
		$this->assign('title',$title);

	



		//用户  统计

		$obj2 = D('Member');
		$map2 = array('closed'=>'0');

		$title2 = "七天用户注册统计图";

		$map2['b_time'] = $s;
		$map2['e_time'] = $e;
		$from_list = D('Member')->_from_list();
		$this->assign('tongji2',D('Tongjimember')->tongjiComm($map2));
		$this->assign('title2',$title2);
		$this->assign('from_list',$from_list);


		//在线预约统计

		$map3 = array();
		$title3 = "七天在线预约统计图";
		$map3['b_time'] = $s;
		$map3['e_time'] = $e;
		$this->assign('tongji3',D('Memberyuyue')->tongjiComm($map3));
		$this->assign('title3',$title3);


		//评论

		$map4 = array();
		$title4 = "七天评论统计图";
		$map4['b_time'] = $s;
		$map4['e_time'] = $e;
		$this->assign('tongji4',D('Membercomment')->tongjiComm($map4));
		$this->assign('title4',$title4);

		//用户反馈

		$this->assign('feedback', D('Feedback')->where($map)->order('dateline desc')->limit('5')->select());



		//公告

		$cache = cache(array('type'=>'File','expire'=> '21600'));

		if(!$gonggao= $cache->get('gonggao')){
			$gonggao = file_get_contents("http://www.izhuchuang.com/index/gonggao.html");
			$cache->set('gonggao',$gonggao);
		}

		$this->assign('gonggao',unserialize($gonggao));
		

        $this->display();
     }
     
     public function check(){ //后期获得通知使用！
         die('1');
     }
     
}