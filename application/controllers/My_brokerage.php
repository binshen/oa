<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_brokerage extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('finance_model');
	}
	
	public function list_brokerage($page=1) {
		
		$data = $this->finance_model->list_brokerage($page);
		$base_url = "/my_brokerage/list_brokerage";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$houses = $this->basic_model->get_house_list();
		$this->assign('houses', $houses);
		
		$this->show('/mine/list_brokerage');
	}
	
	public function add_brokerage() {
		
		$this->show('/mine/add_brokerage');
	}
}