<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_brokerage extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('finance_model');
	}
	
	public function list_brokerage($page=1) {
		
		$data = $this->finance_model->list_brokerage($page);
		$base_url = "/cw_brokerage/list_brokerage";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$houses = $this->basic_model->get_house_list();
		$this->assign('houses', $houses);
		
		$this->show('finance/list_brokerage');
	}
	
	public function view_brokerage($id, $key=0) {
		
		$data = $this->finance_model->get_brokerage($id);
		$this->assign('data', $data);
		
		$this->assign('key', $key);
		
		$this->show('finance/view_brokerage');
	}
}