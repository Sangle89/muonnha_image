<?php
class Country_model extends CI_Model {
	//Country
    public function _Get_All_Country($per_page = 9999, $page = 0) {
        return $this->db->order_by('title')->limit($per_page, $page)->get('country')->result_array();
    }
    function _Get_Country_By_Id($id) {
        return $this->db->where('country_id', intval($id))->get('country')->row_array();
    }
    function _Count_All_Country() {
        return $this->db->get('country')->num_rows();
    }
    function _Add_Country($data) {
        $this->db->insert('country', array(
            'title' => trim($data['title']),
            'code' => trim($data['code'])
        ));
        return $this->db->insert_id();
    }
    function _Update_Country($data, $id) {
        $this->db->where('country_id', intval($id))->update('country', array(
            'title' => trim($data['title']),
            'code' => intval($data['code'])
        ));
    }
    function _Delete_Country($id) {
        $this->db->where('country_id', intval($id))->delete('country');
    }
    
	//City
    public function _Get_All_City($per_page = 9999, $page = 0) {
        return $this->db->select('ci.city_id,ci.title,ci.postal_code, co.title AS country_title')
        ->from('city ci')
        ->join('country co', 'co.country_id=ci.country_id')
        ->order_by('title')
        ->get()->result_array();
    }
    function _Get_All_City_By_Country($country_id, $per_page=9999, $page=0) {
        $this->db->select('ci.city_id,ci.title,ci.postal_code, co.title AS country_title')
        ->from('city ci')
        ->join('country co', 'co.country_id=ci.country_id');
        if($country_id!=null) 
            $this->db->where('ci.country_id', intval($country_id));
        $this->db->order_by('title');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    function _Get_City_By_Id($id) {
        return $this->db->where('city_id', intval($id))->get('city')->row_array();
    }
    function _Count_All_City() {
        return $this->db->get('city')->num_rows();
    }
    function _Count_All_City_By_Country($country_id) {
        return $this->db->where('country_id', intval($country_id))->get('city')->num_rows();
    }
    function _Add_City($data) {
        $this->db->insert('city', array(
            'title' => trim($data['title']),
            'country_id' => intval($data['country_id']),
            'postal_code' => intval($data['postal_code'])
        ));
        return $this->db->insert_id();
    }
    function _Update_City($data, $city_id) {
        $this->db->where('city_id', intval($city_id))->update('city', array(
            'title' => trim($data['title']),
            'country_id' => intval($data['country_id']),
            'postal_code' => intval($data['postal_code'])
        ));
    }
    function _Delete_City($id) {
        $this->db->where('city_id', intval($id))->delete('city');
    }
}