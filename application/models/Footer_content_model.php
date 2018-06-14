<?php
class Footer_content_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('footer_content');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_By_Parent($parent=0, $status='') {
        
        $result = $this->db->get('footer_content')->result_array();
        
        return $result;
    }
    
    function _Get_All($per_page=9999, $page=0, $status='') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('footer_content')->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('footer_content')->row_array();
    }
    
    function _Get_Selected($content_id, $field) {
        $sql = "SELECT DISTINCT ".$field." FROM bds_footer_content_maps WHERE content_id=".$content_id;
        $results = $this->db->query($sql)->result_array();
        $temp = array();
        foreach($results as $row) {
            $temp[] = $row[$field];
        }
        return $temp;
    }
    
    function _Add($data) {
        $this->db->insert('footer_content', array(
            'content' => ($data['content']),
            'show_project' => isset($data['show_project']) ? $data['show_project']:0
        ));
        $content_id = $this->db->insert_id();
        if(isset($data['category_id'])) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        $flag = false;
                        if($data['checkall_ward'][$district_id]){
                            $wards = $this->ward_model->_Get_All($district_id);
                            foreach($wards as $ward) {
                                $this->db->insert('footer_content_maps', array(
                                    'content_id' => $content_id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'ward_id' => $ward['id']
                                ));
                                $flag = true;
                            }
                        }
                        if($data['checkall_street']) {
                            $streets = $this->street_model->_Get_Street_By_District($district_id);
                            foreach($streets as $street) {
                                $this->db->insert('footer_content_maps', array(
                                    'content_id' => $content_id,
                                    'category_id' => $category_id,
                                    'city_id' => $city_id,
                                    'district_id' => $district_id,
                                    'street_id' => $street['id']
                                ));
                                $flag = true;
                            }
                        }
                        
                        if($flag===true)
                            $this->db->insert('footer_content_maps', array(
                                'content_id' => $content_id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id
                            ));
                    }
					$this->db->insert('footer_content_maps', array(
                                'content_id' => $content_id,
                                'category_id' => $category_id,
                                'city_id' => $city_id
                    ));
                }
            }
        }
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id)->update('footer_content', array(
			'content' => $data['content'],
            'show_project' => isset($data['show_project']) ? $data['show_project']:0
        ));
        $this->db->where('content_id', $id)->delete('footer_content_maps');
        if(isset($data['category_id'])) {
            foreach($data['category_id'] as $category_id) {
                foreach($data['city_id'] as $city_id) {
                    foreach($data['district_id'] as $district_id) {
                        if(isset($data['ward_id']))
                        {
							foreach($data['ward_id'][$district_id] as $ward_id) {
								$this->db->insert('footer_content_maps', array(
									'content_id' => $id,
									'category_id' => $category_id,
									'city_id' => $city_id,
									'district_id' => $district_id,
									'ward_id' => $ward_id
								));
							}	
						}
                        if(isset($data['street_id']))
                        {
							foreach($data['street_id'][$district_id] as $street_id) {
								$this->db->insert('footer_content_maps', array(
									'content_id' => $id,
									'category_id' => $category_id,
									'city_id' => $city_id,
									'district_id' => $district_id,
									'street_id' => $street_id
								));
							}	
						}
                        $this->db->insert('footer_content_maps', array(
                                'content_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id,
                                'district_id' => $district_id
                        ));
                    }
					$this->db->insert('footer_content_maps', array(
                                'content_id' => $id,
                                'category_id' => $category_id,
                                'city_id' => $city_id
                            ));
                }
            }
        }
        $this->cache->delete(md5('footer_content_project'));
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
        $this->db->where('id', $id)->update('footer_content', array(
            'status' => trim($status)
        ));
    }
    
     function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('footer_content', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('footer_content');
			$this->db->where('content_id', $id)->delete('footer_content_maps');
        }
        
    }
}