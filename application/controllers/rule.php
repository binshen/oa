<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('rule_model');
		
	}
	
	
	public function list_rule($page=1){
		$data = $this->rule_model->list_rule($page);
		$base_url = "/rule/list_rule";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('rule/list_rule');
	}
	
	public function add_rule(){
		$menus = $this->rule_model->list_menu_all();
		$operation_menu = $this->rule_model->get_operation_menu();
		$this->assign('menus', $menus);
		$this->assign('operation_menu', $operation_menu);
		$this->show('rule/add_rule');
	}
	
	public function save_rule(){
		$rs = $this->rule_model->save_rule();
		if($rs == 1){
			$this->show_message('保存成功',site_url('rule/list_rule'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_rule($id){
		$rs = $this->rule_model->del_rule($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('rule/list_rule'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
	public function show_rule($id){
		$data = $this->rule_model->get_rule($id);
		$menus = $this->rule_model->list_menu_all();
		$operation_menu = $this->rule_model->get_operation_menu();
		$this->assign('operation_menu', $operation_menu);
		$this->assign('menus', $menus);
		$this->assign('data', $data);
		$this->show('rule/edit_rule');
	}
	
	public function edit_rule($id){
		$this->show_rule($id);
	}
	
	
}