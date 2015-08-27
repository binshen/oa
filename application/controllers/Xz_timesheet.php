<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_timesheet extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('executive_model');
	}
	
	public function index() {
		$this->assign('pager', NULL);
		$this->assign('data', NULL);
		$this->show('executive/list_timesheet');
	}
	
	public function list_timesheet($page=1) {
		$data = $this->executive_model->list_timesheet($page);
		$base_url = "/xz_timesheet/list_timesheet";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('executive/list_timesheet');
	}
	
	public function import_timesheet() {
		$this->show('executive/import_timesheet');
	}
	
	public function upload_timesheet() {
		require_once (APPPATH . 'libraries/PHPExcel/PHPExcel.php');
		
		$file = $_FILES['uploadFile'];
		$name = $file['name'];
//		$size = $file['size'];
//		$type = $file['type'];
		$path = $file['tmp_name'];
		$uploadFile = "upload/timesheet/" . mb_convert_encoding($name, "gbk", "utf-8");
		move_uploaded_file($path, $uploadFile);
		
 		$PHPExcel = new \PHPExcel();
 		$PHPReader = new \PHPExcel_Reader_Excel2007();
 		
 		//为了可以读取所有版本Excel文件
 		if(!$PHPReader->canRead($uploadFile))
 		{
 			$PHPReader = new \PHPExcel_Reader_Excel5();
 			if(!$PHPReader->canRead($uploadFile))
 			{
 				echo '未发现Excel文件！';
 				return;
 			}
 		}
 		
 		//不需要读取整个Excel文件而获取所有工作表数组的函数，感觉这个函数很有用，找了半天才找到
 		$sheetNames  = $PHPReader->listWorksheetNames($uploadFile);
 		
 		//读取Excel文件
 		$PHPExcel = $PHPReader->load($uploadFile);
 		
 		//获取工作表的数目
 		$sheetCount = $PHPExcel->getSheetCount();
 		
 		//选择第一个工作表
 		$currentSheet = $PHPExcel->getSheet(0);
 		
 		//取得一共有多少列
 		$allColumn = $currentSheet->getHighestColumn();
 		
 		//取得一共有多少行
 		$allRow = $currentSheet->getHighestRow();
 		
 		//循环读取数据，默认编码是utf8，这里转换成gbk输出
 		for($currentRow = 2;$currentRow<=$allRow;$currentRow++) 
 		{
 			$column1 = trim($currentSheet->getCell("A".$currentRow)->getValue());
 			$column2 = trim($currentSheet->getCell("B".$currentRow)->getValue());
 			$column3 = trim($currentSheet->getCell("C".$currentRow)->getValue());
 			$column4 = trim($currentSheet->getCell("D".$currentRow)->getValue());
 			$column5 = trim($currentSheet->getCell("E".$currentRow)->getValue());
 			$column6 = trim($currentSheet->getCell("F".$currentRow)->getValue());
 			$column7 = trim($currentSheet->getCell("G".$currentRow)->getValue());
 			$column8 = trim($currentSheet->getCell("H".$currentRow)->getValue());
 			$column9 = trim($currentSheet->getCell("I".$currentRow)->getValue());
 			
 			$data = array(
 				'dept' => $column1,
 				'name' => $column2,
 				'code' => $column3,
 				'time' => $column4,
 				'status' => $column5,
 				'machine' => $column6,
 				'num' => $column7,
 				'method' => $column8,
 				'card' => $column9,
 				'import_uid' => $this->session->userdata('user_info')['id'],
 				'uid' => null,
 				'cdate' => date('Y-m-d H:i:s')
 			);
 			$this->executive_model->save_timesheet($data);
 		}
 		
 		$this->list_timesheet(1);
	}
}