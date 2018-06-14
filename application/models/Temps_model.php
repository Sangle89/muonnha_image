<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Temps_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('temps');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('create_time DESC');
        
        $this->db->limit($per_page, $page);
        $result = $this->db->get('temps')->result_array();
        return $result;
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('temps')->result_array();
    }
    
    //L?y danh sách tin dang h?t h?n
    function _Get_Out_Date() {
        $now = date("Y-m-d H:i:s", time());
        return $this->db->select('id,title,create_time,sort_order,status')
        ->from('temps')
        ->where('to_date <=', $row)
        ->get()
        ->result_array();
    }
    
    //Ğ?m danh sách tin dang h?t h?n
    function _Count_Out_Date() {
        $now = date("Y-m-d H:i:s", time());
        return $this->db->select('id')->from('temps')->where('to_date <=', $now)->get()->num_rows();
    }
    
    //L?y danh sách tin dang trong ngày
    function _Get_In_Date() {
        $now = date("Y-m-d", time());
        return $this->db->select('id,title,create_time,sort_order,status')
        ->from('temps')
        ->where('to_date', $row)
        ->get()
        ->result_array();
    }
    
    //Ğ?m danh sách tin dang trong ngày
    function _Count_In_Date() {
        $now = date("Y-m-d", time());
        return $this->db->query("SELECT id FROM bds_temps WHERE DATE_FORMAT(`create_time`, '0000-00-00') ='".$now."'")->num_rows();
        
       // return $this->db->select('id')->from('temps')->where('(create_time, "yyyy-mm-dd")', $now)->get()->num_rows();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('temps')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('alias', $id)->get('temps')->row_array();
    }
    
    function _Count_Images($real_estate_id) {
        return $this->db->where('real_estate_id', $real_estate_id)->get('real_estate_images')->num_rows();
    }
    
    function _Get_Images($real_estate_id) {
        return $this->db->where('real_estate_id', $real_estate_id)->get('real_estate_images')->result_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('temps')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('temps', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('temps', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('temps');
            //$this->db->where('key', $category['alias'])->delete('app_routes');
            //@unlink('./uploads/images/' . $category['image']);
            //$images = $this->_Get_Images($id);
            //foreach($images as $image) @unlink('./uploads/images/'.$image['image']);
        }
    }
}