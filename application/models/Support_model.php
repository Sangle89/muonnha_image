<?php

class Support_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('supports');
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
        
        $result = $this->db->get('supports')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('supports')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('supports', array(
            'title' => trim($data['title']),
            'phone' => trim($data['phone']),
            'email' => trim($data['email']),
            'address' => trim($data['address']),
            'skype' => trim($data['skype']),
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
        $this->db->update('supports', array(
            'title' => trim($data['title']),
            'phone' => trim($data['phone']),
            'email' => trim($data['email']),
            'address' => trim($data['address']),
            'skype' => trim($data['skype']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status'],
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('supports', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('supports', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('supports');
            @unlink('./public/uploads/images/' . $category['image']);
        }
        
    }
}