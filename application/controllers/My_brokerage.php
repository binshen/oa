<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_brokerage extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('basic_model');
		$this->load->model('finance_model');
	}
	
	public function list_brokerage($page=1) {
		
		$user_info = $this->session->userdata('user_info');
		$user_id = $user_info['id'];
		$data = $this->finance_model->list_brokerage($page, $user_id);
		$base_url = "/my_brokerage/list_brokerage";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$houses = $this->basic_model->get_house_list();
		$this->assign('houses', $houses);
		
		$this->show('/mine/list_brokerage');
	}
	
	public function add_brokerage() {
		
		$houses = $this->basic_model->get_house_list();
		$this->assign('houses', $houses);
		
		$this->assign('house_id', null);
		$this->show('/mine/add_brokerage');
	}
	
	public function save_brokerage(){
		$rs = $this->finance_model->save_brokerage();
		if($rs == 1){
			$this->show_message('保存成功',site_url('my_brokerage/list_brokerage'));
		}else{
			$this->show_message('保存失败');
		}
	}
	
	public function del_brokerage() {
		$rs = $this->finance_model->del_brokerage($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('my_brokerage/list_brokerage'));
		}else{
			$this->show_message('删除失败');
		}
	}
}