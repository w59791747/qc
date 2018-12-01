<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  SetAction extends CommonAction {
	
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
				$this->chuangSuccess('编辑成功', U('index/index'));
			}
			$this->chuangError('操作失败');
           
        } else {
			$this->assign('fromlist', D('Membercate')->select());
			$this->assign('catelist', D('Projectcate')->select());

			$this->assign('fanwei', D('Member')->fanwei());

			$this->assign('zhuanye', D('Member')->zhuanye());
			$this->display();
		}
	}

	public function renzheng()
	{
		$this->display();
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
			$this->error('请先通过实名认证和手机认证',U('set/name'));
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

	public function sendsms2() {
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
			$this->error('请先通过实名认证和邮箱认证',U('set/name'));
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
		//if (empty($data['zhihang'])) {
            //$this->chuangError('银行支行不能为空');
       // }
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


	public function mobile()
	{
		if ($this->isPost()) {
            $mobile = $this->_post('mobile');
            $yzm = $this->_post('yzm');
            if (empty($mobile) || empty($yzm))
                $this->chuangError('请填写正确的手机及手机收到的验证码！');
            $s_code = session($mobile);
            if ($yzm != $s_code)
                $this->chuangError('验证码不正确');
            $data = array(
                'uid' => $this->uid,
                'mobile' => $mobile
            );
            if (D('Member')->save($data)) {
				D('member')->updateCount($this->uid,'verify','2');
                $this->chuangSuccess('恭喜您通过手机认证', U('set/mobile'));
            }
            $this->chuangError('更新数据失败！');
        } else {
			$this->display(); 
		}
	}

	 public function sendsms() {
        if (!$mobile = htmlspecialchars($_POST['mobile'])) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
        if (!isMobile($mobile)) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'请输入正确的手机号码'));
        }
        if ($user = D('Member')->where(array('mobile'=>$mobile))->find()) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'手机号码已经存在！'));
        }
        $randstring = session($_POST['mobile']);
        if (empty($randstring)) {
                $randstring = rand_string(6, 1);
                session($_POST['mobile'], $randstring);
        }
		$res = D('Sms')->sendSms('sms_code', $mobile, array('code' => $randstring));
        if(true===$res){
			 $this->ajaxReturn(array('status'=>'success','msg'=>'发送成功'));
		}else if(false===$res){
			 $this->ajaxReturn(array('status'=>'error','msg'=>'发送失败'));
		}else{
			 $this->ajaxReturn($res);
		}
    }


	public  function mail(){
		 $this->display();
	}

	public function sendemail() {
        $email = $this->_post('email');
        if (isEmail($email)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'];
            $uid = $this->uid;
            $local = array(
                'email' => $email,
                'uid' => $uid,
                'time' => NOW_TIME,
                'sig' => md5($uid . $email . NOW_TIME . C('AUTH_KEY'))
            );
            $link .=U('public/email', $local);
            D('Email')->sendMail('email_rz', $email, $this->_CONFIG['site']['sitename'] . '邮件认证', array('link' => $link));
        }
    }


	public function name()
	{
		
		if ($this->isPost()) {
           $data = $this->nameCheck();
		   $data['uid'] = $this->uid;
		   if(D('Memberdetail')->find($this->uid)){
				D('Memberdetail')->save($data);
		   }else{
			    $data['dateline'] = time();
				D('Memberdetail')->add($data);
		   }
		   $datas['uid'] = $this->uid;
		   $datas['audit'] = 0;
		   $datas['dateline'] = time();
		   D('Verify')->add($datas);
		    $obj = D('Verify');$Member = D('Member');
			
			$user = $Member->find($this->uid);
			if($user['verify']>=4){
					$obj->save(array('uid' => $this->uid, 'audit' => 1));
					$this->chuangSuccess('审核通过！', U('set/name'));
			}else{
				if(false !==$Member->updateCount($this->uid,'verify',4)){
					if($this->audit_comment($this->uid,'member','Verify','reg',$user['fenxiao_id'],'fenxiang_reg')){ 
						
						$obj->cleanCache();
						$this->chuangSuccess('审核通过！', U('set/name'));
					}
				}
			}
        } else {
			if(D('Verify')->find($this->uid)){
				$this->assign('renzheng', '1');
			}
			$this->display(); 
		}
	}

	 private function nameCheck() {
        $data = $this->_post('data', 'htmlspecialchars');
        $data['realname'] = htmlspecialchars($data['realname']);
        if (!isAllChinese($data['realname'])) {
            $this->chuangError('真实姓名必须是汉字');
        }
		if (!isCreditNo($data['card'])) {
            $this->chuangError('身份证号码不正确');
        } 
        return $data;
    }


	private $create_adress = array('addr','contact','default','phone','city');
    private $edit_adress = array('addr','contact','default','phone','city','citys');
    
    public  function adress(){
       $Memberaddr = D('Memberaddr');
       import('ORG.Util.Page');// 导入分页类
       $map = array();
	   $map['uid'] = $this->uid;
       $count      = $Memberaddr->where($map)->count();// 查询满足要求的总记录数 
       $Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
       $show       = $Page->show();// 分页显示输出
       $list = $Memberaddr->where($map)->order(array('dateline'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
    }
    
   

    public function adresscreate() {
        if ($this->isPost()) {
            $data = $this->createCheck_a();
            $obj = D('Memberaddr');
			$data['uid'] = $this->uid;
            if($obj->add($data)){
                $obj->cleanCache();
                $this->chuangSuccess('添加成功',U('set/adress'));
            }
            $this->chuangError('操作失败！');
        } else {
            $this->display();
        }
    }

	private function createCheck_a() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_adress);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		
        if (empty($data['city']) || $data['city'] == '城市') {
            $this->chuangError('请选择城市');
        }
		$is_default = D('Memberaddr')->where(array('default'=>'1','uid'=>$this->uid))->find();
		if(!$is_default){
			$data['default'] = 1;
		}
		$data['dateline'] = time();
        return $data;
    }



	public function adressedit($addr_id = 0) {
		 $obj = D('Memberaddr');
		 if (!$detail = $obj->find($addr_id)) {
			$this->error('请选择要编辑的地址');
		 }
        if ($this->isPost()) {
            $data = $this->editCheck_a();
           
			$data['addr_id'] = $addr_id;
			
			if ($obj->save($data)) {
				$this->chuangSuccess('修改成功', U('set/adress'));
			}
            $this->chuangError('操作失败！');
        } else {
			$this->assign('member', D('member')->find($detail['uid']));
			$this->assign('detail', $detail);
            $this->display();
        }
    }

	private function editCheck_a() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->edit_adress);
        if (empty($data['addr'])) {
            $this->chuangError('详细地址不能为空');
        }
		if (empty($data['contact'])) {
            $this->chuangError('联系人不能为空');
        }
		if (!isMobile($data['phone'])) {
            $this->chuangError('手机格式不正确');
        }
		if ($data['city'] == '城市' && $data['citys'] == '') {
            $this->chuangError('城市不能为空');
        }elseif($data['city'] == '城市' && $data['citys']){
			unset($data['city']);
		}else{
			unset($data['citys']);
		}
		
        return $data;
    }

	public function set_default($addr_id = 0)
	{
		 $obj = D('Memberaddr');
		 if (!$detail = $obj->find($addr_id)) {
			$this->chuangError('请选择要编辑的地址');
		 }else{
			$is_default = $obj->where(array('default'=>'1','uid'=>$this->uid))->find();
			$data['addr_id'] = $addr_id;
			$data['default'] = 1;
			if ($obj->save($data)) {
				$datas = array('default'=>'0','addr_id'=>$is_default['addr_id']);
				$obj->save($datas);
				$this->chuangSuccess('设置成功', U('set/adress'));
			}
		 }
	}
    
    public function adressdelete($addr_id = 0){
		 $obj =D('Memberaddr');
		 $addr = $obj->find($addr_id);
		 if(!$detail = $obj->find($addr_id)){
			 $this->chuangError('请选择要删除的收货地址');
		}elseif($detail['uid'] != $this->uid){
			 $this->chuangError('您没有权限删除该收货地址');
		}else{
			 if($detail['default'] == 1){
				$this->chuangError('默认地址不能删除,请先重新设置默认地址!');
			 }else{
				 $obj->delete($addr_id);
				 $obj->cleanCache();
				 $this->chuangSuccess('删除成功！',U('set/adress'));
			 }
         }
    }


    
}
	