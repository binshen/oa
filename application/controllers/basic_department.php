<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_department extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->library('pagination');
		$this->load->model('basic_model');
		$this->load->model('rule_model');
	}
	
	function _remap($method,$params = array())
	{
		$operation = $this->session->userdata('operation');
		if(in_array($method, $operation)){
			return call_user_func_array(array($this,$method), $params);
		}else{
			$this->show_message('您没有权限');
		}

	}
	

	
	public function list_department($page=1){
		$data = $this->basic_model->list_department($page);
		$base_url = "/basic_department/list_department";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('basic/list_department');
	}
	
	public function add_department(){
		
		$company_all = $this->rule_model->list_company_all();
		$this->assign('company_all', $company_all);
		
		$this->show('basic/add_department');
	}
	
	public function save_department(){
		$rs = $this->basic_model->save_department();
		if($rs == 1){
			$this->show_message('保存成功',site_url('basic_department/list_department'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_department($id){
		$rs = $this->basic_model->del_department($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('basic_department/list_department'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
	public function show_department($id){
		$data = $this->basic_model->get_department($id);
		$company_all = $this->rule_model->list_company_all();
		$this->assign('company_all', $company_all);
		$this->assign('data', $data);
		$this->show('basic/edit_department');
	}
	
	public function edit_department($id){
		$this->show_department($id);
	}
}