<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cw_filemanage extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
	}
	
	public function list_reports($page=1) {
		
		$this->show('finance/list_reports');
	}
}