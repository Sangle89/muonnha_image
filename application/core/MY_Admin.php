<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class MY_Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        
        $this->load->library(array('ion_auth','form_validation', 'table','template','breadcrumb'));
		$this->load->helper(array('Url','admin_menu','Functions','htmlpurifier'));
		$this->load->config('admin_menu');
        $this->load->model(
            array(
                'real_estate_model',
                'content_category_model'
            )
        );
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->breadcrumb->__construct(array(
            'tag_open' => '<ol class="breadcrumb">',
            'tag_close' => '</ol>'
        ));
        $this->lang->load('auth');
        $this->_Check_Login();
		//var_dump($this->_Check_Login());
        //$this->_Check_Module_Permission();
        $this->template->_Set_Template(ADMIN_FOLDER);
        $this->template->_Set_Script('');
        $this->_Set_Default_Template();
        $this->template->_Set_Title('Administrator');
        if($this->uri->segment(3)=='add' || $this->uri->segment(3)=='change')
            $table_class = 'table-form';
        else 
            $table_class = 'table-list';
        $this->table->set_template(array(
            'table_open'		=>	'<table class="table table-bordered table-hover table-striped '.$table_class.'">'
        ));
        $this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
    }
    
    
    function _Set_Post($data) {
        foreach($this->input->post() as $name=>$val) {
            $data[$name] = $val;
        }
        return $data;
    }
    
    function _Check_Login() {
        
        if( !$this->ion_auth->logged_in() ) {
            redirect(ADMIN_FOLDER . '/login', 'refresh');
        } else {
            
            if(($this->ion_auth->is_writer() && $this->uri->segment(2) == 'content' && $this->uri->segment(3) !='delete')
            || $this->ion_auth->is_writer() && $this->uri->segment(2) == '') {
                return true;
            } elseif($this->ion_auth->is_admin()) {
                return true;
            } else {
                echo '<div style="text-align:center;padding: 50px;background:#efefef;border:1px solid #ddd">
                    <p>Bạn không có quyền truy cập trang web này !</p>
                    <a href="'.admin_url().'">Quay lại</a>
                </div>';
                exit;
            }
        }
        
    }
    
    function _Check_Module_Permission() {
        $user = $this->ion_auth->user()->row();
        $group = $this->ion_auth->get_users_groups($user->id)->row();
        
        $modules_permission = explode("|", $group->modules);
        $cur_module = $this->uri->segment(2);
        
        if(isset($cur_module) && !in_array($cur_module, $modules_permission)){
            die("Bạn không có quyền truy cập chức năng này!");  
        } else
            return false;
    }
    
    function _Set_Default_Template() {
        $user = $this->ion_auth->user()->row();
        $group = $this->ion_auth->get_users_groups($user->id)->row();
        $this->template->_Set_Data('username', $user->username);
        $this->template->_Set_Data('avatar', $user->avatar);
        $this->template->_Set_Data('group', $group->name);
        $this->template->_Set_Data('user_id', $user->id);
       $this->template->_Set_Data('user', $user);
    }
    
    
}