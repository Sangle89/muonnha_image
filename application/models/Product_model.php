<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function _Count_All($status = '') {
        $this->db->select('id')->from('products');
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get()->num_rows();
    }
    
    function _Get_All($per_page=9999, $page=0, $status='', $sort_by = 'create_time') {
        
        $this->db->select('p.id,p.title,p.alias,p.image,p.price,p.special_price,p.unit,p.status,p.create_time,p.sort_order,pc.title AS category_title');
        $this->db->from('products p');
        $this->db->join('product_categories pc', 'p.category_id=pc.id', 'LEFT');
        if($status) {
            $this->db->where('p.status', $status);
        }
        if($sort_by=='create_time') {
            $this->db->order_by('p.create_time', 'DESC');
        }elseif($sort_by=='sort_order') {
            $this->db->order_by('p.sort_order', 'ASC');
        }
        
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    function _Get_All_Search($sku='', $title='', $category_id='', $status='') {
        $this->db->select('p.id,p.title,p.alias,p.image,p.status,p.sort_order,pc.title AS category_title');
        $this->db->from('products p');
        $this->db->join('product_categories pc', 'p.category_id=pc.id', 'LEFT');
        if($sku)
            $this->db->like('p.sku', $sku);
        if($title)
            $this->db->like('p.title', $title);
        if($category_id)
        $this->db->where('p.category_id', $category_id);
        if($status) 
            $this->db->where('p.status', $status);
        $this->db->order_by('sort_order', 'ASC');
        
        $result = $this->db->get()->result_array();
        echo $this->db->last_query();
        return $result;
    }
    
    function _Get_All_New($per_page=9999, $page=0, $status='') {
        
        $this->db->select('p.id,p.title,p.product_title,p.url_alias,p.sku,p.image,p.status,p.sort_order,pc.title AS category_title');
        $this->db->from('products p');
        $this->db->join('categories pc', 'p.category_id=pc.id', 'LEFT');
        if($status) {
            $this->db->where('p.status', $status);
        }
        $this->db->order_by('p.create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    function _Count_All_By_Category($category_id, $status='') {
        $this->db->select('id')->from('products')->where('category_id', $category_id);
        if($status) {
            $this->db->where('status', $status);
        }
        $result = $this->db->get()->num_rows();
        return $result;
    }
    
    function _Get_All_By_Category($category_id, $per_page = 9999, $page = 0, $status='') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->where('category_id', $category_id);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('products')->result_array();
       
        return $result;
    }
    /*
    function _Count_By_NCategory($category_id = array(), $status = 'active') {
        $result = $this->db->select('id')
        ->from('products')
        ->where('status', $status)
        ->where_in('category_id', $category_id)
        ->get()
        ->num_rows();
        return $result;
    }
    
    function _Get_By_NCategory($category_id = array(), $per_page=9999, $page=0, $status = 'active') {
        $result = $this->db->select('id,title,alias,price,special_price,image,rate')
        ->from('products')
        ->where('status', $status)
        ->where_in('category_id', $category_id)
        ->order_by('sort_order','ASC')
        ->limit($per_page, $page)
        ->get()
        ->result_array();
        return $result;
    }*/
    
    function _Count_By_NCategory($category_id) {
        $table = $this->db->dbprefix('products');
        $table_cat = $this->db->dbprefix('product_categories');
        $result = $this->db->select("COUNT(id) AS total")
        ->where("category_id = ".$category_id." OR category_id IN (SELECT id FROM ".$table_cat." WHERE parent_id=".$category_id." OR parent_id IN (SELECT id FROM ".$table_cat." WHERE parent_id=".$category_id."))")
        ->get('products')
        ->row_array();
        return $result['total'];
    }
    
    function _Get_By_NCategory($category_id , $per_page=9999, $page=0, $status = 'active') {
        $table = $this->db->dbprefix('products');
        $table_cat = $this->db->dbprefix('product_categories');
        $result = $this->db->select("id,title,alias,price,special_price,image,rate,new,unit")
        ->where("category_id = ".$category_id." OR category_id IN (SELECT id FROM ".$table_cat." WHERE parent_id=".$category_id." OR parent_id IN (SELECT id FROM ".$table_cat." WHERE parent_id=".$category_id."))")
        ->order_by('create_time', 'DESC')
        ->limit($per_page, $page)
        ->get('products')
        ->result_array();
        return $result;
    }
    
    function _Get_By_Filter($category_id, $property, $per_page=9999, $page = 0) {
        $table_cat = $this->db->dbprefix('product_categories');
        $this->db->distinct();
        $this->db->select('f1.id,f1.title,f1.alias,f1.price,f1.special_price,f1.image')
        ->from('products f1');
        
        if($property)
            $this->db->join('product_properties f2', 'f1.id=f2.product_id');
        
        $this->db->where('f1.category_id', $category_id);
        $this->db->or_where('f1.category_id IN (SELECT id from '.$table_cat.' WHERE parent_id='.$category_id.' OR parent_id IN (SELECT id from '.$table_cat.' WHERE parent_id='.$category_id.'))');
        
        if($property){
            foreach($property as $val) {
                $this->db->where('f2.property_id', $val);
            }
        }
            //$this->db->where_in('f2.property_id', $property);
        $result = $this->db->get()
        ->result_array();
        
        return $result;
    }
    
    function _Get_By_Id($id) {
        return $this->db->where('id', $id)->get('products')->row_array();
    }
    
    function _Get_By_Url_Alias($id) {
        return $this->db->where('url_alias', $id)->get('products')->row_array();
    }
    
    function _Get_Related($product, $per_page=9999, $page=0) {
        return $this->db->where('id!=', $product['id'])->where('category_id', $product['category_id'])->where('status', 'active')->limit($per_page, $page)->get('products')->result_array();
    }
    
    function _Get_Related2($product_id, $category_id, $property, $per_page=999, $page=0) {
        $this->db->distinct();
        $result = $this->db->select('f1.id,f1.title,f1.price,f1.image,f1.alias')
        ->from('products f1')
        ->join('product_properties f2', 'f1.id=f2.product_id')
        ->where('f1.status', 'active')
        ->where('f1.category_id', $category_id)
        ->where_in('f2.property_id', $property)
        ->where('f1.id !=', $product_id)
        ->get()
        ->result_array();
        return $result;
    }
    
    function _Count_Search($string, $status='') {
        $this->db->select('id')->from('products');
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->like('title', $string)->or_like('keywords', $string);
        $result = $this->db->get()->num_rows();
        return $result;
    }
    
    function _Get_Search($string, $per_page=9999, $page=0, $status='active') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->like('title', $string)
                ->or_like('keywords', $string);
                
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('products')->result_array();
       
        return $result;
    }
    
    function _Count_Search_By_Tags($tag_id) {
        return $this->db->select('product_id')->from('product_tags')->where('tag_id', $tag_id)->get()->num_rows();
    }
    
    function _Get_Search_By_Tags($tag_id, $per_page=999, $page=0) {
        $this->db->distinct();
        return $this->db->select('f1.id, f1.title, f1.alias,f1.price, f1.image')
        ->from('products f1')
        ->join('product_tags f2', 'f2.product_id=f1.id')
        ->where('status', 'active')
        ->order_by('f1.sort_order ASC,f1.create_time DESC')
        ->limit($per_page, $page)
        ->get()
        ->result_array();
    }
    
    function _Get_Latest($per_page = 9999, $page=0, $status='active') {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('create_time', 'DESC');
        $this->db->limit($per_page, $page);
        
        $result = $this->db->get('products')->result_array();
        
        return $result;
    }
    
    function _Get_Property($product_id) {
        return $this->db->select('property_id')->from('product_properties')
        ->where('product_id', $product_id)
        ->get()
        ->result_array();
    }
    
    function _Get_Product_Image($product_id) {
        return $this->db->where('product_id', $product_id)
        ->get('product_images')
        ->result_array();
    }
    
    function _Get_Product_tag($product_id) {
        return $this->db->where('product_id', $product_id)
        ->get('product_tags')
        ->result_array();
    }
    
    function _Add($data) {
        $this->load->model('routes_model');
        $alias = $this->routes_model->_Create_Key($data['title']);
        $this->db->insert('products', array(
            
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'alias' => $alias,
            'rate' => $data['rate'],
            'image' => $data['image'],
            'category_id' => intval($data['category_id']),
            'price' => _Price_To_Int($data['price']),
            'special_price' => _Price_To_Int($data['special_price']),
            'unit' => trim($data['unit']),
            'content' => htmlpurifier($data['content']),
            'short_content' => htmlpurifier($data['short_content']),
            'sort_order' => intval($data['sort_order']),
            'create_time' => _Current_Date(),
            'status' => $data['status'],
            'new' => $data['new']
        ));
        
        $id = $this->db->insert_id();
        
        foreach($data['property'] as $val) {
            $this->db->insert('product_properties', array(
            'product_id' => $id,
            'property_id' => $val
            ));
        }
        
        if($data['images']) {
            foreach($data['images'] as $image) {
                $this->db->insert('product_images', array(
                    'product_id' => $id,
                    'image' => $image
                ));
            }
        }
        
        if($data['tags']) {
            foreach($data['tags'] as $tag_id) {
                $this->db->insert('product_tags', array(
                    'product_id' => $id,
                    'tag_id' => $tag_id
                ));
            }
        }
        
        $this->routes_model->_Add_Route($alias, 'product', $id);
        return $id;
    }
    
    function _Update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('products', array(
            'title' => trim($data['title']),
            'keywords' => trim($data['keywords']),
            'description' => trim($data['description']),
            'image' => $data['image'],
            'rate' => $data['rate'],
            'price' => _Price_To_Int($data['price']),
            'special_price' => _Price_To_Int($data['special_price']),
            'unit' => trim($data['unit']),
            'category_id' => intval($data['category_id']),
            'content' => htmlpurifier($data['content']),
            'short_content' => htmlpurifier($data['short_content']),
            'sort_order' => intval($data['sort_order']),
            'update_time' => _Current_Date(),
            'status' => $data['status'],
            'new' => $data['new']
        ));
        $this->db->where('product_id', $id)->delete('product_properties');
        foreach($data['property'] as $val) {
            $this->db->insert('product_properties', array(
            'product_id' => $id,
            'property_id' => $val
            ));
        }
        
        $this->db->where('product_id', $id)->delete('product_images');
        if($data['images']) {
            foreach($data['images'] as $image) {
                $this->db->insert('product_images', array(
                    'product_id' => $id,
                    'image' => $image
                   // 'sort_order'=>$image['sort_order']
                ));
            }
        }
        
        $this->db->where('product_id', $id)->delete('product_tags');
        if($data['tags']) {
            foreach($data['tags'] as $tag_id) {
                $this->db->insert('product_tags', array(
                    'product_id' => $id,
                    'tag_id' => $tag_id
                ));
            }
        }
        
    }
    
    function _Update_Status($status, $id) {
        $this->db->where('id', $id)->update('products', array(
            'status' => trim($status)
        ));
    }
    
    function _Update_View($id) {
        $prefix = $this->db->prefix();
        $this->db->query("UPDATE ".$prefix."products SET views=views+1 WHERE id='".intval($id)."' LIMIT 1");
    }
    
    function _Update_Sort($id, $value) {
        $this->db->where('id', intval($id))->update('products', array('sort_order'=>$value));
    }
    
    function _Delete($id) {
        $category = $this->_Get_By_Id($id);
        if($category) {
            $this->db->where('id', $id)->delete('products');
            $this->db->where('key', $category['url_alias'])->delete('app_routes');
            @unlink(FCPATH . 'public/uploads/images/' . $category['image']);
            @unlink(FCPATH . 'public/uploads/files/' . $category['catalogue']);
            
        }
    }
}