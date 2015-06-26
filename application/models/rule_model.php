<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class rule_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function get_count() {
    	
    	$count = $this->db->count_all_results('company');
    	return $count;
    }
    
    public function get_tests($offset, $limit){
    	$results = $this->db->limit($limit, $offset)->get('company')->result_array();
    	return $results;
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
}