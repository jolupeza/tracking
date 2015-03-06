<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loading
{
	protected $CI;

	public function setConfiguration()
	{
		$this->CI =& get_instance();

		$this->CI->load->model('Configuration_model');
		$this->CI->config->set_item('cms_site_name', $this->CI->Configuration_model->getRow('blogname')->option_value);
		$this->CI->config->set_item('cms_site_desc', $this->CI->Configuration_model->getRow('blogdescription')->option_value);
		$this->CI->config->set_item('cms_thumbnail_size_w', $this->CI->Configuration_model->getRow('thumbnail_size_w')->option_value);
		$this->CI->config->set_item('cms_thumbnail_size_h', $this->CI->Configuration_model->getRow('thumbnail_size_h')->option_value);
		$this->CI->config->set_item('cms_medium_size_w', $this->CI->Configuration_model->getRow('medium_size_w')->option_value);
		$this->CI->config->set_item('cms_medium_size_h', $this->CI->Configuration_model->getRow('medium_size_h')->option_value);
		$this->CI->config->set_item('cms_large_size_w', $this->CI->Configuration_model->getRow('large_size_w')->option_value);
		$this->CI->config->set_item('cms_large_size_h', $this->CI->Configuration_model->getRow('large_size_h')->option_value);
		$this->CI->config->set_item('cms_thumbnail_crop', $this->CI->Configuration_model->getRow('thumbnail_crop')->option_value);
	}

}

/* End of file Loading.php */
/* Location: ./application/hooks/Loading.php */