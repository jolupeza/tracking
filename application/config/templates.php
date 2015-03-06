<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['templates']['front']['default'] = array(
	'regions'		=>	array('header' => array(), 'main_menu' => array(), 'sidebar' => array(), 'footer' => array()),
	'scripts'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/moment/moment.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-filestyle.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/jquery/jquery.easing.min'),
		array('type' => 'base', 'value' => 'libraries/jquery/jquery.nicescroll.min'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.concat.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'template', 'value' => 'script'),
	),
	'styles'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'template', 'value' => 'css/style'),
	)
);

$config['templates']['extranet']['default'] = array(
	'regions'		=>	array('header' => array(), 'main_menu' => array(), 'sidebar' => array(), 'footer' => array()),
	'scripts'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/moment/moment.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox.pack'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-switch.min'),
		array('type' => 'template', 'value' => 'script'),
	),
	'styles'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-switch.min'),
		array('type' => 'template', 'value' => 'css/style'),
	)
);

$config['templates']['admin']['default'] = array(
	'regions'		=>	array('header' => array(), 'main_menu' => array(), 'sidebar' => array(), 'footer' => array()),
	'scripts'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/moment/moment.min'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.concat.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/bootstrap.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-switch.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-filestyle.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'libraries/tinymce/tinymce.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox.pack'),
		array('type' => 'template', 'value' => 'script'),
	),
	'styles'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap.min'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.min'),
		array('type' => 'base', 'value' => 'libraries/formValidation/formValidation.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-switch.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox'),
		array('type' => 'template', 'value' => 'style'),
	)
);

/* End of file templates.php */
/* Location: ./application/config/templates.php */