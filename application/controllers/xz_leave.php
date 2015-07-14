<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_leave extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('document_model');
		$this->load->model('executive_model');
	}
	
	public function list_leave($page=1){
		$data = $this->executive_model->list_leave($page);
		$base_url = "/xz_leave/list_leave";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$leave_types = $this->document_model->get_leavetype_list();
		$this->assign('leave_types', $leave_types);
		
		$this->show('executive/list_leave');
	}
	
	public function view_leave($id) {
		$data = $this->executive_model->get_leave($id);
		$this->assign('data', $data);
		
		$this->show('executive/view_leave');
	}
}