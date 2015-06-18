<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Tests_model extends MY_Model
{
    
    public function __construct ()
    {
    	parent::__construct();
    }
    
    public function get_count() {
    	
    	$count = $this->db->count_all_results('tests');
    	return $count;
    }
    
    public function get_tests($offset, $limit){
    	
    	$results = $this->db->limit($limit, $offset)->get('tests')->result_array();
    	return $results;
    }
}