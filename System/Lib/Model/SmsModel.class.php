<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class SmsModel extends CommonModel{
    protected $pk   = 'sms_id';
    protected $tableName =  'sms';
    protected $token  = 'chuang_sms';
    
    public function sendSms($code,$mobile,$data){
		if($code ==  'sms_reg'){
			$logsms = true;
		}else{
			$logsms = D('Smslog')->check_sms($mobile);
		}
		if($logsms !== true){
			return $logsms;
		}else{	
			$tmpl = $this->fetchAll();
			if(!empty($tmpl[$code]['is_open'])){
				$content = $tmpl[$code]['sms_tmpl'];
				$config = D('Setting')->fetchAll();
				$data['sitename'] = $config['site']['sitename'];
				$data['tel']      = $config['site']['tel'];
				foreach($data as $k=>$val){
					$val = str_replace('【', '', $val);
					$val = str_replace('】', '', $val);
					$content =  str_replace('{'.$k.'}', $val, $content);
				}
				if(is_array($mobile)){
					$mobile = join(',',$mobile);
				}
			  
				
				$logs['mobile'] = $mobile;
				$logs['content'] = $content;
				$logs['clientip'] = get_client_ip();
				$logs['dateline'] = NOW_TIME;
				$log_id = D('Smslog')->add($logs);
				if($config['sms']['charset']){
					$content = auto_charset($content,'UTF8','gbk');
				}
				$local = array(
					'mobile'    => $mobile,
					'content'   => $content
				);
				$http = tmplToStr($config['sms']['url'], $local);
				$res = file_get_contents($http);


				if($res == '100'){
					$log['log_id'] = $log_id;
					$log['status'] = '1';
					D('Smslog')->save($log);
					return true;
				}else{
					return false;
				}
				
			}
		}
        return false;
    }

	
    
     public function fetchAll(){
        $cache = cache(array('type'=>'File','expire'=>  $this->cacheTime));
        if(!$data = $cache->get($this->token)){
            $result = $this->order($this->orderby)->select();
            $data = array();
            foreach($result  as $row){
                $data[$row['sms_key']] = $row;
            }
            $cache->set($this->token,$data);
        }   
        return $data;
     }
  
}