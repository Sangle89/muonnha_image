<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . '/core/MY_Controller.php';
class Image extends MY_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'cache/main_model'
        ));
        $this->load->library(array('table', 'form_validation'));
    }
    
    function index() {
        $this->generateImage();
    }
    
    private function generateImage() {
        $this->load->library('image_moo');
        $type = $this->input->get('type') ? $this->input->get('type') : 'images';
        $filename = $this->input->get('image');
        $width = 200;//(int)$this->input->get('thumb');
        $height = 200;//(int)$this->input->get('thumb');
        if($filename && $width && $height && $type) {
            //L?y thông tin hình ?nh trong thu m?c uploads/type
    		$info = pathinfo(FCPATH . '/uploads/' . $type . '/' . $filename);
            //L?y d?nh d?ng file
    		$extension = $info['extension'];
            
    		$old_image = $filename;
            
    		$new_image = 'uploads/' . $type . '/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height .'.' . $extension;
            
            if (!file_exists(FCPATH . '/' . $new_image) || (filemtime(FCPATH . '/uploads/' . $type . '/' . $old_image) > filemtime(FCPATH . '/'  . $new_image))) {
    			
                $this->image_moo->load(FCPATH . '/uploads/' . $type . '/'. $old_image)
                //->set_background_colour('#ffffff')
                ->resize_crop($width, $height, TRUE)
                ->save(FCPATH . '/' . $new_image, TRUE);
    		}
            
            $file = basename($filename);
            $file_extension = strtolower(substr(strrchr($file,"."),1));
            
            switch( strtolower($file_extension) ) {
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpeg":
                case "jpg": $ctype="image/jpeg"; break;
                default:
            }
            redirect(base_url($new_image));
            //echo $ctype;
            //header('Content-Type: image/jpeg');
    		//imagejpeg(readfile(FCPATH .'/'. $new_image), null, 75);
            //die();
        } else {
            echo 'Faild !';
        }
    }
}