<?php
class Banner_model extends CI_Model {
    private $_param = array(
        'title' => 'string',
        'link' => 'string',
        'image' => 'text',
        'position' => 'number',
        'content_cat_id' => 'number',
        'type' => 'string',
        'adsense' => 'string',
        'html5' => 'string',
        'sort_order' => 'number',
        'status' => 'int'
    );
    private $_table = 'banners';
    private $_controller = 'banner';
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    private function _Post_Data() {
        $post = $this->input->post();
        foreach($post as $field => $type) {
            if(!array_key_exists($field, $this->_param)) {
                unset($post[$field]);
            } else {
                if($this->_param[$field] == 'string') {
                    $post[$field] = $post[$field];
                } elseif($this->_param[$field] == 'number') {
                    $post[$field] = intval($post[$field]);
                } else {
                    $post[$field] = trim($post[$field]);
                }
            }
        }
        if($this->uri->segment(3) == 'add') $post['create_time'] = date("Y-m-d H:i:s", time());
        if($this->uri->segment(3) == 'change') $post['update_time'] = date("Y-m-d H:i:s", time());
        return $post;
    }
    
    function _Count_All() {
        $this->db->select('id')->from($this->_table);
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get($this->_table)->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get($this->_table)->row_array();
    }
    
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from($this->_table)->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
	
    function _Add() {
        $post_data = $this->_Post_Data();
        
        $this->db->insert($this->_table, $post_data);
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Update($id) {
        $post_data = $this->_Post_Data();
        
        $this->db->where('id', $id);
        
        $this->db->update($this->_table, $post_data);
        
        return $id;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', $id)->update($this->_table, array(
            'status' => ($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update($this->_table, array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete($this->_table);
        }
    }
}