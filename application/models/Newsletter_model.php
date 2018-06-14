<?php
class Newsletter_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('newsletter');
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('newsletter')->result_array();
        
        return $result;
    }
    
    function _Add($data) {
        $email = $this->db->where('email', $data['email'])->get('newsletter')->row_array();
        if(!$email){
            $this->db->insert('newsletter', array(
          //  'name' => trim($data['fullname']),
            'email' => trim($data['email']),
            'create_time' => time()
        ));
        }
        
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('newsletter');
    }
}
    