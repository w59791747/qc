<?php


/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class ArticleAction extends CommonAction {

    private $create_fields = array('cate_id' ,'title', 'thumb', 'linkurl', 'desc', 'hidden', 'orderby', 'audit', 'views','details');
    private $edit_fields = array('cate_id' ,'title', 'thumb', 'linkurl', 'desc', 'hidden', 'orderby', 'audit', 'views','details');

    public function index() {
        $Article = D('Article');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>'0');
        if ($title = $this->_param('title', 'htmlspecialchars')) {
            $map['title'] = array('LIKE', '%' . $title . '%');
            $this->assign('title', $title);
        }
        if ($cate_id = (int) $this->_param('cate_id')) {
            $map['cate_id'] = $cate_id;
            $this->assign('cate_id', $cate_id);
        }
				$map['from'] = 'article';

        $count = $Article->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Article->where($map)->order(array('article_id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$map['from'] = 'article';
		$c = D('Articlecate')->where($map)->order(array('orderby'=>'asc'))->select();
		foreach($c as $k => $v){
			$cates[$v['cate_id']] = $v;
		}
		$this->assign('cates', $cates);
		$this->display(); // 输出模板
    }

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Article');
            if ($obj->add($data)) {
                $this->chuangSuccess('添加成功', U('article/index'));
            }
            $this->chuangError('操作失败！');
        } else {
			$map['from'] = 'article';
            $this->assign('cates', D('Articlecate')->where($map)->order(array('orderby'=>'asc'))->select());
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
         $data['cate_id'] = (int) $data['cate_id'];
        if (empty($data['cate_id'])) {
            $this->chuangError('分类不能为空');
        } 
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }
        
        $data['source'] = htmlspecialchars($data['source']);
        $data['keywords'] = htmlspecialchars($data['keywords']);
        $data['desc'] = htmlspecialchars($data['desc']);
       
        $data['details'] = SecurityEditorHtml($data['details']);
        
       

		 if (empty($data['desc'])) {
            $this->chuangError('摘要不能为空');
        }
		 if (empty($data['details'])) {
            $this->chuangError('详细内容不能为空');
        }

        $data['dateline'] = NOW_TIME;
        return $data;
    }

    public function edit($article_id = 0) {
        if ($article_id = (int) $article_id) {
            $obj = D('Article');
            if (!$detail = $obj->find($article_id)) {
                $this->error('请选择要编辑的文章');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['article_id'] = $article_id;
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('修改成功', U('article/index'));
                }
                $this->chuangError('操作失败');
            } else {
				$detail['details']  = htmlspecialchars_decode($detail['details']);
                $this->assign('detail', $detail);
                $this->assign('parent_id',D('Articlecate')->getParentsId($detail['cate_id']));
				$map['from'] = 'article';
				$c = D('Articlecate')->where($map)->order(array('orderby'=>'asc'))->select();
				foreach($c as $k => $v){
					$cates[$v['cate_id']] = $v;
				}
				$this->assign('cates', $cates);				 
				 
				 $this->display();
            }
        } else {
            $this->error('请选择要编辑的文章');
        }
    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->chuangError('标题不能为空');
        }       
        $data['source'] = htmlspecialchars($data['source']);
        $data['keywords'] = htmlspecialchars($data['keywords']);
        $data['desc'] = htmlspecialchars($data['desc']);
       
        $data['details'] = SecurityEditorHtml($data['details']);
        if (empty($data['desc'])) {
            $this->chuangError('摘要不能为空');
        }
		 if (empty($data['details'])) {
            $this->chuangError('详细内容不能为空');
        }

        return $data;
    }

    public function delete($article_id = 0) {
        if (is_numeric($article_id) && ($article_id = (int) $article_id)) {
            $obj = D('Article');
			$data['article_id'] = $article_id;
			$data['closed'] = '1';
            $obj->save($data);
            $this->chuangSuccess('删除成功！', U('article/index'));
        } else {
            $article_id = $this->_post('article_id', false);
            if (is_array($article_id)) {
                $obj = D('Article');
                foreach ($article_id as $id) {
					$data = array();
					$data['article_id'] = $id;
					$data['closed'] = '1';
                    $obj->delete($id);
                }
                $this->chuangSuccess('删除成功！', U('article/index'));
            }
            $this->error('请选择要删除的文章');
        }
    }


	public function select($title='title'){
        $Article = D('Article');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('audit'=>1);
        if($title = $this->_param('title','htmlspecialchars')){
            $map['title'] = array('LIKE','%'.$title.'%');
            $this->assign('title',$title);
        }
		
        $count = $Article->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $pager = $Page->show(); // 分页显示输出
        $list = $Article->where($map)->order(array('orderby'=>'asc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $pager); // 赋值分页输出
		$this->assign('id', 'article_id'); // 赋值数据集
		$this->assign('title1', 'title'); // 赋值数据集
        $this->display(); // 输出模板
        
    }

}
