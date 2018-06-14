<?php
class Register_model extends CI_Model {
    
    function _Count_All() {
        return $this->db->select('id')->from('register')->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0) {
        return $this->db->order_by('create_time','DESC')->limit($per_page, $page)->get('register')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('register')->row_array();
    }
    
    function _Add($data) {
        
        $this->db->insert('register', array(
            'type' => intval($data['type']),
            'name' => trim($data['name']),
            'fullname' => trim($data['fullname']),
            'position' => trim($data['position']),
            'department' => trim($data['department']),
            'company' => trim($data['company']),
            'website' => trim($data['website']),
            'company_type' => trim($data['company_type']),
            'company_address' => trim($data['company_address']),
            'city' => trim($data['city']),
            'country' => trim($data['country']),
            'postal_code' => trim($data['postal_code']),
            'telephone' => trim($data['telephone']),
            'mobiphone' => trim($data['mobiphone']),
            'fax' => trim($data['fax']),
            'email' => trim($data['email']),
            'create_time' => time()
        ));
        
    }
}