<?php
/**
 * Copy Right IJH.CC
 * Each engineer has a duty to keep the code elegant
 * $Id$
 */
if(!defined('__CORE_DIR')){
    exit("Access Denied");
}

class Wxpay_Redpack
{

    public function _construct($cfg)
    {
        $this->APPID = $cfg['mch_id'];
        $this->MCHID = $cfg['mch_id'];
        $this->KEY = $cfg['key'];
        $this->APPSECRET = $cfg['appsecret']
    }

    public function sendredpack($openid,$amount,$params=array())
    {
        $api = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $params = $this->format_params($params);
        $sign = $this->makesign($params);
        $params['sign'] = $sign;
        $params_xml = $this->build_xml($params);
        $xml = $this->post_xml($api, $params_xml);
        $data = $this->parse_xml($xml);
        if($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS'){
            return $data;
        }
        return false;
    }


    public function sendgroupredpack($openid,$amount,$params=array())
    {
        $api = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
        $params = $this->format_params($params);
        $params['amt_type'] = 'ALL_RAND';
        $sign = $this->makesign($params);
        $params['sign'] = $sign;
        $params_xml = $this->build_xml($params);
        $xml = $this->post_xml($api, $params_xml);
        $data = $this->parse_xml($xml);
        if($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS'){
            return $data;
        }
        return false;

    }

    public function gethbinfo($mch_billno)
    {
        $api = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';
        $params = array();
        $params['mch_id'] = $this->MCHID;
        $params['wxappid'] = $this->APPID;
        $params['mch_id'] = $this->MCHID;
        $params['bill_type'] = 'MCHT';
        $params['nonce_str'] = $this->random(32);
        $sign = $this->makesign($params);
        $params['sign'] = $sign;
        $params_xml = $this->build_xml($params);
        $xml = $this->post_xml($api, $params_xml);
        $data = $this->parse_xml($xml);
        if($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS'){
            return $data;
        }
        return false;
        
    }

    public function sendcoupon()
    {
        $api = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon';
        
    }

    protected function format_params($params)
    {
        $params['mch_id'] = $this->MCHID;
        $params['wxappid'] = $this->APPID;
        $params['mch_id'] = $this->MCHID;
        $params['re_openid'] = $openid;
        $params['total_amount'] = $amount * 100;
        if(!isset($params['total_num'])){
            $params['total_num'] = 1;
        }
        if(!isset($params['mch_billno'])){
            $params['mch_billno'] = $this->make_mch_billno();
        }
        if(!isset($params['client_ip'])){
            $params['client_ip'] = __IP;
        }
        $params['nonce_str'] = $this->random(32); 
        return $params;       
    }

    public function params_build_url($params)
    {
        ksort($params);
        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

    protected function build_xml($params)
    {
        if(!is_array($params) || count($params) <= 0){
            exit("数组数据异常！");
        }
        
        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * 将xml转为array
     * @param string $xml
     */
    public function parse_xml($xml)
    {   
        if(!$xml){
            exit("xml数据异常！");
        }
        //将XML转为array 
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }



    protected function makesign($params)
    {
        $string = $this->params_build_url($params);
        $string = $string . "&key=".$this->PAY_KEY;
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    protected function make_mch_billno()
    {
        $rand = $this->random(10, 1);
        $no = $this->mch_id.date('Ymd', __TIME).$rand;
    }


    protected function random($len, $type='', $extchars='')
    {
        switch($type){
            case 1:
                $chars= str_repeat('0123456789',3);
                break;
            case 2:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 3:
                $chars='abcdefghijklmnopqrstuvwxyz';
                break;
            case 4:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;              
            default :
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        }
        $chars .= $extchars;
        if($len>10 ) {//位数过长重复字符串一定次数
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        $chars = str_shuffle($chars);
        $charslen = strlen($chars);
        $start = mt_rand(0,$charslen-$len);
        $hash = substr($chars,$start,$len);
        return $hash;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function post_xml($url, $xml, $second = 30)
    {       
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            exit("curl出错，错误码:$error");
        }
    }

}
