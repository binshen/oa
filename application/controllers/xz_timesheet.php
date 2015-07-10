<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xz_timesheet extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('executive_model');
	}
	
	public function index() {
		$this->show('executive/list_timesheet');
	}
	
	public function list_timesheet($page=1){
		$this->show('executive/list_timesheet');
	}
}