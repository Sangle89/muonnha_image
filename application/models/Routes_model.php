<?php
class Routes_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Get_All($per_page = 9999, $page=0) {
        $this->db->order_by('controller');
        $this->db->limit($per_page, $page);
        return $this->db->get('app_routes')->result_array();
        
    }
    
    function _Count_All() {
        return $this->db->select('id')->from('app_routes')->get()->num_rows();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('app_routes')->row_array();
    }
    
    function _Create_Key($string) {
        $key = to_slug($string);
        while($this->_Get_Key($key)) {
            $key = $key.'-'.$this->_Random();
            if(!$this->_Get_Key($key)) break;
        }
        return $key;
    }
    
    function _Get_Key($key) {
        $key = str_replace('.html', '', $key);
        return $this->db->where('key', $key)->get('app_routes')->row_array();
    }
    
    function _Get_Search_URL($level, $category_id, $value, $district_id = '') {
        
        $this->db->select('key')->from('app_routes')
        ->where('controller','search')
        ->where('category_id', $category_id)
        ->where('search_level', $level);
        
        if($level == 'city') $this->db->where('city_id', $value);
        elseif($level == 'district') $this->db->where('district_id', $value);
        elseif($level == 'ward') $this->db->where('ward_id', $value)->where('district_id', $district_id);
        elseif($level == 'street') $this->db->where('street_id', $value)->where('district_id', $district_id);
        elseif($level == 'project') $this->db->where('project_id', $value);
        elseif($level == 'category_project') $this->db->where('project_id', $value);
        
        
        $result = $this->db->get()->row_array();
        return $result['key'];
    }
    
    function _Get_Category_Project_Url($category_id, $project_id) {
        $result = $this->db->select('key')->from('app_routes')
        ->where('controller', 'category_project')
        ->where('category_id', $category_id)
        ->where('project_id', $project_id)
        ->get()
        ->row_array();
        return $result['key'];
    }
    
    function _Get_Key_By_Param($data) {
        $where = array(
            'category_id' => isset($data['category_id']) ? $data['category_id'] : 0,
            'city_id' => isset($data['city_id']) ? $data['city_id'] : 0,
            'district_id' => isset($data['district_id']) ? $data['district_id'] : 0,
            'ward_id' => isset($data['ward_id']) ? $data['ward_id'] : 0,
            'street_id' => isset($data['street_id']) ? $data['street_id'] : 0,
        );
        foreach($where as $k=>$v) {
            $this->db->where($k, $v);
        }
        $result = $this->db->get('app_routes')->row_array();
        echo $this->db->last_query();
        return $result;
    }
    
    function _Random() {
        return substr(md5(mt_rand(10, 9999)), 0, 3);
    }
    
    function _Add_Route($key, $controller, $val, $param = array()) {
        
        $insert = array(
            'key' => $key,
            'controller' => $controller,
            'value' => $val
        );
        
        if($param) $insert = array_merge($insert, $param);
        
        $this->db->insert('app_routes', $insert);
    }
    
    function _Update_Route($data, $id) {
        $this->db->where('id', $id)->update('app_routes', array(
            //'key' => $this->_Create_Key($data['key']),
            'controller' => trim($data['controller']),
            'value' => intval($data['value'])
        ));
       // $this->db->where('id', $data['value'])->update($data['controller']);
    }
    
    function _Delete($id) {
        $this->db->where('id', $id)->delete('app_routes');
    }
    
    function _Delete_Search($key) {
        $this->db->where('key', $key)->delete('app_routes');
    }
    
    function _Delete_Key($controller, $value) {
        $this->db->where('controller', $controller)->where('value', $value)->delete('app_routes');
    }
}