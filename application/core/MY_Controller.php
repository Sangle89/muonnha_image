<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth','send_email','form_validation','template','session','cart'));
		$this->load->helper(array('url','language','functions','htmlpurifier', 'utf8'));
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model(array(
            'banner_model', 
            'setting_model',
            'content_model',
            'routes_model',
            'tags_model'
        ));
        
        //if($this->uri->segment(1)=='') redirect('/vn/');
        $language = array('vn','en');
        
        if($this->session->userdata('_lang') && $this->session->userdata('_lang') == 'en'){
            $lang = 'english';
            define('LANG', '_en');
        }else{ 
            $lang = 'vietnamese';
            define('LANG', '');
        }
		/*
		if(USERTYPE == 'Mobile') {
			$this->template->_Set_Template('mobile');
		}*/
       
        $this->load->language('site',$lang);
        $this->_Set_Default_Data();
        
        if ($this->ion_auth->logged_in()){
            $this->template->_Set_Data('user', $this->ion_auth->user()->row());
        } else {
            
        }
        
    }
    
    function _Set_Default_Data() {
        $this->template->_Set_Data('pageindex', true);
    }
    
    function _Set_Post($data) {
        foreach($this->input->post() as $name=>$val) {
            $data[$name] = $val;
        }
        return $data;
    }
    
    function _Check_Login() {
        if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			//return show_error('You must be an administrator to view this page.');
            echo 'Bạn không có quyền truy cập trang web này';
            exit;
		}
		else
		{
            return true;
        }
    }
}