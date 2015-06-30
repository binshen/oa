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
    	$this->db->select("a.*,b.name pname")->from('company a');
    	$this->db->join('company b',"a.pid=b.id","left");
    	if($this->input->post('company_name')){
    		$this->db->like('a.name',$this->input->post('company_name'));
    		$data['company_name'] = $this->input->post('company_name');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	
    	return $data;
    	
    }
    
    public function save_company(){
    	$data = array(
    			'name'=>$this->input->post('company_name'),
    			'pid'=>$this->input->post('pid'),
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
    
    public function list_company_all(){
    	return $this->db->select()->from('company')->where('pid',0)->where('status',1)->get()->result_array();
    }
    
    
    public function list_rule($page){
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('rule');
    	if($this->input->post('mark')){
    		$this->db->like('mark',$this->input->post('mark'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['rule'] = null;
    	 
    	//获取详细列
    	$this->db->select()->from('rule');
    	if($this->input->post('mark')){
    		$this->db->like('mark',$this->input->post('mark'));
    		$data['mark'] = $this->input->post('mark');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    	 
    }
    
    public function save_rule(){
    	$this->db->trans_start();//--------开始事务
    	 
    	$operation = $this->input->post('operation');
    	$id = $this->input->post('id');
    	if($id){//修改
    		$this->db->where('id',$id);
    		$this->db->update('rule',array('mark'=>$this->input->post('mark')));
    		
    		$this->db->where('rid',$id);
    		$this->db->delete('rule_operation');
    		
    		foreach($operation as $k=>$v){
    			$this->db->insert('rule_operation',array('rid'=>$id,'operation'=>$v));
    		}
    		
    	}else{//新增
    		$this->db->insert('rule',array('mark'=>$this->input->post('mark')));
    		$rid = $this->db->insert_id();
    		
    		foreach($operation as $k=>$v){
    			$this->db->insert('rule_operation',array('rid'=>$rid,'operation'=>$v));
    		}
    	}
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_rule($id){
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
    
    public function get_operation_menu(){
    	$data = array();
    	$rs = $this->db->select('a.*,b.name')->from('operation_menu a')->join('menu b','a.mid=b.id','left')->get()->result_array();
    	foreach($rs as $k=>$v){
    		$data[$v['mid']][] = $v;
    	}
    	return $data;
    }
    
    public function get_rule($id){
    	$data['main'] = $this->db->select()->from('rule')->where('id',$id)->get()->row_array();
    	
    	$rs = $this->db->select('a.*,b.mid')->from('rule_operation a')->join('operation_menu b','a.operation=b.operation','left')->where('rid',$id)->get()->result_array();
    	$mid = array();
    	$operation = array();
    	foreach($rs as $k=>$v){
    		if(!in_array($v['mid'],$mid)){
    			$mid[] = $v['mid'];
    		}
    		if(!in_array($v['operation'],$operation)){
    			$operation[] = $v['operation'];
    		}
    	}
    	
    	$data['mid'] = $mid;
    	$data['operation'] = $operation;
    	return $data;
    }
    
    public function list_menu_all(){
    	return $this->db->select()->from('menu')->get()->result_array();
    }
    
}