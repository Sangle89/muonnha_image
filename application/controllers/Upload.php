<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . '/core/MY_Controller.php';
class Upload extends MY_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'cache/main_model'
        ));
        $this->load->library(array('table', 'form_validation'));
    }
    
    //Upload image real estate
    function upload_avatar() {
        $output_dir = "./uploads/";
        if(isset($_FILES["avatar"]))
        {
        	$ret = array();
        	$fileName = '';
        	$error =$_FILES["avatar"]["error"];
            
        	//You need to handle  both cases
        	//If Any browser does not support serializing of multiple files using FormData() 
        	if(!is_array($_FILES["avatar"]["name"])) //single file
        	{
 	              
                $file_upload = _Upload_Avatar('avatar');
                $fileName = $file_upload['file_name'];
            	$ret[]= $fileName;
                
        	}
        	
            echo $fileName;
         }
    }
    
    //Delete image
    function delete_avatar() {
        if($this->input->post('op') == 'delete') {
            $name = $this->input->post('name');
            $name = str_replace('"', '', $name);
            $name = str_replace('[', '', $name);
            $name = str_replace(']', '', $name);
            var_dump(@unlink(FCPATH . '/uploads/avatar/'.$name));
        }
    }
    
    //Upload image real estate
    function upload_photo() {
        $output_dir = "./uploads/";
        if(isset($_FILES["myfile"]))
        {
        	$ret = array();
        	
        	$error =$_FILES["myfile"]["error"];
            
            $width = 900;
            $height = 600;
            $folder = 'images';
        	//You need to handle  both cases
        	//If Any browser does not support serializing of multiple files using FormData() 
        	if(!is_array($_FILES["myfile"]["name"])) //single file
        	{
 	              
                $file_upload = _Upload_Image2($folder, 'myfile', $width, $height);
                $fileName = $file_upload['file_path'];
            	$ret[]= $fileName;
        	}
        	else  //Multiple files, file[]
        	{
        	  $fileCount = count($_FILES["myfile"]["name"]);
        	  for($i=0; $i < $fileCount; $i++)
        	  {
        	  	$file_upload = _Upload_Image2($folder, 'myfile['.$i.']', $width, $height);
                $fileName = $file_upload['file_path'];
            	$ret[]= $fileName;
        	  }
        	
        	}
            echo json_encode($ret);
         }
    }
    
    //Delete image
    function delete_photo() {
        if($this->input->post('op') == 'delete') {
            $name = $this->input->post('name');
            $name = str_replace('"', '', $name);
            $name = str_replace('[', '', $name);
            $name = str_replace(']', '', $name);
            var_dump(@unlink(FCPATH . '/uploads/images/'.$name));
        }
    }
    
    function uploadRealEstate(){
        
        $upload_data = _Upload_Image2('images', 'uploadfile', 900, 600);
        
        if(!$upload_data['error']) {
            $res = array(
                'success' => true,
                'file_name' => $upload_data['file_path'],
                'file_url' => base_url('uploads/images/'.$upload_data['file_path'])
            );
        } else {
            $res = array(
                'success' => false
            );
        }
        echo json_encode($res);
    }
    
    function deleteImage($file_name) {
        @unlink('./uploads/images/'.$file_name);
        @unlink('./uploads/images/thumb/'.$file_name);
    }
    
    function add_image() {
        $this->load->library('RandomStringGenerator');
        $random = $this->randomstringgenerator->generate(32);
        $data['cache_id'] = $random . time();
        echo $this->load->view( 'default/ajax/add_image', $data, TRUE);
    }
    
    function load_image($id) {
        $id = $this->input->get('id');
        $this->load->library('RandomStringGenerator');
        $data['images'] = $this->main_model->_Get_Real_Estate_Images($id);
      
        echo $this->load->view( 'default/ajax/load_image', $data, true);
    }
}