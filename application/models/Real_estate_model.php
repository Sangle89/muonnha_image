<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Real_estate_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }
    
    function _Count_All($param = array()) {
        
        $this->db->select('id')->from('real_estates');
        if(isset($param['category_id']) && $param['category_id']!=-1){
            $this->db->where('category_id', $param['category_id']);
        }
        
        if(isset($param['city_id']) && $param['city_id']!=-1){
            $this->db->where('city_id', $param['city_id']);
        }
        
        if(isset($param['district_id']) && $param['district_id']!=-1){
            $this->db->where('district_id', $param['district_id']);
        }
        
        if(isset($param['project_id']) && $param['project_id']!=-1){
            $this->db->where('project_id', $param['project_id']);
        }
        
        if(isset($param['create_by']) && $param['create_by']!=-1){
            $this->db->where('create_by', $param['create_by']);
        }
        
        if(isset($param['partket']) && $param['partket']!=-1){
            $this->db->where('type_id', $param['partket']);
        }
        
        if(isset($param['status']) && $param['status']!=-1){
            $this->db->where('status', $param['status']);
        }
        if(isset($param['unit']) && $param['unit']!=-1){
            $this->db->where('price_unit', $param['unit']);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($param = array(), $per_page=9999, $page=0) {
        
        if(isset($param['category_id']) && $param['category_id']!=-1){
            $this->db->where('category_id', $param['category_id']);
        }
        
        if(isset($param['city_id']) && $param['city_id']!=-1){
            $this->db->where('city_id', $param['city_id']);
        }
        
        if(isset($param['district_id']) && $param['district_id']!=-1){
            $this->db->where('district_id', $param['district_id']);
        }
        
        if(isset($param['project_id']) && $param['project_id']!=-1){
            $this->db->where('project_id', $param['project_id']);
        }
        
        if(isset($param['create_by']) && $param['create_by']!=-1){
            $this->db->where('create_by', $param['create_by']);
        }
        
        if(isset($param['status']) && $param['status']!=-1){
            $this->db->where('status', $param['status']);
        }
        
        if(isset($param['partket']) && $param['partket']!=-1){
            $this->db->where('type_id', $param['partket']);
        }
        if(isset($param['unit']) && $param['unit']!=-1){
            $this->db->where('price_unit', $param['unit']);
        }
        $this->db->order_by($param['sortby']);
        
        $this->db->limit($per_page, $page);
        $result = $this->db->get('real_estates')->result_array();
        return $result;
    }
    
    function _Get_Search($string) {
        return $this->db->like('title', $string)->get('real_estates')->result_array();
    }
    
    //Lấy danh sách tin đăng hết hạn
    function _Get_Out_Date() {
        $now = date("Y-m-d H:i:s", time());
        return $this->db->select('id,title,create_time,sort_order,status')
        ->from('real_estates')
        ->where('to_date <=', $row)
        ->get()
        ->result_array();
    }
    
    //Đếm danh sách tin đăng hết hạn
    function _Count_Out_Date() {
        $now = date("Y-m-d H:i:s", time());
        return $this->db->select('id')->from('real_estates')->where('to_date <=', $now)->get()->num_rows();
    }
    
    //Lấy danh sách tin đăng trong ngày
    function _Get_In_Date() {
        $now = date("Y-m-d", time());
        return $this->db->select('id,title,create_time,sort_order,status')
        ->from('real_estates')
        ->where('to_date', $row)
        ->get()
        ->result_array();
    }
    
    //Đếm danh sách tin đăng trong ngày
    function _Count_In_Date() {
        $now = date("Y-m-d", time());
        return $this->db->query("SELECT id FROM ".$this->db->dbprefix('real_estates')." WHERE DATE_FORMAT(`create_time`, '0000-00-00') ='".$now."'")->num_rows();
        
       // return $this->db->select('id')->from('real_estates')->where('(create_time, "yyyy-mm-dd")', $now)->get()->num_rows();
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('real_estates')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('alias', $id)->get('real_estates')->row_array();
    }
    
    function _Count_Images($real_estate_id) {
        return $this->db->where('real_estate_id', $real_estate_id)->get('real_estate_images')->num_rows();
    }
    
    function _Get_Images($real_estate_id) {
        return $this->db->where('real_estate_id', $real_estate_id)->get('real_estate_images')->result_array();
    }
    
    function _Get_Max_Sort_Order() {
        $result = $this->db->select('sort_order')->from('real_estates')->order_by('sort_order','DESC')->limit(0,1)->get()->row_array();
        return $result['sort_order'];
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('real_estates', array(
            'category_id' => intval($data['category_id']),
            'city_id' => 1,
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'project_id' => intval($data['project_id']),
            'price_number' => str_replace(",",".",$data['price_number']),
            'area' =>  str_replace(",",".",$data['area']),
            'price_unit' => intval($data['price_unit']),
            'address' => trim($data['address']),
            'sotang' => intval($data['sotang']),
            'sophong' => intval($data['sophong']),
            'sotoilet' => intval($data['sotoilet']),
            'huongnha' => intval($data['huongnha']),
            'noithat' => trim($data['noithat']),
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            'type_id' => intval($data['type_id']),
            'from_date' => _convert_date($data['from_date']),
            'to_date' => _convert_date($data['to_date']),
            'content' => htmlpurifier($data['content']),
            'video' => trim($data['video']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _convert_date($data['create_time']),
            'status' => $data['status'],
            'featured' => $data['featured'],
            'create_by' => $data['create_by'],
            'views1' => intval($data['views1']),
            'views2' => intval($data['views2']),
            'tag1' => trim($data['tag1']),
            'tag1_alias' => $data['tag1']!=''?to_slug(trim($data['tag1'])) : '',
            'tag2' => trim($data['tag2']),
            'tag2_alias' => $data['tag2']!=''?to_slug(trim($data['tag2'])) : '',
            'tag3' => trim($data['tag3']),
            'tag3_alias' => $data['tag3']!=''?to_slug(trim($data['tag3'])) : '',
            'tag4' => trim($data['tag4']),
            'tag4_alias' => $data['tag4']!=''?to_slug(trim($data['tag4'])) : '',
            'tag5' => trim($data['tag5']),
            'tag5_alias' => $data['tag5']!=''?to_slug(trim($data['tag5'])) : '',
            'tag6' => trim($data['tag6']),
            'tag6_alias' => $data['tag6']!=''?to_slug(trim($data['tag6'])) : '',
        ));
        
        $id = $this->db->insert_id();
        
        //Update category alias
        $this->db->select('key')->from('app_routes')
        ->where('controller','search')
        ->where('category_id', $data['category_id']);
        if($data['ward_id']) $this->db->where('ward_id', $data['ward_id']);
        else if($data['district_id']) $this->db->where('district_id', $data['district_id']);
        else if($data['city_id']) $this->db->where('city_id', $data['city_id']);
        $category_alias = $this->db->get()->row_array();
        if(!empty($category_alias)) {
            $this->db->where('id', $id)->update('real_estates', array(
                'category_alias' => $category_alias['key']
            ));
        }
        
        //Add tag
        for($i=1; $i<=6;$i++) {
            if($data['tag'.$i]!=''){
                $check_tag = $this->db->where('alias', to_slug(trim($data['tag'.$i])))->get('real_tags')->num_rows();
                if($check_tag == 0) {
                    $this->db->insert('real_tags', array(
                        'title' => trim($data['tag'.$i]),
                        'alias' => to_slug(($data['tag'.$i]))
                    ));
                }
            }
        }
        
        $param = array(
            'category_id' => intval($data['category_id']),
            'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id'])
        );
        
        $this->routes_model->_Add_Route($alias, 'real_estate', $id, $param);
        
        if($data['images']) {
            $count = 1;
            foreach($data['images'] as $key => $image) {
				if($data['image_default']==($key+1)) $is_default=1;
                else $is_default = 0;
                $this->db->insert('real_estate_images', array(
                    'real_estate_id' => $id,
                    'image' => $image,
                    'sort_order' => $count,
                    'is_default' => $is_default
                ));
                $count++;
            }
        }
        
        $this->db->where('real_estate_id', $id)->delete('real_estate_utilities');
        if(!empty($data['utilities'])) {
            foreach($data['utilities'] as $utility) {
                $this->db->insert("real_estate_utilities", array(
                   'real_estate_id' => $id,
                   'utility_id' => $utility 
                ));
            }
         }
        $this->cache->clean();
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('real_estates', array(
            'category_id' => intval($data['category_id']),
            //'city_id' => intval($data['city_id']),
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
            'project_id' => intval($data['project_id']),
            'price_number' => str_replace(",",".",$data['price_number']),
            'price_unit' => intval($data['price_unit']),
            'area' =>  str_replace(",",".",$data['area']),
            'address' => trim($data['address']),
         //   'mattien' => str_replace(",",".",$data['mattien']),
         //   'duongtruocnha' => str_replace(",",".",$data['duongtruocnha']),
            'sotang' => intval($data['sotang']),
            'sophong' => intval($data['sophong']),
            'sotoilet' => intval($data['sotoilet']),
            'huongnha' => intval($data['huongnha']),
            'noithat' => trim($data['noithat']),
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'type_id' => intval($data['type_id']),
            'from_date' => _convert_date($data['from_date']),
            'to_date' => _convert_date($data['to_date']),
            'content' => htmlpurifier($data['content']),
            'video' => trim($data['video']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _convert_date($data['create_time']),
            'update_time' => _Current_Date(),
            'featured' => $data['featured'],
            'status' => $data['status'],
            'views1' => intval($data['views1']),
            'views2' => intval($data['views2']),
            'create_by' => $data['create_by'],
            'guest_fullname' => trim($data['guest_fullname']),
            'guest_telephone' => trim($data['guest_telephone']),
            'guest_mobiphone' => trim($data['guest_mobiphone']),
            'guest_email' => trim($data['guest_email']),
            'guest_address' => trim($data['guest_address']),
            'tag1' => trim($data['tag1']),
            'tag1_alias' => $data['tag1']!=''?to_slug(trim($data['tag1'])) : '',
            'tag2' => trim($data['tag2']),
            'tag2_alias' => $data['tag2']!=''?to_slug(trim($data['tag2'])) : '',
            'tag3' => trim($data['tag3']),
            'tag3_alias' => $data['tag3']!=''?to_slug(trim($data['tag3'])) : '',
            'tag4' => trim($data['tag4']),
            'tag4_alias' => $data['tag4']!=''?to_slug(trim($data['tag4'])) : '',
            'tag5' => trim($data['tag5']),
            'tag5_alias' => $data['tag5']!=''?to_slug(trim($data['tag5'])) : '',
            'tag6' => trim($data['tag6']),
            'tag6_alias' => $data['tag6']!=''?to_slug(trim($data['tag6'])) : '',
        ));
        
        //Add tag
        for($i=1; $i<=6;$i++) {
            if($data['tag'.$i]!=''){
                $check_tag = $this->db->where('alias', to_slug(trim($data['tag'.$i])))->get('real_tags')->num_rows();
                if($check_tag == 0) {
                    $this->db->insert('real_tags', array(
                        'title' => trim($data['tag'.$i]),
                        'alias' => to_slug(($data['tag'.$i]))
                    ));
                }
            }
            
        }
        
        $this->db->where('real_estate_id', $id)->delete('real_estate_images');
        if($data['images']) {
            $count = 1;
            foreach($data['images'] as $key=>$image) {
               if($data['image_default']==($key+1)) $is_default=1;
                else $is_default = 0;
                $this->db->insert('real_estate_images', array(
                    'real_estate_id' => $id,
                    'image' => $image,
                    'sort_order' => $count,
                    'is_default' => $is_default
                ));
                $count++;
            }
        }
        
        $this->db->where('real_estate_id', $id)->delete('real_estate_utilities');
        if(!empty($data['utilities'])) {
            foreach($data['utilities'] as $utility) {
                $this->db->insert("real_estate_utilities", array(
                   'real_estate_id' => $id,
                   'utility_id' => $utility 
                ));
            }
         }
         
        $this->cache->clean();
    }
    
    function _Get_Utility($real_estate_id) {
        $temp = array();
        $results = $this->db->select('utility_id')->from('real_estate_utilities')->where('real_estate_id', $real_estate_id)->get()->result_array();
        foreach($results as $row) {
            $temp[] = $row['utility_id'];
        }
        return $temp;
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('real_estates', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('real_estates', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('real_estates');
            $this->db->where('key', $category['alias'])->delete('app_routes');
            //@unlink('./uploads/images/' . $category['image']);
            $images = $this->_Get_Images($id);
            foreach($images as $image) @unlink('./uploads/images/'.$image['image']);
        }
    }
}