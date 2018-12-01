<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CommonAction extends Action {

    protected $uid = 0;
    protected $member = array();
    protected $_CONFIG = array();
    protected $seodatas = array();
    protected $template_setting = array();
	protected $_DAOHANG = array();
	protected $city = array();

    protected function _initialize() {
        define('__HOST__', 'http://' . $_SERVER['HTTP_HOST']);
        $this->_CONFIG = D('Setting')->fetchAll();
		import('ORG/Net/IpLocation');
        $IpLocation = new IpLocation('UTFWry.dat'); 
        $result = $IpLocation->getlocation($_SERVER['REMOTE_ADDR']);
        $this->city = $result['country'];
        
        $this->uid =  getUid();
        if (!empty($this->uid)) {
            $user = D('Member')->find($this->uid);
			$this->member = D('Member')->_format_row($user);
            if(empty($this->member)){
                setUid(0);
                $this->uid = 0;
            }
        }
		

		if (is_mobile()) {
			if(strpos($_SERVER['REDIRECT_URL'],'user') !== false){
				$atr = str_replace('user','member',$_SERVER['REDIRECT_URL']);
				header("Location:" .$this->_CONFIG['site']['host'].'/mobile'.$atr);
			}else{
				if(false!==strpos($_SERVER['REDIRECT_URL'],'home')){
					$_SERVER['REDIRECT_URL'] = str_replace('/home','',$_SERVER['REDIRECT_URL']);
				}
				header("Location:" .$this->_CONFIG['site']['host'].'/mobile'.str_replace('/index.php','',$_SERVER['REDIRECT_URL']));
			}
            die;
        }

		$fenxiao_id = $_GET['fenxiao'];
		if(!empty($fenxiao_id)){
            $member_fenxiao = D('Member')->find($fenxiao_id);
			if($member_fenxiao){
				cookie('fenxiao_id',$fenxiao_id);
			}
        }

		$Articlecate = D('Articlecate')->where(array('parent_id'=>111,'hidden'=>0))->order(array('orderby'=>'asc'))->limit(2)->select();
		foreach($Articlecate as $k => $v){
			$Articlecate[$k]['list'] = D('Article')->where(array('cate_id'=>$v['cate_id'],'closed'=>0,'audit'=>1))->limit(3)->select();
		}

		D('Project')->change_status();
		
		$this->assign('Articlecate', $Articlecate);
        $this->assign('ctl', strtolower(MODULE_NAME)); 
        $this->assign('act', ACTION_NAME);
        $this->assign('nowtime', NOW_TIME); 
        $this->assign('city',$city);

		$this->assign('from_list',D('Member')->_from_list());

		//获取导航信息

		$this->_DAOHANG = D('Daohang')->order(array('orderby'=>'asc'))->where(array('is_hidden'=>'1'))->select();

        $this->getTemplateTheme();
        $this->assign('CONFIG', $this->_CONFIG);
		$this->assign('DAOHANG', $this->_DAOHANG);
        $this->assign('MEMBER', $this->member);
        $this->assign('today', TODAY); //兼容模版的其他写法
      
    }
	
    //seo

	public function itemsby($list,$var,$table,$assign)
	{
		$obj = D($table);
		foreach($list as $k =>$v){
			$lists[$v[$var]] = $v[$var];
	    }
		if($lists){
			$$assign = $obj->itemsByIds($lists);
		}
		$this->assign($assign, $$assign);
		return $$assign;
	}

    private function seo() {
        $seo = D('Seo')->fetchAll();
        $this->seodatas['sitename'] = $this->_CONFIG['site']['sitename'];
        $this->seodatas['tel'] = $this->_CONFIG['site']['tel'];
        $key = strtolower(MODULE_NAME . '_' . ACTION_NAME);
        if (isset($seo[$key])) {
            $this->assign('seo_title', $this->tmplToStr($seo[$key]['seo_title'], $this->seodatas));
            $this->assign('seo_keywords', $this->tmplToStr($seo[$key]['seo_keywords'], $this->seodatas));
            $this->assign('seo_description', $this->tmplToStr($seo[$key]['seo_desc'], $this->seodatas));
        } else {
            $this->assign('seo_title', $this->_CONFIG['site']['title']);
            $this->assign('seo_keywords', $this->_CONFIG['site']['keyword']);
            $this->assign('seo_description', $this->_CONFIG['site']['description']);
        }
    }

    private function tmplToStr($str, $datas) {
        return tmplToStr($str, $datas);
    }

    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        $this->seo();
        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }

    private function parseTemplate($template = '') {

        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        // 获取当前主题名称
        $theme = $this->getTemplateTheme();
        
        define('NOW_PATH',BASE_PATH.'/themes/'.$theme.'Home/');
        // 获取当前主题的模版路径
        define('THEME_PATH', BASE_PATH . '/themes/default/Home/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/'.$theme.'Home/');
        // 分析模板文件规则

        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = strtolower(MODULE_NAME) . $depr . strtolower(ACTION_NAME);
        } elseif (false === strpos($template, '/')) {
            $template = strtolower(MODULE_NAME) . $depr . strtolower($template);
        }
        
        $file = NOW_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
        if(file_exists($file)){
			return $file;
		}

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
         
           if(C('TMPL_DETECT_THEME')) {// 自动侦测模板主题
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

    protected function chuangMsg($message, $jumpUrl = '', $time = 3000,$callback = '',$parent=true) {
        $parents = $parent ? 'parent.':'';
        $str = '<script>';
        $str .=$parents.'bmsg("' . $message . '","' . $jumpUrl .'","'.$time. '","'.$callback.'");';
        $str.='</script>';
        exit($str);
    }
    
    protected function chuangOpen($message, $close = true, $style) {
        $str = '<script>';
        $str .='parent.bopen("' . $message . '","' . $close .'","'.$style. '");';
        $str.='</script>';
        exit($str);
    }
    
    protected function chuangSuccess($message, $jumpUrl = '', $time = 3000, $parent = true) {
        $this->chuangMsg($message,$jumpUrl,$time,'',$parent);
    }

    protected function chuangJump($jumpUrl) {
        $str = '<script>';
        $str .='parent.jumpUrl("' . $jumpUrl . '");';
        $str.='</script>';
        exit($str);
    }

    protected function chuangErrorJump($message, $jumpUrl = '', $time = 3000) {
        $this->chuangMsg($message,$jumpUrl,$time);
    }

    protected function chuangError($message, $time = 3000, $yzm = false, $parent = true) {

        $parent = $parent ? 'parent.' : '';
        $str = '<script>';
        if ($yzm) {
            $str .= $parent . 'bmsg("' . $message . '","",' . $time . ',"yzmCode()");';
        } else {
            $str .= $parent . 'bmsg("' . $message . '","",' . $time . ');';
        }
        $str.='</script>';
        exit($str);
    }

    protected function chuangLoginSuccess() { //异步登录
        $str = '<script>';
        $str .='parent.parent.LoginSuccess();';
        $str.='</script>';
        exit($str);
    }

    protected function ajaxLogin() {
        if ($mini = $this->_get('mini')) { //如果是迷你的弹出层操作就输出0即可
            die('0');
        }
        $str = '<script>';
        $str .='parent.ajaxLogin();';
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
