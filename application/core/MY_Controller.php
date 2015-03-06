<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->config->item('cms_admin_panel_uri')) {
			show_error('Configuration error.');
		}

		$this->_setLanguage();
		$this->lang->load(array('cms_general', 'error'));
		$this->load->library(array('template', 'user'));
	}

	public function adminPanel()
	{
		if ($this->uri->segment(1) == $this->config->item('cms_admin_panel_uri')) {
			return strtolower($this->config->item('cms_admin_panel_uri'));
		} else if ($this->uri->segment(1) == $this->config->item('cms_extranet_panel_uri')) {
			return strtolower($this->config->item('cms_extranet_panel_uri'));
		}
		//return strtolower($this->uri->segment(1)) == $this->config->item('cms_admin_panel_uri');
	}

	private function _setLanguage()
	{
		$lang = $this->session->userdata('global_lang');

		if ($lang && in_array($lang, $this->config->item('cms_languages'))) {
			$this->config->set_item('language', $lang);
		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */