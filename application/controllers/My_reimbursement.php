<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_reimbursement extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('document_model');
	}
	
	public function list_reimbursement($page=1) {
		
		$this->show('/mine/list_reimbursement');
	}
	
	public function add_reimbursement() {
	
		$this->show('/mine/add_reimbursement');
	}
}