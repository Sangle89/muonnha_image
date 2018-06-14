<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Album_model extends CI_Model {
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('albums');
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
        
        $result = $this->db->get('albums')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('albums')->row_array();
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('albums', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            'image' => $data['image'],
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status']
        ));
        
        $id = $this->db->insert_id();
        $this->routes_model->_Add_Route($alias, 'album', $id);
        
        if($data['images']) {
            foreach($data['images'] as $image) {
                $this->db->insert('album_images', array(
                    'album_id' => $id,
                    'image' => $image
                ));
            }
        }
        
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('albums', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'image' => $data['image'],
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status']
        ));
        
        $this->db->where('album_id', $id)->delete('album_images');
        if($data['images']) {
            foreach($data['images'] as $image) {
                $this->db->insert('album_images', array(
                    'album_id' => $id,
                    'image' => $image
                   // 'sort_order'=>$image['sort_order']
                ));
            }
        }
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('albums', array(
            'status' => trim($status)
        ));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('albums');
            @unlink('./public/uploads/images/' . $category['image']);
        }
    }
    
    function _Get_Image_By_Album($album_id) {
        return $this->db->where('album_id', $album_id)->get('album_images')->result_array();
    }
    
    function _Get_Array_Image_By_Album($album_id) {
        $array = array();
        $images = $this->_Get_Image_By_Album($album_id);
        foreach($images as $image) {
            $array[] = $image;
        }
        return $array;
    }
}