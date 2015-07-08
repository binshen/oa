<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_overtime extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		
	}
	
	public function list_overtime() {
	
		$this->show('/mine/list_overtime');
	}
}