<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main/index';
$route['upload/uploadRealEstate'] = 'upload/uploadRealEstate';
$route['image/real_estate/(:any)'] = 'uploads/images/$1';
$route['error404'] = 'main/error404';
/* Location: ./application/config/routes.php */