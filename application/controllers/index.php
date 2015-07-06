<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

	/**
	 * Index Page for this controller.
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('index_model');
		
		$this->load->model('executive_model');
	}
	
	public function index()
	{
		$this->display('layout/index.html');
	}
	
	public function main(){
		
		$bulletins = $this->executive_model->display_bulletin();
		$this->assign('bulletins', $bulletins);
		
		$this->show('index');
	}
	
	public function list_user(){
		for($i=0;$i<10000000;$i++){
			
		}
		
		
		$this->show('list_user');
	}
	


}
