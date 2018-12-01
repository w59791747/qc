<?php

/* 
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */

class  TagLibCalldata extends TagLib{
    
    protected  $tags = array(
        'calldata'=> array(
            'attr'=> 'mdl,where,limit,order,debug,cache,format','close'=>1
        ),
        'callfunc'=>array(
            'attr' => 'mdl,func,debug','close' => 1
        ),
		'callfuncw'=>array(
            'attr' => 'mdl,func,debug,where','close' => 1
        )
    );
    
    
    public function _callfunc($attr,$content){
        $attr = $this->parseXmlAttr($attr);
        $attr['mdl'] = empty($attr['mdl']) ? '' : $attr['mdl'];
        $attr['func'] = empty($attr['func']) ? '' : $attr['func'];
        $attr['debug'] = $attr['debug'] ? true : false;
        if(empty($attr['mdl']) || empty($attr['func'])){
            return  '';
        }
        $str = '<?php  ';
        
         $str.=' $items = D("'.$attr['mdl'].'")->'.$attr['func'].'(); ';
  
         if($attr['debug']){           
            $str.=' print_r(D("'.$attr['mdl'].'")->getLastSql()); ';
        }
        
        $str.= ' $index=0; foreach($items  as $item): $index++; ?>';
        $str.=$content;
        $str.=' <?php endforeach; ?>';
        return $str;
    }

	public function _callfuncw($attr,$content){
        $attr = $this->parseXmlAttr($attr);
        $attr['mdl'] = empty($attr['mdl']) ? '' : $attr['mdl'];
        $attr['func'] = empty($attr['func']) ? '' : $attr['func'];
		$attr['where'] = empty($attr['where']) ? '' : $attr['where'];
        $attr['debug'] = $attr['debug'] ? true : false;
        if(empty($attr['mdl']) || empty($attr['func'])){
            return  '';
        }
        $str = '<?php  ';
        
         $str.=' $items = D("'.$attr['mdl'].'")->'.$attr['func'].'('.$attr['where'].'); ';
  
         if($attr['debug']){           
            $str.=' print_r(D("'.$attr['mdl'].'")->getLastSql()); ';
        }
        
        $str.= ' $index=0; foreach($items  as $item): $index++; ?>';
        $str.=$content;
        $str.=' <?php endforeach; ?>';
        return $str;
    }

	

    public function _calldata($attr,$content){

        $attr = $this->parseXmlAttr($attr);
        
        $attr['mdl'] = empty($attr['mdl']) ? 'Recommend' : $attr['mdl'];
		if($attr['mdl'] == 'adv'){
			$attr['mdl'] = 'Advitem';
		}else if($attr['mdl'] == 'block'){
			$attr['mdl'] = 'Blockitem';
		}
		$attr['where'] = empty($attr['where']) ? ' 1=1' : $this->parseCondition($attr['where']);
		$attr['order'] = empty($attr['order']) ? '' : $attr['order'];
		
		if($attr['mdl'] == 'Advitem'){
			$attr['where'] .= ' and audit=1  and ((`stime`=0 && `ltime`=0) || (`stime`<'.time() .'&& `ltime`>'.time().'))';
		}
		if($attr['mdl'] == 'article'){
			$m['parent_id'] = array('IN','3,111');
			$catelist = D('Articlecate')->where($m)->order(array('orderby'=>'asc'))->select();
			foreach($catelist as $k => $v){
				$arr[] = "'".$v['cate_id']."'";
			}

			$attr['where'] .= ' and cate_id not in '."(".implode(',',$arr).")";

		}
		if($attr['mdl'] == 'Blockitem'){
			$attr_l = explode(' and ',$attr['where']);
			$block_id = substr($attr_l[0],strpos($attr_l[0],'=')+1);
			$from = substr($attr_l[1],strpos($attr_l[1],'=')+1);
			//$attr['where'] = ' 1=1';
			if(strpos($attr['where'],' and')){
				$attr['where'] =  substr($attr['where'],0,strpos($attr['where'],' and'));
			}
			$attr['where'] .= ' and audit=1 and ((`stime`=0 && `ltime`=0) || (`stime`<'.time() .'&& `ltime`>'.time().'))';
			$attr['order'] = 'orderby asc';
		}
		if($attr['mdl'] == 'member'){
			$attr_l = explode(' and ',$attr['where']);
			$block_id = substr($attr_l[0],strpos($attr_l[0],'=')+1);
			$from = substr($attr_l[1],strpos($attr_l[1],'=')+1);

		}

        $attr['limit'] = empty($attr['limit']) ? '0,10' : $attr['limit'];
        //$attr['cache'] = $attr['cache'] ? (int)$attr['cache'] : 0;
		$attr['cache'] =0;
		if($attr['format'] == 'true'){
			$attr['format'] = true;
		}else{
			$attr['format'] = false;
		}
        $token = join(',',$attr );
        $str = '<?php  ';
        if($attr['cache']){
            $str.='
                $cache = cache(array(\'type\'=>\'File\',\'expire\'=> '.$attr['cache'].'));
                $token = md5("'.$token.'");   
                if(!$items= $cache->get($token)){ ';
        }
		
        $str.=' $items = D("'.$attr['mdl'].'")->where("'.$attr['where'].'")->order("'.$attr['order'].'")->limit("'.$attr['limit'].'")->select(); ';
		//$items = D($attr['mdl'])->where($attr['where'])->order($attr['order'])->limit($attr['limit'])->select(); 
		//echo  D($attr['mdl'])->getLastSql();
		 $attr['mdl'] = ucwords($attr['mdl']);
        if($attr['format']){
			
            if($attr['mdl'] == 'Blockitem'){

				$str.='
					$items = D("'.$attr['mdl'].'")->CallDataForMat($items,"'.$block_id.'","'.$from.'","'.$attr['limit'].'");
				
				';

			}else{
				$str.='
					$items = D("'.$attr['mdl'].'")->CallDataForMat($items);
				
				';
			}
        }
		

        if($attr['cache']){
            
            $str.='  
                $cache->set($token,$items);
              }      ;
            ';
        }

		
		
        $str.= ' $index=0; foreach($items  as $item){$index++; ?>';
        $str.=$content;
        $str.=' <?php } ?>';
        return $str;
    }

	
    
}