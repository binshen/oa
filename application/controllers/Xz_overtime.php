<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_overtime extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('executive_model');
	}
	
	public function list_overtime($page=1){
		$data = $this->executive_model->list_overtime($page);
		$base_url = "/xz_overtime/list_overtime";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('executive/list_overtime');
	}
	
	public function view_overtime($id, $key=NULL) {
		$data = $this->executive_model->get_overtime($id);
		$this->assign('data', $data);
		$this->assign('key', $key);
		
		$this->show('executive/view_overtime');
	}
}