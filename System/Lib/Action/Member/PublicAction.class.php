<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PublicAction extends CommonAction {

    public function email() { //email验证接口
        $email = $this->_get('email');
        if (!isEmail($email)) {
            $this->error('EMAIL地址不正确', U('index/index'));
        }
        $uid = (int) $this->_get('uid');
        $time = (int) $this->_get('time');
        $sig = $this->_get('sig');
        if (empty($uid) || empty($time) || empty($sig)) {
            $this->error('参数不能为空', U('index/index'));
        }
        if (NOW_TIME - $time > 3600) {
            $this->error('验证链接已经超时了！', U('index/index'));
        }
        $sign = md5($uid . $email . $time . C('AUTH_KEY'));
        if ($sig != $sign) {
            $this->error('签名失败', U('index/index'));
        }
        $Member = D('Member')->find($uid);
        if (empty($Member))
            $this->error('用户不存在！', U('index/index'));
        if ($Member['verify'] == 1 || $Member['verify'] == 3 || $Member['verify'] == 5 || $Member['verify'] == 7)
            $this->error('用户已经通过邮件认证的！', U('index/index'));
        $data = array(
            'uid' => $uid,
            'mail' => $email
        );

		if (D('Member')->save($data)) {
			D('member')->updateCount($uid,'verify','1');
			$this->success('恭喜您邮件认证成功！', U('set/mail'));
		}

    }

	public function maps(){
		  $getIp=get_client_ip();
		  if($getIp='127.0.0.1'){
				$getIp= '114.96.185.64';
		  }
		  $content = file_get_contents("http://api.map.baidu.com/location/ip?ak=7b92b3afff29988b6d4dbf9a00698ed8&ip={$getIp}&coor=bd09ll");
		  $json = json_decode($content);
        $lat = $this->_get('lat','htmlspecialchars');
        $lng = $this->_get('lng','htmlspecialchars');
        
        $this->assign('lat' , $lat ? $lat : $json->{'content'}->{'point'}->{'y'});
        $this->assign('lng' , $lng ? $lng : $json->{'content'}->{'point'}->{'x'});
        $this->display();
    }
    
}
