<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Document_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_leave($page)
    {
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('leave');
    	if($this->input->post('type_id')){
    		$this->db->where('type_id',$this->input->post('type_id'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['type_id'] = null;
    
    	//获取详细列
    	$this->db->select('a.*, b.name')->from('leave a');
    	$this->db->join('leave_type b','a.type_id = b.id','left');
    	if($this->input->post('type_id')){
    		$this->db->where('type_id',$this->input->post('type_id'));
    		$data['type_id'] = $this->input->post('type_id');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
    
    public function get_leavetype_list() {
    	return $this->db->select()->from('leave_type')->get()->result_array();
    }
    
    public function save_leave() {
    	$data = array(
    		'uid'=>$this->input->post('uid'),
    		'dept_id'=>$this->input->post('dept_id'),
    		'company_id'=>$this->input->post('company_id'),
    		'position'=>$this->input->post('position'),
    		'agent'=>$this->input->post('agent'),
    		'type_id'=>$this->input->post('type_id'),
    		'reason'=>$this->input->post('reason'),
    		'start_time'=>$this->input->post('start_time'),
    		'end_time'=>$this->input->post('end_time'),
    		'direct_uid'=>$this->input->post('direct_uid'),
    		'direct_checked'=>0,
    		'manager_uid'=>NULL,
    		'manager_checked'=>0,
    		'cdate'=>date('Y-m-d H:i:s')
    	);
    	 
    	$this->db->trans_start();//--------开始事务
    	 
    	if($this->input->post('id')){//修改
    		die("NOT ALLOWED");
    	}else{//新增
    		$this->db->insert('leave',$data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_leave($id) {
    	$this->db->trans_start();//--------开始事务
    	
    	$this->db->where('id',$id);
    	$this->db->delete('leave');
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    public function list_overtime($page)
    {
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('overtime');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['title'] = null;
    
    	//获取详细列
    	$this->db->select()->from('overtime');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
    
    public function save_overtime() {
    	$data = array(
    		'uid'=>$this->input->post('uid'),
    		'dept_id'=>$this->input->post('dept_id'),
    		'company_id'=>$this->input->post('company_id'),
    		'position'=>$this->input->post('position'),
    		'address'=>$this->input->post('address'),
    		'reason'=>$this->input->post('reason'),
    		'start_time'=>$this->input->post('start_time'),
    		'end_time'=>$this->input->post('end_time'),
    		'direct_uid'=>$this->input->post('direct_uid'),
    		'direct_checked'=>0,
    		'executive_uid'=>NULL,
    		'executive_checked'=>0,
    		'cdate'=>date('Y-m-d H:i:s')
    	);
    
    	$this->db->trans_start();//--------开始事务
    
    	if($this->input->post('id')){//修改
    		die("NOT ALLOWED");
    	}else{//新增
    		$this->db->insert('overtime',$data);
    	}
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_overtime($id) {
    	$this->db->trans_start();//--------开始事务
    	 
    	$this->db->where('id',$id);
    	$this->db->delete('overtime');
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
}