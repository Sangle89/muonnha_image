<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chat_model extends CI_Model
{
	private $users_table_name	= 'users';
	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		$this->users_table_name	= $this->users_table_name;
		
	}
    function login() {
        
    }
	function saveUser($username)
	{
		
	}
	
	function getUser($user_id)
	{
		$this->db->where('id',$user_id);
		$query=$this->db->get('users');
		return $query->row_array();
	}
}