<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Basic_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_department($page)
    {
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('department');
    	if($this->input->post('department_name')){
    		$this->db->like('name',$this->input->post('department_name'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['company_name'] = null;
    	 
    	//获取详细列
    	$this->db->select()->from('department');
    	if($this->input->post('department_name')){
    		$this->db->like('name',$this->input->post('department_name'));
    		$data['department_name'] = $this->input->post('department_name');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    }
}