<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class MemberAction extends CommonAction {


	
    public function index($uid = null) {
        $member = D('Member');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'));
		if($uid){
			 $map['uid'] = $uid;
		}
        if($name = $this->_param('name','htmlspecialchars')){
            $map['name'] = array('LIKE','%'.$name.'%');
            $this->assign('name',$name);
        }
		if($from = $this->_param('from','htmlspecialchars')){
			if($from != 1){
				$map['from'] =  $from;
			}
            $this->assign('from',$from);
        }
        
        $count = $member->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $member->where($map)->order(array('uid'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $list[$k] = $val;
			$lists[$k] = $member->_format_row($val);
        }
        $this->assign('list', $lists); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('fromlist', $member->_from_list());
        $this->display(); // 输出模板
    }

	

	public function detail($uid = 0)
	{
		$obj = D('Member');
        if ($uid = (int) $uid) {
            if (!$detail = $obj->detail($uid)) {
                $this->error('请选择要查看的会员');
            }
			$fenxiao = D('Member')->find($detail['fenxiao_id']);
			$detail['login_ip_area'] = $this->ipToArea($detail['loginip']);
			$detail['regip_area'] = $this->ipToArea($detail['regip']);
			$this->assign('detail', $detail);
			$catelist = D('Membercate')->select();
			foreach($catelist  as $k =>$v){
				$catelist[$v['cate_id']] = $v;
			}
			$this->assign('catelist', $catelist);
			$this->assign('fenxiao', $fenxiao);
			$this->assign('closed', $obj->closed());
			$this->assign('fromlist', $obj->_from_list());
			$this->display();
            
        } else {
            $this->error('请选择要查看的会员');
        }
	}

    
    public function select($from='1',$id='uid',$title='name'){
        $member = D('Member');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('closed'=>array('IN','0,1,2'));
        if($name = $this->_param('name','htmlspecialchars')){
            $map['name'] = array('LIKE','%'.$name.'%');
            $this->assign('name',$name);
        }
		if($from != 1){
			$map['from'] =  $from;
			$this->assign('from',$from);
		}
		
		if($from = $this->_param('from','htmlspecialchars')){
			if($from != 1){
				$map['from'] =  $from;
			}
            $this->assign('from',$from);
        }
        $count = $member->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $pager = $Page->show(); // 分页显示输出
        $list = $member->where($map)->order(array('uid'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $pager); // 赋值分页输出
		$this->assign('fromlist', $member->_from_list());
		$this->assign('id', $id); // 赋值数据集
		$this->assign('title', $title); // 赋值数据集
        $this->display(); // 输出模板
        
    }

    public function create() {
		 $obj = D('Member');
        if ($this->isPost()) {
            $data = $this->createCheck();
            if ($obj->add($data)) {
                $this->chuangSuccess('添加成功', U('Member/index'));
            }
            $this->chuangError('操作失败！');
        } else {
			$this->assign('fromlist', $obj->_from_list()); 
            $this->display();
        }
    }

    private function createCheck() {
		$obj = D('Member');
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $obj->create_fields());
        $data['name'] = htmlspecialchars($data['name']);
        if (empty($data['name'])) {
            $this->chuangError('账户不能为空');
        } 
		$f['name'] = $data['name'];
        if($obj->where($f)->find()){
            $this->chuangError('该账户已经存在！');
        }
        $data['passwd'] = htmlspecialchars($data['passwd']);
        if (empty($data['passwd'])) {
            $this->chuangError('密码不能为空');
        } 
		if($data['xuni'] == '1'){
			$data['verify'] = '6';
		}
        $data['face'] = htmlspecialchars($data['face']);
        $data['passwd'] = md5($data['passwd']);
		$data['closed'] = 0;
        $data['loginip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	public function gold($uid = 0)
	{
		$obj = D('Member');
		$memberlog = D('Memberlog');
        if ($uid = (int) $uid) {
            if (!$detail = $obj->find($uid)) {
                $this->error('请选择要增加积分的会员');
            }
            if ($this->isPost()) {
                $data = $this->goldCheck();
				if($detail['gold']+$data['number']<0){
					$this->chuangError('积分不能小于0,操作失败');
				}else{

					$data['uid'] = $uid;
					if (false !==$memberlog->insert($data)) {
						$this->chuangSuccess('修改成功', U('member/index'));
					}
				}
                $this->chuangError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要增加积分的会员');
        }
	}

	public function manage(){
       $uid = (int)$this->_get('uid'); 
       if(empty($uid)) $this->error ('请选择用户');
       if(!$detail = D('Member')->find($uid)){
           $this->error('没有该用户！');
       }
       setUid($uid);
       header("Location:".U('member/index/index'));
       die;
    }

	private function goldCheck() {
		$obj = D('Member');
        $data = $this->_post('data', 'htmlspecialchars');
        if (empty($data['log'])) {
            $this->chuangError('日志不能为空');
        }
       
		$data['number'] = htmlspecialchars($data['number']);
        if (!is_numeric($data['number'])) {
            $this->chuangError('增加积分数量不能为空');
        } 
		$data['from'] = 'gold';
		$data['admin'] = $this->admin['admin_id'];
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
        return $data;
    }


	public function price($uid = 0)
	{
		$obj = D('Member');
		$memberlog = D('Memberlog');
        if ($uid = (int) $uid) {
            if (!$detail = $obj->find($uid)) {
                $this->error('请选择要增加资金的会员');
            }
            if ($this->isPost()) {
                $data = $this->priceCheck();
				if($detail['price']+$data['number']<0){
					$this->chuangError('资金不能小于0,操作失败');
				}else{
					$data['uid'] = $uid;
					if (false !==$memberlog->insert($data)) {
						$this->chuangSuccess('修改成功', U('member/index'));
					}
				}
                $this->chuangError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要增加资金的会员');
        }
	}

	private function priceCheck() {
		$obj = D('Member');
        $data = $this->_post('data', 'htmlspecialchars');
        if (empty($data['log'])) {
            $this->chuangError('日志不能为空');
        }
       
		$data['number'] = htmlspecialchars($data['number']);
       
		 if (!is_numeric($data['number'])) {
            $this->chuangError('增加资金数量不能为空');
        } 
		$data['number'] = $data['number']*100;
		$data['from'] = 'price';
		$data['admin'] = $this->admin['admin_id'];
		if($data['number']<0){
			$data['status'] = 2;
		}
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	public function news_create()
	{
		$obj = D('Member');
		$Membernew = D('Membernew');
		if ($this->isPost()) {
			$data = $this->_post('data', 'htmlspecialchars');
			$data['title'] = htmlspecialchars($data['title']);
			$data['content'] = htmlspecialchars($data['content']);
			if (empty($data['title'])) {
				$this->chuangError('标题不能为空');
			} 
			if (empty($data['content'])) {
				$this->chuangError('内容不能为空');
			}
			if($count = $Membernew->addnew($data,$this->admin['admin_id'])){
				$this->chuangSuccess('推送成功,共推送'.$count.'条', U('member/news_create'));
			}
			$this->chuangError('操作失败');
		}else{
			$this->assign('fromlist', $obj->_from_list());
			$this->display();
		}
	}

    public function edit($uid = 0) {
		$obj = D('Member');
        if ($uid = (int) $uid) {
            if (!$detail = $obj->find($uid)) {
                $this->error('请选择要编辑的会员');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['uid'] = $uid;
                if (false !==$obj->save($data)) {
                    $this->chuangSuccess('编辑成功', U('member/index'));
                }
                $this->chuangError('操作失败');
            } else {
                $this->assign('detail', $detail);
				$this->assign('fromlist', $obj->_from_list());
				$this->assign('closed', $obj->closed());
				
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的会员');
        }
    }

    private function editCheck() {
		$obj = D('Member');
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $obj->edit_fields());
        $data['name'] = htmlspecialchars($data['name']);
        if (empty($data['name'])) {
            $this->chuangError('账户不能为空');
        }
        if($data['passwd'] == '******'){
            unset($data['passwd']);
        }else{
            $data['passwd'] = htmlspecialchars($data['passwd']);
            if (empty($data['passwd'])) {
                $this->chuangError('密码不能为空');
            } 
            $data['passwd'] = md5($data['passwd']);
        }
		if($data['face'] == ''){
            unset($data['face']);
        }
		
        return $data;
    }

    public function delete($uid = 0) {
        if (is_numeric($uid) && ($uid = (int) $uid)) {
            $obj = D('Member');
			$obj->save(array('uid'=>$uid,'closed'=>3));
            $this->chuangSuccess('删除成功！', U('member/index'));
        } else {
            $uid = $this->_post('uid', false);
            if (is_array($uid)) {
                $obj = D('Member');
                foreach ($uid as $id) {
					$obj->save(array('uid'=>$id,'closed'=>3));
                }
                $this->chuangSuccess('删除成功！', U('member/index'));
            }
            $this->error('请选择要删除的会员');
        }
    }
     public function audit($uid = 0) {
        if (is_numeric($uid) && ($uid = (int) $uid)) {
            $obj = D('Member');
            $obj->save(array('uid'=>$uid,'closed'=>0));
            $this->chuangSuccess('审核成功！', U('member/index'));
        } else {
            $uid = $this->_post('uid', false);
            if (is_array($uid)) {
                $obj = D('Member');
                foreach ($uid as $id) {
                    $obj->save(array('uid'=>$id,'closed'=>0));
                }
                $this->chuangSuccess('审核成功！', U('member/index'));
            }
            $this->error('请选择要审核的会员');
        }
    }

	
    

}