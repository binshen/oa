<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {
	
	public function __construct() {
		parent::__construct();
	}
	
	function getPageLink($total_rows, $per_page) {
		
		$config['base_url'] = '/test/index/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['use_page_numbers'] = true;
		
		$config['cur_tag_open'] = '<li class="am-active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_link'] = '«';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$config['next_link'] = '»';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		
		$this->initialize($config);
		
		return '共 ' . $total_rows . ' 条记录<div class="am-fr"><ul class="am-pagination">' . $this->create_links() . '</ul></div>';
	}
}
