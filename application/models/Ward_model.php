<?php
class Ward_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function _Get_All($district_id = 0) {
        if($district_id) {
            $this->db->where('district_id', $district_id);
        }
        return $this->db->get('wards')->result_array();
    }
    
    function _Count_All($district_id=0) {
        $this->db->select('id')->from('wards');
        if($district_id) {
            $this->db->where('district_id', $district_id);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('wards')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('wards')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('wards')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('wards', array(
            'title' => $data['title'],
            'alias' => $alias,
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'create_time' => _Current_Date()
        ));
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'ward', $id, array(
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
        ));
        
        //Add route search
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            if($this->link_model->_Check_Link_Ward($category['id'], $id)==0){
                $this->link_model->_Add_Link_Ward($category['id'], $id);
            }
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('wards', array(
            'title' => $data['title'],
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'update_time' => _Current_Date()
        ));
    }
    
    function _Get_Dropdown($district_id=0) {
        $option = array();
        $option[0] = '-- Phường/Xã --';
        if($district_id > 0) {
            $results = $this->_Get_All($district_id);
        
            foreach($results as $val) {
                $option[$val['id']] = $val['title'];
            }
        }
        
        return $option;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', $id)->update('wards', array(
            'status' => $status
        ));
    }
    
    function _Update_Sort($id, $sort_order) {
        $this->db->where('id', $id)->update('wards', array(
            'sort_order' => $sort_order
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('wards');
        $this->db->where('controller', 'ward')
        ->where('value', $id)->delete('app_routes');
        
        //Xoa link search
        $this->db->where('controller', 'search')
        ->where('search_level', 'ward')
        ->where('ward_id', $id)
        ->delete('app_routes');
    }
}