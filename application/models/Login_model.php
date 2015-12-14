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
        
        $rs = $this->db->select()->from('users')
        		->where('username', $username)
        		->where('pwd', sha1($passwd))
        		->where('status', 1)
        		->get()->row_array();
        
        if($rs){
        	$company = $this->db->select('id,name,status')->from('company')->where('id',$rs['company_id'])->get()->row_array();
        	if($company['status'] == '-1'){
        		return -2; //上级公司已经被停用
        	}
        	$data['company'] = $company;
            $operation = array();
            $menu = array();
            //取用户角色
            $res = $this->db->select('a.operation,b.mid')->from('rule_operation a')
            		->join('operation_menu b','a.operation=b.operation','left')
            		->where('rid',$rs['rule_id'])->get()->result();
            foreach ($res as $k=>$v){
            	$operation[] = $v->operation;
            	if(!in_array($v->mid, $menu)){
            		$menu[] = $v->mid;
            	}
            }
            $data['operation'] = $operation;
            $data['menu'] = $menu;
            $data['user_info'] = $rs;
            $data['supper'] = $rs['supper'];
            $this->session->set_userdata($data);
            return 1;
        }else{
        	return -1;//用户名或密码错误
        }
    }
    
    

}