<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_brokerage($page, $user_id=NULL) {
    	
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('brokerage a');
    	$this->db->join('users b', 'a.user_id = b.id', 'left');
    	$this->db->join('house c', 'a.house_id = c.id', 'left');
    	if($this->input->post('house_id')){
    		$this->db->where('a.house_id',$this->input->post('house_id'));
    	}
    	if($this->input->post('uname')){
    		$this->db->like('a.customer',$this->input->post('uname'));
    	}
    	if(!empty($user_id)) {
    		$this->db->where('user_id',$user_id);
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	
    	//搜索条件
    	$data['uname'] = null;
    	$data['house_id'] = null;
    	
    	//获取详细列
    	$this->db->select('a.*, b.rel_name, c.name')->from('brokerage a');
    	$this->db->join('users b', 'a.user_id = b.id', 'left');
    	$this->db->join('house c', 'a.house_id = c.id', 'left');
    	if($this->input->post('house_id')){
    		$this->db->where('a.house_id',$this->input->post('house_id'));
    		$data['house_id'] = $this->input->post('house_id');
    	}
    	if($this->input->post('uname')){
    		$this->db->like('a.customer',$this->input->post('uname'));
    		$data['uname'] = $this->input->post('uname');
    	}
    	if(!empty($user_id)) {
    		$this->db->where('user_id',$user_id);
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	
    	return $data;
    }
    
    public function get_brokerage($id) {
    	$this->db->select('a.*, b.rel_name, c.name')->from('brokerage a');
    	$this->db->join('users b', 'a.user_id = b.id', 'left');
    	$this->db->join('house c', 'a.house_id = c.id', 'left');
    	return $this->db->where('a.id', $id)->get()->row_array();
    }
    
    
    public function save_brokerage(){
    	$user_info = $this->session->userdata('user_info');
    	$user_id = $user_info['id'];
    	$data = array(
    			'customer'=>$this->input->post('customer'),
    			'phone'=>$this->input->post('phone'),
    			'house_no'=>$this->input->post('house_no'),
    			'acreage'=>$this->input->post('acreage'),
    			'total_price'=>$this->input->post('total_price'),
    			'retailer'=>$this->input->post('retailer'),
    			'retailer_tel'=>$this->input->post('retailer_tel'),
    			'status'=>$this->input->post('status'),
    			'date'=>$this->input->post('date'),
    			'item1'=>$this->input->post('item1'),
    			'item2'=>$this->input->post('item2'),
    			'item3'=>$this->input->post('item3'),
    			'item4'=>$this->input->post('item4'),
    			'item5'=>$this->input->post('item5'),
    			'item6'=>$this->input->post('item6'),
    			'house_id'=>$this->input->post('house_id'),
    			'user_id'=>$user_id
    	);
    
    	$this->db->trans_start();//--------开始事务
    
    	if($this->input->post('id')){//修改
    		$this->db->where('id', $this->input->post('id'));
    		$this->db->update('brokerage',$data);
    	}else{//新增
    		$this->db->insert('brokerage',$data);
    	}
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_brokerage($id) {
    	$this->db->trans_start();//--------开始事务
    	
    	$this->db->where('id',$id);
    	$this->db->delete('brokerage');
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function list_month_reports($page) {
    	
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('month_reports');
    	if($this->input->post('year')){
    		$this->db->where('year',$this->input->post('year'));
    	}
    	if($this->input->post('month')){
    		$this->db->like('month',$this->input->post('month'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['year'] = null;
    	$data['month'] = null;
    	 
    	//获取详细列
    	$this->db->select('a.*, b.rel_name as user_name');
    	$this->db->from('month_reports a');
    	$this->db->join('users b', 'a.user_id = b.id');
    	if($this->input->post('year')){
    		$this->db->where('a.year',$this->input->post('year'));
    	}
    	if($this->input->post('month')){
    		$this->db->like('a.month',$this->input->post('month'));
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    }
    
    public function save_month_reports($file) {
    	
    	$user_info = $this->session->userdata('user_info');
    	$user_id = $user_info['id'];
    	
    	$year = $this->input->post('year');
    	$month = $this->input->post('month');
    	$data = $this->db->from('month_reports')->where('year', $year)->where('month', $month)->get()->row_array();
    	if(empty($data)) {
    		$data = array(
	    		'year'=>$year,
	    		'month'=>$month,
	    		'created'=>date('Y-m-d H:i:s'),
	    	);
    	}
    	$data['file'] = $file;
    	$data['user_id'] = $user_id;
    	$data['updated'] = date('Y-m-d H:i:s');
    
    	$this->db->trans_start();//--------开始事务
    	
    	if(isset($data['id'])){//修改
    		$this->db->where('id', $data['id']);
    		$this->db->update('month_reports',$data);
    	}else{//新增
    		$this->db->insert('month_reports',$data);
    	}
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function delete_month_reports($id) {
    	$this->db->trans_start();//--------开始事务
    	 
    	$this->db->where('id',$id);
    	$this->db->delete('month_reports');
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function list_statistic($page) {
    	 
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num');
    	$this->db->from('statistics a');
    	$this->db->join('house c', 'a.house_id = c.id');
    	if($this->input->post('search_user_id')){
    		$this->db->like('a.user_id',$this->input->post('search_user_id'));
    	}
    	if($this->input->post('house_name')){
    		$this->db->like('c.name',$this->input->post('house_name'));
    	}
    	if($this->input->post('query_month')){
    		$this->db->where("DATE_FORMAT(a.date, '%Y-%m') = '" . $this->input->post('query_month') . "'");
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['search_user_id'] = null;
    	$data['house_name'] = null;
    	$data['query_month'] = null;
    
    	//获取详细列
    	$this->db->select('a.*, b.rel_name as user_name, c.name as house_name');
    	$this->db->from('statistics a');
    	$this->db->join('users b', 'a.user_id = b.id');
    	$this->db->join('house c', 'a.house_id = c.id');
    	if($this->input->post('search_user_id')){
    		$data['search_user_id'] = $this->input->post('search_user_id');
    		$this->db->like('a.user_id',$this->input->post('search_user_id'));
    	}
    	if($this->input->post('house_name')){
    		$data['house_name'] = $this->input->post('house_name');
    		$this->db->like('c.name',$this->input->post('house_name'));
    	}
    	if($this->input->post('query_month')){
    		$data['query_month'] = $this->input->post('query_month');
    		$this->db->where("DATE_FORMAT(a.date, '%Y-%m') = '" . $this->input->post('query_month') . "'");
    	}
    	if($page > 0) {
    		$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	}
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
    
    public function get_house_list() {
    	$this->db->from('house');
    	return $this->db->get()->result_array();
    }
    
    public function get_user_list() {
    	$this->db->from('users');
    	return $this->db->get()->result_array();
    }
    
    public function edit_statistic() {
    	
    }
    
    public function get_statistic($id) {
    	$this->db->from('statistics');
    	$this->db->where('id', $id);
    	return $this->db->get()->row_array();
    }
    
    public function update_statistic() {
    	$data = array(
    		'house_id'=>$this->input->post('house_id'),
    		'house_no'=>$this->input->post('house_no'),
    		'room_no'=>$this->input->post('room_no'),
    		'date'=>$this->input->post('date'),
    		'amount'=>$this->input->post('amount'),
    		'user_id'=>$this->input->post('user_id'),
    		'store_info'=>$this->input->post('store_info'),
    		'contract_no'=>$this->input->post('contract_no'),
    		'status'=>$this->input->post('status'),
    		'remark'=>$this->input->post('remark')
    	);
    	
    	$this->db->trans_start();//--------开始事务
    	
    	if($this->input->post('id')){//修改
    		$this->db->where('id', $this->input->post('id'));
    		$this->db->update('statistics',$data);
    	}else{//新增
    		$this->db->insert('statistics',$data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_statistic($id) {
    	$this->db->trans_start();//--------开始事务
    	 
    	$this->db->where('id',$id);
    	$this->db->delete('statistics');
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_expense_style_list() {
    	
    	$this->db->from('expense_style');
    	return $this->db->get()->result_array();
    }
    
    public function get_expense_type_list($style_id) {
    	
    	$this->db->select('id, name');
    	$this->db->from('expense_type');
    	$this->db->where('style_id', $style_id);
    	return $this->db->get_where()->result_array();
    }
    
    public function get_expense_list($expense_id) {
    	
    	$this->db->select('a.*, b.name AS style_name, c.name AS type_name');
    	$this->db->from('expense_list a');
    	$this->db->join('expense_style b', 'a.style_id = b.id', 'left');
    	$this->db->join('expense_type c', 'a.type_id = c.id', 'left');
    	$this->db->where('a.expense_id', $expense_id);
    	return $this->db->get_where()->result_array();
    }
    
    public function save_reimbursement() {
    	
    	$user_info = $this->session->userdata('user_info');
    	$user_id = $user_info['id'];
    	
    	$this->db->trans_start();//--------开始事务
    	
    	$expense = array(
    		'dept_id' => $_POST['dept_id'],
    		'creator' => $_POST['creator'],
    		'user_id' => $user_id,
    		'total'   => $_POST['total'],
    		'status'  => 0,
    		'created' => date('Y-m-d H:i:s')
    	);
    	
    	$expense_id = $_POST['expense_id'];
    	if(!empty($expense_id)) {
    		$this->db->where('id', $expense_id);
    		$this->db->update('expense', $expense);
    		
    		$this->db->where('expense_id', $expense_id);
    		$this->db->delete('expense_list');
    	} else {
    		$this->db->insert('expense', $expense);
    		
    		$expense_id = $this->db->insert_id();
    	}
    	
    	$amounts = $_POST['amount'];
    	foreach ($amounts as $idx => $amount) {
    		$data = array();
    		$data['amount'] = $amount;
    		$data['project'] = $_POST['project'][$idx];
    		$data['date'] = $_POST['date'][$idx];
    		$data['style_id'] = $_POST['style_id'][$idx];
    		$data['type_id'] = $_POST['type_id'][$idx];
    		$data['expense_id'] = $expense_id;
    		$data['created'] = date('Y-m-d H:i:s');
    		$this->db->insert('expense_list', $data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_reimbursement($id) {
    	$this->db->select('a.*, b.name AS dept_name, c.name AS company_name');
    	$this->db->from('expense a');
    	$this->db->join('department b', 'a.dept_id = b.id', 'left');
    	$this->db->join('company c', 'b.pid = c.id', 'left');
    	return $this->db->where('a.id', $id)->get()->row_array();
    }
    
    public function get_reimbursement_list() {
    	
    	$this->db->select('a.*, b.name AS dept_name, c.name AS company_name');
    	$this->db->from('expense a');
    	$this->db->join('department b', 'a.dept_id = b.id', 'left');
    	$this->db->join('company c', 'b.pid = c.id', 'left');
    	
    	if($this->input->post('reporter_name')){
    		$this->db->like('a.creator', $this->input->post('reporter_name'));
    	}
    	return $this->db->get()->result_array();
    }
    
    public function del_reimbursement($id) {
    	$this->db->trans_start();//--------开始事务
    	
    	$this->db->where('expense_id', $id);
    	$this->db->delete('expense_list');
    	
    	$this->db->where('id', $id);
    	$this->db->delete('expense');
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
}