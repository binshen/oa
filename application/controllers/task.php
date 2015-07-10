<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('task_model');
	}
	
	public function list_task($page=1){
		$data = $this->task_model->list_task($page);
		$base_url = "/task/list_task";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		$this->show('task/list_task');
	}
	
	public function add_task_self(){
		$user_dept = $this->task_model->get_users_dept();
		$this->assign('user_dept', $user_dept);
		$this->show('task/add_task_self');
	}
	
	public function add_task_other(){
		$user_all = $this->task_model->get_users_all();
		$this->assign('user_all', $user_all);
		$this->show('task/add_task_other');
	}
	
	public function save_task(){
		$rs = $this->task_model->save_task();
		if($rs == 1){
			$this->show_message('保存成功',site_url('task/list_task'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function show_task($id){
		$data = $this->common_model->get_task($id);
		if($data == '-1'){
			$this->show_message('非法操作');
		}
		$this->assign('data', $data);
		$this->show('common/show_task');
	}
	
	public function list_audit_task($page=1){
		$data = $this->task_model->list_audit_task($page);
		$base_url = "/task/audit_task";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		$this->show('task/audit_task');
	}
	
}