<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_statistic extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
 		$this->load->library('pagination');
		$this->load->model('finance_model');
	}

	public function index() {
		
		$houseList = $this->finance_model->get_house_list();
		$this->assign('houseList', $houseList);
		
		$userList = $this->finance_model->get_user_list();
		$this->assign('userList', $userList);
		
		$this->assign('statistic', array('house_id'=>null, 'user_id'=>null, 'status'=>null));
		
		$data = $this->finance_model->list_statistic(0);
// 		$base_url = "/cw_statistic/list_statistic";
// 		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
// 		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('finance/list_statistic');
	}
	
	public function list_statistic($page=1) {
		
		$houseList = $this->finance_model->get_house_list();
		$this->assign('houseList', $houseList);
		
		$userList = $this->finance_model->get_user_list();
		$this->assign('userList', $userList);
		
		$this->assign('statistic', array('house_id'=>null, 'user_id'=>null, 'status'=>null));
		
		$data = $this->finance_model->list_statistic($page);
		$base_url = "/cw_statistic/list_statistic";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('statistic/list_statistic');
	}
	
	public function edit_statistic($id) {
		
		$statistic = $this->finance_model->get_statistic($id);
		echo json_encode($statistic);
	}
	
	public function update_statistic() {
	
		echo $this->finance_model->update_statistic();
		die;
	}
	
	public function del_statistic($id) {
		$rs = $this->finance_model->del_statistic($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('cw_statistic/list_statistic'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
	public function download_statistic() {
		require_once (APPPATH . 'libraries/PHPExcel/PHPExcel.php');
		
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->createSheet(0);
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '序号')
			->setCellValue('B1', '楼盘')
			->setCellValue('C1', '楼栋号')
			->setCellValue('D1', '房号')
			->setCellValue('E1', '成交日期')
			->setCellValue('F1', '成交金额')
			->setCellValue('G1', '业务员')
			->setCellValue('H1', '所属门店')
			->setCellValue('I1', '合同号')
			->setCellValue('J1', '付款状况')
			->setCellValue('K1', '备注');
		
		$data = $this->finance_model->list_statistic(0);
		$items = $data['items'];
		$total = 0;
		foreach ($items as $i => $d) {
			$idx = $i + 2;
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . $idx, $d['id'])
			->setCellValue('B' . $idx, $d['house_name'])
			->setCellValue('C' . $idx, $d['house_no'])
			->setCellValue('D' . $idx, $d['room_no'])
			->setCellValue('E' . $idx, $d['date'])
			->setCellValue('F' . $idx, $d['amount'])
			->setCellValue('G' . $idx, $d['user_name'])
			->setCellValue('H' . $idx, $d['store_info'])
			->setCellValueExplicit('I' . $idx, $d['contract_no'], \PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('J' . $idx, $d['status'] == 1 ? "已付款" : "未付款")
			->setCellValueExplicit('K' . $idx, $d['remark'], \PHPExcel_Cell_DataType::TYPE_STRING);
			
			$total += $d['amount'];
		}
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . ($idx+1), '合计：')
			->setCellValue('B' . ($idx+1), $total);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		
		$objPHPExcel->getActiveSheet()->setTitle("月度统计报表");
		
		$filename = "月度统计报表.xls";
		$this->outputExcel($objPHPExcel, $filename);
	}
	
	private function outputExcel($objPHPExcel, $filename) {
	
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
	
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . $filename);
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
	
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
	
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}