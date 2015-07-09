<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_leave extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
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
		$user_info = $this->session->userdata('user_info');
		$company_id = $user_info['company_id'];
		$dept_id = $user_info['dept_id'];
		$rel_name = $user_info['rel_name'];
		
		$company = $this->basic_model->get_company($company_id);
		$department = $this->basic_model->get_department($dept_id);
		$this->assign('user', $rel_name);
		$this->assign('company', $company['name']);
		$this->assign('department', $department['name']);
		
		$departments = $this->basic_model->get_department_list();
		$this->assign('departments', $departments);
		
		$this->show('mine/add_leave');
	}
}