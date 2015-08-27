<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_overtime extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('document_model');
	}
	
	public function list_overtime($page=1) {
		$data = $this->document_model->list_overtime($page);
		$base_url = "/my_overtime/list_overtime";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		$this->show('/mine/list_overtime');
	}
	
	public function add_overtime() {
		$user_info = $this->session->userdata('user_info');
		$company_id = $user_info['company_id'];
		$dept_id = $user_info['dept_id'];
		$user_id = $user_info['id'];
		$user_name = $user_info['rel_name'];
	
		$company = $this->basic_model->get_company($company_id);
		$department = $this->basic_model->get_department($dept_id);
		$this->assign('user_id', $user_id);
		$this->assign('user_name', $user_name);
		$this->assign('department_id', $dept_id);
		$this->assign('department_name', $department['name']);
		$this->assign('company_id', $company_id);
		$this->assign('company_name', $company['name']);
	
		$departments = $this->basic_model->get_department_list();
		$this->assign('departments', $departments);
	
		$this->show('mine/add_overtime');
	}
	
	public function save_overtime(){
		$rs = $this->document_model->save_overtime();
		if($rs == 1){
			$this->show_message('保存成功',site_url('my_overtime/list_overtime'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_overtime() {
		$rs = $this->document_model->del_overtime($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('my_overtime/list_overtime'));
		}else{
			$this->show_message('删除失败');
		}
	}
}