<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  CommonAction extends  Action{
    protected  $_CONFIG = array();
    protected  $_token  = '07e9d1f8962c13a5f3366c2ca23126e0'; //默认的TOKEN
    protected  $shopdetails = array();
    protected  $weixin = null;
    protected  function _initialize(){ //SHOP_ID 为空的时候    
		
        $this->_CONFIG = D('Setting')->fetchAll();               
        define('__HOST__', 'http://'.$_SERVER['HTTP_HOST']);
        
        $this->assign('CONFIG', $this->_CONFIG);
		
        $this->assign('ctl', strtolower(MODULE_NAME)); //主要方便调用
        $this->assign('act', ACTION_NAME);

        $this->assign('today', TODAY); //兼容模版的其他写法
        $this->assign('nowtime', NOW_TIME);
		
		//微信关键字回复的时候需要使用以及微信对接的时候
		if(strtolower(MODULE_NAME) == 'index'){
		   $this->_token = $this->_get_token();
		    $this->weixin = D('Weixin');
			$this->weixin->init($this->_token); // 修改了 ThinkWechat  让他支持  主动发送微信消息
			//
			include_once "System/Lib/Action/Weixin/weixin.class.php";
			$client = new WeChatServer($this->_token);
		}

       
    }
	
	protected function wechat_client()
    {
        static $client = null;
		$weixin_a = $this->_CONFIG['weixin'];
        if($client === null){
            if(!$client = D('Weixin')->admin_wechat_client($weixin_a)){
                exit('网站公众号设置错误');
            }
        }
        return $client;
    }

    protected function weixin_jssdk()
    {
        static $jssdk = null;
        if($jssdk === null){
			include_once "System/Lib/Action/Weixin/jssdk.php";
            $jssdk = new WeixinJSSDK($this->request['weixin']['appid'], $this->request['weixin']['secret']);
        }
        return $jssdk;
    }

	 protected function access_openid($force = false)
    {

		cookie('REDIRECT_URL', $_SERVER['REDIRECT_URL']);
		//$_COOKIE['openid'] = '';
		if($_COOKIE['openid']){
			$m = D('Connect')->getConnectByOpenid('weixin', $_COOKIE['openid']);
			if($m['uid']){
				$weixin = D('Member_weixin')->where(array('openid'=>$_COOKIE['openid']))->find();
				if(!$weixin){
					$client = $this->wechat_client();
					$wx_info = $client->getUserInfoByAuth('',$_COOKIE['openid']);
					$info['nickname'] = $wx_info['nickname'];
					$info['img'] = $wx_info['headimgurl'];
					$info['unionid'] = $wx_info['unionid'];
					$info['openid'] = $wx_info['openid'];
					$info['uid'] = $m['uid'];
					$info['dateline'] = time();
					D('Member_weixin')->where(array('uid'=>$m['uid']))->delete();
					D('Member_weixin')->add($info);
				}
				  return $_COOKIE['openid'];
				  exit;
			}else{
				$openid = $_COOKIE['openid'];
				  return $openid;
			}
		}
        static $openid = null;
        if($force || $openid === null){
            if($code = $_REQUEST['code']){
                $client = $this->wechat_client();
                $ret = $client->getAccessTokenByCode($code);
                $openid = $ret['openid'];
				if(!$openid){
					
					$openid =  $ret['openid'] =  $_COOKIE['openid'];
				}
				cookie('openid', $ret['openid']);
                if($openid){
                    $m = D('Connect')->getConnectByOpenid('weixin', $ret['openid']);
                }else{
					 exit('获取授权失败1');
				}
                if($m['uid']){
					$weixin = D('Member_weixin')->where(array('openid'=>$m['open_id']))->find();
					if(!$weixin){
						$wx_info = $client->getUserInfoById($m['open_id']);
						$info['nickname'] = $wx_info['nickname'];
						$info['img'] = $wx_info['headimgurl'];
						$info['unionid'] = $wx_info['unionid'];
						$info['openid'] = $wx_info['openid'];
						$info['uid'] = $m['uid'];
						$info['dateline'] = time();
						D('Member_weixin')->add($info);

					}
                }else if($wx_info = $client->getUserInfoById($ret['openid'])){
					$data['open_id'] = $wx_info['openid'];
					$data['type'] = 'weixin';
					$data['token'] = md5(uniqid(rand(), TRUE));
					$this->thirdlogin($data);
                }
				
                cookie('openid', $openid);
            }else{
                if(!$openid = $_COOKIE['openid']){
                    $client = $this->wechat_client();
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'];
					$state = md5(uniqid(rand(), TRUE));
                    $authurl = $client->getOAuthConnectUri($url, $state, 'snsapi_userinfo');
					
                    header('Location:'.$authurl);
                    exit();
                }
                $openid = $_COOKIE['openid'];
            }
            if(!defined('WX_OPENID')){
                define('WX_OPENID', $openid);
            }       
        }
		
        if(empty($openid)){
            exit('获取授权失败');
        }
        return $openid;
    }

	private function thirdlogin($data) {
		$connect = D('Connect')->getConnectByOpenid($data['type'], $data['open_id']);
		               
		if( $tokenkey = $_COOKIE['tokenkey']){
			$Tokentable = D('Token');
			$Tokentable->where(array('token'=>$tokenkey))->save(array('status'=>'2','open_id'=>$data['open_id']));
		}

		if (empty($connect)) {
			$connect = $data;
			$connect['connect_id'] = D('Connect')->add($data);
			
		} else {
			D('Connect')->save(array('connect_id' => $connect['connect_id'], 'token' => $data['token'], 'nickname' => $data['nickname']));
		}
		if (empty($connect['uid'])) {
			if($this->uid){
				D('Connect')->save(array('connect_id' => $connect['connect_id'], 'uid' => $this->uid));
				D('Member_weixin')->save(array('openid' => $wx_info['openid'], 'img' => $wx_info['headimgurl'], 'nickname' => $wx_info['nickname']));
				if( $tokenkey = $_COOKIE['tokenkey']){
					$Tokentable = D('Token');
					$Tokentable->where(array('token'=>$tokenkey))->save(array('status'=>'3','open_id'=>$data['open_id']));
					
				}
				
				$this->success('绑定第三方登录成功', $_COOKIE['REDIRECT_URL']);
			}else{
				session('connect', $connect['connect_id']);
				header("Location: " . U('ucenter/passport/bind'));
				die;
			}
		} else {
			if( $tokenkey = $_COOKIE['tokenkey']){
				$Tokentable = D('Token');
				$Tokentable->where(array('token'=>$tokenkey))->save(array('status'=>'3','open_id'=>$data['open_id']));
			}
			setUid($connect['uid']);
			session('access', $connect['connect_id']);
			if($_COOKIE['REDIRECT_URL']){
				header("Location:" . $_COOKIE['REDIRECT_URL']);
			}else{
				header("Location:" . U('ucenter/index/index'));
			}
		}
		die;
	}

    /*protected function access_openid($force = false)
    {
        static $openid = null;
        if($force || $openid === null){
            if($code = $_REQUEST['code']){
                $client = $this->wechat_client();
                $ret = $client->getAccessTokenByCode($code);
                $openid = $ret['openid'];
                if($unionid = $ret['unionid']){
					cookie('unionid', $ret['unionid']);
                    $m = D('Memberweixin')->detail_by_unionid($unionid);
                }else if($openid){
                    $m = D('Memberweixin')->detail_by_openid($openid);
                }else{
					 exit('获取授权失败');
				}

                if($m['uid']){
                }else if($wx_info = $client->getUserInfoById($ret['openid'])){
					$data['name'] = $wx_info['nickname'];
				    $data['passwd'] = '123456';
				    $uid = D('Passport')->reg($data);
					if($uid){
						$info['nickname'] = $wx_info['nickname'];
						$info['img'] = $wx_info['headimgurl'];
						$info['unionid'] = $wx_info['unionid'];
						$info['openid'] = $wx_info['openid'];
						$info['uid'] = $uid;
						$info['dateline'] = time();
						D('Member_weixin')->add($info);

					}
                }
                cookie('openid', $openid);
            }else{
                if(!$openid = $_COOKIE['openid']){
                    $client = $this->wechat_client();
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'];
					$state = md5(uniqid(rand(), TRUE));
                    $authurl = $client->getOAuthConnectUri($url, $state, 'snsapi_userinfo');
                    header('Location:'.$authurl);
                    exit();
                }
                $unionid = $_COOKIE['unionid'];
            }
            if(!defined('WX_OPENID')){
                define('WX_OPENID', $openid);
            }
            if(!defined('WX_UNIONID')){
                define('WX_UNIONID', $unionid);
            }            
        }
        if(empty($openid)){
            exit('获取授权失败');
        }
        return $openid;
    }*/

   
    protected function _init_pagedata()
    {
        parent::_init_pagedata();
        $this->pagedata['weixinJS'] = $this->weixin_jssdk()->getSignPackage();
    }
    
 


    protected function _get_token(){     
        if(!empty($this->shop_id)){
            return $this->shopdetails['token'];
        }
        return $this->_CONFIG['weixin']['token']; 
    }
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {

        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }
//----------
      private function parseTemplate($template = '') {

        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        // 获取当前主题名称
        // 获取当前主题的模版路径
        $theme = $this->getTemplateTheme();
        
        define('NOW_PATH',BASE_PATH.'/themes/'.$theme.'Weixin/');
        // 获取当前主题的模版路径
        define('THEME_PATH', BASE_PATH . '/themes/default/Weixin/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/default/Weixin/');
        
        // 分析模板文件规则
        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = strtolower(MODULE_NAME) . $depr . strtolower(ACTION_NAME);
        } elseif (false === strpos($template, '/')) {
            $template = strtolower(MODULE_NAME) . $depr . strtolower($template);
        }
        $file = NOW_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
        if(file_exists($file)) return $file;
        return THEME_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
    }
    private function getTemplateTheme() {
        define('THEME_NAME','default');

        if ($this->theme) { // 指定模板主题
            $theme = $this->theme;
        } else {
            /* 获取模板主题名称 */
            $default = D('Template')->getDefaultTheme();
            $themes = D('Template')->fetchAll();
            if (C('TMPL_DETECT_THEME')) {// 自动侦测模板主题
                $t = C('VAR_TEMPLATE');
                if (isset($_GET[$t])) {
                    $theme = $_GET[$t];
                    cookie('think_template', $theme, 864000);
                } elseif (cookie('think_template')) {
                    $theme = cookie('think_template');
                }
                if (!isset($themes[$theme])) {
                    $theme = $default;
                }
              
            }else{
                $theme = $default;
            }
        }
        return $theme ? $theme . '/' : '';
    }
    protected function chuangSuccess($message, $jumpUrl = '', $time = 3000) {
        $str = '<script>';
        $str .='parent.success("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
        $str.='</script>';
        exit($str);
    }

    protected function chuangErrorJump($message, $jumpUrl = '', $time = 3000) {
        $str = '<script>';
        $str .='parent.error("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
        $str.='</script>';
        exit($str);
    }
	
	protected function chuangreturn($error,$message)
	{
		echo '{"success":"'.$error.'","message":"'.$message.'"}';
		
	} 

	protected function Errors($message)
	{
		echo '{"error":"2","message":"'.$message.'"}';
		
	} 

    protected function chuangError($message, $time = 3000, $yzm = false) {
        $str = '<script>';
        if ($yzm) {
            $str .='parent.error("' . $message . '",' . $time . ',"yzmCode()");';
        } else {
            $str .='parent.error("' . $message . '",' . $time . ');';
        }
        $str.='</script>';
        exit($str);
    }

    protected function checkFields($data = array(), $fields = array()) {
        foreach ($data as $k => $val) {
            if (!in_array($k, $fields)) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    protected function ipToArea($_ip) {
        return IpToArea($_ip);
    }
    
}