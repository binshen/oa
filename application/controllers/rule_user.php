<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule_user extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('rule_model');
		
	}
	
	
	public function list_user($page=1){
		$data = $this->rule_model->list_user($page);
		$base_url = "/rule_user/list_user";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('rule/list_user');
	}
	
	public function add_user(){
		$rules = $this->rule_model->list_rule_all();
		$dept = $this->rule_model->list_dept_all();
		$this->assign('rules', $rules);
		$this->assign('dept', $dept);
		$this->show('rule/add_user');
	}
	
	public function save_user(){
		$rs = $this->rule_model->save_user();
		if($rs == 1){
			$this->show_message('保存成功',site_url('rule_user/list_user'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_user($id){
		$rs = $this->rule_model->del_user($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('rule_user/list_user'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
	public function show_user($id){
		$data = $this->rule_model->get_user($id);
		$rules = $this->rule_model->list_rule_all();
		$dept = $this->rule_model->list_dept_all();
		$this->assign('rules', $rules);
		$this->assign('dept', $dept);
		$this->assign('data', $data);
		$this->show('rule/edit_user');
	}
	
	public function edit_user($id){
		$this->show_user($id);
	}
	
	
}