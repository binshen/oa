<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Task_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_task($page)
    {
    	$user_info = $this->session->userdata('user_info');
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('task');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$this->db->where('from_uid',$user_info['id']);
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    
    	//搜索条件
    	$data['title'] = null;
    
    	//获取详细列
    	$this->db->select('a.id,a.title,a.cdate,a.lev,a.status,to_uid,b.rel_name,close_name')->from('task a');
    	$this->db->join('users b','a.target_uid=b.id','left');
    	if($this->input->post('title')){
    		$this->db->like('a.title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->where('a.from_uid',$user_info['id']);
    	$this->db->order_by('cdate','desc');
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
    
	public function save_task(){
		$user_info = $this->session->userdata('user_info');
		$data = array(
				'from_uid'=>$user_info['id'],
				'to_uid'=>$this->input->post('to_uid'),
				'target_uid'=>$this->input->post('target_uid'),
				'lev'=>$this->input->post('lev'),
				'title'=>$this->input->post('title'),
				'content'=>$this->input->post('content',true),
				'from_name'=>$user_info['rel_name'],
				'status'=>1,
				'audit'=>2,
				'cdate'=>date('Y-m-d H:i:s',time())
		);
		
		$this->db->trans_start();//--------开始事务
		
		$this->db->insert('task',$data);
		
		$this->db->trans_complete();//------结束事务
		if ($this->db->trans_status() === FALSE) {
			return -1;
		} else {
			return 1;
		}
	}    
    
    
    public function get_users_all(){
    	$user_info = $this->session->userdata('user_info');
    	$rs = $this->db->select('id,rel_name')->from('users')
    			->where('company_id',$user_info['company_id'])
    			->where('supper',0)
    			->get()->result_array();
    	return $rs;
    }
    
    public function get_users_dept(){
    	$user_info = $this->session->userdata('user_info');
    	$rs = $this->db->select('id,rel_name')->from('users')
    	->where('company_id',$user_info['company_id'])
    	->where('id !=',$user_info['id'])
    	->where('dept_id',$user_info['dept_id'])
    	->where('supper',0)
    	->get()->result_array();
    	return $rs;
    }
    
    public function list_audit_task($page){
    	$user_info = $this->session->userdata('user_info');
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('task');
    	$this->db->where('to_uid',$user_info['id']);
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$this->db->where('to_uid',$user_info['id']);
    	$this->db->where('audit',-1);
    	$this->db->where('status',1);
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	
    	//搜索条件
    	$data['title'] = null;
    	
    	//获取详细列
    	$this->db->select('a.id,a.title,a.cdate,a.lev,a.status,to_uid,b.rel_name,from_name')->from('task a');
    	$this->db->join('users b','a.to_uid=b.id','left');
    	if($this->input->post('title')){
    		$this->db->like('a.title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->where('to_uid',$user_info['id']);
    	$this->db->where('audit',-1);
    	$this->db->where('a.status',1);
    	$this->db->order_by('cdate','desc');
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	
    	return $data;
    }
    
    public function get_audit_task($id){
    	return $this->db->select()->from('task')->where('id',$id)->get()->row_array();
    }
    
    public function audit_task(){
    	$user_info = $this->session->userdata('user_info');
    	$data = array(
    			'target_uid'=>$this->input->post('target_uid'),
    			'lev'=>$this->input->post('lev'),
    			'title'=>$this->input->post('title'),
    			'content'=>$this->input->post('content',true),
    			'from_name'=>$user_info['rel_name'],
    			'audit'=>'2'
    	);
    	
    	$this->db->trans_start();//--------开始事务
    	
    	$this->db->where('id',$this->input->post('id'));
    	$this->db->update('task',$data);
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function close_task($id){
    	$user_info = $this->session->userdata('user_info');
    	
    	$this->db->trans_start();//--------开始事务
    	 
    	$this->db->where('id',$id);
    	$this->db->update('task',array('status'=>3,'close_name'=>$user_info['rel_name']));
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function list_my_task($page){
    	$user_info = $this->session->userdata('user_info');
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('task');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$this->db->where('target_uid',$user_info['id']);
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['title'] = null;
    	 
    	//获取详细列
    	$this->db->select('a.id,a.title,a.cdate,a.lev,a.status,to_uid,b.rel_name,from_name,close_name')->from('task a');
    	$this->db->join('users b','a.to_uid=b.id','left');
    	if($this->input->post('title')){
    		$this->db->like('a.title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->where('target_uid',$user_info['id']);
    	$this->db->order_by('cdate','desc');
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    }
    
    public function save_my_task(){
    	$user_info = $this->session->userdata('user_info');
    	$this->db->trans_start();//--------开始事务
    	if($this->input->post('id')){//修改
    		
    	}else{//新增
    		$data = array(
    			'progress'=>$this->input->post('progress'),
    			'task_id'=>$this->input->post('task_id'),
    			'from_name'=>$user_info['rel_name'],
    			'cdate'=>date('Y-m-d H:i:s',time()),
    		);
    		
    		$this->db->insert('task_progress',$data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_my_task($id){
    	$data['main'] = $this->db->select()->from('task')->where('id',$id)->get()->row_array();
    	$data['list'] = $this->db->select()->from('task_progress')->where('task_id',$id)->get()->result_array();
    	return $data;
    }
    

}