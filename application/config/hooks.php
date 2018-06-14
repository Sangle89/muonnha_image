<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
/*$hook['pre_system'][] = array(
	'class' => '',
	'function' => 'check_ssl',
	'filename' => 'ssl.php',
	'filepath' => 'hooks'
);
$hook['display_override'] = array(
    'class' => '',
    'function' => 'compress',
    'filename' => 'compress.php',
    'filepath' => 'hooks'
);*/
$hook['pre_system'][] = array(
	'class' => '',
	'function' => 'check_mobile',
	'filename' => 'detect.php',
	'filepath' => 'hooks'
);