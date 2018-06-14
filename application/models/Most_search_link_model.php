<?php
class Most_search_link_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('most_search_links');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_By_Parent($parent=0, $status='') {
        
        $result = $this->db->get('most_search_links')->result_array();
        
        return $result;
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('most_search_links')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('most_search_links')->row_array();
    }
    
    function _Get_Selected($link_id, $field) {
        $sql = "SELECT DISTINCT ".$field." FROM bds_most_search_link_maps WHERE link_id=".$link_id;
        $results = $this->db->query($sql)->result_array();
        $temp = array();
        foreach($results as $row) {
            $temp[] = $row[$field];
        }
        return $temp;
    }
    
    function _Add($data) {
        $this->db->insert('most_search_links', array(
            'content' => implode(",",$data['links'])
        ));
        $link_id = $this->db->insert_id();
        if(isset($data['category_id'])) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        $flag = false;
                        foreach($data['ward_id'][$district_id] as $ward_id) {
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $link_id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'ward_id' => $ward_id
                            ));
                            $flag = true;
                        }
                        foreach($data['street_id'][$district_id] as $street_id) {
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $link_id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'street_id' => $street_id
                            ));
                            $flag = true;
                        }
                        if($flag===true)
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $link_id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id
                            ));
                    }
					$this->db->insert('most_search_link_maps', array(
                        'link_id' => $link_id,
                        'category_id' => $category_id,
                        'city_id' => $city_id
                    ));
                }
            }
        }
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('most_search_links', array(
			'content' => implode(",",$data['links'])
        ));
        $this->db->where('link_id', $id)->delete('most_search_link_maps');
        if(isset($data['category_id'])) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        
                        if(isset($data['ward_id']))
                        foreach($data['ward_id'][$district_id] as $ward_id) {
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'ward_id' => $ward_id
                            ));
                        }
                        if(isset($data['street_id']))
                        foreach($data['street_id'][$district_id] as $street_id) {
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'street_id' => $street_id
                            ));
                        }
                            $this->db->insert('most_search_link_maps', array(
                                'link_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id
                            ));
                    }
					$this->db->insert('most_search_link_maps', array(
                        'link_id' => $id,
                        'category_id' => $category_id,
                        'city_id' => $city_id
                    ));
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
        $this->db->where('id', $id)->update('most_search_links', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('most_search_links', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('most_search_links');
			$this->db->where('link_id', $id)->delete('most_search_link_maps');
        }
        
    }
}