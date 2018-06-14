<?php

class Tags_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All() {
        $this->db->select('id')->from('real_tags');
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('real_tags')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('real_tags')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('real_tags', array(
            'title' => trim($data['title']),
            'alias' => to_slug($data['title'])
        ));
        
        $id = $this->db->insert_id();
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('real_tags', array(
            'title' => trim($data['title']),
            'alias' => to_slug($data['title'])
        ));
    }
	
	function _Get_Dropdown() {
        $results = $this->_Get_All();
        $option = array();
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('real_tags', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('real_tags', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('real_tags');
    }
}