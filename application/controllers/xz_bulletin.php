<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_bulletin extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('executive_model');
	}
	
	public function list_bulletin($page=1){
		$data = $this->executive_model->list_bulletin($page);
		$base_url = "/xz_bulletin/list_bulletin";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
	
		$this->show('executive/list_bulletin');
	}
	
	public function add_bulletin(){
		//$company_all = $this->executive_model->list_bulletin_all();
		//$this->assign('company_all', $company_all);
		$this->show('executive/add_bulletin');
	}
	
	public function save_bulletin(){
		$rs = $this->executive_model->save_bulletin();
		if($rs == 1){
			$this->show_message('保存成功',site_url('xz_bulletin/list_bulletin'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_bulletin($id){
		$rs = $this->executive_model->del_bulletin($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('xz_bulletin/list_bulletin'));
		}else{
			$this->show_message('删除失败');
		}
	}
	
	public function show_bulletin($id){
		$data = $this->executive_model->get_bulletin($id);
		$this->assign('data', $data);
		$company_all = $this->executive_model->list_bulletin_all();
		$this->assign('company_all', $company_all);
		$this->show('executive/edit_bulletin');
	}
	
	public function edit_bulletin($id){
		$this->show_bulletin($id);
	}
}