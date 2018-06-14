<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Download_model extends CI_Model {
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('download');
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
        
        $result = $this->db->get('download')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('download')->row_array();
    }
    
    function _Add($data) {
        $this->db->insert('download', array(
            'title' => trim($data['title']),
            'file_name' => $data['file_name'],
            'create_time' => date('Y-m-d H:i:s', time()),
            'status' => $data['status']
        ));
        $id = $this->db->insert_id();
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('download', array(
            'title' => trim($data['title']),
            'file_name' => $data['file_name'],
            'update_time' => date('Y-m-d H:i:s', time()),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('download', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete($id) {
        $category = $this->_Get_Banner_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('download');
            @unlink('./public/uploads/images/' . $category['file_name']);
        }
    }
}