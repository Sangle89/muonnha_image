<?php
class Contact_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('contacts');
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('contacts')->result_array();
        
        return $result;
    }
    
    function _Count_Unview($limit=9999) {
        return $this->db->select('id')->from('contacts')->where('viewed', 0)->limit($limit)->get()->num_rows();
    }
    
    function _Get_Unview() {
        return $this->db->where('viewed=0')->order_by('create_time','DESC')->get('contacts')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('contacts')->row_array();
    }
    
    function _Update_Viewed($id) {
        $contact = $this->_Get_By_Id($id);
        if($contact['viewed']!=1)
            $this->db->where('id', intval($id))->update('contacts', array(
                'viewed' => 1
            ));
    }
    
    function _Add($data) {
        
        $this->db->insert('contacts', array(
            'fullname' => trim($data['fullname']),
            'email' => trim($data['email']),
            'phone' => trim($data['phone']),
            'address' => trim($data['address']),
           // 'subject' => trim($data['subject']),
            'message' => trim($data['message']),
			'viewed' => 'no',
            'create_time' => time()
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('contacts');
    }
}
    