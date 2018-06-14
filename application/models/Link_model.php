<?php
class Link_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function _Count_All() {
        return $this->db->where('controller', 'search')->get('app_routes')->num_rows();
    }
    
    function _Get_All($per_page, $page) {
        return $this->db->where('controller', 'search')->order_by('key')->limit($per_page, $page)->get('app_routes')->result_array();
    }
    
    function _Get_Link_City() {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'city')
        ->get('app_routes')
        ->result_array();
    }
    
    function _Add_Link_City($category_id, $city_id) {
        $category = $this->db->select('alias')->from('categories')->where('id', $category_id)->get()->row_array();
        $city = $this->db->select('alias')->from('city')->where('id', $city_id)->get()->row_array();
        $this->load->model('routes_model');
        $key = $this->routes_model->_Create_Key($category['alias'].'-'.$city['alias']);
        $this->db->insert('app_routes', array(
           'key' => $key,
           'controller' => 'search_level',
           'category_id' => $category_id,
           'city_id' => $city_id,
           'search_level' => 'city' 
        ));
    }
    
    function _Check_Link_City($category_id, $city_id) {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'city')
        ->where('category_id', $category_id)
        ->where('city_id', $city_id)
        ->get('app_routes')
        ->num_rows();
    }
    
    function _Get_Link_By_City($category_id, $city_id) {
        return $this->db->select('key')->from('app_routes')->where('controller', 'search')
        ->where('search_level', 'city')
        ->where('category_id', $category_id)
        ->where('city_id', $city_id)
        ->get()
        ->row_array();
    }
    
    /**
    * Search level district
    */
    function _Get_Link_District() {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'district')
        ->get('app_routes')
        ->result_array();
    }
    
    function _Add_Link_District($category_id, $district_id) {
        $category = $this->db->select('alias')->from('categories')->where('id', $category_id)->get()->row_array();
        $city = $this->db->select('alias,city_id')->from('district')->where('id', $district_id)->get()->row_array();
        $this->load->model('routes_model');
        $key = $this->routes_model->_Create_Key($category['alias'].'-'.$city['alias']);
        $this->db->insert('app_routes', array(
           'key' => $key,
           'controller' => 'search',
           'category_id' => $category_id,
           'city_id' => $city['city_id'],
           'district_id' => $district_id,
           'search_level' => 'district' 
        ));
    }
    
    function _Check_Link_District($category_id, $district_id) {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'district')
        ->where('category_id', $category_id)
        ->where('district_id', $district_id)
        ->get('app_routes')
        ->num_rows();
    }
    
    function _Get_Link_By_District($category_id,$city_id) {
        return $this->db->select('key')->from('app_routes')->where('controller', 'search')
        ->where('search_level', 'district')
        ->where('category_id', $category_id)
        ->where('district_id', $city_id)
        ->get()
        ->row_array();
    }
    
    /**
    * Search level ward
    */
    function _Get_Link_Ward() {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'district')
        ->get('app_routes')
        ->result_array();
    }
    
    function _Add_Link_Ward($category_id, $ward_id) {
        $category = $this->db->select('alias')->from('categories')->where('id', $category_id)->get()->row_array();
        $ward = $this->db->select('alias,district_id')->from('wards')->where('id', $ward_id)->get()->row_array();
        $disitrct = $this->db->select('alias,city_id')->from('district')->where('id', $ward['district_id'])->get()->row_array();
        $this->load->model('routes_model');
        $key = $this->routes_model->_Create_Key($category['alias'].'-'.$ward['alias']);
        $this->db->insert('app_routes', array(
           'key' => $key,
           'controller' => 'search',
           'category_id' => $category_id,
           'city_id' => $disitrct['city_id'],
           'district_id' => $ward['district_id'],
           'ward_id' => $ward_id,
           'search_level' => 'ward' 
        ));
    }
    
    function _Check_Link_Ward($category_id, $ward_id) {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'ward')
        ->where('category_id', $category_id)
        ->where('ward_id', $ward_id)
        ->get('app_routes')
        ->num_rows();
    }
    
    function _Get_Link_By_Ward($category_id,$city_id) {
        return $this->db->select('key')->from('app_routes')->where('controller', 'search')
        ->where('search_level', 'ward')
        ->where('category_id', $category_id)
        ->where('ward_id', $city_id)
        ->get()
        ->row_array();
    }
    
    /**
    * Search level street
    */
    function _Get_Link_Street() {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'street')
        ->get('app_routes')
        ->result_array();
    }
    
    function _Add_Link_Street($category_id, $street_id) {
        $category = $this->db->select('alias')->from('categories')->where('id', $category_id)->get()->row_array();
        $street = $this->db->select('alias,ward_id')->from('streets')->where('id', $street_id)->get()->row_array();
        $ward = $this->db->select('alias,district_id')->from('wards')->where('id', $street['ward_id'])->get()->row_array();
        $disitrct = $this->db->select('alias,city_id')->from('district')->where('id', $ward['district_id'])->get()->row_array();
        //print_r($street);
        //print_r($ward);
        //print_r($disitrct);
        $this->load->model('routes_model');
        $key = $this->routes_model->_Create_Key($category['alias'].'-duong-'.$street['alias']);
        
        $this->db->insert('app_routes', array(
           'key' => $key,
           'controller' => 'search',
           'category_id' => $category_id,
           //'city_id' => $disitrct['city_id'],
           //'district_id' => $ward['district_id'],
           //'ward_id' => $street['ward_id'],
           'street_id' => $street_id,
           'search_level' => 'street' 
        ));
    }
    
    function _Check_Link_Street($category_id, $street_id) {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'street')
        ->where('category_id', $category_id)
        ->where('street_id', $street_id)
        ->get('app_routes')
        ->num_rows();
    }
    
    function _Get_Link_By_Street($category_id,$city_id) {
        return $this->db->select('key')->from('app_routes')->where('controller', 'search')
        ->where('search_level', 'street')
        ->where('category_id', $category_id)
        ->where('street_id', $city_id)
        ->get()
        ->row_array();
    }
    
    /**
    * Search level project
    */
    function _Get_Link_Project() {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'project')
        ->get('app_routes')
        ->result_array();
    }
    
    function _Add_Link_Project($category_id, $project_id) {
        $category = $this->db->select('alias')->from('categories')->where('id', $category_id)->get()->row_array();
        $city = $this->db->select('alias')->from('projects')->where('id', $street_id)->get()->row_array();
        $this->load->model('routes_model');
        $key = $this->routes_model->_Create_Key($category['alias'].'-'.$city['alias']);
        $this->db->insert('app_routes', array(
           'key' => $key,
           'controller' => 'search',
           'category_id' => $category_id,
           'project_id' => $project_id,
           'search_level' => 'project' 
        ));
    }
    
    function _Check_Link_Project($category_id, $project_id) {
        return $this->db->where('controller', 'search')
        ->where('search_level', 'project')
        ->where('category_id', $category_id)
        ->where('project_id', $project_id)
        ->get('app_routes')
        ->num_rows();
    }
    
    function _Get_Link_By_Project($category_id, $city_id) {
        return $this->db->select('key')->from('app_routes')->where('controller', 'search')
        ->where('search_level', 'project')
        ->where('category_id', $category_id)
        ->where('project_id', $city_id)
        ->get()
        ->row_array();
    }
}