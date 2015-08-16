<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if (!function_exists('slug')) {
		function slug($string) {
			$characters = array(
				"Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u",
				"á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
				"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
			);

			$string = strtr($string, $characters);
			$string = strtolower(trim($string));
			$string = preg_replace("/[^a-z0-9-]/", "-", $string);
			$string = preg_replace("/-+/", "-", $string);

			if(substr($string, strlen($string) - 1, strlen($string)) === "-") {
				$string = substr($string, 0, strlen($string) - 1);
			}
			return $string;
		}
	}

	// Función para crear minuaturas o redimesiones de imágenes
	if (!function_exists('create_thumbnail')) {
		function create_thumbnail($filename = '', $width, $height) {
			if (!empty($filename)) {
				$CI =& get_instance();

				$config['image_library'] = 'gd2';
		        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
		        $config['source_image'] = 'ad-content/uploads/'.$filename;
		        $config['create_thumb'] = TRUE;
		        $crop = ($CI->config->item('cms_thumbnail_crop') == 0) ? TRUE : FALSE;
		        $config['maintain_ratio'] = $crop;
		        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
		        $config['new_image']='ad-content/thumbs/';
		        $config['thumb_marker'] = '-' . $width . 'x' . $height;
		        $config['width'] = $width;
		        $config['height'] = $height;
		        $CI->image_lib->initialize($config);
		        $CI->image_lib->resize();
			}
		}
	}

	// Helper para eliminar carpeta que tenga contenido
	if (!function_exists('rrmdir')) {
		function rrmdir($dir) {
	   		if (is_dir($dir)) {
	     		$objects = scandir($dir);

	     		foreach ($objects as $object) {
	       			if ($object != "." && $object != "..") {
	         			if (filetype($dir . "/" . $object) == "dir") {
	         				rrmdir($dir . "/" . $object);
	         			} else {
	         				unlink($dir . "/" . $object);
	         			}
	       			}
	     		}

	     		reset($objects);
	     		rmdir($dir);
	   		}
		}
	}

	// Helper para generar código aleatorio
	if (!function_exists('randomString')) {
		function randomString($length = 3)
		{
			$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$max = strlen($base) - 1;
			$code = '';

			while (strlen($code) < $length) {
				$code .= $base{mt_rand(0, $max)};
			}

			return $code;
		}
	}

	if (!function_exists('randomNumber')) {
		function randomNumber($length = 11)
		{
			$base = '0123456789';
			$max = strlen($base) - 1;
			$code = '';

			while (strlen($code) < $length) {
				$code .= $base{mt_rand(0, $max)};
			}

			return $code;
		}
	}

	if (!function_exists('format_date')) {
		function format_date($date) {
			$nameDay = array('Domingo', 'Lunes', 'Marte', 'Miercoles', 'Jueves', 'Viernes', 'Sábado');
			$nameMonth = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');

			$date = ($date != '' && !empty($date)) ? strtotime($date) : date('Y-m-d H:i:s');

			$day = $nameDay[date('w', $date)];
			$month = $nameMonth[date('n', $date) - 1];
			return 'Lima, ' . date('d', $date) . ' de ' . $month . ' del ' . date('Y', $date);
		}
	}

	if (!function_exists('elapsed_time')) {
		function elapsed_time($seconds) {
			$minutes = $seconds / 60;
			$hours = floor($minutes / 60);
			$days = $hours % 24;
			$minutes2 = $minutes % 60;
			$seconds_2 = $seconds % 60 % 60 % 60;
			if ($minutes2 < 10) $minutes2 = '0' . $minutes2;
			if ($seconds_2 < 10) $seconds_2 = '0' . $seconds_2;

			if ($seconds < 60) { /* seconds */
				$resultado = round($seconds) . ' segundos';
			} elseif ($seconds > 60 && $seconds < 3600) {/* Minutos  */
				$resultado = $minutes2 . ' minutos';
				//$resultado = $minutes2 . ' minutos' . $seconds_2 . ' segundos ';
			} elseif ($minutes > 60 && $minutes < 3600) {/* horas */
				$resultado = $hours . ' horas ' . $minutes2 . ' minutos ';
				// $resultado = $hours . ' horas ' . $minutes2 . ' minutos ' . $seconds_2 . ' segundos';
			} else {
				$resultado = $days . ' días ' . $hours . ' horas ' . $minutes2 . ' minutos ';
				//$resultado = $days . ' días ' . $hours . ' horas ' . $minutes2 . ' minutos ' . $seconds_2 . ' segundos';
			}

			return $resultado;
		}
	}

	if (!function_exists('convert_excel')) {
		function convert_excel($title, $tags, $sql, $filename = 'exceloutput') {
			$headers = ''; // just creating the var for field headers to append to below
		    $data = ''; // just creating the var for field data to append to below

		    $CI =& get_instance();

		    $query = $sql;
		    $fields = $tags;

		    if (count($query) == 0) {
		        echo '<p>The table appears to have no data.</p>';
		    } else {
		    	if (!empty($title)) {
		    		$headers = $title . "\n\n";
		    	}

		        foreach ($fields as $field) {
		        	$headers .= $field . "\t";
		    	}

		    	foreach ($query as $row) {
		    		$line = '';
		    		foreach ($row as $item) {
		    			if ((!isset($item->recipient)) OR $item->recipient == "") {
			        		$value = "\n";
			        	} else {
			        		$value = str_replace('"', '""', $item->recipient);
			                $value = '"' . $item->recipient . '"' . "\n";
			        	}

			        	$line .= $value;
		    		}

		    		$data .= trim($line)."\n";
		    	}

		    	$data = str_replace("\r","",$data);

		    	header("Content-type: application/x-msdownload");
		    	header("Content-Disposition: attachment;filename=$filename.xls");
		    	echo mb_convert_encoding("$headers\n$data",'utf-16','utf-8');
		    }
		}
	}

	// Helper que permite verificar si slug ya existe, si existe agregará un '-' seguido de un número correlativo
	if (!function_exists('verify_slug')) {
		function verify_slug($data, $slug) {
			$last_slug = 0;
			foreach ($data as $slg) {
				$num = explode('-', $slg->slug);
				$num = $num[count($num) - 1];
				$last_slug = ((int)$num > $last_slug) ? $num : $last_slug;
			}
			$slug .= '-' . ($last_slug + 1);

			return $slug;
		}
	}

	if (!function_exists('verify_create_thumb')) {
		function verify_create_thumb($name, $dir) {
			$CI =& get_instance();
			$CI->load->model('Configuration_model');

			$crop = $CI->Configuration_model->getRow('thumbnail_crop')->option_value;
			$sizeW = $CI->Configuration_model->getRow('thumbnail_size_w')->option_value;
			$sizeH = $CI->Configuration_model->getRow('thumbnail_size_h')->option_value;
			if ($sizeW > 0 OR $sizeH > 0) {
        		if (!in_array($name . '-' . $sizeW . 'x' . $sizeH, $dir)) {
        			create_thumbnail($name, $sizeW, $sizeH, $crop);
        		}
        	}

        	$sizeW = $CI->Configuration_model->getRow('medium_size_w')->option_value;
			$sizeH = $CI->Configuration_model->getRow('medium_size_h')->option_value;
        	if ($sizeW > 0 OR $sizeH > 0) {
        		if (!in_array($name . '-' . $sizeW . 'x' . $sizeH, $dir)) {
        			create_thumbnail($name, $sizeW, $sizeH, $crop);
        		}
        	}

        	$sizeW = $CI->Configuration_model->getRow('large_size_w')->option_value;
			$sizeH = $CI->Configuration_model->getRow('large_size_h')->option_value;
        	if ($sizeW > 0 OR $sizeH > 0) {
        		if (!in_array($name . '-' . $sizeW . 'x' . $sizeH, $dir)) {
        			create_thumbnail($name, $sizeW, $sizeH, $crop);
        		}
        	}
		}
	}

	if(!function_exists('send_email')) {
		function send_email($email, $subject, $message) {
			$CI =& get_instance();
			$CI->email->from('no-reply@ad-inspector.com', 'Shanoc');
			$CI->email->to($email);
			$CI->email->subject($subject);
			$CI->email->message($message);

			if ($CI->email->send()) {
				return TRUE;
			}

			return FALSE;
		}
	}

	if(!function_exists('body_email'))
	{
		function body_email($name, $body)
		{
			$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
				.'<html xmlns="http://www.w3.org/1999/xhtml">'
				.'<head>'
  				.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'
  				.'<title>A Simple Responsive HTML Email</title>'
  				.'<style type="text/css">'
  				.'body {margin: 0; padding: 0; min-width: 100%!important;}'
				.'img {height: auto; margin: 0 auto;}'
				.'.content {width: 100%; max-width: 600px;}'
				.'.header {padding: 40px 30px 20px 30px;}'
				.'.innerpadding {padding: 30px 30px 30px 30px;}'
				.'.innermargin {margin: 20px 0 0 0;}'
				.'.bg {background-color: #f7f7f7;}'
				.'.borderbottom {border-bottom: 1px solid #f2eeed;}'
				.'.subhead {font-size: 14px; color: #484848; font-family: Arial, sans-serif; text-align: left;}'
				.'.h1, .h2, .bodycopy {color: #484848; font-family: Arial, sans-serif;}'
				.'.h1 {font-size: 33px; line-height: 38px; font-weight: bold;}'
				.'.h2 {padding: 15px 10px 15px 10px; font-size: 18px; line-height: 20px;}'
				.'.hight {font-size: 18px}'
				.'.bodycopy {font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;}'
				.'.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}'
				.'.button a {color: #ffffff; text-decoration: none;}'
				.'.footer {padding: 20px 30px 15px 30px;}'
				.'.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}'
				.'.footercopy a {color: #ffffff; text-decoration: underline;}'
				.'@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {'
    			.'body[yahoo] .hide {display: none!important;}'
    			.'body[yahoo] .buttonwrapper {background-color: transparent!important;}'
    			.'body[yahoo] .button {padding: 0px!important;}'
    			.'body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}'
    			.'body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}'
    			.'body[yahoo] .bodycopy { padding: 20px 10px 20px 10px !important;}'
  				.'}'
				.'@media only screen and (min-device-width: 601px) {'
    			.'.content {width: 600px !important;}'
    			.'.col330 {width: 330px !important;}'
    			.'.col380 {width: 380px !important;}'
  				.'}'
  				.'</style>'
				.'</head>'
				.'<body yahoo bgcolor="#ebeef0" style="margin: 0; padding: 0; min-width: 100%!important;">'
  				.'<table width="100%" bgcolor="#ebeef0" border="0" cellpadding="0" cellspacing="0">'
  				.'<tr>'
    			.'<td>'
      			.'<!--[if (gte mso 9)|(IE)]>'
        		.'<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">'
          		.'<tr>'
            	.'<td>'
      			.'<![endif]-->'
      			.'<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">'
        		.'<tr>'
          		.'<td>'
            	.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
              	.'<tr>'
                .'<td class="h2" style="color: #153643; font-family: sans-serif; padding: 15px 10px 15px 10px; font-size: 24px; line-height: 20px; text-align: center;" bgcolor="#ebeef0">'
                .'<img src="https://ad-inspector.com/proyectos/web/tracking/assets/images/shanoc-logo.png" margin: 0 auto; />'
                .'</td>'
              	.'</tr>'
            	.'</table>'
          		.'</td>'
        		.'</tr>'
        		.'<tr>'
          		.'<td>'
            	.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
              	.'<tr>'
                .'<td class="h2 bg" style="color: #484848; font-family: Arial, sans-serif; padding: 15px 10px 15px 10px; font-size: 18px; line-height: 28px; background-color: #f7f7f7;" bgcolor="#f7f7f7">'
                .'hola, <span class="hight" style="font-size: 18px">' . $name . '</span>'
                .'</td>'
              	.'</tr>'
                .$body
            	.'</table>'
          		.'</td>'
              	.'</tr>'
            	.'</table>'
          		.'</td>'
        		.'</tr>'
				.'</table>'
      			.'<!--[if (gte mso 9)|(IE)]>'
            	.'</td>'
          		.'</tr>'
      			.'</table>'
      			.'<![endif]-->'
      			.'</td>'
    			.'</tr>'
  				.'</table>'
				.'</body>'
				.'</html>';

			return $html;
		}
	}

/* End of file functions_helper.php */
/* Location: ./application/helpers/functions_helper.php */ ?>