<?php
class Image_model extends CI_Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/

	public function resize($filename, $width, $height, $type = "images") {
		if (!file_exists(FCPATH . '/uploads/' . $type . '/' . $filename) || !is_file(FCPATH . '/uploads/' . $type . '/' . $filename)) {
			return;
		} 
        $this->load->library('image_moo');
        //L?y thông tin hình ?nh trong thu m?c uploads/type
		$info = pathinfo(FCPATH . '/uploads/' . $type . '/' . $filename);
        //L?y d?nh d?ng file
		$extension = $info['extension'];
        
		$old_image = $filename;
        
		$new_image = 'uploads/' . $type . '/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height .'.' . $extension;
		
        if (!file_exists(FCPATH . '/' . $new_image) || (filemtime(FCPATH . '/uploads/' . $type . '/' . $old_image) > filemtime(FCPATH . '/'  . $new_image))) {
			//$path = '';
			
            //$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
            /*foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists(FCPATH . '/uploads/' . $type . '/' . $path)) {
					@mkdir(FCPATH . '/uploads/' . $type . '/' . $path, 0777);
				}		
			}*/
            
            $this->image_moo->load(FCPATH . '/uploads/' . $type . '/'. $old_image)
            //->set_background_colour('#ffffff')
            ->resize_crop($width, $height, TRUE)
            ->save(FCPATH . '/' . $new_image, TRUE);
		}
		return  $new_image;
	}
}
?>