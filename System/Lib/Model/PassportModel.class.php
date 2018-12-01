<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PassportModel {

    private $CONFIG = array();
    private $charset = 0;
    private $error = null; 
    private $domain = '@qq.com'; 
    private $member  = array();

    public function __construct() {
        $config = D('Setting')->fetchAll();
        $this->CONFIG = $config;
    }

    public function getUserInfo(){
        return $this->user;
    }
    
    public function getError() {
        return $this->error;
    }

    public function logout() {
        clearUid();
        return true;
    }

    public function uppwd($name, $oldpwd, $newpwd) {
       	$Member = D('Member')->where(array('name'=>$name))->find();
        return D('Member')->save(array('uid' => $Member['uid'], 'passwd' => md5($newpwd)));
    }

    public function login($name, $passwd) {
		$Member = D('Member')->where(array('name'=>$name))->find();
        if (empty($Member)) {
            $Member = D('Member')->where(array('mobile'=>$name))->find();
        }
		if (empty($Member)) {
			$this->error = '账号或密码不正确';
			return false;
		}
		if ($Member['closed'] >=2) {
			$this->error = '用户不存在或被删除！';
			return false;
		}
		if ($Member['passwd'] != md5($passwd)) {
			$this->error = '账号或密码不正确！';
			return false;
		}
		$m = array('lastlogin'=>NOW_TIME,'loginip'=>get_client_ip(),'uid'=>$Member['uid']);
		D('Member')->save($m);

		setUid($Member['uid']);
        
        $connect = session('connect');
        if (!empty($connect)) {
            D('Connect')->save(array('connect_id' => $connect, 'uid' => $Member['uid']));
        }
        $this->member = $Member;
        return true;
    }

    public function reg($data = array()) {
        
        $data['dateline'] = NOW_TIME;
        $data['regip'] = get_client_ip();
        $fenxiao_id = (int)cookie('fenxiao_id');
        if(!empty($fenxiao_id)){
            $member_fenxiao = D('Member')->find($fenxiao_id);
			$data['fenxiao_id'] = $fenxiao_id;
        }
        if (empty($data)){
            return false;
		}
        
		$obj = D('Member');
		$data['passwd'] = md5($data['passwd']);
		 
		$Member = D('Member')->where(array('name'=>$data['name']))->find();
		if ($Member) {
			$this->error = '该账户已经存在';
			return false;
		}
		$data['lastlogin'] = time();
		$data['uid'] = $obj->add($data);
        $connect = session('connect');
        if (!empty($connect)) {
            D('Connect')->save(array('connect_id' => $connect, 'uid' => $data['uid']));
        }
        setUid($data['uid']);
        return $data['uid'];
    }
}