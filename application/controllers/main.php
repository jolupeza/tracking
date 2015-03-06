<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
	/**
	* Portada principal For - Get
	*
	* @access public
	*/
	public function index()
	{
		// Verificar si estamos logueados

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Portada');
		$this->template->add_js('view', 'main/script');
		$this->template->render('main/index');
	}

	/**
	* Método que realiza el login del usuario.
	*
	* @access public
	* @param  $login_email 		Str 	Email del usuario
	* @param  $login_password 	Str 	Password del usuario
	*/
	public function login()
	{
		if (!$this->user->is_logged_in()) {
			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');
				$rules = array(
					array(
						'field'	=>	'login_email',
						'label'	=>	'lang:cms_general_label_email',
						'rules'	=>	'trim|required|valid_email'
					),
					array(
						'field'	=>	'login_pass',
						'label'	=>	'lang:cms_general_label_password',
						'rules'	=>	'required|min_length[6]|max_length[30]'
					),
				);

				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() === TRUE) {
					if ($this->user->loginGeneral($this->input->post('login_email'), $this->input->post('login_pass')) === TRUE) {
						// Editamos el campo last_login para indicar cual ha sido su último logueo.
						$this->load->model('admin/Users_model');
						$this->session->set_userdata('user_id', $this->user->id);
						$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
						redirect('main');
					}
				}

				$this->template->add_message(array('error' => $this->user->errors()));
			}
		} else {
			redirect('main');
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Portada');
		$this->template->add_js('view', 'main/script');
		$this->template->render('main/login');
	}

	/**
	* Deslogueo de usuario
	*
	* Realizar deslogueo de usuario, para lo cual eliminamos las variables de sesión existentes. Verificamos que estemos logueado y redirigimos a la portada.
	*
	* @access public
	*/
	public function logout()
	{
		if ($this->user->is_logged_in()) {
			$this->session->sess_destroy();
		}
		redirect();
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */