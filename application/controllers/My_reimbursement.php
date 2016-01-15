<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_reimbursement extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('finance_model');
	}
	
	public function list_reimbursement($page=1) {
		
		$this->show('/mine/list_reimbursement');
	}
	
	public function add_reimbursement() {
	
		$style_list = $this->finance_model->get_expense_style_list();
		$this->assign('style_list', $style_list);
		
		$this->show('/mine/add_reimbursement');
	}
	
	
	public function view_reimbursement() {
	
		$this->show('/mine/view_reimbursement');
	}
	
	public function get_reimbursement_type_list($style_id) {
		
		$type_list = $this->finance_model->get_expense_type_list($style_id);
		echo json_encode($type_list);
	}
}