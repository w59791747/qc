<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class EmailAction extends CommonAction {

    private $create_fields = array('email_key', 'email_tmpl');
    private $edit_fields = array('email_key', 'email_tmpl');

    public function index() {
        $Email = D('Email');
        import('ORG.Util.Page'); // 导入分页类
        $map = array();
        $count = $Email->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Email->where($map)->order(array('email_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Email');
            if ($obj->add($data)) {
                $this->chuangSuccess('添加成功', U('email/index'));
            }
            $obj->cleanCache();
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        $data['email_key'] = htmlspecialchars($data['email_key']);
        if (empty($data['email_key'])) {
            $this->chuangError('标签不能为空');
        } $data['email_tmpl'] = SecurityEditorHtml($data['email_tmpl']);
        if (empty($data['email_tmpl'])) {
            $this->chuangError('模版内容不能为空');
        }
        return $data;
    }

    public function edit($email_id = 0) {
        if ($email_id = (int) $email_id) {
            $obj = D('Email');
            if (!$detail = $obj->find($email_id)) {
                $this->error('请选择要编辑的邮件模版');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['email_id'] = $email_id;
                if (false !== $obj->save($data)) {
                    $this->chuangSuccess('编辑成功', U('email/index'));
                }
                $obj->cleanCache();
                $this->chuangError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的邮件模版');
        }
    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_fields);
        $data['email_key'] = htmlspecialchars($data['email_key']);
        if (empty($data['email_key'])) {
            $this->chuangError('标签不能为空');
        }$data['email_tmpl'] = SecurityEditorHtml($data['email_tmpl']);
        if (empty($data['email_tmpl'])) {
            $this->chuangError('模版内容不能为空');
        }
        return $data;
    }

    public function delete($email_id = 0) {
        if (is_numeric($email_id) && ( $email_id = (int) $email_id)) {
            $obj = D('Email');
            $obj->save(array('email_id' => $email_id, 'is_open' => 0));
            $obj->cleanCache();
            $this->chuangSuccess('关闭成功！', U('email/index'));
        } else {
            $email_id = $this->_post('email_id', false);
            if (is_array($email_id)) {
                $obj = D('Email');
                foreach ($email_id as $id) {
                    $obj->save(array('email_id' => $id, 'is_open' => 0));
                }
                $obj->cleanCache();
                $this->chuangSuccess('关闭成功！', U('email/index'));
            }
            $this->error('请选择要关闭的邮件模版');
        }
    }

    public function audit($email_id = 0) {
        if (is_numeric($email_id) && ($email_id = (int) $email_id)) {
            $obj = D('Email');
            $obj->save(array('email_id' => $email_id, 'is_open' => 1));
            $obj->cleanCache();
            $this->chuangSuccess('开启成功！', U('email/index'));
        } else {
            $email_id = $this->_post('email_id', false);
            if (is_array($email_id)) {
                $obj = D('Email');
                foreach ($email_id as $id) {
                    $obj->save(array('email_id' => $id, 'is_open' => 1));
                }
                $obj->cleanCache();
                $this->chuangSuccess('开启成功！', U('email/index'));
            }
            $this->chuangError('请选择要开启的邮件模版');
        }
    }

}
