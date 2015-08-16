<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Main extends MY_Controller
	{
		/**
		* Dashboard
		*
		* @access public
		*/
		public function index()
		{
			/*$this->load->model('Orders_model');
			//$this->load->library('encrypt');
			//echo $this->encrypt->password('1234', 'sha256'); exit;
			for ($i = 1; $i < 26; $i++) {
				$data = array(
					'post_title'	=>	'Pedido-' . $i,
					'post_content'	=>	'Es una descripción del pedido ' . $i,
					'post_status'	=>	'initiated',
					'post_excerpt'	=>	'Lima',
					'post_author'	=>	4,
					'post_type'		=>	'orders',
					'created'		=>	$this->user->id,
					'created_at'	=>	date('Y-m-d H:i:s'),
					'published_at'	=>	'2015-03-30 23:59:59'
				);
				$last_id = $this->Orders_model->add(NULL, $data);
			}

			echo 'bien se agregraron';
			exit;*/

			if (!$this->user->is_logged_in()) {
				redirect();
			}

			if (!$this->user->has_permission('view_orders')) {
				show_error('¡Acceso restringido!');
			}

			$this->template->set('_token', $this->user->token());
			$this->template->set('_title', $this->lang->line('cms_general_title_dashboard'));
			$this->template->add_js('view', 'extranet/main/script');
			$this->template->render('main/index', 'orders');
		}

		/**
		* Mostrar grilla de pedidos vía ajax
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
			$this->load->model('Orders_model');
			$this->load->helper('form');

			if ($search !== 'all') {
				$like = array('post_title' => urldecode($search));
			}

			$result = $this->Orders_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $like);
			$orders = $result['data'];
			$total = $result['num_rows'];

			if (count($orders)) {
				$this->template->set('_orders', $orders);

				if ($total > $limit) {
					$this->load->library('pagination');
					$config = array();
					$config['base_url'] = site_url('extranet/main/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
					$config['total_rows'] = $total;
					$config['per_page'] = $limit;
					$config['uri_segment'] = 9;

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

		/**
		* Actualizar estado de Pedido
		*
		* @access public
		* @param  $id 			int 		Id del pedido
		* @param  $field 		str 		Indicamos el campo que queremos actualizar
		* @param  $value 		str 		Indicamos el valor del campo que queremos actualizar
		* @return 				bol 		Si se produce el cambio se devuelve TRUE, caso contrario devolvemos FALSE
		*/
		public function action()
		{
			$id = $this->input->post('id');
			$field = $this->input->post('field');
			$value = $this->input->post('value');

			if ((int)$id > 0 && !empty($field) && !empty($value)) {
				$this->load->model('Orders_model');
				$data = array(
					$field				=>	$value,
					'modified'			=>	$this->user->id,
					'modified_at'		=>	date('Y-m-d H:i:s')
				);

				if ($this->Orders_model->edit(NULL, array('id' => $id), $data)) {
					echo TRUE;
				} else {
					echo FALSE;
				}
			} else {
				echo FALSE;
			}

			exit;
		}

		/**
		* Eliminar pedido
		*
		* @access public
		* @param  $id 			int 		Id del pedido.
		* @return 				bol 		Si eliminamos correctamente devolvemos TRUE. Caso contrario devolvemos FALSE.
		*/
		public function delete()
		{
			$id = $this->input->post('id');

			if ((int)$id > 0) {
				$this->load->model('Orders_model');

				if ($this->Orders_model->delete('posts', array('id' => $id))) {
					echo TRUE;
				} else {
					echo FALSe;
				}
			} else {
				echo FALSE;
			}

			exit;
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
						if ($this->user->loginGeneral($this->input->post('login_email'), $this->input->post('login_pass'), 'sha256', 2) === TRUE) {
							// Editamos el campo last_login para indicar cual ha sido su último logueo.
							$this->load->model('admin/Users_model');
							$this->session->set_userdata('user_id', $this->user->id);
							$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
							redirect('extranet/main');
						}
					}

					$this->template->add_message(array('error' => $this->user->errors()));
				}
			} else {
				redirect('extranet/main');
			}

			$this->load->helper('form');
			$this->template->set('_token', $this->user->token());
			$this->template->set('_title', 'Portada');
			$this->template->add_js('view', 'extranet/main/script');
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
			redirect('extranet');
		}

	}

	/* End of file main.php */
	/* Location: ./application/modules/extranet/controllers/main.php */