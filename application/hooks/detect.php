<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function check_mobile()
{
    require_once APPPATH . "/third_party/mobile-detect/Mobile_Detect.php";
	$detect = new Mobile_Detect();
	if($detect->isMobile()) {
		define("USERTYPE", "Mobile");	
	} else {
		define("USERTYPE", "PC");	
	}
}

/* End of file detect.php */
/* Location: ./system/application/hooks/detect.php */