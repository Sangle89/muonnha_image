<?php
class City_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function _Get_All($per_page=999, $page=0) {
        return $this->db->order_by('sort_order','DESC')->limit($per_page, $page)->get('city')->result_array();
    }
    
    function _Count_All() {
        return $this->db->select('id')->from('city')->get()->num_rows();
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('city')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('city')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('city')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        
        $this->db->insert('city', array(
            'title' => $data['title'],
            'alias' => $alias,
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'create_time' => _Current_Date()
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'city', $id);
        
        //Add route search
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            if($this->link_model->_Check_Link_City($category['id'], $id)==0){
                $this->link_model->_Add_Link_City($category['id'], $id);
            }    
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('city', array(
            'title' => $data['title'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'update_time' => _Current_Date()
        ));
    }
    
    function _Get_Dropdown() {
        $results = $this->_Get_All();
        $option = array();
        $option[0] = '-- Tỉnh/TP --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', $id)->update('city', array(
            'status' => $status
        ));
    }
    
    function _Update_Sort($id, $sort_order) {
        $this->db->where('id', $id)->update('city', array(
            'sort_order' => $sort_order
        ));
    }
    
    function _Delete($id) {
        //Xoa 1 dòng city
        $this->db->where('id', $id)->delete('city');
        
        //Xoa route city
        $this->db->where('controller', 'city')
        ->where('value', $id)
        ->delete('app_routes');
        
        //Xoa link search
        $this->db->where('controller', 'search')
        ->where('search_level', 'city')
        ->where('city_id', $id)
        ->delete('app_routes');
    }
}