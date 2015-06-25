<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 权限模型
 *
 * 权限及用户的处理模型
 * 
 * @package		app
 * @subpackage	core
 * @category	model
 * @author		yaobin
 *        
 */
class Login_model extends MY_Model
{
    /**
     * 表名数组
     * @var array
     */
    protected $tables = array(
            'users',
    );

    public function __construct ()
    {
        parent::__construct();
    }

    public function __destruct ()
    {
        parent::__destruct();
    }
    
    
    /**
     * 用户登录检查
     * 
     * @return boolean
     */
    public function check_login ()
    {
        $username = $this->input->post('username');
        $passwd = $this->input->post('password');
        $this->db->from($this->tables[0]);
        $this->db->where('username', $username);
        $this->db->where('pwd', sha1($passwd));
        $this->db->where('status', 1);
        $rs = $this->db->get();
        
        if ($rs->num_rows() > 0) {
            $user_info = $rs->row_array();
/*            $role = '';
            //取用户角色
            $res = $this->db->select('role')->from($this->tables[4])->where('user_id',$login_id)->get()->result();
            foreach ($res as $k=>$v){
            	$role[] = $v->role;
            }
            if(!$role)
            	return false;
            $result = $this->db->select('module')->from($this->tables[2])->where_in('role',$role)->get()->result();
            foreach ($result as $v){
            	$module[] = $v->module;
            }
			if(!$role)
            	return false;

            $user_info['module'] = $module;*/
            $this->session->set_userdata($user_info);

            return true;
        } else {
            return false;
        }
    }
    
    

}