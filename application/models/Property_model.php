<?php

class Property_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('properties');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Count_By_Group($group_id) {
        $this->db->select('id')->from('properties');
        $this->db->where('group_id', $group_id);
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('properties')->result_array();
        
        return $result;
    }
    
    function _Get_By_Group($group_id, $per_page=9999, $page=0, $status='') {
        
        $this->db->where('group_id', $group_id);
        
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('properties')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('properties')->row_array();
    }
    
    function _Get_Dropdown($group_id) {
        $option = array();
        $results = $this->_Get_By_Group($group_id);
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Add($data) {
        
        $this->db->insert('properties', array(
            'title' => trim($data['title']),
            'group_id' => $data['group_id'],
            'sort_order' => intval($data['sort_order'])
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('properties', array(
            'title' => trim($data['title']),
            'group_id' => $data['group_id'],
            'sort_order' => intval($data['sort_order'])
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('properties', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('properties', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('properties');
            @unlink('./public/uploads/images/' . $category['image']);
        }
        
    }
}