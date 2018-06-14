<?php

class Slider_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('slides');
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
        
        $result = $this->db->get('slides')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('slides')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('slides', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'link' => trim($data['link']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status'],
            'create_by' => $this->ion_auth->get_user_id()
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('slides', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'link' => $data['link'],
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status'],
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('slides', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('slides', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('slides');
            @unlink('./public/uploads/images/' . $category['image']);
        }
        
    }
}