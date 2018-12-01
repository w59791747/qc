<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  TemplatesettingModel extends CommonModel{
    protected $pk   = 'theme';
    protected $tableName =  'template_setting';
    protected $token = 'template_setting';
    
    public function detail($theme){
        $data = $this->fetchAll();
        return $data[$theme];
    }

    public function _format($data) {
        $data['setting'] = unserialize($data['setting']);
        return $data;
    }
    
   
    
}
