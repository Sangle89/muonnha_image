<?php
class Gallery_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All_Gallery($status = '') {
        $this->db->select('id')->from('gallery');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_Gallery($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('gallery')->result_array();
        
        return $result;
    }
    
    function _Get_Gallery_By_Id($id) {
        return $this->db->where('id', $id)->get('gallery')->row_array();
    }
    
    function _Get_Gallery_By_Url_Alias($url_alias) {
        return $this->db->where('url_alias', $url_alias)->get('gallery')->row_array();
    }
    
    function _Get_All_Gallery_Main($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', 0);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('gallery')->result_array();
    }
    
    function _Get_All_Gallery_Sub($parent_id, $per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', intval($parent_id));
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('gallery')->result_array();
    }
    
    function _Get_All_Gallery_Lv($status = '') {
        $Gallery = $this->_Get_All_Gallery_Main(9999, 0, $status);
        foreach($Gallery as $i => $val) {
            $Gallery[$i]['sub'] = $this->_Get_All_Gallery_Sub($val['id']);
            
        }
        return $Gallery;
    }
    
    function _Add_Gallery($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('gallery', array(
            'title' => trim($data['title']),
            'title_en' => trim($data['title_en']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => time(),
            'status' => $data['status']
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'Gallery', $id);
        
        return $id;
    }
    
    function _Update_Gallery($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('gallery', array(
            'title' => trim($data['title']),
            'title_en' => trim($data['title_en']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => time(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('gallery', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete_Gallery($id) {
        $Gallery = $this->_Get_Gallery_By_Id($id);
        if($Gallery) {
            $this->db->where('id', $id)->delete('gallery');
            $this->db->where('key', $Gallery['url_alias'])->delete('app_routes');
            @unlink('./public/uploads/images/' . $Gallery['image']);
            if($Gallery['images']) {
                $images = unserialize($Gallery['images']);
                foreach($images as $image) {
                    @unlink(FCPATH . 'public/uploads/images/' . $image);
                    @unlink(FCPATH . 'public/uploads/images/thumbs/' . $image);
                }
            }
        }
        
    }
}