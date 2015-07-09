<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_leave extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('document_model');
	}
	
	public function list_leave($page=1) {
		$data = $this->document_model->list_leave($page);
		$base_url = "/basic_department/list_department";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		$this->show('/mine/list_leave');
	}
	
	public function add_leave() {
		$this->show('mine/add_leave');
	}
}