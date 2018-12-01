<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class FeedbackAction extends CommonAction {

    public function create()
	{	
		$data = $this->_post('data', 'htmlspecialchars');
		$obj = D('Feedback');
		$data = $this->createCheck();
		$data['uid'] = $this->uid;
		if($back_id = $obj->add($data)){
		   $obj->cleanCache();
		   $this->chuangSuccess('反馈成功',U('index/index'));
		}
		$this->chuangError('操作失败！');
	}

	private function createCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
		
		if (!isMobile($data['mobile'])) {
            $this->chuangError('电话不能为空');
        }

		if (empty($data['content'])) {
            $this->chuangError('内容不能为空');
        }
		$data['status'] = 0;
        $data['dateline'] = NOW_TIME;
		
        return $data;
    }
}