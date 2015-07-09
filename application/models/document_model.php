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
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['title'] = null;
    
    	//获取详细列
    	$this->db->select('a.*, b.name')->from('leave a');
    	$this->db->join('leave_type b','a.type_id = b.id','left');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
}