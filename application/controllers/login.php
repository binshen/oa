<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		ini_set('date.timezone','Asia/Shanghai');
		$this->load->model('login_model');
	}
	
	public function index() {
		
		$this->cismarty->display('login.html');
	}
	
	public function check_login(){
		$rs = $this->login_model->check_login();
		if($rs){
			redirect('/index');
		}else{
			redirect('/login');
		}
	}
}