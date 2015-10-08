<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_brokerage($page) {
    	
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('brokerage a');
    	$this->db->join('users b', 'a.user_id = b.id', 'left');
    	$this->db->join('house c', 'a.house_id = c.id', 'left');
    	if($this->input->post('house_id')){
    		$this->db->where('a.house_id',$this->input->post('house_id'));
    	}
    	if($this->input->post('uname')){
    		$this->db->like('user_id',$this->input->post('user_id'));
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
    	if($this->input->post('user_id')){
    		$this->db->like('user_id',$this->input->post('user_id'));
    		$data['user_id'] = $this->input->post('user_id');
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
}