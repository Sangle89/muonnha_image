<?php
class Product_image_model extends CI_Model{
    
    function _Count_All_Image_By_Product($product_id, $status = '') {
        $this->db->select('id')->from('product_image');
        $this->db->where('product_id', $product_id);
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All_Image_By_Product($product_id, $per_page=9999, $page=0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        
        $this->db->where('product_id', $product_id);
        
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('product_image')->result_array();
        
        return $result;
    }
    
    function _Get_Image_By_Id($id) {
        return $this->db->where('id', $id)->get('product_image')->row_array();
    }
    
    function _Add_Image($data) {
        $this->db->insert('product_image', array(
            'title' => trim($data['title']),
            'image' => trim($data['image']),
            'product_id' => intval($data['product_id']),
            'sort_order' => intval($data['sort_order'])
        ));
        return $this->db->insert_id();
    }
    
    function _Update_Image($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('product_image', array(
             'title' => trim($data['title']),
            'image' => trim($data['image']),
            'product_id' => intval($data['product_id']),
            'sort_order' => intval($data['sort_order'])
        ));
    }
    
    function _Delete_Image($id) {
        $this->db->where('id', $id)->delete('product_image');
    }
}