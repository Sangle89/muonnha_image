<?php
class Hoidap_model extends CI_Model{
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('hoidap');
        
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('hoidap')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('hoidap')->row_array();
    }
    
    function _Add($data) {
        $this->db->insert('hoidap', array(
            'title' => trim($data['title']),
            'content' => trim($data['content']),
            'title_en' => trim($data['title_en']),
            'content_en' => trim($data['content_en']),
        ));
        return $this->db->insert_id();
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('hoidap', array(
            'title' => trim($data['title']),
            'content' => trim($data['content']),
            'title_en' => trim($data['title_en']),
            'content_en' => trim($data['content_en']),
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('hoidap');
    }
}