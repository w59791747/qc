<?php

class EmptyAction extends CommonAction{ 
    function _empty(){ 
        header("HTTP/1.0 404 Not Found");//ʹHTTP����404״̬�� 
        $this->display("Public:404"); 
    } 

	public function index(){
       
        header("HTTP/1.0 404 Not Found");//ʹHTTP����404״̬�� 
        $this->display("public:404"); 
        //$this->error('�����ʵ�ҳ�治���ڣ�404');
    }  
} 