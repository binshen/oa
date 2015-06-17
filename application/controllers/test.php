<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		
		$items = array(
			array('id' => 1, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
			array('id' => 2, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
			array('id' => 3, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
			array('id' => 4, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
			array('id' => 5, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
			array('id' => 6, 'title' => 'Business management', 'type' => 'default', 'author' => '测试1号', 'date' => date('Y-m-d H:i:s')),
		);
		$this->assign('items', $items);
		
		$this->show('test');
	}
}