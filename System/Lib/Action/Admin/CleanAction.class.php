<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */
class  CleanAction extends CommonAction{
    
    
    public function cache(){
        
        delFileByDir(APP_PATH.'Runtime/');
        $time = NOW_TIME - 900;//15分钟的会删除
        M("session")->delete(array('where'=>" session_expire < '{$time}' "));
        $this->success('更新缓存成功！',U('index/main'));
    }
    
}
