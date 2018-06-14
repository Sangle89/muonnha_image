<?php
class Gallery_image_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All_Image($status = '') {
        $this->db->select('id')->from('gallery_image');
        if($status) {
            $this->db->where('status', $status);
        }
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_Image($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        $result = $this->db->get('gallery_image')->result_array();
        
        return $result;
    }
    
    function _Count_Image_By_Gallery($gallery_id=NULL, $status = '') {
        $this->db->select('id')->from('gallery_image');
        if($gallery_id)
            $this->db->where('gallery_id', $gallery_id);
        if($status) 
            $this->db->where('status', $status);
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_Image_By_Gallery($gallery_id=NULL, $per_page=9999, $page=0, $status='') {
        $this->db->select('c.*,cc.title AS gallery_title');
        $this->db->from('gallery_image c');
        $this->db->join('gallery cc', 'cc.id=c.gallery_id', 'LEFT');
        
        if($gallery_id)
            $this->db->where('c.gallery_id', $gallery_id);
        
        if($status) 
            $this->db->where('c.status', $status);
        
        $this->db->order_by('c.create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get()->result_array();
        
        return $result;
    }
    
    function _Get_Image_By_Id($id) {
        return $this->db->where('id', $id)->get('gallery_image')->row_array();
    }
    
    function _Add_Image($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('gallery_image', array(
            'title' => trim(strip_tags($data['title'])),
            'title_en' => trim(strip_tags($data['title_en'])),
            'image' => $data['image'],
            'gallery_id' => intval($data['gallery_id']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => time(),
            'status' => $data['status'],
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'content', $id);
        
        return $id;
    }
    
    function _Update_Image($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('gallery_image', array(
            'title' => trim(strip_tags($data['title'])),
            'title_en' => trim(strip_tags($data['title_en'])),
            'image' => $data['image'],
            'gallery_id' => intval($data['gallery_id']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => time(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('gallery_image', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_View($id) {
        $this->db->query("UPDATE `gallery_image` SET views=(views+1) WHERE `id`='".$id."'");
    }
    
    function _Delete_Image($id) {
        $category = $this->_Get_Image_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('gallery_image');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink(FCPATH . 'public/uploads/images/' . $category['image']);
        }
        
    }
}