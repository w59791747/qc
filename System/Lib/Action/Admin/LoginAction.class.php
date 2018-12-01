<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class LoginAction extends CommonAction{
    
    public function index(){
        $this->display();
    }
    
    public function loging(){
		if($this->_CONFIG['power']['admin'] == 'on'){
			$yzm = $this->_post('yzm');
			if(strtolower($yzm) != strtolower(session('verify'))){
				session('verify',null);
				$this->chuangError('验证码不正确!',2000,true);
			}
		}
        $username = $this->_post('username','trim');
        $password = $this->_post('password','trim,md5');
        $adminObj = D('Admin');
        $admin = $adminObj->getAdminByUsername($username);
        if($admin['role_id'] == 3){
            $this->chuangError('管理员类型错误!');
        }
        if(empty($admin) || $admin['passwd'] != $password){
            session('verify',null);
            $this->chuangError('用户名或密码不正确!',2000,true);
        }
        if($admin['closed'] == 1){
           session('verify',null);
           $this->chuangError('该账户已经被禁用!',2000,true); 
        }
        $admin['last_login'] = NOW_TIME;
        $admin['last_ip']  = get_client_ip();
        $adminObj->where("admin_id=%d",$admin['admin_id'])->save(array('last_login'=>$admin['last_login'],'last_ip'=>$admin['last_ip']));
        session('admin',$admin);
        $this->chuangSuccess('登录成功！',U('index/index'));
    }
    
    public function logout(){
        session('admin',null);
        $this->success('退出成功',U('login/index'));
    }
    
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(5,2,'png',60,30);
    }
    
}
