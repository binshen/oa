<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class rule_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function company_count() {
    	
    	$count = $this->db->count_all_results('company');
    	return $count;
    }
    
    public function list_company($page){
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('company');
    	if($this->input->post('company_name')){
    		$this->db->like('name',$this->input->post('company_name'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	
    	//搜索条件
    	$data['company_name'] = null;
    	
    	//获取详细列
    	$this->db->select()->from('company');
    	if($this->input->post('company_name')){
    		$this->db->like('name',$this->input->post('company_name'));
    		$data['company_name'] = $this->input->post('company_name');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	
    	return $data;
    	
    }
    
    public function save_company(){
    	$data = array(
    			'name'=>$this->input->post('company_name'),
    			'status'=>$this->input->post('status'),
    			'cdate'=>date('Y-m-d H:i:s',time())
    	);
    	
    	$this->db->trans_start();//--------开始事务
    	
    	if($this->input->post('id')){//修改
    		$this->db->where('id',$this->input->post('id'));
    		$this->db->update('company',$data);
    	}else{//新增
    		$this->db->insert('company',$data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_company($id){
    	$this->db->trans_start();//--------开始事务
    	 
		$this->db->where('id',$id);
		$this->db->delete('company');
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_company($id){
    	return $this->db->select()->from('company')->where('id',$id)->get()->row_array();
    }
}