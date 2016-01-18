<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_reimbursement extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('finance_model');
	}
	
	public function list_reimbursement() {
		
		$reimbursement_list = $this->finance_model->get_reimbursement_list();
		$this->assign('reimbursement_list', $reimbursement_list);
		
		$this->assign('reporter_name', $this->input->post('reporter_name'));
		
		$this->show('/finance/list_reimbursement');
	}
}