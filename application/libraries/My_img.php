<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	class My_Img{
		var $obj;
		var $data;
		var $upload_data='';
		
		function My_Img(){
			$this->obj=&get_instance();
		}
		
		function watermark($sourcefile, $watermarkfile) {
			#
			# $sourcefile = Filename of the picture to be watermarked.
			# $watermarkfile = Filename of the 24-bit PNG watermark file.
			#
	 
			//Get the resource ids of the pictures
			$watermarkfile_id = imagecreatefrompng($watermarkfile);
	 
			imagealphablending($watermarkfile_id, false);
			imageSaveAlpha($watermarkfile_id, true);
	 
			$fileType = strtolower(substr($sourcefile, strlen($sourcefile)-3));
	 
			switch($fileType) {
				case('gif'):
					$sourcefile_id = imagecreatefromgif($sourcefile);
					break;
				case('png'):
					$sourcefile_id = imagecreatefrompng($sourcefile);
					break;
				default:
					$sourcefile_id = imagecreatefromjpeg($sourcefile);
			}
			//Get the sizes of both pix  
			$sourcefile_width=imagesx($sourcefile_id);
			$sourcefile_height=imagesy($sourcefile_id);
			$watermarkfile_width=imagesx($watermarkfile_id);
			$watermarkfile_height=imagesy($watermarkfile_id);
	 
			//$dest_x = ( $sourcefile_width / 2 ) - ( $watermarkfile_width / 2 );
			//$dest_y = ( $sourcefile_height / 2 ) - ( $watermarkfile_height / 2 );
	 
			//$dest_x = ( $sourcefile_width ) - ( $watermarkfile_width )-10;
			//$dest_y = ( $sourcefile_height ) - ( $watermarkfile_height)-10;
			$dest_x = 0;
			$dest_y = 0;
			// if a gif, we have to upsample it to a truecolor image
			if($fileType == 'gif') {
				// create an empty truecolor container
				$tempimage = imagecreatetruecolor($sourcefile_width, $sourcefile_height);
				   // copy the 8-bit gif into the truecolor image
				imagecopy($tempimage, $sourcefile_id, 0, 0, 0, 0, $sourcefile_width, $sourcefile_height);
				  // copy the source_id int
				$sourcefile_id = $tempimage;
			}
			imagecopy($sourcefile_id, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
			//Create a jpeg out of the modified picture
			switch($fileType) {    
				// remember we don't need gif any more, so we use only png or jpeg.
				// See the upsaple code immediately above to see how we handle gifs
				case('png'):
					imagepng($sourcefile_id,$sourcefile);
					//header("Content-type: image/png");
					//imagepng ($sourcefile_id);
					break;
				case('gif'):{
				 	imagegif($sourcefile_id,$sourcefile);
					break;
				}
				default:
				 	imagejpeg($sourcefile_id,$sourcefile,100);
					//header("Content-type: image/jpg");
					//imagejpeg ($sourcefile_id);
			}
			imagedestroy($sourcefile_id);
			imagedestroy($watermarkfile_id);
		}
		
		function ___Resize($nwidth, $nheight, $file_name, $tmp){
			$file=$tmp;
			$image_info = getimagesize($file);
			$imgtype = $image_info[2];
			ini_set('memory_limit', '-1');
			if( $imgtype == IMAGETYPE_JPEG ) {
				 $src= imagecreatefromjpeg($file);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 $src= imagecreatefromgif($file);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 $src= imagecreatefrompng($file);
			  }
			  list($width,$height)=getimagesize($file);
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
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($tmp, false);
			imagesavealpha($tmp,true);
			$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			$filename = $file_name;
			if( $imgtype == IMAGETYPE_JPEG ) {
				imagejpeg($tmp,$filename,100);
			} elseif( $imgtype == IMAGETYPE_GIF ) {
				imagegif($tmp,$filename);
			} elseif( $imgtype == IMAGETYPE_PNG ) {
				imagepng($tmp,$filename);
			}
			imagedestroy($src);
			imagedestroy($tmp);
			return;
		}
		
		function Do_Resize_White($nwidth, $nheight, $file_name, $tmp, $w = false){
			if($w==true){
				return $this->___Resize($nwidth, $nheight, $file_name, $tmp);
			}
			//return;
			//echo $file_name, ' - ', $tmp;
			//echo $nwidth, ' @@ ', $nheight, ' ; ';
			$file=$tmp;
			$image_info = getimagesize($file);
			$imgtype = $image_info[2];
			ini_set('memory_limit', '-1');
			if( $imgtype == IMAGETYPE_JPEG ) {
				 $src= imagecreatefromjpeg($file);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 $src= imagecreatefromgif($file);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 $src= imagecreatefrompng($file);
			  }
			  list($width,$height)=getimagesize($file);
			//Khỡi tạo kích cỡ
			$newwidth=$nwidth;
			
			$a=false;
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
				if(($width/$nwidth)>($height/$nheight)){
					$ratio = $width/$nwidth;
					$newheight = $height / $ratio;
					$newwidth=$nwidth;
				}
				else{
					$ratio = $height / $nheight;
					$newwidth = $width / $ratio;
					$newheight = $nheight;
				}
				$a=true;
			}
			//echo $newwidth, ' - ' , $newheight, ' ; ';
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($tmp, false);
			imagesavealpha($tmp,true);
			$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
			//Thay đổi kích cỡ ảnh gốc
			//echo $newwidth, ' - ',$newheight, ' - ',$width, ' - ',$height, ' ## ';
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			if($a==true){
				$dest_x=($nwidth-$newwidth)/2;
				$dest_y=($nheight-$newheight)/2;
				$sourcefile_id = imagecreatetruecolor($nwidth, $nheight);
				imagealphablending($sourcefile_id, false);
				imagesavealpha($sourcefile_id,true);
				$transparent = imagecolorallocatealpha($sourcefile_id, 255, 255, 255, 127);
				imagefilledrectangle($sourcefile_id, 0, 0, $nwidth, $nheight, $transparent);
				/*$almostblack = imagecolorallocate($sourcefile_id,254,254,254); 
			   	imagefill($sourcefile_id,0,0,$almostblack); 
			   	$black = imagecolorallocate($sourcefile_id,0,0,0); 
			   	imagecolortransparent($sourcefile_id,$almostblack); */
				imagecopy($sourcefile_id, $tmp, $dest_x, $dest_y, 0, 0, $newwidth, $newheight);
				$tmp=$sourcefile_id;
			}
			if($black==true){
				$bwimage= imagecreate($newwidth, $newheight); 

 				//Creates the 256 color palette
 				for ($c=0;$c<256;$c++){
 					$palette[$c] = imagecolorallocate($bwimage,$c,$c,$c);
 				}

				for ($y=0;$y<$height;$y++){
 					for ($x=0;$x<$width;$x++){
				 		$rgb = imagecolorat($src,$x,$y);
				 		$r = ($rgb >> 16) & 0xFF;
				 		$g = ($rgb >> 8) & 0xFF;
				 		$b = $rgb & 0xFF;

 						//This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette
 						$gs = $this->yiq($r,$g,$b);
 						imagesetpixel($bwimage,$x,$y,$palette[$gs]);
 					}
 				}
				$tmp = $bwimage;

    			//imagefilter($tmp, IMG_FILTER_NEGATE);
			}
			//Lưu ảnh đã sửa vào server
			$filename = $file_name;
			//imagejpeg($tmp,$filename,100);
			if( $imgtype == IMAGETYPE_JPEG ) {
				 imagejpeg($tmp,$filename,100);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 imagegif($tmp,$filename);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 imagepng($tmp,$filename);
			  }
			//kết thúc
			imagedestroy($src);
			imagedestroy($tmp);
			return;
			$file=$tmp;
			$image_info = getimagesize($file);
			$imgtype = $image_info[2];
			ini_set('memory_limit', '-1');
			if( $imgtype == IMAGETYPE_JPEG ) {
				 $src= imagecreatefromjpeg($file);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 $src= imagecreatefromgif($file);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 $src= imagecreatefrompng($file);
			  }
			  list($width,$height)=getimagesize($file);
			//Khá»¡i táº¡o kÃ­ch cá»¡
			$newwidth=$nwidth;
			$a=false;
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
				if(($width/$nwidth)>($height/$nheight)){
					$ratio = $width/$nwidth;
					$newheight = $height / $ratio;
					$newwidth=$nwidth;
				}
				else{
					$ratio = $height / $nheight;
					$newwidth = $width / $ratio;
					$newheight = $nheight;
				}
				$a=true;
			}
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($tmp, false);
			imagesavealpha($tmp,true);
			$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
			//Thay Ä‘á»•i kÃ­ch cá»¡ áº£nh gá»‘c
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			if($a==true){
				$dest_x=($nwidth-$newwidth)/2;
				$dest_y=($nheight-$newheight)/2;
				$sourcefile_id = imagecreatetruecolor($nwidth, $nheight);
				imagealphablending($sourcefile_id, false);
				imagesavealpha($sourcefile_id,true);
				$transparent = imagecolorallocatealpha($sourcefile_id, 255, 255, 255, 127);
				imagefilledrectangle($sourcefile_id, 0, 0, $nwidth, $nheight, $transparent);
				/*$almostblack = imagecolorallocate($sourcefile_id,254,254,254); 
			   	imagefill($sourcefile_id,0,0,$almostblack); 
			   	$black = imagecolorallocate($sourcefile_id,0,0,0); 
			   	imagecolortransparent($sourcefile_id,$almostblack); */
				imagecopy($sourcefile_id, $tmp, $dest_x, $dest_y, 0, 0, $newwidth, $newheight);
				$tmp=$sourcefile_id;
			}
			//LÆ°u áº£nh Ä‘Ã£ sá»­a vÃ o server
			$filename = $file_name;
			//imagejpeg($tmp,$filename,100);
			if( $imgtype == IMAGETYPE_JPEG ) {
				 imagejpeg($tmp,$filename,100);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 imagegif($tmp,$filename);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 imagepng($tmp,$filename);
			  }
			//káº¿t thÃºc
			imagedestroy($src);
			imagedestroy($tmp);
		}
		

		function DoResize($nwidth, $nheight, $file_name, $tmp, $black = false){
			//echo $nwidth, ' @@ ', $nheight, ' ; ';
			$file=$tmp;
			$image_info = getimagesize($file);
			$imgtype = $image_info[2];
			ini_set('memory_limit', '-1');
			if( $imgtype == IMAGETYPE_JPEG ) {
				 $src= imagecreatefromjpeg($file);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 $src= imagecreatefromgif($file);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 $src= imagecreatefrompng($file);
			  }
			  list($width,$height)=getimagesize($file);
			//Khỡi tạo kích cỡ
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
			//echo $newwidth, ' - ' , $newheight, ' ; ';
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($tmp, false);
			imagesavealpha($tmp,true);
			$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
			//Thay đổi kích cỡ ảnh gốc
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			if($black==true){
				$bwimage= imagecreate($width, $height); 

 				//Creates the 256 color palette
 				for ($c=0;$c<256;$c++){
 					$palette[$c] = imagecolorallocate($bwimage,$c,$c,$c);
 				}

				for ($y=0;$y<$height;$y++){
 					for ($x=0;$x<$width;$x++){
				 		$rgb = imagecolorat($src,$x,$y);
				 		$r = ($rgb >> 16) & 0xFF;
				 		$g = ($rgb >> 8) & 0xFF;
				 		$b = $rgb & 0xFF;

 						//This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette
 						$gs = $this->yiq($r,$g,$b);
 						imagesetpixel($bwimage,$x,$y,$palette[$gs]);
 					}
 				}
				$tmp = $bwimage;

    			//imagefilter($tmp, IMG_FILTER_NEGATE);
			}
			//Lưu ảnh đã sửa vào server
			$filename = $file_name;
			//imagejpeg($tmp,$filename,100);
			if( $imgtype == IMAGETYPE_JPEG ) {
				 imagejpeg($tmp,$filename,100);
			  } elseif( $imgtype == IMAGETYPE_GIF ) {
				 imagegif($tmp,$filename);
			  } elseif( $imgtype == IMAGETYPE_PNG ) {
				 imagepng($tmp,$filename);
			  }
			//kết thúc
			imagedestroy($src);
			imagedestroy($tmp);
		}
 		//Creates yiq function
	 	function yiq($r,$g,$b) {
			return (($r*0.299)+($g*0.587)+($b*0.114));
	 	} 
		
		function _Get_Upload_Data(){
			return $this->upload_data;
		}
		
		function Upload($url_dir='public/uploads/images/', $upload_name='user_file'){
			$config['upload_path'] 		= 	$url_dir;
			$config['allowed_types'] 	= 	'gif|jpg|png';
			$config['is_image']			=	true;
			$config['max_size']			= 	8*1024;
			$config['max_width']  		= 	1024*100;
			$config['max_height']  		= 	1024*100;
			$config['max_filename']		=	250;
			$config['encrypt_name']		=	false;
			$config['overwrite'] = false;
			$this->obj->load->library('upload', $config);
			$this->obj->upload->initialize($config);
			if (!$this->obj->upload->do_upload($upload_name)){
				$data['log']=$this->obj->upload->display_errors();
				$this->upload_data='';
				$data['error']=false;
				return $data;
			}else{
				$data['upload_data']=$this->obj->upload->data();
				$this->upload_data=$data['upload_data'];
				$data['error']=true;
				return $data;
			}
		}
	}
?>