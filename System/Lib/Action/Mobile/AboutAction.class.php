<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class AboutAction extends CommonAction {

    public function detail($article_id = 0) {

		$Articlecate = D('Articlecate')->where(array('parent_id'=>111,'hidden'=>0))->order(array('orderby'=>'asc'))->select();
		foreach($Articlecate as $k => $v){ 
			$Articlecate[$k]['list'] = D('Article')->where(array('cate_id'=>$v['cate_id'],'closed'=>0,'audit'=>1))->find();
		}
		

        if ($article_id = (int) $article_id) {
            $obj = D('Article');
            if (!$detail = $obj->find($article_id)) {
                $this->error('没有该文章');
            }
            $cates = D('Articlecate')->fetchAll();
            $obj->updateCount($article_id, 'views');
            $detail['details'] = htmlspecialchars_decode($detail['details']);
            $this->assign('detail', $detail);
			$this->assign('article_id', $article_id);
            $this->seodatas['title'] = $detail['title'];
			$this->assign('Aboutcate', $Articlecate);
            $this->display();
        } else {
            $this->error('没有该文章');
        }
    }

	public function clause()
	{
		$this->display();
	}

	
    
}