<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logo_model extends CI_Model {
    
    function _Count_All_Logo($status = '') {
        $this->db->select('id')->from('logo');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_Logo($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('logo')->result_array();
        
        return $result;
    }
    
    function _Get_Logo_By_Category_Id($category_id, $per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('category_id', intval($category_id));
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('logo')->result_array();
        
        return $result;
    }
    
    function _Get_Logo_By_Id($id) {
        return $this->db->where('id', $id)->get('logo')->row_array();
    }
    
    function _Add_Logo($data) {
        
        $this->db->insert('logo', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'link' => trim($data['link']),
          //  'category_id' => intval($data['category_id']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => time(),
            'status' => $data['status']
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update_Logo($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('logo', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'link' => $data['link'],
          //  'category_id' => intval($data['category_id']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => time(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('logo', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete_Logo($id) {
        $category = $this->_Get_Logo_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('logo');
            @unlink('./public/uploads/images/' . $category['image']);
        }
    }
}