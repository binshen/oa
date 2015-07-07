<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Executive_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function list_notice($page)
    {
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('notice_main');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['title'] = null;
    	 
    	//获取详细列
    	$this->db->select()->from('notice_main');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    }
    
    public function save_notice(){
    	$data = array(
    		'name'=>$this->input->post('department_name'),
    		'pid'=>$this->input->post('pid'),
    		'status'=>$this->input->post('status'),
    		'cdate'=>date('Y-m-d H:i:s',time())
    	);
    	 
    	$this->db->trans_start();//--------开始事务
    	 
    	if($this->input->post('id')){//修改
    		$this->db->where('id',$this->input->post('id'));
    		$this->db->update('department',$data);
    	}else{//新增
    		$this->db->insert('department',$data);
    	}
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_department($id){
    	$this->db->trans_start();//--------开始事务
    
    	$this->db->where('id',$id);
    	$this->db->delete('department');
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_department($id){
    	return $this->db->select()->from('department')->where('id',$id)->get()->row_array();
    }
    
    /**
     * 首页*公告
     */
    public function display_bulletin() {
    	
    	return $this->db->from('bulletin')->order_by('cdate', 'DESC')->limit(3)->get()->result_array();
    }
    
    /**
     * 行政*公告(Bulletin)
     */
    public function list_bulletin($page) {
    	 
    	$data['limit'] = $this->limit;
    	//获取总记录数
    	$this->db->select('count(1) num')->from('bulletin');
    	if($this->input->post('bulletin_title')){
    		$this->db->like('title',$this->input->post('bulletin_title'));
    	}
    	$num = $this->db->get()->row();
    	$data['total'] = $num->num;
    	 
    	//搜索条件
    	$data['bulletin_title'] = null;
    	 
    	//获取详细列
    	$this->db->select('bulletin.*, users.username AS uname')->from('bulletin');
    	$this->db->join('users', 'bulletin.from_uid = users.id', 'left');
    	if($this->input->post('bulletin_title')){
    		$this->db->like('title',$this->input->post('bulletin_title'));
    		$data['bulletin_title'] = $this->input->post('bulletin_title');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    	 
    	return $data;
    }
    
    public function save_bulletin() {
    	
    	$data = array(
    		'title'=>$this->input->post('title'),
    		'content'=>$this->input->post('editorValue'),
    		'cdate'=>date('Y-m-d H:i:s'),
    		'from_uid'=>$this->session->get_userdata()['user_info']['id']
    	);
    	
    	$this->db->trans_start();//--------开始事务
    	
    	if($this->input->post('id')){//修改
    		$this->db->where('id',$this->input->post('id'));
    		$this->db->update('bulletin',$data);
    		//TODO
    	}else{//新增
    		$this->db->insert('bulletin',$data);
    		$bid = $this->db->insert_id();
    		
    		$check_data = array(
    			'bid' => $bid,
    			'uid' => $this->input->post('user_id'),
    			'dept_id' => $this->input->post('dept_id'),
    			'status' => 0
    		);
    		$this->db->insert('bulletin_check',$check_data);
    	}
    	
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function del_bulletin($id){
    	$this->db->trans_start();//--------开始事务
    
    	$this->db->where('id',$id);
    	$this->db->delete('bulletin');
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_bulletin($id){
    	return $this->db->select()->from('bulletin')->where('id',$id)->get()->row_array();
    }
    
    public function get_department_list() {
    	$where = ' select department.id, CONCAT(company.name, "-", department.name) AS name from department ';
    	$where .= ' left join company on department.pid = company.id ';
    	return $this->db->query($where)->result_array();
    }
    
    public function get_user_list($dept_id) {
    	return $this->db->select('id, rel_name')->get_where('users', array('dept_id' => $dept_id))->result_array();
    }
    
    public function get_bulletin_check($bid) {
    	return $this->db->order_by('id', 'ASC')->get_where('bulletin_check', array('bid' => $bid))->row_array();
    }
}