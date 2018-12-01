<?php

class  UploadAction extends  CommonAction{
    public function upload() {

        $model = $this->_get('model');
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $name = date('Y/m/d', NOW_TIME);
        $dir = BASE_PATH . '/attachs/' . $name . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $upload->savePath = $dir; // 设置附件上传目录
        if ($this->_CONFIG['attachs'][$model]) {
            $upload->thumb = true;
			$upload->thumbPrefix = 'thumb_';
			list($w, $h) = explode('*', $this->_CONFIG['attachs'][$model]);
			$upload->thumbMaxWidth = $w;
			$upload->thumbMaxHeight = $h;
            
        }
        if (!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        } else {// 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            if (!empty($this->_CONFIG['attachs'][$model]['water'])) {
                import('ORG.Util.Image');
                $Image = new Image();
                $Image->water(BASE_PATH . '/attachs/' . $name . '/thumb_' . $info[0]['savename'], BASE_PATH . '/attachs/' . $this->_CONFIG['attachs']['water']);
            }
            if ($upload->thumb) {
                echo $name . '/thumb_' . $info[0]['savename'];
            } else {
                echo $name . '/' . $info[0]['savename'];
            }
        }
        die;
    }
    public function uploadify() {
		
		

		

        $model = $this->_get('model');
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $name = date('Y/m/d', NOW_TIME);
        $dir = BASE_PATH . '/attachs/' . $name . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $upload->savePath = $dir; // 设置附件上传目录
		
        if ($this->_CONFIG['attachs'][$model]) {
            $upload->thumb = true;
			$upload->thumbPrefix = 'thumb_';
			list($w, $h) = explode('*', $this->_CONFIG['attachs'][$model]);
			$upload->thumbMaxWidth = $w;
			$upload->thumbMaxHeight = $h;
            
        }
        if (!$upload->upload()) {// 上传错误提示错误信息
            var_dump($upload->getErrorMsg());
        } else {// 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            if(!empty($this->_CONFIG['attachs']['water'])){
                import('ORG.Util.Image');
                $Image = new Image();
                $Image->water(BASE_PATH . '/attachs/'. $name . '/thumb_' . $info[0]['savename'],BASE_PATH . '/attachs/'.$this->_CONFIG['attachs']['water']);
            }
            if($upload->thumb){
                echo $name . '/thumb_' . $info[0]['savename'];
            }else{
                echo $name . '/' . $info[0]['savename'];
            }
        }
    }

    public function editor() {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $name = date('Y/m/d', NOW_TIME);
        $dir = BASE_PATH . '/attachs/editor/' . $name . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $upload->savePath = $dir; // 设置附件上传目录

		$editor_thumb = $this->_CONFIG['attachs']['editor_thumb'];
        
        if ($editor_thumb) {      
            $upload->thumb = true;
            $upload->thumbPrefix = 'thumb_';
            $upload->thumbType = 0; //不自动裁剪
            list($w, $h) = explode('*', $this->_CONFIG['attachs']['editor_thumb']);
            $upload->thumbMaxWidth = $w;
            $upload->thumbMaxHeight = $h;
        }
        if (!$upload->upload()) {// 上传错误提示错误信息
            var_dump($upload->getErrorMsg());
        } else {// 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
             if(!empty($this->_CONFIG['attachs']['water'])){
                import('ORG.Util.Image');
                $Image = new Image();

				if($editor_thumb){
              
                 $Image->water(BASE_PATH . '/attachs/editor/'. $name . '/thumb_' . $info[0]['savename'],BASE_PATH . '/attachs/'.$this->_CONFIG['attachs']['water']);
				}else{
				$Image->water(BASE_PATH . '/attachs/editor/'. $name.'/' . $info[0]['savename'],BASE_PATH . '/attachs/'.$this->_CONFIG['attachs']['water']);

				}




				
            }
			if ($editor_thumb) {
				$return = array(
					'url' => $name . '/thumb_' . $info[0]['savename'],
					'originalName' => $name . '/thumb_' . $info[0]['savename'],
					'name' => $name . '/thumb_' . $info[0]['savename'],
					'state' => 'SUCCESS',
					'size' => $info['size'],
					'type' => $info['extension'],
				);
			}else{
				$return = array(
					'url' => $name . '/' . $info[0]['savename'],
					'originalName' => $name . '/' . $info[0]['savename'],
					'name' => $name . '/' . $info[0]['savename'],
					'state' => 'SUCCESS',
					'size' => $info['size'],
					'type' => $info['extension'],
				);
			}
            echo json_encode($return);
        }
    }

	 


	 public function files() {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->allowExts = array('doc', 'docx'); // 设置附件上传类型
        $name = date('Y/m/d', NOW_TIME);
        $dir = BASE_PATH . '/attachs/other/' . $name . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $upload->savePath = $dir; // 设置附件上传目录
        
        
        if (!$upload->upload()) {// 上传错误提示错误信息
            var_dump($upload->getErrorMsg());
        } else {// 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            
			$return = 'other/'.$name . '/'. $info[0]['savename'];
            echo $return;
        }
    }

    
  
}