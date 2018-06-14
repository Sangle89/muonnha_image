<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Project_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('projects');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        $result = $this->db->get('projects')->result_array();
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('projects')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('alias', $id)->get('projects')->row_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('projects')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Get_Dropdown() {
        $results = $this->_Get_All();
        $option = array();
        $option[0] = '-- Dự án --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('projects', array(
            'title' => trim($data['title']),
            'meta_title' => trim($data['meta_title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            //'image' => $data['image'],
            'content' => htmlpurifier($data['content']),
            'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status'],
            'create_by' => $this->ion_auth->get_user_id()
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'project', $id, array(
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'ward_id' => $data['ward_id'],
            'street_id' => $data['street_id']
        ));
        
        //Add route search
        $this->load->model('link_model');
        $all_category = $this->db->select('id')->from('categories')->get()->result_array();
        foreach($all_category as $category) {
            if($this->link_model->_Check_Link_Project($category['id'], $id)==0)
                $this->link_model->_Add_Link_Project($category['id'], $id);
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('projects', array(
            'title' => trim($data['title']),
            'meta_title' => trim($data['meta_title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            //'image' => $data['image'],
            'content' => htmlpurifier($data['content']),
            'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status']
        ));
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('projects', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('projects', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('projects');
            
            $this->db->where('key', $category['alias'])->delete('app_routes');
            @unlink('./uploads/images/' . $category['image']);
            //Xoa link search
            $this->db->where('controller', 'search')
            ->where('search_level', 'project')
            ->where('project_id', $id)
            ->delete('app_routes');
            
        }
    }
}