<?php
/*
 * 软件为合肥注创科技出品，未经授权许可不得使用！
 * 作者：zhuchuang团队
 * 官网：www.izhuchuang.com
 * 邮件: 849171265@qq.com.com  QQ 849171265
 */


class OrderAction extends CommonAction {

    public function index() {
        $obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类

        $name = $this->_param('name','htmlspecialchars');
		 $beizhu = $this->_param('beizhu','htmlspecialchars');
		$where = " and a.closed IN (0,1,2)";
		if($name){
			$where .= " and b.name LIKE '%".$name."%'";
		}
		if($beizhu){
			$where .= " and a.note LIKE '%".$beizhu."%'";
		}
		$this->assign('beizhu',$beizhu);
		$this->assign('name',$name);
        if($type = $this->_param('type','htmlspecialchars')){
			if($type != 1){
			   $where .= " and a.type ='".$type."'";
			}
            $this->assign('type',$type);
        }
		
		if($status = $this->_param('status','htmlspecialchars')){
			if($status != 1){
				$S = $status-3;
				 $where .= " and a.pay_status =".$S;
			}
            $this->assign('status',$status);
        }
        $count = count($obj->query("select a.*,b.* from ".$obj->getTableName()." as a,".D('Member')->getTableName()." as b where a.uid=b.uid ".$where));
		$orderby = "a.dateline desc";

       // $count = $obj->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $obj->query("select a.*,b.* from ".$obj->getTableName()." as a,".D('Member')->getTableName()." as b where a.uid=b.uid ".$where." ORDER BY ".$orderby." limit ".$Page->firstRow.','.$Page->listRows);
        foreach($list as $k=>$val){
			$lists[$k] = $obj->_format_row($val);
        }
		$this->itemsby($list,'uid','Member','memberlist');
        $this->assign('list', $lists); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
		$this->assign('typelist', $obj->getType());
		$this->assign('statuslist', $obj->status());
		$this->assign('payment', D('Payment')->getPayments());
		
        $this->display(); // 输出模板
    }

	public function audit($order_id)
	{
		 $obj = D('Order');
		 if ($order_id = (int) $order_id) {
            if (!$trade = $obj->find($order_id)) {
                $this->chuangError('请选择要审核的订单');
            }else if(!$log = D('Paymentlogs')->getLogsByOrderno($trade['order_no'])){
				 $this->chuangError('支付的订单不存在');
			}else{
				if($log['payed'] == '1'){
					 $this->chuangError('该订单已支付过了');
				}else{
					D('Paymentlogs')->set_payed($log['log_id']);
					D('Order')->set_payed($log, $trade);
					$order = D('Order')->find($log['order_id']);
					D('Memberlog')->change_status($order,$code);
					
					if($log['from'] == 'crowd'){
						$Goodslist = D('Goodslist');
						$where['uid'] = $log['uid'];
						$where['list_id'] = $log['product_id'];
						$data['is_pay'] = 1;
						$data['pay_time'] = NOW_TIME;
						$Goodslist->where($where)->save($data);
						$lists = $Goodslist->find($log['product_id']);

						D('Project')->updateCount($lists['project_id'],'have_num',1);
						D('Project')->updateCount($lists['project_id'],'have_price',$log['amount']);
						$Project_detail = D('Project')->find($lists['project_id']);
						if($Project_detail['have_price'] >=  $Project_detail['fund_raising']){
							D('Project')->where(array('project_id'=>$lists['project_id']))->save(array('status'=>'2'));
						}

						$lists_goods = $Goodslist->where(array('project_id'=>$lists['project_id']))->select();
						foreach($lists_goods as $k => $v){
							$member = D('Member')->find($v['uid']);
							D('Sms')->sendSms('sms_chouzi', $member['mobile'], array('title'=>$Project_detail['title']));
						}
					}
					$this->chuangSuccess('审核通过', U('order/index'));
				}
			}
		}
	}


	

	public function detail($order_id = 0)
	{
		$obj = D('Order');
        if ($order_id = (int) $order_id) {
            if (!$detail = $obj->find($order_id)) {
                $this->error('请选择要查看的订单');
            }
			$this->assign('detail', $obj->_format_row($detail));
			$this->assign('typelist', $obj->getType());
			$this->assign('member', D('Member')->find($detail['uid']));
			$this->assign('statuslist', $obj->status());
			$this->display();
            
        } else {
            $this->error('请选择要查看的订单');
        }
	}

    public function delete($order_id = 0) {
        if (is_numeric($order_id) && ($order_id = (int) $order_id)) {
            $obj = D('Order');
            $obj->delete($order_id);
            $this->chuangSuccess('删除成功！', U('order/index'));
        } else {
            $order_id = $this->_post('order_id', false);
            if (is_array($order_id)) {
                $obj = D('Order');
                foreach ($order_id as $id) {
                    $obj->delete($id);
                }
                $this->chuangSuccess('删除成功！', U('order/index'));
            }
            $this->error('请选择要删除的会员');
        }
    }

public function dayin($name='',$type='1',$status='1')
	{

		$obj = D('Order');
        import('ORG.Util.Page'); // 导入分页类

		$where = " and a.closed IN (0,1,2)";
		if($name){
			$where .= " and b.name LIKE '%".$name."%'";
		}
		if($type != 1){
		   $where .= " and a.type ='".$type."'";
		}
        
		
		if($status != 1){
			$s = $status-3;
			$where .= " and a.pay_status =".$s;
		}
		$orderby = "a.dateline desc";

        $list = $obj->query("select a.*,b.* from ".$obj->getTableName()." as a,".D('Member')->getTableName()." as b where a.uid=b.uid ".$where." ORDER BY ".$orderby);
        foreach($list as $k=>$val){
			$lists[$k] = $obj->_format_row($val);
        }

		$typelist = $obj->getType();
		$statuslist = $obj->status();
		$payment = D('Payment')->getPayments();
		

		vendor("phpexcel.Classes.PHPExcel"); 

		 $objPHPExcel = new PHPExcel();  
        // Set properties  
        $objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
  
        //set width  
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
  
        //设置行高度  
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
  
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
        //set font size bold  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);  
  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  
        //设置水平居中  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
		$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  
        //合并cell  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');  
  
        // set table header content  
        $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1', '订单数据汇总  时间:'.date('Y-m-d H:i:s'))  
            ->setCellValue('A2', '订单ID')  
            ->setCellValue('B2', '订单号')  
            ->setCellValue('C2', '客户名称')  
            ->setCellValue('D2', '类型')  
            ->setCellValue('E2', '支付方式')  
            ->setCellValue('F2', '备注')  
            ->setCellValue('G2', '总金额')  
            ->setCellValue('H2', '支付状态')
			->setCellValue('I2', '支付时间');
  
        // Miscellaneous glyphs, UTF-8  
		foreach($lists  as $i => $v){
			if($v['log']['payment'] == 'bank'){
				$payment[$v['log']['payment']]['name'] = '银行卡支付';
			}elseif($v['log']['payment'] == 'alipay'){
				$payment[$v['log']['payment']]['name'] = '支付宝支付';
			}elseif($v['log']['payment'] == 'wxpay'){
				$payment[$v['log']['payment']]['name'] = '微信支付';
			}


			$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $v['order_id']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), ' '.$v['order_no']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $v['name']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $typelist[$v['type']]); //这里调用了common.php的时间戳转换函数  
            $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $payment[$v['log']['payment']]['name']); 
			$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), $v['note']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), $v['amount']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+3), $statuslist[$v['pay_status']]);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('I'.($i+3), date('Y-m-d H:i:s',$v['log']['payedtime']));  
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':J'.($i+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':J'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
            $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);  

		}
  
  
        //  sheet命名  
        $objPHPExcel->getActiveSheet()->setTitle('订单汇总表');  
  
  
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet  
        $objPHPExcel->setActiveSheetIndex(0);  
  
  
        // excel头参数  
        header('Content-Type: application/vnd.ms-excel');  
        header('Content-Disposition: attachment;filename="订单汇总表('.date('Ymd-His').').xls"');  //日期为文件名后缀  
        header('Cache-Control: max-age=0');  
  
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式  
        $objWriter->save('php://output');  
	}
   
}