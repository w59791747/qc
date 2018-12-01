<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class PublicAction extends CommonAction {
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