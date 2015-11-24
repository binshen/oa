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
		
		$this->show('finance/list_month_reports');
	}
}