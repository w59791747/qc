<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class HelpAction extends CommonAction {

    public function index($cate_id=0) {
        $Article = D('Article');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('audit' => 1,'closed'=>0,'from'=>'help');
        if ($cate_id) {
            $map['cate_id'] = $cate_id;
        }

		$maps=array('hidden'=>'0','from'=>'help');
        $this->assign('cates', D('Articlecate')->where($maps)->order(array('orderby'=>'asc'))->select());
		$cates2 = D('Articlecate')->fetchAll();
		$this->assign('cates2', $cates2);

		

        $count = $Article->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 6); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Article->where($map)->order($orderby)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

		$this->assign('cate_id', $cate_id); 
		$this->assign('p_id', $cates2[$cate_id]['parent_id']); 

        $this->display(); // 输出模板
    }


	public function tips($tips_id=0) {
        $Article = D('Article');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('audit' => 1,'closed'=>0,'from'=>'help');
        if ($tips_id) {
            $map['tips'] = array('LIKE','%'.$tips_id.'%');
        }

		$this->assign('tips', D('Articletips')->fetchAll());


        $count = $Article->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 6); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Article->where($map)->order($orderby)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('tips_id', $tips_id); 
        $this->display(); // 输出模板
    }

	

    public function detail($article_id = 0) {

        if ($article_id = (int) $article_id) {
            $obj = D('Article');
            if (!$detail = $obj->find($article_id)) {
                $this->error('没有该文章');
            }
            $cates = D('Articlecate')->fetchAll();
            $obj->updateCount($article_id, 'views');
            $detail['details'] = htmlspecialchars_decode($detail['details']);
            $this->assign('detail', $detail);

			//上一个 下一个
			$m['closed'] = 0;$m['audit'] = 1;$m['from'] = 'help';
			$m['article_id'] = array('lt',$article_id);
			$up = $obj->where($m)->limit('1')->order('article_id desc')->find();
			
			$m2['closed'] = 0;$m2['audit'] = 1;$m2['from'] = 'help';
			$m2['article_id'] = array('gt',$article_id);
			$down = $obj->where($m2)->limit('1')->order('article_id asc')->find();

			$this->assign('up', $up);	
			$this->assign('down', $down);

			
            $this->assign('parent_id', D('Articlecate')->getParentsId($detail['cate_id']));
            $this->assign('cates', $cates);
            $this->assign('cate',$cates[$detail['cate_id']]);
            $this->seodatas['title'] = $detail['title'];
            $this->seodatas['keywords'] = $detail['keywords'];
            $this->seodatas['desc'] = $detail['desc'];
			$this->assign('type', D('Project')->type_list());

			$maps=array('hidden'=>'0','from'=>'help');
			$this->assign('cates', D('Articlecate')->where($maps)->order(array('orderby'=>'asc'))->select());
			$cates2 = D('Articlecate')->fetchAll();
			$this->assign('cates2', $cates2);
			$this->assign('tips', D('Articletips')->where(array('from'=>'help'))->select());
            $this->display();
        } else {
            $this->error('没有该文章');
        }
    }

	public function comments($article_id=0)
	{
		if(!$this->member){
			$this->chuangSuccess('请先登录再评论',U('passport/login'));
		}else{
			if ($article_id = (int) $article_id) {
				$obj = D('Article');$Articlecomment = D('Articlecomment');
				if (!$detail = $obj->find($article_id)) {
					$this->chuangError('没有该文章');
				}else{
					$is_comment = $Articlecomment->where(array('uid'=>$this->uid,'article_id'=>$article_id))->find();
					if($is_comment){
						$this->chuangError('您已经评论过了');
					}else{
						$data = $this->_post('data', 'htmlspecialchars');
						$data['dateline'] = NOW_TIME;
						$data['article_id'] = $article_id;
						$data['content'] = $data['content'];
						$data['score'] = $this->_post('rating', false);
						$data['uid'] = $this->uid;
						$power = $this->_CONFIG['power'];
						if($power['audit_comments'] != 'on'){
							$data['audit'] = 1;
						}else{
							$data['audit'] = 0;
						}
						if($comment_id = $Articlecomment->add($data)){
						   $obj->cleanCache();
						   $obj->updateCount($article_id,'comments',1);
						   $obj->updateCount($article_id,'score',$data['score']);
						   $this->chuangSuccess('评论成功',$_SERVER['HTTP_REFERER']);
						   
						}
					}
				}
			}
		}
	}
    
}