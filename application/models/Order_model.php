<?php
class Order_model extends CI_Model {
    function _Get_All($per_page, $page=0, $status = '') {
        
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('create_time','DESC');
        $this->db->limit($per_page, $page);
        return $this->db->get('orders')->result_array();
    }
    
    function _Count_All() {
        return $this->db->select('id')->from('orders')->get()->num_rows();
    }
    
    function _Get_By_Id($id) {
        $order = $this->db->where('id', intval($id))->get('orders')->row_array();
        
        $order['detail'] = $this->db->select('od.*, p.title')->from('order_details od')->join('products p', 'p.id=od.product_id')->where('order_id', $id)->get()->result_array();
        
        return $order;
    }
    
    function _Get_Order_Detail($order_id) {
        return $this->db->where('order_id', $order_id)->get('order_details')->result_array();
    }
    
    function _Get_Order_By_User_Id($user_id, $status='') {
        $this->db->where('user_id', intval($user_id));
        if($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get('orders')->result_array();
    }
    
    function _Check_Order_Of_User_Id($user_id, $order_id) {
        $this->db->where('user_id', $user_id)->where('id', $order_id);
        $result = $this->db->get('orders');
        if($result->num_rows())
            return true;
        else
            return false;
    }
    
    function _Add($data) {
        
        $code = $this->_General_Code();
        
        $this->db->insert('orders', array(
           // 'user_id' => $data['user_id'],
            'name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => trim($data['email']),
            'address' => trim($data['address']),
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
            'note' => trim($data['note']),
            'code' => $code,
            'status' => 'pending',
            'create_time' => date('Y-m-d H:i:s', time())
        ));
        $order_id = $this->db->insert_id();
        foreach($data['products'] as $rowid=>$item) {
            $this->db->insert('order_details', array(
               'order_id' => $order_id,
               'product_id' => intval($item['id']),
               'price' => intval($item['price']) ,
               'quantity' => intval($item['qty'])
            ));
        }
        return $code;
    }
    
    function _Update_Status($id, $status) {
        $this->db->where('id', intval($id))->update('orders', array(
                'status' => trim($status),
                'update_time' => time()
            ));
        
    }
    
    function _Delete($id) {
        $this->db->where('id', intval($id))->delete('orders');
        $this->db->where('order_id', intval($id))->delete('order_details');
    }
    
    function _General_Code() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i < 10; $i++)
            $result .= $characters[mt_rand(0, 36)];
        if($this->_Check_Code($result))
            $this->_General_Code();
        return $result;
    }
    
    function _Check_Code($code) {
        return $this->db->select('id')
        ->from('orders')
        ->where('code', $code)
        ->get()
        ->row_array();
    }
}