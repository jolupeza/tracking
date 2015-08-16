<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_system'][] = array(
	'class'			=>	'Loading',
	'function'		=>	'setConfiguration',
	'filename'		=>	'Loading.php',
	'filepath'		=>	'hooks'
);

$hook['post_system'][] = array(
	'class'			=>	'Loading',
	'function'		=>	'verifyOrders',
	'filename'		=>	'Loading.php',
	'filepath'		=>	'hooks'
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */