<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  SetAction extends CommonAction {

	public function set()
	{
		$this->display(); 
	}
	
	public function index()
	{
		$obj = D('Member');
		if ($this->isPost()) {
			$data = $this->editCheck();
			$data['uid'] =  $detail['uid'] = $this->uid;
			if (false !==$obj->save($data)) {
				$Memberdetail = D('Memberdetail');
				if($Memberdetail->find($this->uid)){
					$Memberdetail->save($detail);
				}else{
					$detail['dateline'] = time();
					$Memberdetail->add($detail);
				}
				$this->chuangSuccess('编辑成功', U('member/info'));
			}
			$this->chuangError('操作失败');
           
        } else {
			$this->assign('fanwei', D('Member')->fanwei());

			$this->assign('zhuanye', D('Member')->zhuanye());
			$this->assign('fromlist', D('Membercate')->select());
			$this->assign('catelist', D('Projectcate')->select());
			$this->display();
		}
	}

	private function editCheck() {
		$obj = D('Member');
        $data = $this->_post('data', 'htmlspecialchars');
        if (empty($data['face'])) {
            $this->chuangError('头像不能为空');
        }
		
		
		 return $data;
	}

	

	public function passwd()
	{
		if ($this->isPost()) {
            $oldpwd = $this->_post('oldpwd', 'htmlspecialchars');
            if (empty($oldpwd)) {
                $this->chuangError('旧密码不能为空！');
            }
            $newpwd = $this->_post('newpwd', 'htmlspecialchars');
            if (empty($newpwd)) {
                $this->chuangError('请输入新密码');
            }
            $pwd2 = $this->_post('pwd2', 'htmlspecialchars');
            if (empty($pwd2) || $newpwd != $pwd2) {
                $this->chuangError('两次密码输入不一致！');
            }
            if ($this->member['passwd'] != md5($oldpwd)) {
                $this->chuangError('原密码不正确');
            }
            if (D('Passport')->uppwd($this->member['name'], $oldpwd, $newpwd)) {
                $this->chuangSuccess('更改密码成功！', U('set/passwd'));
            }
            $this->chuangError('修改密码失败！');
        } else {
            $this->display();
        }
	}

	public function pwd()
	{
		if($this->member['verify'] <6){
			$this->error('请先通过实名认证和邮箱认证',U('member/name'));
		}else{
			if ($this->isPost()) {
				$mobile = $this->member['mobile'];
				$yzm = $this->_post('yzm');
				$pwd = $this->_post('pwd');
				if (empty($mobile) || empty($yzm)){
					$this->chuangError('请填写正确的手机及手机收到的验证码！');
				}
				$s_code = session($mobile.'pwd');
				if ($yzm != $s_code){
					$this->chuangError('验证码不正确');
				}

				if (strlen($pwd) != 6){
					$this->chuangError('支付密码必须是6位');
				}else{
					$data['pwd'] = $pwd;
					$data['uid'] = $this->uid;
					if(D('member')->save($data)){
						$this->chuangError('支付密码设置成功');
					}
				}

			 }else{
				$this->display();
			 }
		}
	}

	public function sendsms() {
        if (!$mobile = htmlspecialchars($_POST['mobile'])) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
        if (!isMobile($mobile)) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
      
        $randstring = session($_POST['mobile'].'pwd');
        if (empty($randstring)) {
                $randstring = rand_string(6, 1);
                session($_POST['mobile'].'pwd', $randstring);
        }

		$res = D('Sms')->sendSms('sms_pwd', $mobile, array('code' => $randstring));
        if(true===$res){
			 $this->ajaxReturn(array('status'=>'success','msg'=>'发送成功'));
		}else if(false===$res){
			 $this->ajaxReturn(array('status'=>'error','msg'=>'发送失败'));
		}else{
			 $this->ajaxReturn($res);
		}
    }

	public function card()
	{
		$Membercard = D('Membercard');
		import('ORG.Util.Page');// 导入分页类
		$map = array();
		$map['uid'] = $this->uid;
		$count      = $Membercard->where($map)->count();// 查询满足要求的总记录数 
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $Membercard->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
	}



	function card_create()
	{
		if($this->member['verify'] <6){
			$this->error('请先通过实名认证和邮箱认证',U('member/name'));
		}else{
			$Membercard = D('Membercard');
			if ($this->isPost()) {
				$data = $this->cardCheck();
				$data['uid']  =  $this->uid;
				if($Membercard->add($data)){
					$this->chuangSuccess('添加成功', U('set/card'));
				}else{
					$this->chuangError('操作失败');
				}
			} else {
				$this->assign('bank',$this->bank_bianma());
				$this->display();
			}
		}
	}
	
	
	private function bank_bianma()
	{
		$bianma['01040000'] = '中国银行';
		$bianma['01030000'] = '农业银行';
		$bianma['01020000'] = '工商银行';
		$bianma['01050000'] = '建设银行';
		$bianma['03080000'] = '招商银行';
		$bianma['03030000'] = '光大银行';
		$bianma['03100000'] = '浦发银行';
		$bianma['03090000'] = '兴业银行';
		$bianma['01000000'] = '邮储银行';
		$bianma['03070000'] = '平安银行';
		$bianma['03020000'] = '中信银行';
		$bianma['03040000'] = '华夏银行';
		$bianma['03050000'] = '民生银行';
		$bianma['03060000'] = '广发银行';
		$bianma['03010000'] = '交通银行';
		$bianma['04791920'] = '包商银行';
		$bianma['04256020'] = '东莞银行';
		$bianma['05083000'] = '江苏银行';
		$bianma['04031000'] = '北京银行';
		$bianma['04012900'] = '上海银行';
		$bianma['04233310'] = '杭州银行';
		$bianma['04083320'] = '宁波银行';
		$bianma['64135810'] = '广州银行';
		$bianma['04375850'] = '珠海华润';
		$bianma['64895910'] = '广东南粤';
		$bianma['03160000'] = '浙商银行';
		
		return $bianma;
		
	}

	private function cardCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
       
		$memebr = D('member')->find($this->uid);
		
        if (empty($data['bank']) && empty($data['code'])) {
            $this->chuangError('提现银行不能为空');
        }
		if (empty($data['card'])) {
            $this->chuangError('银行卡号不能为空');
        }
		
		if (empty($data['name'])) {
            $this->chuangError('开户姓名不能为空');
        }
		if(empty($data['zhihang']) && empty($data['city'])){
			unset($data['code']);
		}else{
			$bianma = $this->bank_bianma();
			$data['bank'] = $bianma[$data['code']];
		}
		
		
		$data['clientip'] = get_client_ip();
        $data['dateline'] = NOW_TIME;
        return $data;
    }

	public function card_delete($card_id)
	{
		 if($card_id = (int)$card_id){
             $obj =D('Membercard');
             $obj->delete($card_id);
             $this->chuangSuccess('删除成功！',U('set/card'));
         }
         $this->chuangError('删除失败!');
	}


    
}
	