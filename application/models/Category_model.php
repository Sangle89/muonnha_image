<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('categories');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_Field($id, $field) {
        $result = $this->db->select($field)->from('categories')->where('id', $id)->get()->row_array();
        return $result[$field];
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('categories')->result_array();
        
        return $result;
    }
    
    function _Get_All_Home($status = 'active') {
        return $this->db->where('status', $status)->where('show_home', 1)->order_by('sort_order', 'ASC')->get('categories')->result_array();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('categories')->row_array();
    }
    
    function _Get_By_Url_Alias($url_alias) {
        return $this->db->where('url_alias', $url_alias)->get('categories')->row_array();
    }
    
    function _Get_All_Main($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', 0);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('categories')->result_array();
    }
    
    function _Get_All_Sub($parent_id, $per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('parent_id', intval($parent_id));
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        return $this->db->get('categories')->result_array();
    }
    
    function _Dequy_Dropdown_Category($category, $lv=0, &$dropdown) {
        foreach($category as $val) {
            $dropdown[$val['id']] = _Space($lv,$val['title']);
            $sub = $this->_Get_All_Sub($val['id']);
            if($sub) {
                $this->_Dequy_Dropdown_Category($sub, $lv+1, $dropdown);
            }
        }
    }
    
    function _Dropdown_Category() {
        $main_category = $this->_Get_All_Main();
        $dropdown = array();
        $dropdown[0] = '--Danh mục gốc--';
        $this->_Dequy_Dropdown_Category($main_category, 0,$dropdown);
        return $dropdown;
    }
    
    function _Get_All_Lv($parent_id=0, $status = '') {
        //$category = $this->_Get_All_Main(9999, 0, $status);
        $category = $this->_Get_All_Sub($parent_id);
        if($category) {
            
            foreach($category as $i=>$val) {
                $category[$i]['sub'] = $this->_Get_All_Lv($val['id']);
            }
        } 
        
        return $category;
    }
    
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('categories', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            'parent_id' => intval($data['parent_id']),
            'content' => htmlpurifier($data['content']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status']
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'category', $id);
        
		$city = $this->db->get('city')->result_array();
        foreach($city as $ci) {
            $key = $this->routes_model->_Create_Key($alias.'-'.$ci['alias']);
            $this->db->insert('app_routes', array(
               'key' => $key,
               'controller' => 'search',
               'category_id' => $id,
               'city_id' => $ci['id'],
               'search_level' => 'city' 
            ));
        }
        
        $district = $this->db->get('district')->result_array();
        foreach($district as $ci) {
            $key = $this->routes_model->_Create_Key($alias.'-'.$ci['alias']);
            $this->db->insert('app_routes', array(
               'key' => $key,
               'controller' => 'search',
               'category_id' => $id,
               'district_id' => $ci['id'],
               'search_level' => 'district' 
            ));
        }
        
        $street = $this->db->select('t2.district_id,t1.alias,t1.id')->from('streets t1')->join('district_streets t2', 't2.street_id=t1.id')->get()->result_array();
        foreach($street as $ci) {
            $key = $this->routes_model->_Create_Key($alias.'-'.$ci['alias'].'-'.$ci['district_id']);
            $this->db->insert('app_routes', array(
               'key' => $key,
               'controller' => 'search',
               'category_id' => $id,
               'street_id' => $ci['id'],
               'search_level' => 'street' 
            ));
        }
		
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('categories', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'parent_id' => intval($data['parent_id']),
            'content' => htmlpurifier($data['content']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('categories', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('categories', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('categories');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink('./public/uploads/images/' . $category['image']);
            
        }
        
    }
}