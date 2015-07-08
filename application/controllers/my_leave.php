<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_leave extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		
	}
	
	public function list_leave() {
		
		$this->show('/mine/list_leave');
	}
}