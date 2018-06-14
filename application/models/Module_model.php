<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Module_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('modules');
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
        
        $result = $this->db->get('modules')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('modules')->row_array();
    }
    
    function _Add_Module($data) {
        $this->db->insert('modules', array(
            'title' => trim($data['title']),
            'create_time' => date("Y-m-d H:i:s", time())
        ));
        return $this->db->insert_id();
    }
    
    function _Add_Module_Content($data) {
        
        $this->db->insert('module_contents', array(
           'module_id' => $data['module_id'],
           'content' => serialize($data['content']),
           'sort_order' => $data['sort_order'],
           'status' => $data['status'] 
        ));
        
    }
    
    function _Update_Module_Content($id, $data) {
        
        $this->db->where('id', $id)->update('module_contents', array(
           //'module_id' => $data['module_id'],
           'content' => serialize($data['content']),
           'sort_order' => $data['sort_order'],
           'status' => $data['status'] 
        ));
        
    }
    
    function _Get_Module_Content($id) {
        
        $module = $this->db->where('id', $id)->get('module_contents')->row_array();
        $module['content'] = unserialize($module['content']);
        return $module;
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('module_contents', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('module_contents', array('sort_order'=>$value));
    }
    
    
    function _Delete_Module($id) {
        $this->db->where('id', $id)->delete('module_contents');
    }
}