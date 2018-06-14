<?php

class Seo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All() {
        $this->db->select('id')->from('seo');
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('seo')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('seo')->row_array();
    }
    
    function _Add($data) {
        $this->db->insert('seo', array(
            'title' => trim($data['title']),
            'keyword' => trim($data['keyword']),
            'description' => trim($data['description']),
            'url' => trim($data['url'])
        ));
        
        $id = $this->db->insert_id();
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('seo', array(
            'title' => trim($data['title']),
            'keyword' => trim($data['keyword']),
            'description' => trim($data['description']),
            'url' => trim($data['url'])
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
        $this->db->where('id', $id)->update('seo', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('seo', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('seo');
    }
}