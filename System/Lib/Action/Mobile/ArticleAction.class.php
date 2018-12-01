<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ArticleAction extends CommonAction {

    public function index($cate_id=141) {
        $Article = D('Article');$Articlecate = D('Articlecate');
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

        $this->assign('cate_id', $cate_id); // 赋值分页输出

		
		$map2 = array('audit' => 1,'closed'=>0);
        if ($cate_id) {
			$map2['cate_id'] = $cate_id;
		}
		$orderby2 = array('orderby'=>'asc','article_id'=>'desc');
		$count = $Article->where($map2)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 6); // 实例化分页类 传入总记录数和每页显示的记录数
		$list2 = $Article->where($map2)->order($orderby2)->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出
		$this->assign('list2', $list2);
		$this->assign('page',$show);
		if($cate_id == '146'){
			$this->display('index2');
		}else{
			$this->display();
		}
         // 输出模板
    }

    public function detail($article_id = 0) {
		$Articlecate = D('Articlecate');$Article = D('Article');
        if ($article_id = (int) $article_id) {
            $obj = D('Article');
            if (!$detail = $obj->find($article_id)) {
                $this->error('没有该文章');
            }
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
			$this->assign('cate_id', $detail['cate_id']); // 赋值分页输出
            $obj->updateCount($article_id, 'views');
            $detail['details'] = htmlspecialchars_decode($detail['details']);
            $this->assign('detail', $detail);
			//上一个 下一个
			$m['closed'] = 0;$m['audit'] = 1;
			$m['article_id'] = array('lt',$article_id);
			$up = $obj->where($m)->limit('1')->order('article_id desc')->find();
			
			$m2['closed'] = 0;$m2['audit'] = 1;
			$m2['article_id'] = array('gt',$article_id);
			$down = $obj->where($m2)->limit('1')->order('article_id asc')->find();

			$this->assign('up', $up);	
			$this->assign('down', $down);

			//相关文章
			$m3['closed'] = 0;$m3['audit'] = 1;$m3['article_id'] = array('NEQ',$article_id);
			$about = $obj->where($m3)->limit('9')->order('orderby asc')->select();
			$this->assign('about', $about);

            $this->seodatas['title'] = $detail['title'];
			$this->seodatas['cate'] = $cates[$detail['cate_id']]["title"];
			$this->assign('type', D('Project')->type_list());
            $this->display();
        } else {
            $this->error('没有该文章');
        }
    }
}