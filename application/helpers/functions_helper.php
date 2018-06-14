<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//Tạo chuổi ngẩu nhiên
function random_str($length)
{
    return substr(sha1(rand()), 0, $length);
}
function _Upload_Avatar($name) {
    get_instance()->load->library('upload');
		$config = array(
			'upload_path' 		=> 	'./uploads/avatar/',
			'allowed_types'	 	=> 	'jpg|png|JPG|PNG',
			'is_image'			=>	true,
			'max_size'			=> 	80*1024,
			'max_width'  		=> 	1024*100,
			'max_height'  		=> 	1024*100,
			'max_filename'		=>	250,
			'encrypt_name'		=>	true,
			'overwrite'			=> false,
		);
		get_instance()->upload->initialize($config);
		if(!get_instance()->upload->do_upload($name)){
			return get_instance()->upload->display_errors();
		}
		return get_instance()->upload->data();
}

function _Upload_Catalogue($name, $path) {
        get_instance()->load->library('upload');
		$config = array(
			'upload_path' 		=> 	$path,
			'allowed_types'	 	=> 	'doc|docx|pdf',
			'is_image'			=>	true,
			'max_size'			=> 	80*1024,
			'max_width'  		=> 	1024*100,
			'max_height'  		=> 	1024*100,
			'max_filename'		=>	250,
			'encrypt_name'		=>	true,
			'overwrite'			=> false,
		);
		get_instance()->upload->initialize($config);
		if(!get_instance()->upload->do_upload($name)){
			return get_instance()->upload->display_errors();
		}
		return get_instance()->upload->data();
}

use Imagecraft\ImageBuilder;
function _Resize_Img($source, $target, $nwidth, $nheight){
	get_instance()->load->library('my_img');
		return get_instance()->my_img->Do_Resize_White($nwidth, $nheight, $target, $source);
		list($width,$height)=getimagesize($source);
		$newwidth=$nwidth;
		if($nheight==0 && $nwidth!=0){
		  	$ratio = $nwidth / $width;
      		$newheight = $height * $ratio;
			$newwidth=$nwidth;
		}elseif($nwidth==0 && $nheight!=0){
			$ratio = $nheight / $height;
		  	$newwidth = $width * $ratio;
			$newheight=$nheight;
		}elseif($nheight==0 && $nwidth==0){
			$newheight=$height;
			$newwidth=$width;
		}else{
			$newheight=$nheight;
			$newwidth=$nwidth;
		}
		require_once(APPPATH.'third_party/imagecraft/autoload.php');
		$options =array('engine' => 'php_gd', 'locale' => 'en');
		$builder = new ImageBuilder($options);
		$image = $builder
			->addBackgroundLayer()
				->filename($source)
				->resize($newwidth, $newheight, 'fill_crop')
				->done()
				->save();
		if ($image->isValid()) {
			file_put_contents($target, $image->getContents());
			return true;
		} else {
			return $image->getMessage();
		}
		/*require_once(BASEPATH.'plugin/Zebra_Image.php');
		$image = new Zebra_Image();
		$image->source_path = $source;
		$image->target_path = $target;
		if (!$image->resize($width, $height, ZEBRA_IMAGE_BOXED, -1)){
			return _show_img_error($image->error, $image->source_path, $image->target_path);
		}
		return true;*/
}
function _Resize_Crop($file_path, $file_name, $folder = 'images', $width=155, $height=130) {
    $CI =& get_instance();
    $CI->load->library('image_moo');
    $return = $CI->image_moo->load($file_path)
            //->set_background_colour('#ffffff')
            ->resize_crop($width, $height, TRUE)
            ->save(FCPATH . 'uploads/thumb/'.$folder.'/'.$file_name, TRUE);
    return $return;
}
function _Upload_Image2($folder, $name, $width=900, $height=600, $resize = true) {
    
    $CI =& get_instance();
    
    //Upload image
    $CI->load->library('upload');
    
    $filename = random_str(8);
    
    //create path folder 2016/11/07
    $year = date("Y", time());
    $month = date("m", time());
    $date = date("d", time());
   
    if(!is_dir(DIR_UPLOAD . $folder.'/' . $year)) {
        mkdir(DIR_UPLOAD .$folder.'/' . $year, '0755', true);
    }
    if(!is_dir(DIR_UPLOAD.$folder.'/' . $year . '/' . $month)) {
        mkdir(DIR_UPLOAD.$folder.'/'.$year.'/'.$month, '0755', true);
    } 
    if(!is_dir(DIR_UPLOAD.$folder.'/'.$year.'/'.$month.'/'.$date)) {
        mkdir(DIR_UPLOAD.$folder.'/'.$year.'/'.$month.'/'.$date, '0755', true);
    }
    
    $upload_path = DIR_UPLOAD.$folder.'/' . $year .'/' . $month . '/' . $date . '/';
    $image_path = $year .'/' . $month . '/' . $date;
    
    $filename = $filename.'_'.time();
		$config = array(
			'upload_path' 		=> 	$upload_path,
			'allowed_types'	 	=> 'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
			'file_name'         => $filename,
			'is_image'			=>	true,
			'max_size'			=> 	80*1024,
			'max_width'  		=> 	1024*100,
			'max_height'  		=> 	1024*100,
			'max_filename'		=>	250,
			'encrypt_name'		=>	false,
			'overwrite'			=> false,
		);
        
		$CI->upload->initialize($config);
		
        if(!$CI->upload->do_upload($name)){
		      
        	return array(
                'error' => $CI->upload->display_errors()
            );
		
        } else {
		
            $file_upload = $CI->upload->data();
            
            if($resize){
                $image_size = getimagesize($file_upload['full_path']);
                $image_width = $image_size[0];
                $image_height = $image_size[1];
                
                if($image_width > $image_height) {
                    $resize_width = 624;
                    $resize_height = (624 * $image_height) / $image_width;
                } else {
                    $resize_width = (476 * $image_width) / $image_height;
                    $resize_height = 476;
                }
                
                
            }
            
            $CI->load->library('image_moo');
            $return = $CI->image_moo->load($file_upload['full_path'])
            ->set_background_colour('#ffffff')
            ->resize($resize_width, $resize_height, TRUE)
            ->save($file_upload['full_path'], TRUE);
            
            //Wartermark
            $CI->image_moo->load($file_upload['full_path'])
            ->load_watermark(FCPATH . 'theme/images/watermark.png')
            ->set_watermark_transparency(1)
            ->watermark(3)
            ->save($file_upload['full_path'], TRUE);
            
            $source =array(
                'file_name' => $file_upload['file_name'],
                'file_path' => $image_path . '/' . $file_upload['file_name'],
                'full_path' => $file_upload['full_path'],
                'file_type' => $file_upload['file_type'],
                'file_size' => $file_upload['file_size'],
                'file_ext'  => $file_upload['file_ext']
            );
		  
            return $source;
        }
}
	
function _show_img_error($error_code, $source_path, $target_path){
    switch ($error_code) {
            case 1:
                return 'Source file "' . $source_path . '" could not be found!';
                break;
            case 2:
                return 'Source file "' . $source_path . '" is not readable!';
                break;
            case 3:
                return 'Could not write target file "' . $source_path . '"!';
                break;
            case 4:
                return $source_path . '" is an unsupported source file format!';
                break;
            case 5:
                return $target_path . '" is an unsupported target file format!';
                break;
            case 6:
                return 'GD library version does not support target file format!';
                break;
            case 7:
                return 'GD library is not installed!';
                break;
            case 8:
                return '"chmod" command is disabled via configuration!';
                break;
    }
}