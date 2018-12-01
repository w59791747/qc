<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class IndexAction extends CommonAction {

    public function index() 
	{
		$stime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$ltime = time();
		$Membernew = D('Membernew');
		$Order = D('Order');
		$follow = D('Projectfollow');
		$map['dateline'] = array('between',array($stime,$ltime));
		$map['uid'] = $this->uid;
		$count1 = $Membernew->where($map)->count();
		$count2 = $Order->where($map)->count();
		$count3 = $follow->where($map)->count();
		$this->assign('count1',$count1);
		$this->assign('count2',$count2);
		$this->assign('count3',$count3);
		
        $this->display(); 
    }

   
}
