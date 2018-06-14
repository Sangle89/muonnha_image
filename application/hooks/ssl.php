<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function check_ssl()
{
	if(!$_SERVER["HTTPS"] || $_SERVER["HTTPS"]=='off') {
        header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        exit;   
    }
}

/* End of file detect.php */
/* Location: ./system/application/hooks/detect.php */