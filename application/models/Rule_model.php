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
    	$data['mark'] = null;
    	 
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
    	$this->db->delete('rule');
    	
    	$this->db->where('rid',$id);
    	$this->db->delete('rule_operation');
    	 
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
    
    public function list_user($page){
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('users');
    	if($this->input->post('dept_id')){
    		$this->db->where('dept_id',$this->input->post('dept_id'));
    	}
    	
    	if($this->input->post('rel_name')){
    		$this->db->like('rel_name',$this->input->post('rel_name'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['dept_id'] = null;
    	$data['rel_name'] = null;
    
    	//获取详细列
    	$this->db->select('a.*,b.name dname,mark')->from('users a');
    	$this->db->join('department b',"a.dept_id=b.id",'left');
    	$this->db->join('rule c',"a.rule_id=c.id",'left');
    	if($this->input->post('dept_id')){
    		$this->db->where('dept_id',$this->input->post('dept_id'));
    		$data['dept_id'] = $this->input->post('dept_id');
    	}
    	 
    	if($this->input->post('rel_name')){
    		$this->db->like('rel_name',$this->input->post('rel_name'));
    		$data['rel_name'] = $this->input->post('rel_name');
    	}
    	
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    
    }
    
    public function save_user(){
    	$this->db->trans_start();//--------开始事务
    	$company = $this->session->userdata('company');
    	$id = $this->input->post('id');
    	
    	$data= array(
    			'username'=>$this->input->post('username'),
    			'company_id'=>$company['id'],
    			'dept_id'=>$this->input->post('dept_id'),
    			'rule_id'=>$this->input->post('rule_id'),
    			'rel_name'=>$this->input->post('rel_name')
    	);
    	
    	//主管必须唯一
    	$this->db->select('count(1) num')->from('users')
	    	->where('dept_id',$this->input->post('dept_id'))
	    	->where('company_id',$company['id'])
	    	->where('manager >',0);
    	if($id)
	    	$this->db->where('id !=',$id);
    	$rs = $this->db->get()->row();
    	if($rs->num > 0){
    		return -2;
    	}
    	
    	if($this->input->post('manager') > 0){
    		$data['manager'] = $data['dept_id'];
    	}else{
    		$data['manager'] = -1;
    	}
    	
    	if($id){//修改
    		$data['status'] = $this->input->post('status');
    		$this->db->where('id',$id);
    		$this->db->update('users',$data);
    	}else{//新增
    		$data['pwd'] = sha1('888888');
    		$data['cdate'] = date('Y-m-d H:i:s',time());
    		$this->db->insert('users',$data);
    	}
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
/*    public function del_user($id){
    	$this->db->trans_start();//--------开始事务
    
    	$this->db->where('id',$id);
    	$this->db->delete('rule');
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }*/
    
    public function get_user($id){
    	$data = $this->db->select()->from('users')->where('id',$id)->get()->row_array();
    	return $data;
    }
    
    public function list_rule_all(){
    	return $this->db->select()->from('rule')->get()->result_array();
    }
    
    public function list_dept_all(){
    	return $this->db->select()->from('department')->where('status','1')->get()->result_array();
    }
    
}