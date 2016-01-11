<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_meeting extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('executive_model');
	}
	
	
	public function list_meeting($page=1){
		$data = $this->executive_model->list_meeting($page);
		$base_url = "/xz_meeting/list_meeting";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		$this->show('executive/list_meeting');
	}
	
	public function add_meeting(){
		$data = $this->executive_model->get_company_dept();
		$this->assign('data', $data);
		$this->show('executive/add_meeting');
	}
	
	public function show_meeting($id){
		$data = $this->executive_model->get_meeting($id);
		$this->assign('data', $data);
		$this->show('executive/edit_meeting');
	}
	
	public function save_meeting(){
		$rs = $this->executive_model->save_meeting();
		if($rs == 1){
			$this->show_message('保存成功',site_url('xz_meeting/list_meeting'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
}