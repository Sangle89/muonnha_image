<?php
class Setting_model extends CI_Model {
    
    private $setting = array(
        'title',
        'keywords',
        'description',
        'telephone',
        'mobiphone',
        'fax',
        'email',
        'emails',
        'address',
        'status',
        'video_url',
        'facebook',
        'google_plus',
        'twitter',
        'youtube'
    );
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Get_Setting($field = '') {
        if($field) {
            $result = $this->db->select($field)->from('settings')->get()->row_array();
            return $result[$field];
        }else{
            return $this->db->get('settings')->row_array();
        }
    }
    
    function _Update_Setting($data) {
        foreach($this->setting as $setting_key){
            $update[$setting_key] = $data[$setting_key];  
        }
        $this->db->where('id', 1);
        $this->db->update('settings', $update);
       
    }
    
    function _Update_Email($data) {
        $this->db->where('id', 1);
        $this->db->update('settings', array(
            'smtp_email' => $data['smtp_email'],
            'smtp_password' => $data['smtp_password'],
        ));
    }
}