<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_statistic extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
 		$this->load->library('pagination');
		$this->load->model('finance_model');
	}

	public function index() {
		
		$houseList = $this->finance_model->get_house_list();
		$this->assign('houseList', $houseList);
		
		$userList = $this->finance_model->get_user_list();
		$this->assign('userList', $userList);
		
		$this->assign('statistic', array('house_id'=>null, 'user_id'=>null, 'status'=>null));
		
		$data = $this->finance_model->list_statistic(0);
		$base_url = "/cw_statistic/list_statistic";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('finance/list_statistic');
	}
	
	public function list_statistic($page=1) {
		
		$houseList = $this->finance_model->get_house_list();
		$this->assign('houseList', $houseList);
		
		$userList = $this->finance_model->get_user_list();
		$this->assign('userList', $userList);
		
		$this->assign('statistic', array('house_id'=>null, 'user_id'=>null, 'status'=>null));
		
		$data = $this->finance_model->list_statistic($page);
		$base_url = "/cw_statistic/list_statistic";
		$pager = $this->pagination->getPageLink($base_url, $data['total'], $data['limit']);
		$this->assign('pager', $pager);
		$this->assign('data', $data);
		
		$this->show('statistic/list_statistic');
	}
	
	public function edit_statistic($id) {
		
		$statistic = $this->finance_model->get_statistic($id);
		echo json_encode($statistic);
	}
	
	public function update_statistic() {
	
		echo $this->finance_model->update_statistic();
		die;
	}
	
	public function del_statistic($id) {
		$rs = $this->finance_model->del_statistic($id);
		if($rs == 1){
			$this->show_message('删除成功',site_url('cw_statistic/list_statistic'));
		}else{
			$this->show_message('删除失败');
		}
	}
}