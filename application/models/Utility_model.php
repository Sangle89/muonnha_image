<?php
class Utility_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
		$this->table = 'utilities';
    }
    
    function _Count_All() {
        $this->db->select('id')->from('utilities');
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('utilities')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('utilities')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('utilities', array(
            'title' => trim($data['title']),
            'image' => trim($data['image']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => date("Y-m-d H:i:s", time())
        ));
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('utilities')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('utilities', array(
            'title' => trim($data['title']),
            'image' => trim($data['image']),
            'sort_order' => intval($data['sort_order']),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('utilities', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('utilities');
            @unlink('./public/uploads/images/' . $category['image']);
        }
    }
}