<?php
class Web_link_content_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('content_web_links');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_By_Parent($parent=0, $status='') {
        
        $result = $this->db->get('content_web_links')->result_array();
        
        return $result;
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('content_web_links')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('content_web_links')->row_array();
    }
    
    function _Get_Selected($link_id, $field) {
        $sql = "SELECT DISTINCT ".$field." FROM `".$this->db->dbprefix('content_web_link_maps')."` WHERE link_id=".$link_id;
        $results = $this->db->query($sql)->result_array();
        $temp = array();
        foreach($results as $row) {
            $temp[] = $row[$field];
        }
        return $temp;
    }
    
    function _Get_Follow($link_id) {
        $results = $this->db->where('link_id', $link_id)->get('content_web_link_maps')->result_array();
        $temp = array();
        foreach($results as $row) {
            $temp[$row['district_id']] = $row['follow'];
        }
        return $temp;
    }
    
    function _Add($data) {
        $this->db->insert('content_web_links', array(
            'content' => implode(",",$data['links']),
            'show_detail' => isset($data['show_detail']) ? $data['show_detail'] : 0,
            'show_home' => isset($data['show_home']) ? $data['show_home'] : 0
        ));
        $link_id = $this->db->insert_id();
        if(isset($data['category_id']) && $link_id) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        if(isset($data['ward_id']))
                            foreach($data['ward_id'][$district_id] as $ward_id) {
                                $this->db->insert('content_web_link_maps', array(
                                    'link_id' => $link_id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'ward_id' => $ward_id
                                ));
                            }
                        if(isset($data['street_id']))
                            foreach($data['street_id'][$district_id] as $street_id) {
                                $this->db->insert('content_web_link_maps', array(
                                    'link_id' => $link_id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'street_id' => $street_id
                                ));
                            }
                        $this->db->insert('content_web_link_maps', array(
                            'link_id' => $link_id,
                            'category_id' => $category_id,
                            'city_id' => $city_id,
                            'district_id' => $district_id,
                            'follow' => isset($data['follow'][$district_id]) ? 1:0
                        ));
                    }
                }
            }
        }
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('content_web_links', array(
			'content' => implode(",",$data['links']),
            'show_detail' => isset($data['show_detail']) ? $data['show_detail'] : 0,
            'show_home' => isset($data['show_home']) ? $data['show_home'] : 0
        ));
        $this->db->where('link_id', $id)->delete('content_web_link_maps');
        if(isset($data['category_id'])) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        
                        if(isset($data['ward_id']))
                            foreach($data['ward_id'][$district_id] as $ward_id) {
                                $this->db->insert('content_web_link_maps', array(
                                    'link_id' => $id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'ward_id' => $ward_id
                                ));
                            }
                        if(isset($data['street_id']))
                            foreach($data['street_id'][$district_id] as $street_id) {
                                $this->db->insert('content_web_link_maps', array(
                                    'link_id' => $id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'street_id' => $street_id
                                ));
                            }
                            $this->db->insert('content_web_link_maps', array(
                                'link_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'follow' => isset($data['follow'][$district_id]) ? 1:0
                            ));
                    }
                }
            }
        }
    }
    
    function _Get_Dropdown() {
        $results = $this->_Get_All();
        $option = array();
        $option[0] = '-- No parent --';
        foreach($results as $val) {
            $option[$val['id']] = $val['title'];
        }
        return $option;
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('content_web_links', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('content_web_links', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('content_web_links');
			$this->db->where('link_id', $id)->delete('content_web_link_maps');
        }
        
    }
}