<?php

class Category_tags_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All() {
        $this->db->select('id')->from('category_tags');
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('category_tags')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('category_tags')->row_array();
    }
    
    function _Add($data) {
        if(!empty($data['tags'])) {
			$tags = implode(",", $data['tags']);
		} else {
			$tags = '';
		}
        $this->db->insert('category_tags', array(
            'category_id' => intval($data['category_id']),
			'city_id' => intval($data['city_id']),
			'district_id' => intval($data['district_id']),
			'ward_id' => intval($data['ward_id']),
			'street_id' => intval($data['street_id']),
            'tags' => $tags
        ));
        
        $id = $this->db->insert_id();
        return $id;
    }
    
    function _Update($data, $id) {
        if(!empty($data['tags'])) {
			$tags = implode(",", $data['tags']);
		} else {
			$tags = '';
		}
        $this->db->where('id', $id);
        $this->db->update('category_tags', array(
            'category_id' => intval($data['category_id']),
			'city_id' => intval($data['city_id']),
			'district_id' => intval($data['district_id']),
			'ward_id' => intval($data['ward_id']),
			'street_id' => intval($data['street_id']),
            'tags' => $tags
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('category_tags', array(
            'status' => trim($status)
        ));
    }
	
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('category_tags', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('category_tags');
    }
}