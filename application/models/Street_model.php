<?php
class Street_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function _Get_All($per_page, $page) {
        
        return $this->db->order_by('title')->limit($per_page, $page)->get('streets')->result_array();
    }
    
    function _Get_Street_By_District($district_id) {
        return $this->db->select('t1.id,t1.title,t1.alias')
                ->from('streets t1')
                ->join('district_streets t2', 't2.street_id=t1.id')
                ->where('status', 'active')
                ->where('t2.district_id', $district_id)
                ->order_by('t1.title')
                ->get()->result_array();
    }
    
    function _Count_All() {
        $this->db->select('id')->from('streets');
        
        return $this->db->get()->num_rows();
    }
    
    function _Get_Search($string, $cat_id = NULL) {
        $this->db->select('id,title,alias,status,sort_order,create_time')
        ->from('streets')
        ->like('title', $string);
        
        if($cat_id) $this->db->where('ward_id', $cat_id);
        
        return $this->db->get()->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('streets')->row_array();
    }
    
    function _Get_By_Alias($alias) {
        return $this->db->where('alias', $alias)->get('streets')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('streets')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('streets', array(
            'title' => $data['title'],
            'alias' => 'duong-'.$alias,
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'ward_id' => $data['ward_id'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'create_time' => _Current_Date()
        ));
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'street', $id, array(
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'ward_id' => $data['ward_id']
        ));
        
        //Add route search
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            if($this->link_model->_Check_Link_Street($category['id'], $id)==0){
                $this->link_model->_Add_Link_Street($category['id'], $id);
            }
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('streets', array(
            'title' => $data['title'],
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'ward_id' => $data['ward_id'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
            'update_time' => _Current_Date()
        ));
    }
    
    function _Get_By_City($city_id) {
        return $this->db->where('city_id', $city_id)->get('streets')->result_array();
    }
    
    
    
    function _Get_Dropdown($district_id=0) {
        $option = array();
        $option[0] = '-- Đường/Phố --';
        if($district_id > 0) {
        $results = $this->db->select('t1.id,t1.title,t1.alias')
                ->from('streets t1')
                ->join('district_streets t2', 't2.street_id=t1.id')
                ->where('status', 'active')
                ->where('t2.district_id', $district_id)
                ->order_by('t1.title')
                ->get()->result_array();
        
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        }
       
        return $option;
    }
    
    function _Get_Dropdown2($city_id=0) {
        $results = $this->_Get_By_City($city_id);
        $option = array();
        $option[0] = '-- Đường/Phố --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', $id)->update('streets', array(
            'status' => $status
        ));
    }
    
    function _Update_Sort($id, $sort_order) {
        $this->db->where('id', $id)->update('streets', array(
            'sort_order' => $sort_order
        ));
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('streets');
        $this->db->where('controller', 'street')
        ->where('value', $id)->delete('app_routes');
        
        //Xoa link search
        $this->db->where('controller', 'search')
        ->where('search_level', 'street')
        ->where('street_id', $id)
        ->delete('app_routes');
    }
}