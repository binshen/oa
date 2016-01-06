<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_statistic extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
 		$this->load->library('pagination');
		$this->load->model('finance_model');
	}

	public function index($page=1) {
		
		
	}
	
	public function list_statistic($page=1) {
		
		$data = $this->finance_model->list_statistic($page);
		$base_url = "/cw_statistic/list_statistic";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('statistic/list_statistic');
	}
}