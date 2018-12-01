<?php

/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class CommonAction extends BaseAction {
    protected $_admin = array();
    protected $_CONFIG = array();
	protected $session = '';

    protected function _initialize() {
        $this->_admin = session('admin');
		$this->_CONFIG = D('Setting')->fetchAll();
		D('Project')->change_status();
		define('__HOST__', 'http://' . $_SERVER['HTTP_HOST']);
        if (strtolower(MODULE_NAME) != 'login' && strtolower(MODULE_NAME) != 'public') { //public 不受权限控制
            if (empty($this->_admin)) {
                //print_r($this->_admin);die;
                header("Location: " . U('login/index'));
                die;
            }
			$act = ACTION_NAME;
			  
			
            if ($this->_admin['role_id'] != 1) {

                $this->_admin['menu_list'] = D('RoleMaps')->getMenuIdsByRoleId($this->_admin['role_id']);
                if (strtolower(MODULE_NAME) != 'index') { //其他页面需要判断权限
                    $menu_action = strtolower(MODULE_NAME . '/' . ACTION_NAME);


                    $menu = D('Menu')->fetchAll();
                    $menu_id = 0;
                    foreach ($menu as $k => $v) {
                        if ($v['menu_action'] == $menu_action) {
                            $menu_id = (int) $k;
                            break;
                        }
                    }
                    if (empty($menu_id) || !isset($this->_admin['menu_list'][$menu_id])) {
                        $this->error('很抱歉您没有权限操作模块:' . $menu[$menu_id]['menu_name']);
                    }
                }
            }
            //权限及其他的逻辑处理
        }
        $this->_CONFIG = D('Setting')->fetchAll();
        define('__HOST__', 'http://'.$_SERVER['HTTP_HOST']);
		define('THEME_PATH', BASE_PATH . '/' . APP_NAME . '/Tpl/');
        define('APP_TMPL_PATH', __ROOT__ . '/' . APP_NAME . '/Tpl/');
		define('__ADMIN__', __ROOT__ . '/' . APP_NAME . '/Tpl/statics');
        $this->assign('CONFIG', $this->_CONFIG);
        $this->assign('admin', $this->_admin);
		$this->assign('admin_statics', __ADMIN__);
		$this->assign('ctl', strtolower(MODULE_NAME)); //主要方便调用
        $this->assign('today', TODAY); //兼容模版的其他写法
        $this->assign('nowtime', NOW_TIME);
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
		return $$assign;
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
				return false;
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

    protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }

    protected function parseTemplate($template = '') {
        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        // 获取当前主题的模版路径
        define('THEME_PATH', BASE_PATH . '/System/Tpl/');
        // 分析模板文件规则
        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = strtolower(MODULE_NAME) . $depr . strtolower(ACTION_NAME);
        } elseif (false === strpos($template, '/')) {
            $template = strtolower(MODULE_NAME) . $depr . strtolower($template);
        }
        return THEME_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
    }

    protected function chuangSuccess($message, $jumpUrl = '', $time = 3000) {
        $str = '<script>';
        $str .='parent.success("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
        $str.='</script>';
        exit($str);
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