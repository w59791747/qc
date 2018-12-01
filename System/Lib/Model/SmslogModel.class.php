<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class SmslogModel extends CommonModel{
    protected $pk   = 'log_id';
    protected $tableName =  'sms_log';
    
	public function lasttime_by_mobile($mobile)
    {
        $sql = "SELECT dateline FROM ".$this->getTableName()." WHERE mobile='$mobile' ORDER BY log_id DESC LIMIT 1";
        return $this->query($sql);
    }

	public function check_sms($mobile)
    {
		$config = D('Setting')->fetchAll();
		$time = NOW_TIME;
		if($sms_count = (int)$config['power']['fabu_sms']){
			$map['mobile'] = $mobile;
			$map['dateline'] = array('gt',$time-86400);
            if($sms_count < $this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一手机24小时只能发'.$sms_count.'条短信');
                return $error;
            }
        }
        if($sms_time = (int)$config['power']['sms_jiange']){
            $time = $time - $sms_time*60;
			$map['mobile'] = $mobile;
			$map['dateline'] = array('gt',$time);
            if($this->where($map)->count()){
				$error = array('status'=>'error','msg'=>'同一手机两条短信不能少于'.$sms_time.'分钟');
                return $error;
            }
        }
        return true;
    }
}