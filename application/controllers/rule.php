<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('rule_model');
	}
	
	
	public function del() {
		
		echo json_encode(array('referer' => '/'));
	}
	
	public function list_company($page=1){
		$limit = 2;
		$offset = ($page - 1) * $limit;
		
		//$total = $this->rule_model->company_count();
		//$items = $this->rule_model->list_company($offset, $limit);
		$data = $this->rule_model->list_company($offset, $limit);
		
		
		$base_url = "/rule/list_company";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $limit);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('rule/list_company');
	}
	
	public function add_company(){
		$this->show('rule/add_company');
	}
	
	public function save_company(){
		$rs = $this->rule_model->save_company();
		if($rs == 1){
			$this->show_message('保存成功',site_url('rule/list_company'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_company($id){
		$rs = $this->rule_model->del_company($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('rule/list_company'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
}