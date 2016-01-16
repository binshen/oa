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
	
	public function add_reimbursement($expense_id=NULL) {
	
		$this->assign('today', date('Y-m-d'));
		
		$dept_list = $this->basic_model->get_department_list();
		$this->assign('dept_list', $dept_list);
		
		$style_list = $this->finance_model->get_expense_style_list();
		$this->assign('style_list', $style_list);
		
		$expense_list = array();
		if(!empty($expense_id)) {
			$expense_list = $this->finance_model->get_expense_list($expense_id);
			$total = 0;
			foreach ($expense_list as $expense) {
				$total += $expense['amount'];
			}
			$this->assign('total', $total);
		}
		$this->assign('expense_list', $expense_list);
		
		$this->show('/mine/add_reimbursement');
	}
	
	
	public function view_reimbursement() {
	
		$this->show('/mine/view_reimbursement');
	}
	
	public function get_reimbursement_type_list($style_id) {
		
		$type_list = $this->finance_model->get_expense_type_list($style_id);
		echo json_encode($type_list);
	}
	
	public function save_reimbursement() {
		
		var_dump($_POST);
	}
}