<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->library('pagination');
		$this->load->model('tests_model');
	}
	
	public function index($page=1) {

		$limit = 2;
		$offset = ($page - 1) * $limit;
		
		$total = $this->tests_model->get_count();
		
		$items = $this->tests_model->get_tests($offset, $limit);
		
		$pager = $this->pagination->getPageLink($total, $limit);
		
		$this->assign('pager', $pager);
		$this->assign('items', $items);
		
		$this->show('test');
	}
	
	public function del() {
		
		echo json_encode(array('referer' => '/'));
	}
}