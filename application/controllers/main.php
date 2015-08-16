<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
	/**
	* Portada principal Shanoc Tracking
	*
	* @access public
	*/
	public function index()
	{
		// Verificar si estamos logueados
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		// Mandamos meses a la vista
		$months = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Dashboard');
		$this->template->set('months', $months);
		$this->template->add_js('view', 'main/script');
		$this->template->render('main/index', 'main');
	}

	/**
	* Mostrar grilla de pedidos por cliente vía ajax
	*
	* @access public
	* @param  $status 		int 		Status de la orden que queremos mostrar.
	* @param  $sort_id 		str 		Indicamos a través de que campo vamos a realizar el ordenamiento de los datos a mostrar
	* @param  $sort_order	str 		Indicamos si vamos a ordenar ascendete o descentemente
	* @param  $limit		int 		Indicamos el número de registros a mostrar por página
	* @param  $search		str 		Indicamos el parámetro de búsqueda. Por defecto se indica 'all', lo que indica que no realizaremos búsqueda
	* @param  $offset		int 		Indicamos a partir de que registro mostraremos los resultados
	* @return display 		vista
	*/
	public function displayAjax($status = 3, $sort_by = 'id', $sort_order = 'desc', $limit = 10, $search = 'all', $offset = 0)
	{
		$total = 0;
		$like = array();
		$this->load->model('Main_model');
		$this->load->helper('form');

		if ($search !== 'all') {
			$like = array('created_at' => urldecode($search));
		}

		$result = $this->Main_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $like);
		$orders = $result['data'];
		$total = $result['num_rows'];

		if (count($orders)) {
			$this->template->set('orders', $orders);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('main/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 8;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_status', $status);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->set('_offset', $offset);
		$this->template->set('_total', $total);
		$this->template->renderAjax('main/display');
	}

	public function view($id = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if ((int)$id <= 0) {
			show_error('¡Acceso restringido!');
		}


		$this->load->model('Main_model');
		$order = $this->Main_model->getOrder($id);
		$states = $this->Main_model->getStates();

		if ($order && $states) {
			$statesInfo = unserialize($order->meta_value);

			$now        = new DateTime('now');
			$activeDate = 0;
			foreach ($statesInfo as $key => $value) {
				$date = new DateTime($value[2] . ' 00:00:00');
				if ($date <= $now)
				{
					$activeDate = $key;
				}

				$guid = '';
				$name = '';
				foreach ($states as $key2 => $value2) {
					if ($key == $value2->id) {
						$guid = $value2->guid;
						$name = $value2->post_title;
						break;
					}
				}
				array_push($statesInfo[$key], $name);
				array_push($statesInfo[$key], $guid);
			}

			//var_dump($statesInfo); exit;

			$this->template->set('order', $order);
			$this->template->set('states', $states);
			$this->template->set('statesInfo', $statesInfo);
			$this->template->set('activeDate', $activeDate);

			$this->session->set_userdata('orderId', $id);
		}

		$this->template->set('_title', 'Ver pedido');
		$this->template->add_js('view', 'main/script');
		$this->template->render('main/view');
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
					if ($this->user->loginGeneral($this->input->post('login_email'), $this->input->post('login_pass'), 'sha256', 5) === TRUE) {
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
			redirect();
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
		redirect('http://ad-inspector.com/proyectos/web/shanoc');
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */