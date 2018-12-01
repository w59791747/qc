<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PassportAction extends CommonAction {

    private $create_fields = array('name', 'passwd','fenxiao_id');

    

	public function reg() {
		if ($this->isPost()) {
            $mobile = $this->_post('mobile');
            $yzm = $this->_post('yzm');
            if (empty($mobile) || empty($yzm)){
                $this->chuangError('请填写正确的手机及手机收到的验证码！');
			}
            $s_code = session($mobile);
            if ($yzm != $s_code){
                $this->chuangError('验证码不正确');
			}
			$data['name'] = $name = $mobile;
			if (empty($name) || strlen($name) < 6) {
				$this->chuangError('请输入正确的用户名!用户名长度必须要在6个字符以上!');
			}
			$data['passwd'] = rand_string(6, 1);
			$data['mobile'] = $mobile;
			$data['nickname'] = $data['name'];
			$data['reg_ip'] = get_client_ip();
			$data['reg_time'] = NOW_TIME;
			if(cookie('fenxiao_id')){
				$data['fenxiao_id'] = cookie('fenxiao_id');
			}
			if(cookie('fx_id')){
				$data['fx_id'] = cookie('fx_id');
			}
			if ($uid = D('Passport')->reg($data)) {
				D('member')->updateCount($uid,'verify','2');
				D('Sms')->sendSms('sms_reg', $mobile, array('passwd' => $data['passwd']));
				$this->chuangSuccess('恭喜您，注册成功！', U('member/index/index'));
			}
            $this->chuangError(D('Passport')->getError(), 3000, true);

        } else {
			$fx_id = $_GET['fx'];
			if(!empty($fx_id)){
				$member_fenxiao = D('Fx')->where(array('mobile'=>$fx_id))->find();
				if($member_fenxiao){
					cookie('fx_id',$fx_id);
				}
			}
			$this->display(); 
		}
       
    }

	public function check_name($name)
	{
		$member  = D('Member');
		if(strlen($name)<6){
			echo "用户名不能小于6个字符";
		}else if($user = $member->where(array('name'=>$name))->find()){
			echo "该用户名已存在";
		}else{
			echo "success";
		}

	}

	public function check_yzm($yzm)
	{
		if (strtolower($yzm) != strtolower(session('verify'))){
			echo '验证码不正确';
		}else{
			echo "success";
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

    public function logout() {

        D('Passport')->logout();
        $this->chuangSuccess('退出登录成功！', U('index/index'));
    }

    public function login() {
        if($this->uid){
            $this->display('index/index');
        }else{
            if ($this->isPost()) {
				if($this->_CONFIG['power']['login'] == 'on'){
					$yzm = $this->_post('yzm');
					if (strtolower($yzm) != strtolower(session('verify'))) {
						session('verify', null);
						$this->chuangError('验证码不正确!', 2000, true);
					}
				}
                
                $name = $this->_post('name');
                if (empty($name)) {
                    session('verify', null);
                    $this->chuangError('请输入用户名!', 2000, true);
                }

                $passwd = $this->_post('passwd');
                if (empty($passwd)) {
                    session('verify', null);
                    $this->chuangError('请输入登录密码!', 2000, true);
                }
				
                $backurl = $this->_post('backurl', 'htmlspecialchars');
                if (empty($backurl))
                    $backurl = U('member/index/index');
                if (true == D('Passport')->login($name, $passwd)) {
                    $this->chuangSuccess('恭喜您登录成功！', $backurl);
                }
                $this->chuangError(D('Passport')->getError(), 3000, true);
            } else {
                if (!empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) && !strstr($_SERVER['HTTP_REFERER'], 'passport')) {
                    $backurl = $_SERVER['HTTP_REFERER'];
                } else {
                    $backurl = U('member/index/index');
                }
                $this->assign('backurl', $backurl);
                $this->display();
            }
        }   
    }

    public function bind() {
        $connect = session('connect');

        $this->assign('connect', D('Connect')->find($connect));
        $this->assign('types', array('qq' => '腾讯QQ', 'weixin' => '微信', 'weibo' => '微博'));
        $this->display();
    }

   

    public function check() {

        $this->display();
    }

    public function ajaxloging() {

        $this->display();
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', 'htmlspecialchars'), $this->create_fields);
        $data['name'] = htmlspecialchars($_POST['name']);
		if (empty($data['name']) || strlen($data['name']) < 6) {
            session('verify', null);
            $this->chuangError('请输入正确的用户名!用户名长度必须要在6个字符以上', 2000, true);
        }
        
        $data['passwd'] = htmlspecialchars($data['passwd']);
        if (empty($data['passwd']) || strlen($data['passwd']) < 6) {
            session('verify', null);
            $this->chuangError('请输入正确的密码!密码长度必须要在6个字符以上', 2000, true);
        }
        $data['nickname'] = $data['name'];
        $data['reg_ip'] = get_client_ip();
        $data['reg_time'] = NOW_TIME;
        return $data;
    }

    //两种找回密码的方式 1个是通过邮件 //填写了2个就改密码相对来说是不太合理，但是毕竟逻辑和操作相对简单一些！
    public function forget() {

        $this->display();
    }

    public function newpwd() {
       
        $name = $this->_post('name');
        if (empty($name)) {
            $this->chuangError('请输入用户名!', 2000, true);
        }
        $Member = D('Member')->where(array('name'=>$name))->find();
        if (empty($Member)) {
            $this->chuangError('用户不存在!', 2000, true);
        }
        $way = (int) $this->_post('way');
        $password = rand_string(8, 1);
        switch ($way) {
            case 1:
                $mail = $this->_post('mail');
                if (empty($mail) || $mail != $Member['mail']) {
                    $this->chuangError('邮件不正确');
                }
				if($Member['verify'] == 1 || $Member['verify'] == 3 || $Member['verify'] == 5 || $Member['verify'] == 7){
					D('Passport')->uppwd($Member['name'], '', $password);
					D('Email')->sendMail('email_newpwd', $email, '重置密码', array('newpwd' => $password));
				}else{
					 $this->chuangError('该账号邮件未认证');
				}

                break;
			
            default:
                $mobile = $this->_post('mobile');
                if (empty($mobile) || $mobile != $Member['mobile']) {
                    $this->chuangError('手机号码不正确');
                }
				if($Member['verify'] == 2 || $Member['verify'] == 3 || $Member['verify'] == 6|| $Member['verify'] == 7){
					 D('Passport')->uppwd($Member['name'], '', $password);
					 D('Sms')->sendSms('sms_newpwd', $mobile, array('newpwd' => $password));
				}else{
					 $this->chuangError('该账号手机未认证');
				}
               
                break;
        }
        $this->chuangSuccess('重置密码成功！', U('passport/suc', array('way' => $way)));
    }

    public function suc() {
        $way = (int) $this->_get('way');
        switch ($way) {
            case 1:
                $this->success('密码重置成功！请登录邮箱查看！', U('passport/login'));
                break;
            default:
                $this->success('密码重置成功！请查看验证手机！', U('passport/login'));
                break;
        }
    }

    public function wblogin() {
        $login_url = 'https://api.weibo.com/oauth2/authorize?client_id=' . $this->_CONFIG['connect']['wb_app_id'] . '&response_type=code&redirect_uri=' . urlencode(__HOST__ . U('passport/wbcallback'));
        header("Location:$login_url");
        die;
    }

    public function qqlogin() {
        $state = md5(uniqid(rand(), TRUE));
        session('state', $state);
        $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
                . $this->_CONFIG['connect']['qq_app_id'] . "&redirect_uri=" . urlencode(__HOST__ . U('passport/qqcallback'))
                . "&state=" . $state
                . "&scope=";
        header("Location:$login_url");
        die;
    }

    public function wxlogin() {
        
		

		$Tokentable = D('Token');
		$m['dateline'] = array('LT',time()-60*10);
		$Tokentable->where($m)->delete();
        do {
            $state = md5(uniqid(rand(), TRUE));
            $no = $Tokentable->where(array('token'=>$state))->find();
        } while ($no);

		$Tokentable->add(array('token'=>$state,'status'=>'0','dateline'=>time()));

        /*session('state', $state);
        $login_url = 'https://open.weixin.qq.com/connect/qrconnect?appid=' . $this->_CONFIG['connect']['wx_app_id']
                . '&redirect_uri=' . urlencode(__HOST__ . U('passport/wxcallback')) . '&response_type=code&scope=snsapi_login&state=' . $state . '#wechat_redirect';
        header("Location:$login_url");
        die;*/
		 
		 $codeurl = __HOST__ . U('mobile/index').'?tokenkey='.$state;
		 $this->assign('codeurl',$codeurl);
		 $this->assign('token',$state);
		 $this->display();
    }

	public function wxcheck($token)
	{
		$tokenkey =$_GET['tokenkey'];
		$Tokentable = D('Token');
		$m['token'] = $token;
		$m['dateline'] = array('LT',time()-60*10);
		$Tokentable->where($m)->delete();
		$t = $Tokentable->where(array('token'=>$token))->find();
		if($t){
			if($t['status']==1){
				$this->ajaxReturn(array('status' => 'error', 'msg' => '<span>扫描成功</span><br />请在微信中点击确认即可登录'));

			}else if($t['status']==2){
				$this->ajaxReturn(array('status' => 'error', 'msg' => '等待微信端绑定用户'));

			}else if($t['status']==3 && $t['open_id']){
				

				$m = D('Connect')->getConnectByOpenid('weixin',$t['open_id']);
				setUid($m['uid']);
				session('access', $m['connect_id']);
				$this->ajaxReturn(array('status' => 'success', 'msg' => '登录成功'));
			}else{
				$this->ajaxReturn(array('status' => 'error'));

			}
		}else{
			$this->ajaxReturn(array('status' => 'error', 'msg' => '该二维码不存在或已过期'));

		}
	}

    public function wbcallback() {

        import("@/Net.Curl");
        $curl = new Curl();
		
        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $_REQUEST["code"],
            'client_id' => $this->_CONFIG['connect']['wb_app_id'],
            'client_secret' => $this->_CONFIG['connect']['wb_app_key'],
            'redirect_uri' => __HOST__ . U('passport/qqcallback')
        );
        $url = 'https://api.weibo.com/oauth2/access_token';
        $response = $curl->post($url, http_build_query($params));
        $params = json_decode($response, true);
        if (isset($params['error'])) {
            echo "<h3>error:</h3>" . $params['error'];
            echo "<h3>msg  :</h3>" . $params['error_code'];
            exit;
        }
        $url = 'https://api.weibo.com/2/account/get_uid.json?source=' . $this->_CONFIG['connect']['wb_app_key'] . '&access_token=' . $params['access_token'];
        $result = $curl->get($url);
        $user = json_decode($result, true);
        if (isset($user['error'])) {
            echo "<h3>error:</h3>" . $user['error'];
            echo "<h3>msg  :</h3>" . $user['error_code'];
            exit;
        }
        $data = array(
            'type' => 'weibo',
            'open_id' => $user['uid'],
            'token' => $params['access_token']
        );
        $this->thirdlogin($data);
    }

    public function wxcallback() {
            import("@/Net.Curl");
            $curl = new Curl();
            if (empty($_REQUEST["code"])) {
                $this->error('授权后才能登陆！', U('passport/login'));
            }
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->_CONFIG['connect']["wx_app_id"] .
                    '&secret=' . $this->_CONFIG['connect']["wx_app_key"] .
                    '&code=' . $_REQUEST["code"] . '&grant_type=authorization_code';
            $str = $curl->get($token_url);

            $params = json_decode($str, true);
            if (!empty($params['errcode'])) {
                echo "<h3>error:</h3>" . $params['errcode'];
                echo "<h3>msg  :</h3>" . $params['errmsg'];
                exit;
            }
            if (empty($params['openid'])) {
                $this->error('登录失败', U('passport/login'));
            }
            $data = array(
                'type' => 'weixin',
                'open_id' => $params['openid'],
                'token' => $params['refresh_token']
            );
            $this->thirdlogin($data);
        
    }

    public function qqcallback() {
        import("@/Net.Curl");
        $curl = new Curl();
		
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                    . "client_id=" . $this->_CONFIG['connect']["qq_app_id"] . "&redirect_uri=" . urlencode(__HOST__ . U('passport/qqcallback'))
                    . "&client_secret=" . $this->_CONFIG['connect']["qq_app_key"] . "&code=" . $_REQUEST["code"];
            $response = $curl->get($token_url);



			
			


            if (strpos($response, "callback") !== false) {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
                $msg = json_decode($response);
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
            $params = array();
            parse_str($response, $params);
            if (empty($params))
                die;
            $graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
                    . $params['access_token'];

			

            $str = $curl->get($graph_url);
            if (strpos($str, "callback") !== false) {
                $lpos = strpos($str, "(");
                $rpos = strrpos($str, ")");
                $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
            }

            $user = json_decode($str, true);
            if (isset($user['error'])) {
                echo "<h3>error:</h3>" . $user['error'];
                echo "<h3>msg  :</h3>" . $user['error_description'];
                exit;
            }
            if (empty($user['openid']))
                die;
            $data = array(
                'type' => 'qq',
                'open_id' => $user['openid'],
                'token' => $params['access_token']
            );

			$graph_url2 = "https://graph.qq.com/user/get_user_info?access_token="
                    . $params['access_token']. "&oauth_consumer_key=" . $this->_CONFIG['connect']["qq_app_id"] . "&openid=" .$user['openid'];
			$str2 = $curl->get($graph_url2);
			$info = json_decode($str2, true);
			$data['nickname'] = $info['nickname'];
            $this->thirdlogin($data);
        
    }

	

    private function thirdlogin($data) {
		$connect = D('Connect')->getConnectByOpenid($data['type'], $data['open_id']);

		if (empty($connect)) {
			$connect = $data;
			$connect['connect_id'] = D('Connect')->add($data);
		} else {
			D('Connect')->save(array('connect_id' => $connect['connect_id'], 'token' => $data['token'], 'nickname' => $data['nickname']));
		}
		if (empty($connect['uid'])) {
			if($this->uid){
				D('Connect')->save(array('connect_id' => $connect['connect_id'], 'uid' => $this->uid));
				$this->success('绑定第三方登录成功',U('member/index/index'));
			}else{
				session('connect', $connect['connect_id']);
				header("Location: " . U('passport/bind'));
			}
		} else {
			setUid($connect['uid']);
			session('access', $connect['connect_id']);
			header("Location:" . U('member/index/index'));
		}
		die;
	}
    

}
