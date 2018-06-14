<?php

class Web_link_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('web_links');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_By_Parent($parent=0, $status='') {
        $this->db->where('parent_id', $parent);
        if($status) {
            $this->db->where('status', $status);
        }
        $result = $this->db->get('web_links')->result_array();
        
        return $result;
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('web_links')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('web_links')->row_array();
    }
    
    function _Add($data) {
        $this->db->insert('web_links', array(
            'title' => trim($data['title']),
            'link' => $data['link'],
            'parent_id' => $data['parent_id'],
            'category_id' => intval($data['category_id']),
            'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'sort_order' => intval($data['sort_order']),
            'show_footer' => isset($data['show_footer']) ? intval($data['show_footer']) : 0,
			'show_detail' => isset($data['show_detail']) ? intval($data['show_detail']) : 0
        ));
        
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('web_links', array(
            'title' => trim($data['title']),
            'link' => $data['link'],
            'parent_id' => $data['parent_id'],
            'category_id' => intval($data['category_id']),
            'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'sort_order' => intval($data['sort_order']),
            'show_footer' => isset($data['show_footer']) ? intval($data['show_footer']) : 0,
			'show_detail' => isset($data['show_detail']) ? intval($data['show_detail']) : 0
        ));
    }
    
    function _Get_Dropdown() {
        $results = $this->_Get_All();
        $option = array();
        $option[0] = '-- No parent --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('web_links', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('web_links', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('web_links');
            @unlink('./public/uploads/images/' . $category['image']);
        }
        
    }
}