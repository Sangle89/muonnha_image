<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main_model extends CI_Model {
    
    public function __construct() {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }
    
    function _Get_Setting($field = '') {
        $cacheid = md5('get_setting_'.$field);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->get('settings')->row_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    function _Get_Json_Location() {
        $results = array();
        $city = $this->_Get_City();
        foreach($city as $k1 => $v1) {
            
            $district = $this->_Get_District($v1['id']);
            $temp_district = array();
            foreach($district as $k2 => $v2) {
                
                $ward = $this->_Get_Ward($v2['id']);
                $temp_ward = array();
                foreach($ward as $k3 => $v3) {
                    
                    $street = $this->_Get_Street($v3['id']);
                    
                    $tem_ward[$v3['id']] = array(
                        'title' => $v3['title'],
                        'alias' => $v3['alias'],
                        'child' => $street
                    );
                }
                $temp_district[$v2['id']] = array(
                    'title' => $v2['title'],
                    'alias' => $v2['alias'],
                    'child' => $tem_ward
                );
            }
             
            $results[$v1['id']] = array(
                'title' => $v1['title'],
                'alias' => $v1['alias'],
                'child' => $temp_district  
            );
        }
        return json_encode($results);
    }
    
    /**
    * @name lấy danh sách nhóm tin đăng
    * @param parent_id default is null
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Real_Estate_Category($parent_id = 0) {
        
        if ($this->cache->apc->is_supported())
        {
                echo 'support';
        } else {
           // echo 'not support';
        }
        
        $cache_id = 'get_real_estate_category_'.$parent_id;
        $result = $this->cache->get(md5($cache_id));
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')
            ->from('categories')
            ->where('status', 'active')
            ->where('parent_id', $parent_id)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();
            $this->cache->save(md5($cache_id), $result, 60*60);
        }
        return $result;
    }
    
    function _Get_First_Real_Estate_Category() {
        return $this->db->select('id,title,alias')
        ->from('categories')
        ->where('status', 'active')
        ->order_by('sort_order', 'ASC')
        ->limit(1)
        ->get()
        ->row_array();
    }
    
    /**
    * @name lấy chi tiết nhóm tin đăng
    * @param parent_id default is null
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Real_Estate_Category_By_Id($id, $field = array()) {
        if(is_array($field)) {
            $this->db->select(implode(",", $field));
        } else {
            $this->db->select('*');
        }
        return $this->db->from('categories')->where('id', $id)->get()->row_array();
    }
    
    /**
    * @name danh sách tu khoa danh muc
    * @param array default is null
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Category_Tags($param = array()) {
        unset($param['key']);
        unset($param['id']);
        unset($param['controller']);
        unset($param['search_level']);
        unset($param['value']);
        unset($param['project_id']);
		unset($param['filter_area']);
		unset($param['filter_price']);
		unset($param['sophongngu']);
        unset($param['ignore']);
        
        $cacheid = md5('get_category_tag_'.http_build_query($param));
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $this->db->select('tags')
            ->from('category_tags');
            $where = array();
            foreach($param as $key => $value) {
                if($value!='')
                    $where[] = $key.'='.$value;
            }
            $where = implode(" AND ", $where);
            $this->db->where($where);
            $result = $this->db->limit(1)->get()->row_array();
            $this->cache->save($cacheid, $result, 3600*24*15);    
        }
        
        if($result['tags']!='') {
            $tags = explode(",", $result['tags']);
            $array_tags = array();
            foreach($tags as $tag_id) {
                $temp = $this->db->where('id', $tag_id)->get('real_tags')->row_array();
                $array_tags[] = '<a href="'.site_url('tags/'.$temp['alias']).'">'.$temp['title'].'</a>';
            }
            return $array_tags;
        } else {
            return array();
        }
    }
    
    function _Get_Seo_Url($url) {
		//$url = str_replace(".htm", "", $url);
        $cacheid = md5('get_seo_url_'.$url);
       // $result = $this->cache->get($cacheid);
       // if(empty($result)) {
            $result = $this->db->where('url', $url)->limit(1)->get('seo')->row_array();
       //     $this->cache->save($cacheid, $result, 3600*24*15);
       // }
		//echo $this->db->last_query();
        return $result;
    }
    
    /**
    * @name danh sách nhóm tin tức
    * @param parent_id default is null
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Content_Category($parent_id = 0) {
        $cacheid = md5('get_content_category_'.$parent_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')
            ->from('content_categories')
            ->where('status', 'active')
            ->where('parent_id', $parent_id)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();
           $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết nhóm tin tức
    * @param parent_id default is null
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Content_Category_By_Id($id) {
        $cacheid = md5('get_content_category_by_id_'.$id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->where('id', $id)->get('content_categories')->row_array();
            $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    /**
    * @name lấy danh sách tin tức
    * @param category_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Int 
    */
    function _Get_Content_By_Category($category_id, $per_page, $page) {
        $cacheid = md5('get_content_by_category_'.$category_id.$per_page.$page);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('t1.id,t1.title,t1.alias,t1.short_content,t1.create_time,t1.image,t2.alias AS category_alias')
            ->from('contents t1')
            ->join('content_categories t2', 't1.category_id=t2.id')
            ->where('t1.category_id='.$category_id.' OR t1.category_id IN (SELECT id FROM '.$this->db->dbprefix('content_categories').' WHERE parent_id='.$category_id.')')
            ->order_by('t1.create_time','DESC')
            ->limit($per_page, $page)
            ->get()
            ->result_array();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    /**
    * @name đếm danh sách tin tức
    * @param category_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Int 
    */
    function _Count_Content_By_Category($category_id) {
        return $this->db->select('id')
        ->from('contents')
        ->where('status', 'active')
        //->where('category_id', $category_id)
        ->where('category_id='.$category_id.' OR category_id IN (SELECT id FROM '.$this->db->dbprefix('content_categories').' WHERE parent_id='.$category_id.')')
        ->get()
        ->num_rows();
    }
    
    function _Get_Contents($limit) {
        return $this->db->select('contents.id,contents.title,contents.alias,contents.short_content,contents.create_time,contents.image,content_categories.title as category_title,content_categories.alias as category_alias,')
        ->from('contents')
        ->join('content_categories', 'contents.category_id=content_categories.id')
        ->where('contents.status', 'active')
        ->order_by('contents.create_time','DESC')
        ->limit($limit)
        ->get()
        ->result_array();
    }
    
    function _Get_Content_Feature($limit = 10) {
        $cacheid = md5('get_content_feature'.$limit);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias,short_content,create_time,image')
            ->from('contents')
            ->where('feature', 1)
            ->order_by('create_time','DESC')
            ->limit($limit)
            ->get()
            ->result_array();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    function _Get_Content_Most_View($limit=6) {
        return $this->db->select('id,title,alias,image')->from('contents')
        ->where('status', 'active')
        ->order_by('views', 'DESC')
        ->limit($limit)
        ->get()
        ->result_array();
    }
    
    /**
    * @name lấy chi tiết tin tức
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Content_By_Id($id) {
        $cacheid = md5('get_content_by_id_'.$id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('*')
            ->from('contents')
            ->where('id', $id)
            ->or_where('alias', $id)
            ->get()
            ->row_array();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    /**
    * Lấy trang tỉnh
    */
    function _Get_Page_By_Id($id) {
        $cacheid = md5('get_page_by_id_'.$id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->where('id', $id)->or_where('alias', $id)->get('pages')->row_array();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    function _Get_Content_Related($content, $limit) {
        $cacheid = md5('get_content_related_'.http_build_query($content).$limit);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias,short_content,create_time,image')
                ->from('contents')
                ->where("category_id='".$content['category_id']."'")
                ->where('id <>', $content['id'])
                ->order_by('create_time DESC')
                ->limit($limit)
                ->get()
                ->result_array();
                $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    function _Update_Content_View($id) {
        $this->db->query("UPDATE ".$this->db->dbprefix('contents')." SET `views`=`views`+1 WHERE `id`='".$id."'");
    }
    
    /**
    * @name lấy danh sách tỉnh thành
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_City($limit=9999) {
        $cacheid = md5('get_city_'.$limit);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')->from('city')
            ->where('status', 'active')
            ->order_by('sort_order', 'DESC')
            ->limit($limit)
            ->get()->result_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết tỉnh thành
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_City_By_Id($id, $field=array()) {
        if(is_array($field)) {
            $this->db->select(implode(",", $field));
        } else {
            $this->db->select('*');
        }
        return $this->db->from('city')->where('id', $id)->get()->row_array();
    }
    
    /**
    * @name lấy danh sách quận huyện theo tỉnh thành
    * @param city_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_District($city_id, $limit=9999) {
        $cacheid = md5('get_district_'.$city_id . $limit);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')
            ->from('district')
            ->where('status', 'active')
            ->where('city_id', $city_id)
            ->order_by('sort_order')
            ->limit($limit)
            ->get()->result_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết Quận huyện
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_District_By_Id($id, $field=array()) {
        if(is_array($field)) {
            $this->db->select(implode(",", $field));
        } else {
            $this->db->select('*');
        }
        return $this->db->from('district')->where('id', $id)->get()->row_array();
    }
    
    /**
    * @name lấy danh sách phường xã theo quận huyện
    * @param district_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Ward($district_id) {
        $cacheid = md5('get_ward_'.$district_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')
            ->from('wards')->where('status', 'active')
            ->where('district_id', $district_id)
            ->order_by('title')->get()->result_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết phường xã
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Ward_By_Id($id, $field=array()) {
        if(is_array($field)) {
            $this->db->select(implode(",", $field));
        } else {
            $this->db->select('*');
        }
        return $this->db->from('wards')->where('id', $id)->get()->row_array();
    }
    
    /**
    * @name lấy danh sách tỉnh thành
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Street($ward_id) {
        $cacheid = md5('get_street_'.$ward_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title,alias')->from('streets')->where('status', 'active')
            ->where('ward_id', $ward_id)->order_by('title')->get()->result_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    function _Get_Street_By_City($city_id) {
        return $this->db->select('id,title,alias')->from('streets')->where('status', 'active')->where('city_id', $city_id)->order_by('title')->get()->result_array();
    }
    /*
    function _Get_Street_By_District($district_id) {
        return $this->db->select('id,title,alias')->from('streets')->where('status', 'active')->where('district_id', $district_id)->order_by('title')->get()->result_array();
    }*/
    function _Get_Street_By_District($district_id) {
        $cacheid = md5('get_street_by_district_'.$district_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('t1.id,t1.title,t1.alias')
                ->from('streets t1')
                ->join('district_streets t2', 't2.street_id=t1.id')
                ->where('status', 'active')
                ->where('t2.district_id', $district_id)
                ->order_by('t1.title')
                ->get()->result_array();
            $this->cache->save($cacheid, $result, 3600*24*30);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết tỉnh thành
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Street_By_Id($id, $field=array()) {
        if(is_array($field)) {
            $this->db->select(implode(",", $field));
        } else {
            $this->db->select('*');
        }
        return $this->db->from('streets')->where('id', $id)->get()->row_array();
    }
    
    /**
    * @name lấy danh sách dự án
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Project($district_id = 0) {
        $cacheid = md5('get_project_'.$district_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id,title')
            ->from('projects')
            ->where('status', 'active')
            ->where('district_id', $district_id)
            ->order_by('title')
            ->get()
            ->result_array();
            $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    /**
    * @name lấy chi tiết dự án
    * @param id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Project_By_Id($id, $field=array()) {
        $cacheid = md5('get_project_by_id'.$id.http_build_query($field));
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            if(is_array($field)) {
                $this->db->select(implode(",", $field));
            } else {
                $this->db->select('*');
            }
            $result = $this->db->from('projects')->where('id', $id)->get()->row_array();
            $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    function _Get_Utility() {
        $cacheid = md5('utility_');
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->where('status',1)->order_by('sort_order', 'DESC')->get('utilities')->result_array();
            $this->cache->save($cacheid, $result, 60*60*24*30);
        }
        return $result;
    }
    
    function _Get_Real_Estate_Utility($real_estate_id) {
        return $this->db->select('u.title')->from('utilities u')
        ->join('real_estate_utilities ru', 'u.id=ru.utility_id')
        ->where('ru.real_estate_id', $real_estate_id)
        ->get()
        ->result_array();
    }
    
    function _Get_Real_Estate_Utilities($real_estate_id) {
        $result = $this->db->select('utility_id')->from('real_estate_utilities')
        ->where('real_estate_id', $real_estate_id)
        ->get()
        ->result_array();
        $return = array();
        foreach($result as $row) {
            $return[] = $row['utility_id'];
        }
        return $return;
    }
    
    /**
    * @name lấy danh sách tin đăng theo danh mục
    * @param category_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Array 
    */
    function _Get_Real_Estate_By_Category($category_id, $per_page, $page, $order_by = '', $sort = '') {
        $cacheid = md5('get_real_estate_by_category_'.$category_id.$per_page.$page.$order_by.$sort);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            if($order_by == 'price' && $sort == 'DESC')
                $order = 'p.price_number DESC';
            elseif($order_by == 'price' && $sort == 'ASC')
                $order = 'p.price_number ASC';
            elseif($order_by == 'create_time')
                $order = 'p.create_time DESC';
            else
                 $order = 'p.type_id ASC, p.create_time DESC';
            
            $result = $this->db->select('p.id,p.title,p.alias,p.price_number,p.price_unit,p.area,p.type_id,p.create_time,p.content,c.title AS city_title, d.title AS district_title,w.title AS ward_title')
            ->from('real_estates p')
            ->join('city c', 'p.city_id=c.id', 'LEFT')
            ->join('district d', 'p.district_id=d.id', 'LEFT')
            ->join('wards w', 'p.ward_id=w.id', 'LEFT')
            ->where('p.status', 'active')
            ->where('category_id = '.$category_id.' OR p.`category_id` IN (SELECT id FROM `'.$this->db->dbprefix('categories').'` WHERE `parent_id`="'.$category_id.'")')
            ->order_by($order)
            ->limit($per_page, $page)
            ->get()
            ->result_array();
            $this->cache->save($cacheid, $result, 60*60*24*30);
        }
        return $result;        
    }
    
    /**
    * @name đếm danh sách tin đăng theo danh mục
    * @param category_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Int 
    */
    function _Count_Real_Estate_By_Category($category_id) {
        return $this->db->select('p.id')
        ->from('real_estates p')
        ->where('p.status', 'active')
        ->where('category_id = '.$category_id.' OR p.`category_id` IN (SELECT id FROM `'.$this->db->dbprefix('categories').'` WHERE `parent_id`="'.$category_id.'")')
        ->get()
        ->num_rows();
    }
    
    /**
    * Lấy danh sach tin đăng theo city_id
    */
    function _Get_Real_Estate_By_City($city_id, $per_page, $page) {
        $this->db->select('id,title,alias,content')
        ->from('real_estates')
        ->where('city_id', $city_id)
        ->order_by('create_time')
        ->limit($per_page, $page)
        ->get()
        ->result_array();
    }
    
    /**
    * Đếm danh sach tin đăng theo city_id
    */
    function _Count_Real_Estate_By_City($city_id) {
        return $this->db->select('id')
        ->from('real_estates')
        ->where('city_id', $city_id)
        ->where('status', 'active')
        ->get()
        ->num_rows();
    }
    
    /**
    * Lấy danh sach tin đăng theo district_id
    */
    function _Get_Real_Estate_By_District($district_id, $per_page, $page) {
        $this->db->select('id,title,alias')
        ->from('real_estates')
        ->where('district_id', $district_id)
        ->order_by('create_time')
        ->limit($per_page, $page)
        ->get()
        ->result_array();
    }
    
    /**
    * Đếm danh sach tin đăng theo district_id
    */
    function _Count_Real_Estate_By_District($district_id) {
        $cacheid = md5('_Count_Real_Estate_By_District_'.$district_id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->select('id')
            ->from('real_estates')
            ->where('district_id', $district_id)
            ->where('status', 'active')
            ->get()
            ->num_rows();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    /**
    * Đếm danh sach tin đăng theo district_id
    */
    function _Count_Real_Estate($params = array()) {
        $cacheid = md5('_Count_Real_Estate_'.http_build_query($params));
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $this->db->select('id')
            ->from('real_estates')
            ->where('status', 'active');
            
            if($params['district_id']) $this->db->where('district_id', $params['district_id']);
            if($params['ward_id']) $this->db->where('ward_id', $params['ward_id']);
            if($params['category_id']) $this->db->where('category_id', $params['category_id']);
            
            $result = $this->db->get()
            ->num_rows();
            $this->cache->save($cacheid, $result, 3600*24);
        }
        return $result;
    }
    
    /**
    * Tìm kiếm Tin đăng
    */
    function _Search_Real_Estate($data, $per_page = 12, $page = 0, $order_by = '', $sort = '') {
        $this->db->select('p1.id, p1.title, p1.alias, p1.price_number, p1.price_unit, p1.area, p1.type_id, p1.content,p1.create_time, p2.title AS city_title, p3.title AS district_title')
        ->from('real_estates p1')
        ->join('city p2', 'p1.city_id=p2.id', 'LEFT')
        ->join('district p3', 'p1.district_id=p3.id', 'LEFT')
        ->where('p1.status', 'active');
        
        if(isset($data['category_id']) && $data['category_id']!=0) {
            $this->db->where('(p1.category_id='.$data['category_id'].' OR p1.category_id IN (SELECT id FROM '.$this->db->dbprefix('categories').' WHERE parent_id='.$data['category_id'].'))');
			//$this->db->or_where('p1.category_id IN (SELECT id FROM '.$this->db->dbprefix('categories').' WHERE parent_id='.$data['category_id'].')');
        }
        
        if(isset($data['city_id']) && $data['city_id']!=0) {
            $this->db->where('p1.city_id', $data['city_id']);
        }
        
        if(isset($data['district_id']) && $data['district_id']!=0) {
            $this->db->where('p1.district_id', $data['district_id']);
        }
        
        if(isset($data['ward_id']) && $data['ward_id']!=0) {
            $this->db->where('p1.ward_id', $data['ward_id']);
        }
        
        if(isset($data['street_id']) && $data['street_id']!=0) {
            $this->db->where('p1.street_id', $data['street_id']);
        }
        
        if(isset($data['project_id']) && $data['project_id']!=0) {
            $this->db->where('p1.project_id', $data['project_id']);
        }
        
        if(isset($data['sophong']) && $data['sophong']!=0) {
            $this->db->where('p1.sophong', $data['sophong']);
        }
        
        if(isset($data['filter_area']) && is_array($data['filter_area'])) {
            $this->db->where('p1.area >=', $data['filter_area']['min']);
            $this->db->where('p1.area <=', $data['filter_area']['max']);
        }
        
        if(isset($data['filter_price']) && is_array($data['filter_price'])) {
            $this->db->where('p1.price_number >=', $data['filter_price']['min']);
            $this->db->where('p1.price_number <=', $data['filter_price']['max']);
            $this->db->where('p1.price_unit <=', $data['filter_price']['unit']);
        }
        
        if(isset($data['ignore']) && !empty($data['ignore'])) {
            $this->db->where_not_in('p1.id', $data['ignore']);
        }
        
        if($order_by == 'price' && $sort == 'DESC')
            $order = 'p1.price_number DESC';
        elseif($order_by == 'price' && $sort == 'ASC')
            $order = 'p1.price_number ASC';
        elseif($order_by == 'create_time')
            $order = 'p1.create_time DESC';
        else
             $order = 'p1.type_id ASC, p1.create_time DESC';
        
        $this->db->order_by($order)
        ->limit($per_page, $page);
        
        $results = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $results;
    }
    
    /**
    * Đếm số tin đăng theo tìm kiếm
    */
    function _Count_Search_Real_Estate($data) {
        $this->db->select('id')
        ->from('real_estates')
        ->where('status', 'active');
        
        if(isset($data['category_id']) && $data['category_id']!=0) {
            $this->db->where('(category_id='.$data['category_id'].' OR category_id IN (SELECT id FROM '.$this->db->dbprefix('categories').' WHERE parent_id='.$data['category_id'].'))');
			
        }
        
        if(isset($data['city_id']) && $data['city_id']!=0) {
            $this->db->where('city_id', $data['city_id']);
        }
        
        if(isset($data['district_id']) && $data['district_id']!=0) {
            $this->db->where('district_id', $data['district_id']);
        }
        
        if(isset($data['ward_id']) && $data['ward_id']!=0) {
            $this->db->where('ward_id', $data['ward_id']);
        }
        
        if(isset($data['street_id']) && $data['street_id']!=0) {
            $this->db->where('street_id', $data['street_id']);
        }
        
        if(isset($data['project_id']) && $data['project_id']!=0) {
            $this->db->where('project_id', $data['project_id']);
        }
        
        if(isset($data['sophong']) && $data['sophong']!=0) {
            $this->db->where('sophong', $data['sophong']);
        }
        
        if(isset($data['filter_area']) && is_array($data['filter_area'])) {
            $this->db->where('area >=', $data['filter_area']['min']);
            $this->db->where('area <=', $data['filter_area']['max']);
        }
        
        if(isset($data['filter_price']) && is_array($data['filter_price'])) {
            $this->db->where('price_number >=', $data['filter_price']['min']);
            $this->db->where('price_number <=', $data['filter_price']['max']);
            $this->db->where('price_unit', $data['filter_price']['unit']);
        }
        
        if(isset($data['ignore'])) {
            $this->db->where_not_in('p1.id', $data['ignore']);
        }
        
        $this->db->order_by('create_time', 'DESC');
        
        $results = $this->db->get()->num_rows();
        
        return $results;
    }
    
    function _Get_Real_Tag($alias) {
        return $this->db->where('alias', $alias)->get('real_tags')->row_array();
    }
    
    function _Count_Real_Estate_By_Tags($tags) {
        
        return $this->db->select('id')
        ->from('real_estates')
        ->where('status', 'active')
        ->where('tag1_alias', $tags)
        ->or_where('tag2_alias', $tags)
        ->or_where('tag3_alias', $tags)
        ->or_where('tag4_alias', $tags)
        ->or_where('tag5_alias', $tags)
        ->or_where('tag6_alias', $tags)
        ->order_by($order)
        ->get()
        ->num_rows();  
    }
    
    function _Get_Real_Estate_By_Tags($tags, $per_page=20, $page=0) {
        
        $order = 'p.type_id ASC, p.create_time DESC';
        
        return $this->db->select('p.id,p.title,p.alias,p.price_number,p.price_unit,p.area,p.type_id,p.create_time,p.content,c.title AS city_title, d.title AS district_title,w.title AS ward_title')
        ->from('real_estates p')
        ->join('city c', 'p.city_id=c.id', 'LEFT')
        ->join('district d', 'p.district_id=d.id', 'LEFT')
        ->join('wards w', 'p.ward_id=w.id', 'LEFT')
        ->where('p.status', 'active')
        ->where('p.tag1_alias', $tags)
        ->or_where('p.tag2_alias', $tags)
        ->or_where('p.tag3_alias', $tags)
        ->or_where('p.tag4_alias', $tags)
        ->or_where('p.tag5_alias', $tags)
        ->or_where('p.tag6_alias', $tags)
        ->order_by($order)
        ->limit($per_page, $page)
        ->get()
        ->result_array();  
    }
    
    /**
    * @name Đếm danh sách tin đăng theo user
    * @param user_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Int 
    */
    function _Count_Real_Estate_By_User($param = array()) {
        $this->db->select('id')->from('real_estates');
        if(isset($param['user_id']) && $param['user_id']!='') {
            $this->db->where('create_by', intval($param['user_id']));
        }
        if(isset($param['product_id']) && $param['product_id'] > 0) {
            $this->db->where('id', intval($param['product_id']));
        }
        if(isset($param['from_date']) && $param['from_date']!='') {
            $this->db->where('from_date >=', $param['from_date']);
        }
        if(isset($param['to_date']) && $param['to_date']!='') {
            $this->db->where('from_date <=',$param['to_date']);
        }
        if(isset($param['type']) && $param['type']!='') {
            $this->db->where('type_id', intval($param['type']));
        }
        if(isset($param['status']) && $param['status']!='') {
            $this->db->where('status', $param['status']);
        }
        return $this->db->get()->num_rows();
    }
    
    /**
    * @name lấy danh sách tin đăng theo user
    * @param user_id
    * @author SangIT - 0906 493 124 - slevan89@gmail.com
    * @return Int 
    */
    function _Get_Real_Estate_By_User($param = array(), $per_page, $page) {
       
       $order = 'p.type_id ASC, p.create_time DESC';
        
        $this->db->select('p.id,p.title,p.alias,p.create_time,p.views1,p.from_date,p.to_date,p.type_id,p.status,p.create_time')
        ->from('real_estates p');
        
        if(isset($param['user_id']) && $param['user_id']!='') {
            $this->db->where('p.create_by', intval($param['user_id']));
        }
        if(isset($param['product_id']) && $param['product_id'] > 0) {
            $this->db->where('id', intval($param['product_id']));
        }
        if(isset($param['from_date']) && $param['from_date']!='') {
            $this->db->where('p.from_date >=', $param['from_date']);
        }
        if(isset($param['to_date']) && $param['to_date'] != '') {
            $this->db->where('p.from_date <=',$param['to_date']);
        }
        if(isset($param['type']) && $param['type']!='') {
            $this->db->where('p.type_id', intval($param['type']));
        }
        if(isset($param['status']) && $param['status']!='') {
            $this->db->where('p.status', $param['status']);
        }
        
        $result = $this->db->order_by($order)
        ->limit($per_page, $page)
        ->get()
        ->result_array();    
        
        return $result;
    }
    
    function _Get_Real_Estate_By_User_Id($id, $user_id) {
        return $this->db->where('id', intval($id))
        ->where('create_by', intval($user_id))
        ->get('real_estates')
        ->row_array();
    }
    
    function _Delete_Real_Estate_By_User_Id($id, $user_id) {
        $this->db->where('id', intval($id))
        ->where('create_by', intval($user_id))
        ->delete('real_estates');
    }
    
    function _Renew_Real_Estate($id) {
        $this->db->where('id', $id)->update('real_estates', array(
            'from_date' => date("Y-m-d H:i:s", time()),
            'to_date' => date("Y-m-d H:i:s", time() + 60*60*24*15) // 15 ngay
        ));
    }
    
	
	/**
	* Real Estate
	*/
    
	/**
	* Lấy chi tiết tin rao
	*/
    function _Get_Real_Estate_By_Id($id) {
        $cacheid = md5('get_real_estate_by_id_'.$id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->where('id', $id)->where('status', 'active')->get('real_estates')->row_array();
            $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    function _Count_Real_Estate_Images($id) {
        $result = $this->db->where('real_estate_id', $id)->get('real_estate_images')->num_rows();
        
        return $result;
    }
    
	/**
	* Lấy hình ảnh của tin rao
	*/
    function _Get_Real_Estate_Images($id) {
        $cacheid = md5('get_real_estate_images_'.$id);
        $result = $this->cache->get($cacheid);
        if(empty($result)) {
            $result = $this->db->where('real_estate_id', $id)->get('real_estate_images')->result_array();    
            $this->cache->save($cacheid, $result, 3600*24*15);
        }
        return $result;
    }
    
    /**
    * Lấy hình đại diện
    */
    function _Get_Real_Estate_Image($id) {
        $cacheid = md5('get_real_estate_image_'.$id);
        $results = $this->cache->get($cacheid);
        if(empty($results)) {
            $default = '';
    		$results = $this->db->select('image')
    		->from('real_estate_images')
    		->where('real_estate_id', $id)
    		->get()->result_array();
    		$this->cache->save($cacheid, $results, 3600*24*15);
        }
        if(!empty($results)) {
			$default = $results[0]['image'];
			foreach($results as $image) {
				if(isset($image['is_default']) && $image['is_default'] == 1) $default = $image['image'];
			}
		}
        return $default;
    }
	
	/**
	* Lấy danh sách tin rao cùng khu vực đường/phố
	*/
	function _Get_Real_Estate_By_Location($data, $per_page, $page) {
		$this->db->select('id, title,alias,price_number,price_unit,area,content,city_id,district_id,create_time,type_id')
        ->from('real_estates')
        ->where('status', 'active')
        ->where('category_id', $data['category_id']);
        $this->db->where('id <>', $data['id']);
        /*if(isset($data['street_id'])) {
            $this->db->where('street_id', intval($data['street_id']));
        }
        if(isset($data['ward_id'])) {
            $this->db->where('ward_id', intval($data['ward_id']));
        }*/
        if(isset($data['district_id'])) {
            $this->db->where('district_id', intval($data['district_id']));
        }
        
        $this->db->order_by('create_time DESC, ward_id ASC')->limit($per_page, $page);
        
        $results = $this->db->get()->result_array();
        
        foreach($results as $key => $result) {
            $images = $this->_Get_Real_Estate_Images($result['id']);
            $results[$key]['image'] = $images[0]['image'];
            
            if($result['city_id'])
                $results[$key]['city'] = $this->_Get_City_By_Id($result['city_id']);
            else
                $results[$key]['city'] = '';
            
            if($result['district_id'])
                $results[$key]['district'] = $this->_Get_District_By_Id($result['district_id']);
            else
                $results[$key]['district'] = '';
            
        }
        
        return $results;
	}
    
    /**
    * Lấy danh sách tin rao cùng khoảng giá
    */
    function _Get_Real_Estate_By_Price($data, $per_page=12, $page=0) {
        $this->db->select('id, title,alias,price_number,price_unit,area,content,city_id,district_id,create_time,type_id')
        ->from('real_estates')
        ->where('status', 'active')
        ->where('category_id', $data['category_id']);
        $this->db->where('id <>', $data['id']);
        if(isset($data['price_unit'])) {
            $this->db->where('price_unit', intval($data['price_unit']));
        }
        if(isset($data['price_number'])) {
            $this->db->where('price_number', intval($data['price_number']));
        }
        
        $this->db->order_by('create_time', 'DESC')->limit($per_page, $page);
        
        $results = $this->db->get()->result_array();
        
        foreach($results as $key => $result) {
            $images = $this->_Get_Real_Estate_Images($result['id']);
            $results[$key]['image'] = $images[0]['image'];
            
            if($result['city_id'])
                $results[$key]['city'] = $this->_Get_City_By_Id($result['city_id']);
            else
                $results[$key]['city'] = '';
            
            if($result['district_id'])
                $results[$key]['district'] = $this->_Get_District_By_Id($result['district_id']);
            else
                $results[$key]['district'] = '';
            
        }
        
        return $results;
    }
    
    /**
    * Lấy danh sách tin nổi bật
    */
    function _Get_Real_Estate_Featured($per_page, $page) {
        return $this->db->select('p.id,p.title,p.alias,p.price_number,p.price_unit,p.area,p.type_id,p.create_time,p.content,c.title AS city_title, d.title AS district_title,w.title AS ward_title')
        ->from('real_estates p')
        ->join('city c', 'p.city_id=c.id', 'LEFT')
        ->join('district d', 'p.district_id=d.id', 'LEFT')
        ->join('wards w', 'p.ward_id=w.id', 'LEFT')
        ->where('p.status', 'active')
        ->where('p.featured', 1)
        ->order_by('p.create_time','DESC')->limit($per_page, $page)
        ->get()->result_array();
        
    }
	
	/**
	* Search Link
	*/
	function _Get_Footer_Link($parent_id=0, $limit=100) {
	   $this->db->where('parent_id', $parent_id)->where('show_footer', 1);
	   $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($limit);
        
        $result = $this->db->get('footer_link')->result_array();
        
        return $result;
	}
    
    function _Get_Footer_Link_Where_In($id=array()) {
        return $this->db->where_in('id', $id)->get('footer_link')->result_array();
    }
    
    function _Get_Footer_Link_By_Position($position) {
        return $this->db->where('status', 1)->where('position', $position)->get('footer_link')->result_array();
    }
    
    /**
    * Lấy danh sách liên kết nổi bật
    */
    function _Get_Link_By_Location($param=array()) {
        $cacheid = md5(http_build_query($param));
        $results = $this->cache->get($cacheid);//print_r($param);
        if(empty($results)) {
            $this->db->where('status', 1)->where('show_footer', 0);
			if(isset($param['show_detail'])) $this->db->where('show_detail', 1);
            if(isset($param['category_id']) && !isset($param['city_id'])){
                $this->db->where('category_id', (int)$param['category_id']);
                $this->db->where('city_id',0);
                $this->db->where('district_id',0);
                $this->db->where('ward_id',0);
                $this->db->where('street_id',0);
            } else if(isset($param['city_id']) && !isset($param['district_id'])) {
                $this->db->where('category_id', (int)$param['category_id']);
                $this->db->where('city_id', (int)$param['city_id']);
                $this->db->where('district_id',0);
                $this->db->where('ward_id',0);
                $this->db->where('street_id',0);
            } else if(isset($param['district_id']) && !isset($param['ward_id'])) {
                $this->db->where('category_id', (int)$param['category_id']);
                $this->db->where('district_id', (int)$param['district_id']);
                $this->db->where('ward_id',0);
                $this->db->where('street_id',0);
            } 
			/*else if(isset($param['ward_id']) && !isset($param['street_id'])) {
                $this->db->where('category_id', (int)$param['category_id']);
                $this->db->where('ward_id', (int)$param['ward_id']);
                $this->db->where('street_id',0);
            } else if(isset($param['street_id'])) {
                $this->db->where('category_id', (int)$param['category_id']);
                $this->db->where('district_id', (int)$param['district_id']);
                $this->db->where('street_id', (int)$param['street_id']);
            }*/
            $results = $this->db->get('footer_link')->result_array();
            //$this->cache->save($cacheid, $results, 3600*24*30);
        }
		
        //echo $this->db->last_query();
        return $results;
    }
    
    function _Get_Link_Content_By_Url($url) {
		$result = $this->db->select('content')->from('footer_link')->where('link', $url)->get()->row_array();
		return 	$result['content'];
	}
    
    /**
    * Lấy nội dung mô tả theo khu vực
    * Hiển thị trong trang danh mục tin đăng
    */
    function _Get_Footer_Content($param) {
        $this->db->distinct();
        $this->db->select('content')->from('footer_content t1')
        ->join('footer_content_maps t2', 't2.content_id=t1.id');
        if(isset($param['category_id']) && $param['category_id']) 
            $this->db->where('t2.category_id', $param['category_id']);
        else 
            $this->db->where('t2.category_id', 0);
        if(isset($param['city_id']) && $param['city_id']) 
            $this->db->where('t2.city_id', $param['city_id']);
        else 
            $this->db->where('t2.city_id', 0);
        if(isset($param['district_id']) && $param['district_id']) 
            $this->db->where('t2.district_id', $param['district_id']);
        else 
            $this->db->where('t2.district_id', 0);
        if(isset($param['ward_id']) && $param['ward_id']) 
            $this->db->where('t2.ward_id', $param['ward_id']);
        else 
           $this->db->where('t2.ward_id', 0);
        if(isset($param['street_id']) && $param['street_id']) 
            $this->db->where('t2.street_id', $param['street_id']);
        else
            $this->db->where('t2.street_id', 0);
        $result = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return $result['content'];
    }
    
    /**
    * Lấy nội dung chân trang theo dự án
    */
    function _Get_Footer_Content_Project() {
        $cacheid = md5('footer_content_project');
        $result = $this->cache->get($cacheid);
        if(empty($result)){
            $result = $this->db->select('content')->from('footer_content')
            ->where('show_project', 1)->limit(1)->get()->row_array();
            $this->cache->save($cacheid, $result, 3600*24*15);    
        }
        return $result;
    }
    
    /**
    * Lấy danh sách liên kết theo khu vực
    * Hiển thị trong trang chu tiết tin đăng hoặc danh mục
    */
    function _Get_Real_Estate_Links($param, $show_detail=0) {
        
        $this->db->distinct();
        $this->db->select('content')->from('real_estate_links t1')
        ->join('real_estate_link_maps t2', 't2.link_id=t1.id');
        if(isset($show_detail)) $this->db->where('t1.show_detail', $show_detail);
        if(isset($param['category_id']) && $param['category_id']  > 0) $this->db->where('t2.category_id', $param['category_id']);
        if(isset($param['city_id']) && $param['city_id'] > 0) $this->db->where('t2.city_id', $param['city_id']);
        if(isset($param['district_id']) && $param['district_id'] > 0) $this->db->where('t2.district_id', $param['district_id']);
        if(isset($param['ward_id']) && $param['ward_id'] > 0) $this->db->where('t2.ward_id', $param['ward_id']);
        if(isset($param['street_id']) && $param['street_id'] > 0) $this->db->where('t2.street_id', $param['street_id']);
        $result = $this->db->get()->row_array();
        
        return $result['content'];
    }
    
    /**
    * Lấy danh sách liên kết theo khu vực
    * Hiển thị trong trang danh mục
    */
    function _Get_Most_Search_Links($param) {
        
        $this->db->distinct();
        $this->db->select('content')->from('most_search_links t1')
        ->join('most_search_link_maps t2', 't2.link_id=t1.id');
        if(isset($param['category_id'])) $this->db->where('t2.category_id', $param['category_id']);
        if(isset($param['city_id'])) $this->db->where('t2.city_id', $param['city_id']);
        if(isset($param['district_id'])) $this->db->where('t2.district_id', $param['district_id']);
        if(isset($param['ward_id'])) $this->db->where('t2.ward_id', $param['ward_id']);
        if(isset($param['street_id'])) $this->db->where('t2.street_id', $param['street_id']);
        $result = $this->db->get()->row_array();
		//echo $this->db->last_query();
        return $result['content'];
    }
    
    /**
    * Tạo title search
    */
    function _Create_Search_Title($data) {
        $title = '';
        if($data['category_id']) {
            $category = $this->db->select('title')->from('categories')->where('id', intval($data['category_id']))->get()->row_array();
            $title = $category['title'];
        }
        if($data['city_id']) {
            $result = $this->db->select('title')->from('city')->where('id', intval($data['city_id']))->get()->row_array();
            $title .= ' ' . $result['title'];
        }
        if($data['district_id']) {
            $result = $this->db->select('title')->from('district')->where('id', intval($data['district_id']))->get()->row_array();
            $title = $category['title']. ' ' . $result['title'];
        }
        if($data['ward_id']) {
            $result = $this->db->select('title')->from('wards')->where('id', intval($data['ward_id']))->get()->row_array();
            $title = $category['title']. ' ' . $result['title'];
        }
        if($data['street_id']) {
            $result = $this->db->select('title')->from('streets')->where('id', intval($data['street_id']))->get()->row_array();
            $title = $category['title']. ' đường ' . $result['title'];
        }
        if($data['project_id']) {
            $result = $this->db->select('title')->from('projects')->where('id', intval($data['project_id']))->get()->row_array();
            $title = $category['title']. ' ' . $result['title'];
        }
        return $title;
    }
    
    /**
    * Đăng tin
    */
    function _Add_Real_Estate($data) {
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
            'price_unit' => intval($data['price_unit']),
            'area' =>  str_replace(",",".", $data['area']),
            'address' => trim($data['address']),
            'mattien' => str_replace(",",".",$data['mattien']),
            'duongtruocnha' => str_replace(",",".",$data['duongtruocnha']),
            'sotang' => intval($data['sotang']),
            'sophong' => intval($data['sophong']),
            'sotoilet' => intval($data['sotoilet']),
            'huongnha' => intval($data['huongnha']),
            'noithat' => htmlpurifier($data['noithat']),
            'title' => trim($data['title']),
            'content' => htmlpurifier($data['content']),
            'alias' => $alias,
            'type_id' => intval($data['type_id']),
            'from_date' => _convert_date($data['from_date']),
            'to_date' => _convert_date($data['to_date']),
            'create_time' => _Current_Date(),
            'status' => 'hide',
            'create_by' => ($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : '0',
            'guest_fullname' => trim($data['guest_name']) ,
            'guest_address' => trim($data['guest_address']) ,
            'guest_telephone' => trim($data['guest_telephone']) ,
            'guest_mobiphone' => trim($data['guest_mobiphone']) ,
            'guest_email' => trim($data['guest_email']),
            'post_by' => 'user'
        ));
        
        $id = $this->db->insert_id();
        
        //Add tag
        
        $param = array(
            'category_id' => isset($data['category_id']) ? intval($data['category_id']) : 0,
            'city_id' => isset($data['city_id']) ? intval($data['city_id']) : 0,
            'district_id' => isset($data['district_id']) ? intval($data['district_id']) : 0,
            'ward_id' => isset($data['ward_id']) ? intval($data['ward_id']) : 0,
            'street_id' => isset($data['street_id']) ? intval($data['street_id']) : 0
        );
        
        $this->routes_model->_Add_Route($alias, 'real_estate', $id, $param);
        
        if($data['images']) {
            foreach($data['images'] as $key=>$image) {
                if($data['image_default']==($key+1)) $is_default=1;
                else $is_default = 0;
                $this->db->insert('real_estate_images', array(
                    'real_estate_id' => $id,
                    'image' => $image,
                    'is_default' => $is_default
                ));
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
        
        return $id;
    }
    
    function _Update_Real_Estate($id, $data) {
        
        $this->load->model('routes_model');
        
        $this->routes_model->_Delete_Key('real_estate', $id);
        
        $alias = $this->routes_model->_Create_Key($data['title']);
        
        $this->db->where('id', $id)->update('real_estates', array(
            'category_id' => intval($data['category_id']),
			'city_id' => 1,
            'district_id' => intval($data['district_id']),
            'ward_id' => intval($data['ward_id']),
            'street_id' => intval($data['street_id']),
			'project_id' => intval($data['project_id']),
            'price_number' => str_replace(",",".",$data['price_number']),
            'price_unit' => intval($data['price_unit']),
            'area' =>  str_replace(",",".",$data['area']),
            'address' => trim($data['address']),
            'mattien' => str_replace(",",".",$data['mattien']),
            'duongtruocnha' => str_replace(",",".",$data['duongtruocnha']),
            'sotang' => intval($data['sotang']),
            'sophong' => intval($data['sophong']),
            'sotoilet' => intval($data['sotoilet']),
            'huongnha' => intval($data['huongnha']),
            'noithat' => htmlpurifier($data['noithat']),
            'title' => trim($data['title']),
            'content' => htmlpurifier(nl2br($data['content'])),
            'alias' => $alias,
            'type_id' => intval($data['type_id']),
            'from_date' => _convert_date($data['from_date']),
            'to_date' => _convert_date($data['to_date']),
            'update_time' => _Current_Date(),
            'guest_fullname' => trim($data['guest_name']) ,
            'guest_address' => trim($data['guest_address']) ,
            'guest_telephone' => trim($data['guest_telephone']) ,
            'guest_mobiphone' => trim($data['guest_mobiphone']) ,
            'guest_email' => trim($data['guest_email']),
        ));
        
        $param = array(
            'category_id' => isset($data['category_id']) ? intval($data['category_id']) : 0,
            'city_id' => isset($data['city_id']) ? intval($data['city_id']) : 0,
            'district_id' => isset($data['district_id']) ? intval($data['district_id']) : 0,
            'ward_id' => isset($data['ward_id']) ? intval($data['ward_id']) : 0,
            'street_id' => isset($data['street_id']) ? intval($data['street_id']) : 0,
           
        );
        
        $this->routes_model->_Add_Route($alias, 'real_estate', $id, $param);
        
        $this->db->where('real_estate_id', $id)->delete('real_estate_images');
        if($data['images']) {
            foreach($data['images'] as $key=>$image) {
                if($data['image_default']==($key+1)) $is_default=1;
                else $is_default = 0;
                $this->db->insert('real_estate_images', array(
                    'real_estate_id' => $id,
                    'image' => $image,
                    'is_default' => $is_default
                ));
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
    }
    
    function _Update_Real_Estate_View($id) {
        $this->db->query("UPDATE ".$this->db->dbprefix('real_estates')." SET `views1`=`views1`+1 WHERE `id`='".$id."'");
    }
	function _Update_Real_Estate_View_Ao($id) {
        $this->db->query("UPDATE ".$this->db->dbprefix('real_estates')." SET `views2`=`views1`+7 WHERE `id`='".$id."'");
    }
    
    function _Get_Banner($position, $content_cat_id = 0) {
        return $this->db->where('status', 'active')->where('position',$position)->where('content_cat_id', $content_cat_id)->order_by('sort_order', 'ASC')->get('banners')->result_array();
    }
    
    function _Count_Real_Estate_Guest($email) {
        $result = $this->db->select('COUNT(id) as total')->from('real_estates')->where('status', 1)->where('guest_email', $email)->get()->row_array();
        return $result['total'];
    }
    function _Add_Msg($data) {
        $this->db->insert('user_messages', array(
           'fullname' => $data['fullname'],
           'phone' => $data['phone'],
           'email' => $data['email'],
           'message' => $data['msg'],
           'create_time' => date("Y-m-d H:i:s", time()),
           'from_user_id' => $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : 0,
           'to_user_id' => $data['to_user_id'],
           'status' => 0 
        ));
    }
    
    function _Get_User_Msgs($userid, $per_page=12, $page=0) {
        return $this->db->where('to_user_id', $userid)->order_by('create_time DESC')->limit($per_page, $page)->get('user_messages')->result_array();
    }
    
    function _Get_User_Msg($userid, $id) {
        return $this->db->where('to_user_id', $userid)->where('id', $id)->get('user_messages')->row_array();
    }
    
    function _Total_User_Msg_Unview($userid) {
        return $this->db->select('id')->from('user_messages')->where('to_user_id', $userid)->where('status', 0)->get()->num_rows();
    }
    
    function _Update_Msg_Status($userid, $id) {
        $this->db->where('id', $id)->where('to_user_id', $userid)->update('user_messages', array('status' => 1));
    }
    
    function _Delete_Msg($userid, $id) {
        $this->db->where('id', $id)->where('to_user_id', $userid)->delete('user_messages');
    }
}