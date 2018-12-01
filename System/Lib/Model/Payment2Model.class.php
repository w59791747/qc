<?php


/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class Payment2Model extends CommonModel {

    protected $pk = 'payment_id';
    protected $tableName = 'payment2';
    protected $token = 'payment2';
    protected $types = array(
        'crowd' => '众筹',
        'gold' => '积分充值',
        'price' => '资金充值',
    );

	public function getPayments($mobile = false) {
        $datas = $this->fetchAll();
        $return = array();
        foreach ($datas as $val) {
             $return[$val['code']] = $val;
        }


        return $return;
    }

	 public function checkPayment($code) {
        $datas = $this->fetchAll();
        foreach ($datas as $val) {
            if ($val['code'] == $code){
                return $val;
			}
        }
        return false;
    }
	

	public function _format($data) {
        $data['setting'] = unserialize($data['setting']);
        return $data;
    }
}
