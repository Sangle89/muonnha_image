<?php
class Content_category_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All_Category($status = '') {
        $this->db->select('id')->from('content_categories');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_Category($per_page=9999, $page=0, $status='') {
        $this->db->distinct();
        $this->db->select('c1.*,c2.title AS parent_title')
        ->from('content_categories c1')
        ->join('content_categories c2', 'c1.parent_id=c2.id', 'LEFT');
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('content_categories')->result_array();
        
        return $result;
    }
    
    function _Get_Category_By_Id($id) {
        return $this->db->where('id', $id)->get('content_categories')->row_array();
    }
    
    function _Get_Category_By_Url_Alias($id) {
        return $this->db->where('url_alias', $id)->get('content_categories')->row_array();
    }
    
    function _Get_All_Category_Main($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', 0);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('content_categories')->result_array();
    }
    
    function _Get_All_Category_Sub($parent_id, $per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', intval($parent_id));
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('content_categories')->result_array();
    }
    
    function _Get_All_Category_Sub_Lv() {
        
    }
    
    function _Add_Category($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('content_categories', array(
            'title' => trim($data['title']),
            'meta_title' => trim($data['meta_title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'content' => htmlpurifier1($data['content']),
            'alias' => $alias,
            'parent_id' => intval($data['parent_id']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status']
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'content_category', $id);
        
        return $id;
    }
    
    function _Update_Category($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('content_categories', array(
            'title' => trim($data['title']),
             'meta_title' => trim($data['meta_title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'parent_id' => intval($data['parent_id']),
            'content' => htmlpurifier1($data['content']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('content_categories', array(
            'status' => trim($status)
        ));
    }
	
	function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('content_categories', array('sort_order'=>$value));
    }
    
    function _Delete_Category($id) {
        $category = $this->_Get_Category_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('content_categories');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink('./public/uploads/images/' . $category['image']);
            if($category['images']) {
                $images = unserialize($category['images']);
                foreach($images as $image) {
                    @unlink(FCPATH . 'public/uploads/images/' . $image);
                    @unlink(FCPATH . 'public/uploads/images/thumbs/' . $image);
                }
            }
        }
        
    }
}