<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Content_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('htmlpurifier');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('contents');
        if($status) {
            $this->db->where('status', $status);
        }
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        $this->db->select('c.*,cc.title AS category_title,u.username');
        $this->db->from('contents c');
        $this->db->join('content_categories cc', 'cc.id=c.category_id', 'LEFT');
        $this->db->join('users u', 'u.id=c.create_by');
        
        if($status) {
            $this->db->where('c.status', $status);
        }
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        $result = $this->db->get()->result_array();
        
        return $result;
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('contents')->result_array();
    }
    
    function _Count_All_By_Category($category_id, $status = '') {
        $this->db->select('id')->from('contents');
        $this->db->where('category_id', $category_id);
        if($status) 
            $this->db->where('status', $status);
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_By_Category($category_id, $per_page=9999, $page=0, $status='') {
        $this->db->select('c.*,cc.title AS category_title,u.username');
        $this->db->from('contents c');
        $this->db->join('content_categories cc', 'cc.id=c.category_id', 'LEFT');
        $this->db->join('users u', 'u.id=c.create_by');
        
        $this->db->where('c.category_id', $category_id);
        
        if($status) 
            $this->db->where('c.status', $status);
        
        $this->db->order_by('c.create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get()->result_array();
        
        return $result;
    }
    
    function _Count_All_By_Category2($category_id, $status='') {
       return $this->db->query("SELECT c.id FROM cis_contents c
        WHERE category_id='".$category_id."' OR category_id IN (SELECT id FROM cis_content_categories c1 WHERE parent_id='".$category_id."') ORDER BY create_time DESC")
        ->num_rows();
    }
    
    function _Get_All_By_Category2($category_id, $per_page=9999, $page=0, $status='') {
        
       return $this->db->query("SELECT c.id,c.title,c.url_alias,c.image,c.create_time FROM cis_contents c
        WHERE category_id='".$category_id."' OR category_id IN (SELECT id FROM cis_content_categories c1 WHERE parent_id='".$category_id."') ORDER BY create_time DESC LIMIT ".$page.", ".$per_page)
        ->result_array();
        
    }
    
    function _Count_Hide() {
        return $this->db->select('id')->from('contents')->where('status','hide')->get()->num_rows();
    }
    
    function _Count_Active() {
        return $this->db->select('id')->from('contents')->where('status','active')->get()->num_rows();
    }
    
    function _Get_All_Search($keyword) {
        return $this->db->like('title', trim($keyword))
        ->or_like('keywords', trim($keyword))
        ->or_like('description', trim($keyword))
        ->get('contents')->result_array();
    }
    
    function _Get_Related($content , $per_page=9999, $page=0, $status='') {
        $this->db->where('category_id', $content['category_id']);
        $this->db->where('id!='.$content['id']);
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('contents')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('contents')->row_array();
    }
    
    function _Get_By_Url_Alias($string) {
        return $this->db->where('url_alias', trim($string))->get('contents')->row_array();
    }
    
    function _Get_First_By_Category_Id($category_id) {
        return $this->db->order_by('sort_order','ASC')->limit(1)->get('contents')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('contents')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['meta_title']);
        $this->db->insert('contents', array(
            'title' => trim(strip_tags($data['title'])),
            'meta_title' => trim(strip_tags($data['meta_title'])),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            'image' => $data['image'],
            'category_id' => intval($data['category_id']),
            'short_content' => trim(strip_tags($data['short_content'])),
            'content' => htmlpurifier1($data['content']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status'],
            'create_by' => $this->ion_auth->get_user_id(),
            'feature' => isset($data['feature']) ? 1 : 0
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'content', $id);
        
        $this->cache->clean();
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('contents', array(
            'title' => trim(strip_tags($data['title'])),
            'meta_title' => trim(strip_tags($data['meta_title'])),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'image' => $data['image'],
            'category_id' => intval($data['category_id']),
            'short_content' => trim(strip_tags($data['short_content'])),
            'content' => htmlpurifier1($data['content']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status'],
            'feature' => isset($data['feature']) ? 1 : 0
        ));
        
        $this->cache->clean();
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('contents', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('contents', array('sort_order'=>$value));
    }
    
    function _Update_View($id) {
        $db_prefix = $this->db->dbprefix('contents');
        $this->db->query("UPDATE ".$db_prefix."`contents` SET views=(views+1) WHERE `id`='".$id."'");
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('contents');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink(FCPATH . 'public/uploads/images/' . $category['image']);
            if($category['images']) {
                $images = unserialize($category['images']);
                foreach($images as $image) {
                    @unlink(FCPATH . 'public/uploads/images/' . $image);
                    @unlink(FCPATH . 'public/uploads/images/thumbs/' . $image);
                }
            }
        }
        $this->cache->clean();
    }
}