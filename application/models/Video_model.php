<?php
class Video_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('video');
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
        
        $result = $this->db->get('video')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('video')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('video', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'youtube_url' => trim($data['youtube_url']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => time(),
            'status' => $data['status'],
           // 'group_id' => intval($data['group_id'])
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('video', array(
            'title' => trim($data['title']),
            'image' => $data['image'],
            'youtube_url' => $data['youtube_url'],
            'sort_order' => intval($data['sort_order']),
            'update_time' => time(),
            'status' => $data['status'],
          //  'group_id' => intval($data['group_id'])
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('video', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('video');
            @unlink('./public/uploads/images/' . $category['image']);
        }
    }
}