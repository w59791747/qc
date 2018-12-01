<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class SensitiveModel extends CommonModel{
    protected $pk   = 'words_id';
    protected $tableName =  'sensitive_words';
    protected $token = 'sensitive_words';
    protected $cacheTime = 8640000;//100天


    //return false  表示正常，否则会返回对应的敏感词
    public function checkWords($content){
        $words = $this->fetchAll();
        foreach($words as $val){
            $arr[] = $val['words'];
        }
		$c = str_replace($arr,'***',$content);
        return $c;     
    }
    
}