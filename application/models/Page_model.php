<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Page_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('pages');
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
        $result = $this->db->get('pages')->result_array();
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('pages')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('alias', $id)->get('pages')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('pages')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('pages', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            //'image' => $data['image'],
            'content' => htmlpurifier1($data['content']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status'],
            'create_by' => $this->ion_auth->get_user_id()
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'page', $id);
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('pages', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
          //  'image' => $data['image'],
            'content' => htmlpurifier1($data['content']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('pages', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('pages', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('pages');
            $this->db->where('key', $category['alias'])->delete('app_routes');
            @unlink(FCPATH . 'public/uploads/images/' . $category['image']);
        }
    }
}