<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CheshangAction extends CommonAction {

    public function index($article_id = 0) {
		$Articlecate = D('Articlecate'); $Article= D('Article');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('hidden'=>'0','parent_id'=>array('neq','0'));
		$orderby = array('orderby'=>'asc');
        $list = $Articlecate->where($map)->order($orderby)->select();
		foreach($list as $k => $v){
			if($v['cate_id'] == '112' || $v['cate_id'] == '148'){
				$list3 = array();
				$map3 = array('audit' => 1,'closed'=>0);
				$map3['cate_id'] = $v['cate_id'];
				$orderby3 = array('orderby'=>'asc','article_id'=>'desc');
				$list3 = $Article->where($map3)->order($orderby3)->select();
				$list[$k]['child'] = $list3;

			}
		}
        $this->assign('list', $list); // 赋值数据集

		$Cheshang = D('Cheshang');
		$orderby2 = array('orderby'=>'asc');
		$count = $Cheshang->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 6); // 实例化分页类 传入总记录数和每页显示的记录数
		$list2 = $Cheshang->order($orderby2)->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出
		$this->assign('list2', $list2);
		$this->assign('page',$show);
		$this->display(); // 输出模板
    }
}