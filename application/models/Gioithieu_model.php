<?php
class Gioithieu_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('gioithieu');
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
        $result = $this->db->get('gioithieu')->result_array();
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('gioithieu')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('url_alias', $id)->get('gioithieu')->row_array();
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        
        $this->db->insert('gioithieu', array(
            'title' => trim($data['title']),
            'title_en' => trim($data['title_en']),
            'images' => serialize($data['images']),
            'content' => htmlpurifier($data['content']),
            'content_en' => htmlpurifier($data['content_en']),
            'sort_order' => intval($data['sort_order']),
        ));
        
        $id = $this->db->insert_id();
       
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('gioithieu', array(
            'title' => trim($data['title']),
            'title_en' => trim($data['title_en']),
            'images' => serialize($data['images']),
            'content' => htmlpurifier($data['content']),
            'content_en' => htmlpurifier($data['content_en']),
            'sort_order' => intval($data['sort_order']),
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('gioithieu', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('gioithieu');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink(FCPATH . 'public/uploads/images/' . $category['image']);
        }
    }
}