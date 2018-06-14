<?php
class District_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function _Get_All($city_id = 0) {
        if($city_id) {
            $this->db->where('city_id', $city_id);
        }
        return $this->db->get('district')->result_array();
    }
    
    function _Count_All($city_id=0) {
        $this->db->select('id')->from('district');
        if($city_id) {
            $this->db->where('city_id', $city_id);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('district')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('district')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('district')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('district', array(
            'title' => $data['title'],
            'alias' => $alias,
            'city_id' => $data['city_id'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'create_time' => _Current_Date()
        ));
        $id = $this->db->insert_id();
        
        if($data['streets']) {
            foreach($data['streets'] as $street_id) {
                $this->db->insert('district_streets', array(
                   'district_id' => $id,
                    'street_id' => $street_id
                ));
            }
        }
         
        $this->routes_model->_Add_Route($alias, 'district', $id, array(
            'city_id' => $data['city_id']
        ));
        
        //Add route search
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            if($this->link_model->_Check_Link_District($category['id'], $id)==0){
                $this->link_model->_Add_Link_District($category['id'], $id);
            }
        }
        
        foreach($all_category as $category) {
            foreach($data['streets'] as $street_id) {
                if($this->link_model->_Check_Link_Street($category['id'], $street_id)==0){
                    $this->link_model->_Add_Link_Street($category['id'], $street_id);
                }
            }
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('district', array(
            'title' => $data['title'],
            'city_id' => $data['city_id'],
           
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'update_time' => _Current_Date()
        ));
        
        $this->db->where('district_id', $id)->delete('district_streets');
        if($data['streets']) {
            foreach($data['streets'] as $street_id) {
                $this->db->insert('district_streets', array(
                   'district_id' => $id,
                    'street_id' => $street_id
                ));
            }
        }
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            foreach($data['streets'] as $street_id) {
                if($this->link_model->_Check_Link_Street($category['id'], $street_id)==0){
                    $this->link_model->_Add_Link_Street($category['id'], $street_id);
                }
            }
        }
    }
    
    function _Get_Street($district_id) {
        $results = $this->db->where('district_id', $district_id)->get('district_streets')->result_array();
        $array = array();
        foreach($results as $val) {
            $array[] = $val['street_id'];
        }
        return $array;
    }
    
    function _Get_Dropdown($city_id=0) {
        $results = $this->_Get_All($city_id);
        $option = array();
        $option[0] = '-- Quận/Huyện --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', $id)->update('district', array(
            'status' => $status
        ));
    }
    
    function _Update_Sort($id, $sort_order) {
        $this->db->where('id', $id)->update('district', array(
            'sort_order' => $sort_order
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('district');
        
        $this->db->where('controller', 'district')
        ->where('value', $id)->delete('app_routes');
        
        //Xoa link search
        $this->db->where('controller', 'search')
        ->where('search_level', 'district')
        ->where('district_id', $id)
        ->delete('app_routes');
    }
}