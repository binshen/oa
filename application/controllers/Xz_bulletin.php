<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_bulletin extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('basic_model');
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
		$departments = $this->basic_model->get_department_list();
		$this->assign('departments', $departments);
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

		$departments = $this->basic_model->get_department_list();
		$this->assign('departments', $departments);
		
		$bulletin_check = $this->executive_model->get_bulletin_check($id);
		$this->assign('bulletin_check', $bulletin_check);
		
		$this->show('executive/edit_bulletin');
	}
	
	public function check_bulletin($id){
		$departments = $this->basic_model->get_department_list();
		$this->assign('departments', $departments);
		
		$data = $this->executive_model->get_bulletin($id);
		$this->assign('data', $data);
		
		$bulletin_check = $this->executive_model->get_bulletin_check($id);
		$this->assign('bulletin_check', $bulletin_check);
		
		$this->show('executive/check_bulletin');
	}
	
	public function confirm_bulletin() {
		$num = $this->get_bulletin_num(date('Y'));
		if(empty($num)) {
			$num = 1;
		} else {
			$num += 1;
		}
		$this->executive_model->confirm_bulletin($num);
		redirect('/index/main');
	}
	
	public function continue_bulletin() {
		$this->executive_model->continue_bulletin();
		redirect('/index/main');
	}
	
	public function view_bulletin($id, $key=NULL) {
		$data = $this->executive_model->get_bulletin($id);
		$this->assign('data', $data);
		
		$year = date('Y', strtotime($data['cdate']));
		$num = $this->get_bulletin_num($year);
		$bulletin_num = $year . '年第' . str_pad($num, 3, '0', STR_PAD_LEFT) . '号公告';
		$this->assign('bulletin_num', $bulletin_num);
		
		$this->assign('key', $key);
		
		$this->show('executive/view_bulletin');
	}
	
	private function get_bulletin_num($year) {
		return $this->executive_model->get_bulletin_num($year);
	}
}