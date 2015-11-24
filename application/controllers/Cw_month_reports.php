<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_month_reports extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('finance_model');
	}
	
	public function list_month_reports($page=1) {
		
		$data = $this->finance_model->list_month_reports($page);
		$base_url = "/cw_filemanage/list_month_reports";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->assign('year', $this->input->post('year'));
		$this->assign('month', $this->input->post('month'));
		
		$this->show('finance/list_month_reports');
	}
	
	public function add_month_reports(){
		
		$year = date('Y');
		$month = intval(date('m'));
		
		$this->assign('year', $year);
		$this->assign('month', $month);
		
		$this->show('finance/add_month_reports');
	}
	
	public function upload_month_reports(){
		
		$file = $_FILES['uploadFile'];
		$name = $file['name'];
		$path = $file['tmp_name'];
		$extensions = explode('.', $name);
		$uploadFile = "upload/month_reports/" . date("Ym") . "." . $extensions[1];
		move_uploaded_file($path, $uploadFile);
		
		$this->finance_model->save_month_reports($uploadFile);
		
		unset($_POST['year']);
		unset($_POST['month']);
		
		$this->list_month_reports();
	}
	
	public function delete_month_reports($id) {
		
		$this->finance_model->delete_month_reports($id);
		
		$this->list_month_reports();
	}
}