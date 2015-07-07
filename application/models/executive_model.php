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
    	$this->db->select('a.id,title,a.cdate,b.rel_name')->from('notice_main a');
    	$this->db->join('users b','a.from_uid=b.id','left');
    	if($this->input->post('title')){
    		$this->db->like('title',$this->input->post('title'));
    		$data['title'] = $this->input->post('title');
    	}
    	$this->db->limit($this->limit, $offset = ($page - 1) * $this->limit);
    	$data['items'] = $this->db->get()->result_array();
    
    	return $data;
    }
    
    public function save_notice(){
    	$user_info = $this->session->userdata('user_info');
    	$company = $this->session->userdata('company');
    	$data = array(
    			'title'=>$this->input->post('title'),
    			'content'=>$this->input->post('content'),
    			'from_uid'=>$user_info['id'],
    			'company_id'=>$company['id'],
    			'to_user'=>'',
    			'cdate'=>date('Y-m-d H:i:s',time())
    	);
    	 
    	$rs = $this->db->select('rel_name')->from('users')->where_in('id',$this->input->post('uid'))->get()->result_array();
    	 
    	foreach($rs as $v){
    		$data['to_user'] = $v['rel_name'].';'.$data['to_user'];
    	}
    	 
    	 
    	$this->db->trans_start();//--------开始事务
    
    	$this->db->insert('notice_main',$data);
    	 
    	$id = $this->db->insert_id();
    	 
    	foreach ($this->input->post('uid') as $v){
    		$this->db->insert('notice_list',array('mid'=>$id,'uid'=>$v,'read'=>0));
    	}
    
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function get_company_dept(){
    	$company = $this->session->userdata('company');
    	$company_id = $company['id'];
    	$sql = 'SELECT
					a.id,
					a.rel_name,
					b.`name` company_name,
					c.`name` dept_name
				FROM
					users a
				LEFT JOIN company b ON a.company_id = b.id
				LEFT JOIN department c ON a.dept_id = c.id
				WHERE
					dept_id IN (
						SELECT
							a.id
						FROM
							department a
						LEFT JOIN company b ON a.pid = b.id
						WHERE
							a.pid IN (
								SELECT
									id
								FROM
									company
								WHERE
									pid = '.$company_id.'
								OR id = '.$company_id.'
							)
					)';
    
    	$query = $this->db->query($sql);
    	$rs = $query->result_array();
    	$data = array();
    
    	foreach($rs as $k=>$v){
    		$data[$v['company_name']][$v['dept_name']][] = $v;
    	}
    	return $data;
    }
    
    public function get_notice($id){
    	$data = $this->db->select('a.*,b.rel_name')->from('notice_main a')
    	->join('users b','a.from_uid=b.id','left')
    	->where('a.id',$id)->get()->row_array();
    	return $data;
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
    		'from_uid'=>$this->session->userdata('user_info')['id']
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
    			'status' => 1
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
    
    public function confirm_bulletin() {
    	$this->db->trans_start();//--------开始事务
    	
    	$this->db->where('id', $this->input->post('id'));
		$this->db->update('bulletin', array('checked' => 1));
    	
		$this->db->where('id', $this->input->post('bc_id'));
		$this->db->update('bulletin_check', array('status' => 2));
		
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
    
    public function continue_bulletin() {
    	$this->db->trans_start();//--------开始事务
    	 
    	$this->db->where('id', $this->input->post('bc_id'));
    	$this->db->update('bulletin_check', array('status' => 3));
    	
    	$check_data = array(
    		'bid' => $this->input->post('id'),
    		'uid' => $this->input->post('user_id'),
    		'dept_id' => $this->input->post('dept_id'),
    		'status' => 1
    	);
    	$this->db->insert('bulletin_check',$check_data);
    	 
    	$this->db->trans_complete();//------结束事务
    	if ($this->db->trans_status() === FALSE) {
    		return -1;
    	} else {
    		return 1;
    	}
    }
}