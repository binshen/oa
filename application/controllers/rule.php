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
		
		$total = $this->rule_model->get_count();
		$items = $this->rule_model->get_tests($offset, $limit);
		$base_url = "/rule/list_company";
		$pager = $this->pagination->getPageLink($base_url, $total, $limit);
		$this->assign('pager', $pager);
		$this->assign('items', $items);
		
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
		//die($id);
		echo json_encode(array('referer' => '/rule/list_company'));
	}
	
}