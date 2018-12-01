<?php

class EmptyAction extends CommonAction{ 
    function _empty(){ 
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
        $this->display("Public:404"); 
    } 

	public function index(){
       
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
        $this->display("public:404"); 
        //$this->error('您访问的页面不存在！404');
    }  
} 