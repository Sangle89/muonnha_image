<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Imagecraft\ImageBuilder;
require_once(APPPATH . "third_party/PHPMailer/class.phpmailer.php");
require_once(APPPATH . "third_party/PHPMailer/class.smtp.php");
class Sangmaster {

    public function __construct() {
		$this->CI =& get_instance();
	}
    
    function _Add_Msg($string) {
        $message = $this->CI->session->flashdata('_Message');
        if(!$message) $message = array();
        $message[] = $string;
        $this->CI->session->set_flashdata('_Message', $message);
    }
    
    function _Show_Msg($type='success') {
        $html = '';
        $message = $this->CI->session->flashdata('_Message');
        if($message) {
            $html .= '<div class="alert alert-'.$type.'">';
            foreach($message as $msg) {
                $html .= '<p>'.$msg.'</p>';
            }
            $html .='</div>';
        }
        echo $html;
    }
	
    /**
    * Chuyển chuổi có dấu thành không dấu có -
    */
	public function _To_Slug($string) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        $str = preg_replace("/[\/_|+ -]+/", '-', $str);
        return $str;
	}
    
    /**
    * Kiểm tra định dạng email
    */
    function _Valid_Email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
    
    /**
    * Kiểm tra định dạng số
    */
    function _Valid_Number($str) {
        return is_numeric($str);
    }
    
    /**
    * Tạo chuổi ngẩu nhiên
    */
    function _Random_String($length)
    {
        return substr(sha1(rand()), 0, $length);
    }
    
    function _Price_To_Int($str) {
        return intval(str_replace(",","",$str));
    }
    
    function _Format_Price($number) {
        return number_format($number, 0, ",", ".");
    }
    
    function _Get_Date($datetime) {
        $date_obj = new DateTime($datetime);
        return date_format($date_obj, 'd');
    }
    
    function _Get_Month($datetime) {
        $date_obj = new DateTime($datetime);
        return date_format($date_obj, 'm');
    }
    
    function _Get_Year($datetime) {
        $date_obj = new DateTime($datetime);
        return date_format($date_obj, 'Y');
    }
    
    //Convert date d/m/yyyy -> Y-m-d H:i:s
    function _Convert_Date($string_date) {
        $array = explode("/", $string_date);
        $new_date = $array[2].'-'.$array[1].'-'.$array[0];
        return $new_date;
    }
    
    function _Revert_Date($string_date) {
        $array = explode(" ", $string_date);
        
        $temp = explode("-", $array[0]);
        
        $new_date = $temp[2].'/'.$temp[1].'/'.$temp[0];
        return $new_date;
    }
    
    function _Convert_Datetime($string_date) {
        $array = explode("/", $string_date);
        $new_date = $array[2].'-'.$array[1].'-'.$array[0].' '.date('H:i:s', time());
        return $new_date;
    }
    
    function _Revert_Datetime($string_date) {
        $array = explode(" ", $string_date);
        
        $temp_date = explode("-", $array[0]);
        $temp_time = explode(":", $array[1]);
        
        $new_date = $temp_date[2].'/'.$temp_date[1].'/'.$temp_date[0];
        return $new_date.' '.$array[1];
    }
    
    function _Send_Email($subject, $body, $to, $cc = array()) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = false;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = SMTP_EMAIL;
        $mail->Password = SMTP_PASSWORD;
        $mail->CharSet = 'UTF-8';
        $mail->MsgHTML($message);
        $mail->Subject = $subject;
        $mail->SetFrom(FROM_EMAIL, FROM_NAME);
        $mail->FromName = FROM_TITLE;
        $mail->AddReplyTo($from);
        $mail->AddAddress($to);
        
        foreach($cc as $e){
            $mail->AddAddress($e);
        }
        
        $mail->IsHTML(true);
        
        if($mail->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function _Get_Youtube_Id($url) {
        parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
        return $my_array_of_vars['v'];    
    }
    
    
    function _Resize_Img($source, $target, $nwidth, $nheight){
    	$this->CI->load->library('my_img');
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
    
    /*
    * Resize crop hình ảnh đúng kính thước định sẵn
    */
    function _Resize_Crop($source, $target, $width, $height) {
        $this->CI->load->library('image_moo');
        $this->CI->image_moo->load($source)->resize_crop($width, $height)->save($target, true);
        return $target;
    }
    
    function _Upload_Image($name, $folder, $width=900, $height=600) {
        
        $CI =& get_instance();
        
        //Upload image
        $CI->load->library('upload');
        
        $filename = random_str(8);
        
        $upload_path = FCPATH . 'uploads/'.$folder.'/';
        
        $filename = $filename.'_'.time();
    		$config = array(
    			'upload_path' 		=> 	$upload_path,
    			'allowed_types'	 	=> 'jpg|jpeg|png|JPG|JPEG|PNG',
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
                
                return $file_upload;
            }
    }
    
    function _Upload_File($name, $folder) {
        $CI =& get_instance();
        
        //Upload image
        $CI->load->library('upload');
        
        $filename = 'Users_'.date("Y", time()) . date("m", time())  . date('d', time());
        
        $upload_path = FCPATH . 'uploads/'.$folder.'/';
        
        $filename = $filename;
    		$config = array(
    			'upload_path' 		=> 	$upload_path,
    			'allowed_types'	 	=> 'xlsx|xls|csv',
    			'file_name'         => $filename,
    			'is_image'			=>	false,
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
                
                return $file_upload;
            }
    }
    
    function _Upload_Transaction($name, $folder) {
        $CI =& get_instance();
        
        //Upload image
        $CI->load->library('upload');
        
        $filename = 'Transaction_'.date("Y", time()) . date("m", time())  . date('d', time());
        
        $upload_path = FCPATH . 'uploads/'.$folder.'/';
        
        $filename = $filename;
    		$config = array(
    			'upload_path' 		=> 	$upload_path,
    			'allowed_types'	 	=> 'xlsx|xls|csv',
    			'file_name'         => $filename,
    			'is_image'			=>	false,
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
                
                return $file_upload;
            }
    }
    
    function _Pagination($base_url, $total_row, $per_page, $current) {
        $CI=&get_instance();
        $config = array(
            'base_url' => $base_url,
            'total_rows' => $total_row,
            'per_page' => $per_page,
            'cur_page' => $current,
            'full_tag_open' => '<div class="wp-pagenavi">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<span class="current">',
            'cur_tag_close' => '</span>',
            //'first_tag_open' => '<a class="page larger">',
            //'first_tag_close' => '</a>',
            'first_link' => '<<',
            //'last_tag_open' => '<a class="page larger">',
            //'last_tag_close' => '</a>',
            'last_link' => '>>',
            //'next_tag_open' => '<a class="nextpostslink">',
            //'next_tag_close' => '</a>',
            'next_link' => '>',
           // 'prev_tag_open' => '<a class="prevpostslink">',
           // 'prev_tag_close' => '</a>',
            'prev_link' => '<',
           // 'num_tag_open' => '<a class="page larger">',
           // 'num_tag_close' => '</a>',
            'first_url' => $base_url.'',
            'use_page_numbers' => true
        );
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }
    
    function CKEditorReplace($object_name){
    	require_once('./public/ckeditor/ckeditor.php');
    	require_once('./public/ckfinder/ckfinder.php');
    	$CKE=new CKEditor(base_url('public/ckeditor/').'/');
    	$CKF=new CKFinder(base_url('public/ckfinder/').'/');
    	$CKF->SetupCKEditorObject($CKE);
        $config=array(
            'height' => 500
        );
    	$CKE->Replace($object_name, $config);
    }
    
    function CKEBasic($object_name){
    	require_once('./public/ckeditor/ckeditor.php');
    	require_once('./public/ckfinder/ckfinder.php');
    	$CKE=new CKEditor(base_url('public/ckeditor/').'/');
    	$CKF=new CKFinder(base_url('public/ckfinder/').'/');
    	$CKF->SetupCKEditorObject($CKE);
        $config=array(
            'height' => 400,
            'toolbar' => 'Basic'
        );
    	$CKE->Replace($object_name, $config);
    }
}