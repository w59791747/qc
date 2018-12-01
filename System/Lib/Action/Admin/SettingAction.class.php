<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class SettingAction extends CommonAction {

    public function site() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'site', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/site'));
        } else {
            $this->assign('citys',D('City')->fetchAll());
            $this->display();
        }
    }


    public function attachs() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
			if($_POST['closewater'] == 'on'){
				$data['water'] = '0';
			}
            $data = serialize($data);
            D('Setting')->save(array('k' => 'attachs', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/attachs'));
        } else {
            $this->display();
        }
    }
    


    public function sms() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'sms', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/sms'));
        } else {
            $this->display();
        }
    }

  
   
    public function mail() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'mail', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/mail'));
        } else {
            $this->display();
        }
    }

    

    public function mobile() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'mobile', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/mobile'));
        } else {
            $this->display();
        }
    }

	public function integral() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'integral', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/integral'));
        } else {
            $this->display();
        }
    }

	public function home()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'home', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/home'));
        } else {
            $this->display();
        }
	}

	public function power()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'power', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/power'));
        } else {
            $this->display();
        }
	}

	public function weixin()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'weixin', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/weixin'));
        } else {
            $this->display();
        }
	}

	public function connect()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'connect', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/connect'));
        } else {
            $this->display();
        }
	}

	public function comment()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'comment', 'v' => $data));
            D('Setting')->cleanCache();
            $this->chuangSuccess('设置成功', U('setting/comment'));
        } else {
            $this->display();
        }
	}

	public function crowd()
	{
		if ($this->isPost()) {
            $data = $this->_post('data', false);
			if($data['canshu']){
				$datas['canshu'] = $data['canshu'];
				unset($data['canshu']);
			}
			if($data['hetong']){
				$datas['hetong'] = $data['hetong'];
				unset($data['hetong']);
			}
			if($data['chi_days']< $data['toupiao']+$data['days']){
				$this->chuangError('筹资期限不能高于（最长持有期限-投票期限）');
			}
            $data = serialize($data);
            D('Setting')->save(array('k' => 'crowd', 'v' => $data));
            D('Setting')->cleanCache();
			$other = D('Settingother')->find('1');
			$datas['oid'] = '1';
			if($other){
				D('Settingother')->save($datas);
			}else{
				D('Settingother')->add($datas);
			}
            $this->chuangSuccess('设置成功', U('setting/crowd'));
        } else {
			$other = D('Settingother')->find('1');
			$this->assign('other', $other);
            $this->display();
        }
	}

}
