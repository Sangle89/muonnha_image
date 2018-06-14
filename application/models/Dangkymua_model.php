<?php
class Dangkymua_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('dangkymua');
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('dangkymua')->result_array();
        
        return $result;
    }
    
    function _Count_Unview($limit=9999) {
        return $this->db->select('id')->from('dangkymua')->where('viewed', 0)->limit($limit)->get()->num_rows();
    }
    
    function _Get_Unview() {
        return $this->db->where('viewed=0')->order_by('create_time','DESC')->get('dangkymua')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('dangkymua')->row_array();
    }
    
    function _Update_Viewed($id) {
        $contact = $this->_Get_Contact_By_Id($id);
        if($contact['viewed']!=1)
            $this->db->where('id', intval($id))->update('dangkymua', array(
                'viewed' => 1
            ));
    }
    
    function _Add($data) {
        
        $this->db->insert('dangkymua', array(
            'duan_id' => $data['duan_id'],
            'fullname' => trim($data['fullname']),
            'email' => trim($data['email']),
            'phone' => trim($data['phone']),
            'address' => trim($data['address']),
            'message' => trim($data['message']),
            //'create_time' => date(time(), 'Y-m-d H:i:s')
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('dangkymua');
    }
}
    