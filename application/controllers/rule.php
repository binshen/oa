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
	
	
}