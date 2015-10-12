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
}