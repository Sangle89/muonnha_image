<?php

class Property_group_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('property_groups');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('property_groups')->result_array();
        
        foreach($result as $key=>$val) {
            $property = $this->db->where('group_id', $val['id'])
            ->order_by('sort_order','ASC')
            ->get('properties')->result_array();
            $result[$key]['property'] = $property;
        }
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('property_groups')->row_array();
    }
    
    function _Get_Dropdown() {
        $option = array();
        $results = $this->_Get_All();
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Add($data) {
        
        $this->db->insert('property_groups', array(
            'title' => trim($data['title']),
            'sort_order' => intval($data['sort_order'])
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('property_groups', array(
            'title' => trim($data['title']),
            'sort_order' => intval($data['sort_order'])
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('property_groups', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('property_groups', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('property_groups');
            @unlink('./public/uploads/images/' . $category['image']);
        }
        
    }
}