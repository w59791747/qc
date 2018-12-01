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
    protected $bizs = array();
    protected $template_setting = array();
    protected $city = array();

    protected function _initialize() {

        define('__HOST__', 'http://' . $_SERVER['HTTP_HOST']);
        $this->_CONFIG = D('Setting')->fetchAll();
		import('ORG/Net/IpLocation');
        $IpLocation = new IpLocation('UTFWry.dat'); 
        $result = $IpLocation->getlocation($_SERVER['REMOTE_ADDR']);
        $this->city = $result['country'];

        $this->uid =  getUid();
		if($_POST && empty($this->uid)){
			$this->chuangError('请先登录');
		}
        if (empty($this->uid)) {
            header("Location: " . U('home/passport/login'));
            die;
        }
		D('Project')->change_status();
		
        $user = D('Member')->find($this->uid);
		$this->member = D('Member')->_format_row($user);
        if(empty($this->member)){
            header("Location: " . U('home/passport/login'));
            die;
        }

		$Articlecate = D('Articlecate')->where(array('parent_id'=>111,'hidden'=>0))->order(array('orderby'=>'asc'))->limit(2)->select();
		foreach($Articlecate as $k => $v){
			$Articlecate[$k]['list'] = D('Article')->where(array('cate_id'=>$v['cate_id'],'closed'=>0,'audit'=>1))->limit(3)->select();
		}

		D('Project')->change_status();
		
		$this->assign('Articlecate', $Articlecate);
		$this->_DAOHANG = D('Daohang')->order(array('orderby'=>'asc'))->where(array('is_hidden'=>'1'))->select();

        $this->getTemplateTheme();
        $this->assign('DAOHANG', $this->_DAOHANG);
		
        $this->assign('CONFIG', $this->_CONFIG);
        $this->assign('MEMBER', $this->member);
        $this->assign('today', TODAY);
        $this->assign('city',$city);
		$this->assign('fromlist', D('Member')->_from_list()); 
        $this->assign('ctl', strtolower(MODULE_NAME));
        $this->assign('act', ACTION_NAME);
        $this->assign('nowtime', NOW_TIME);
		


		$stime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$ltime = time();
		$Membernew = D('Membernew');
		$Order = D('Order');
		$follow = D('Projectfollow');
		$map['dateline'] = array('between',array($stime,$ltime));
		$map['uid'] = $this->uid;
		$count1 = $Membernew->where($map)->count();
		$count2 = $Order->where($map)->count();
		$count3 = $follow->where($map)->count();
		$this->assign('count01',$count1);
		$this->assign('count02',$count2);
		$this->assign('count03',$count3);
        $this->getTemplateTheme();
    }

    
    

    private function tmplToStr($str, $datas) {
        return tmplToStr($str, $datas);
    }

    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {

        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }

	public  function arrkey($arr,$key){
		if($arr){
			foreach($arr as $k => $v){
				$arr1[$v[$key]] = $v;
			}
		}
		return $arr1;
	}

	//参数1:遍历数组  参数2 统计字段  参数3 表名 参数四 返回字段

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
	}

	//参数1:遍历数组(或id)  参数2 修改评论表  参数3 评论表 参数四 是否有积分

//($comment_id,'member','Membercomment','comment');
	public function audit_comment($comment_id,$table1,$table2,$role,$fenxiao,$fenxiao_role) 
	{
		$obj2= D($table2);
		$obj1 = D($table1);
		$Member = D('Member');
		if (is_numeric($comment_id) && ($comment_id = (int) $comment_id)) {
			$detail = $obj2->find($comment_id);
			if($detail['audit'] == 1){
				return false;
			}else{
				$obj2->save(array('comment_id'=>$comment_id,'audit'=>'1'));
				if($table1 == 'Project'){
					$obj2->update_project($detail['project_id'],1,$detail['score']);
				}elseif($table2 == 'Verify'){
					$obj2->save(array('uid' => $comment_id, 'audit' => 1));
				}elseif($table1 == 'Article'){
					$obj2->update_article($detail['article_id'],1,$detail['score']);
				}else{
					$obj2->update_member($detail['comment_uid'],1,$detail['score']);
				}
				if ($this->_CONFIG['integral'][$role] > 0){
					$Member->updateCount($detail['uid'],'gold',$this->_CONFIG['integral'][$role]);
					$data['uid'] = $detail['uid'];
					$data['from'] = 'gold';
					$data['number'] = $this->_CONFIG['integral'][$role];
					if($table1 == 'Project'){
						$data['log'] = '项目审核通过,返积分';
					}elseif($table2 == 'Verify'){
						$data['log'] = '实名验证通过,返积分';
					}else{
						$data['log'] = '评论审核通过,返积分';
					}
					$data['dateline'] = time();
					D('Memberlog')->add($data);
					if($fenxiao>0){
						$Member->updateCount($fenxiao,'gold',$this->_CONFIG['integral'][$fenxiao_role]);
						$datas['uid'] = $fenxiao;
						$datas['from'] = 'gold';
						$datas['number'] = $this->_CONFIG['integral'][$fenxiao_role];
						$datas['log'] = '分销对方实名验证审核通过,返积分';
						$datas['dateline'] = time();
						D('Memberlog')->add($datas);
					}
					return true;
				}
			}
		}else{

			foreach($comment_id as $k => $v){
				$detail = '';
				$detail = $obj2->find($v);
				if($detail['audit'] == 1){
					continue;
				}else{
					$obj2->save(array('comment_id'=>$v,'audit'=>'1'));
					if($table1 == 'Project'){
						$obj2->update_project($detail['project_id'],1,$detail['score']);
					}elseif($table1 == 'Article'){
						$obj2->update_article($detail['article_id'],1,$detail['score']);
					}else{
						$obj2->update_member($detail['comment_uid'],1,$detail['score']);
					}
					$comments += 1;
					if ($this->_CONFIG['integral'][$role] > 0){
						$Member->updateCount($detail['uid'],'gold',$this->_CONFIG['integral'][$role]);
						$data['uid'] = $detail['uid'];
						$data['from'] = 'gold';
						$data['number'] = $this->_CONFIG['integral'][$role];
						if($table1 == 'Project'){
							$data['log'] = '项目审核通过,返积分';
						}elseif($table2 == 'Verify'){
							$data['log'] = '实名验证通过,返积分';
						}else{
							$data['log'] = '评论审核通过,返积分';
						}
						$data['dateline'] = time();
						D('Memberlog')->add($data);
						if($fenxiao>0){
							$Member->updateCount($fenxiao,'gold',$this->_CONFIG['integral'][$fenxiao_role]);
							$datas['uid'] = $fenxiao;
							$datas['from'] = 'gold';
							$datas['number'] = $this->_CONFIG['integral'][$fenxiao_role];
							$datas['log'] = '分销对方实名验证审核通过,返积分';
							$datas['dateline'] = time();
							D('Memberlog')->add($datas);

						}
					}
				}
			}
			if($comments > 0 ){
				return true;
			}else{
				return false;
			}
			
		}
	}

	
	public function audit_delete($comment_id,$table1,$table2,$role)
	{
		$obj2= D($table2);
		$obj1 = D($table1);
		if (is_numeric($comment_id) && ($comment_id = (int) $comment_id)) {
			$detail = $obj2->find($comment_id);
			if($detail['audit'] != 1){
				$obj2->delete($comment_id);
				return true;
			}else{
				$obj2->delete($comment_id);
				if($table1 == 'Project'){
					$obj2->update_project($detail['project_id'],-1,-$detail['score']);
				}elseif($table1 == 'Article'){
					$obj2->update_article($detail['article_id'],-1,-$detail['score']);
				}else{
					$obj2->update_member($detail['comment_uid'],-1,-$detail['score']);
				}
				return true;
			}
		}else{

			foreach($comment_id as $k => $v){
				$detail = '';
				$detail = $obj2->find($v);
				if($detail['audit'] != 1){
					$obj2->delete($v);
					continue;
				}else{
					$obj2->delete($v);
					if($table1 == 'Project'){
						$obj2->update_project($detail['project_id'],-1,-$detail['score']);
					}elseif($table1 == 'Article'){
						$obj2->update_article($detail['article_id'],-1,-$detail['score']);
					}else{
						$obj2->update_member($detail['comment_uid'],-1,-$detail['score']);
					}
					$comments += 1;
				}
			}
			if($comments > 0 ){
				return true;
			}else{
				return true;
			}
		}
	}

	public function audit_yuyue($yuyue_id,$table1,$table2,$role,$data)
	{
		$obj2= D($table2);
		$obj1 = D($table1);
		$Member = D('Member');
		if (is_numeric($yuyue_id) && ($yuyue_id = (int) $yuyue_id)) {
			$detail = $obj2->find($yuyue_id);
			if($detail['audit'] == 1){
				return false;
			}else{
				$obj2->save(array('yuyue_id'=>$yuyue_id,'audit'=>'1','admin_id'=>$data['admin_id'],'gold'=>$data['gold']));
				//$obj1->updateCount($detail['uid'],'yuyue_count',1);
				if ($this->_CONFIG['integral'][$role] > 0){
					$obj1->updateCount($detail['uid'],'gold',$this->_CONFIG['integral'][$role]);
					$data['uid'] = $detail['uid'];
					$data['from'] = 'gold';
					$data['number'] = $this->_CONFIG['integral'][$role];
					$data['log'] = '预约审核通过,返积分';
					$data['dateline'] = time();
					D('Memberlog')->add($data);
					return true;
				}
			}
		}
	}


    private function parseTemplate($template = '') {

        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        // 获取当前主题名称
        $theme = $this->getTemplateTheme();
        define('NOW_PATH',BASE_PATH.'/themes/'.$theme.'Member/');
        // 获取当前主题的模版路径
        define('THEME_PATH', BASE_PATH . '/themes/default/Member/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/'.$theme.'Member/');
     
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
