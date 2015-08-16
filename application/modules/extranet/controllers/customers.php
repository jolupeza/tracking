<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends MY_Controller
{
	/**
	* Dashboard Clientes
	*
	* @access public
	*/
	public function index()
	{
		/*$this->load->model('Customers_model');
		$this->load->library('encrypt');
		//echo $this->encrypt->password('1234', 'sha256'); exit;
		for ($i=0; $i < 25; $i++) {
			$data = array(
				'name'			=>	'Testing-' . $i,
				'email'			=>	'testing' . $i . '@testing.com',
				'password'		=>	$this->encrypt->password('ABcd1234', 'sha256'),
				'role'			=>	5,
				'status'		=>	1,
				'active'		=>	1,
				'created'		=>	$this->user->id,
				'created_at'	=>	date('Y-m-d H:i:s')
			);
			$last_id = $this->Customers_model->add(NULL, $data);

			if ((int)$last_id > 0) {
				// Agregamos metadatos a la tabla postmeta
				$serie = array(
					'contact'			=>	'Contact-' . $i
				);

				$data = array(
					'user_id'		=>	$last_id,
					'meta_key'		=>	'mb_customer_data',
					'meta_value'	=>	serialize($serie),
					'created'		=>	$this->user->id,
					'created_at'	=>	date('Y-m-d H:i:s')
				);
				$this->Customers_model->add('usermeta', $data);
			}
		}*/

		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if (!$this->user->has_permission('view_customers')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', $this->lang->line('cms_general_title_customers'));
		$this->template->add_js('view', 'extranet/customers/script.min');
		$this->template->render('customers/index', 'customers');
	}

	/**
	* Mostrar grilla de clientes vía ajax
	*
	* @access public
	* @param  $status 		int 		Status de clientes que queremos mostrar. Por defecto mostramos los clientes activos
	* @param  $sort_id 		str 		Indicamos a través de que campo vamos a realizar el ordenamiento de los datos a mostrar
	* @param  $sort_order	str 		Indicamos si vamos a ordenar ascendete o descentemente
	* @param  $limit		int 		Indicamos el número de registros a mostrar por página
	* @param  $search		str 		Indicamos el parámetro de búsqueda. Por defecto se indica 'all', lo que indica que no realizaremos búsqueda
	* @param  $offset		int 		Indicamos a partir de que registro mostraremos los resultados
	* @return display 		vista
	*/
	public function displayAjax($status = 1, $sort_by = 'id', $sort_order = 'asc', $limit = 10, $search = 'all', $offset = 0)
	{
		$total = 0;
		$like = array();
		$this->load->model('Customers_model');
		$this->load->helper('form');

		if ($search !== 'all') {
			$like = array('name' => urldecode($search), 'email' => urldecode($search));
		}

		$result = $this->Customers_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $like);
		$customers = $result['data'];
		$total = $result['num_rows'];

		if (count($customers)) {
			$this->template->set('_customers', $customers);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('extranet/customers/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
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
		$this->template->renderAjax('customers/display');
	}

	/**
	* Agregar cliente
	*
	* @access public
	* @param  $name 		str 		Nombre del cliente
	* @param  $email 		str 		Email del cliente
	* @param  $contact 		str 		Nombre de contacto del cliente
	* @return 				bol 		Si podemos agregar al usuario correctamene devolvemos TRUE, caso contrario devolvemos FALSE. El nombre del contacto se almacena como metadato en tabla usermeta.
	*/
	public function add()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$contact = $this->input->post('contact');
		$ruc = $this->input->post('ruc');
		$avatar = $this->input->post('avatar');

		if ($this->user->has_permission('add_customers')) {
			if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($contact)) {
				$this->load->model('Customers_model');
				$this->load->library(array('encrypt', 'email', 'image_lib'));
				$this->load->helper(array('functions', 'directory'));
				$pass = randomString(6);

				//Validar que ruc ingresado no se encuentre registrado
				$verifyRuc = $this->Customers_model->getRow(NULL, 'id', array('user' => $ruc));
				if (!$verifyRuc) {
					$data = array(
						'name'			=>	$name,
						'email'			=>	$email,
						'user'			=>	$ruc,
						'password'		=>	$this->encrypt->password($pass, 'sha256'),
						'role'			=>	5,
						'status'		=>	1,
						'active'		=>	1,
						'avatar'		=>	$avatar,
						'created'		=>	$this->user->id,
						'created_at'	=>	date('Y-m-d H:i:s')
					);
					$last_id = $this->Customers_model->add(NULL, $data);

					if ((int)$last_id > 0) {
						if (!empty($avatar)) {
							$avatarArr = explode('/', $avatar);
							$nameAvatar = $avatarArr[count($avatarArr) - 1];

							$dirThumb = directory_map('./ad-content/thumbs/');
							if ($this->config->item('cms_thumbnail_size_w') > 0 || $this->config->item('cms_thumbnail_size_h') > 0) {
								if(!in_array($nameAvatar . '-' . $this->config->item('cms_thumbnail_size_w') . 'x' . $this->config->item('cms_thumbnail_size_h'), $dirThumb)) {
									create_thumbnail($nameAvatar, $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'));
								}
							}

							if ($this->config->item('cms_medium_size_w') > 0 || $this->config->item('cms_medium_size_h') > 0) {
								if(!in_array($nameAvatar . '-' . $this->config->item('cms_medium_size_w') . 'x' . $this->config->item('cms_medium_size_h'), $dirThumb)) {
									create_thumbnail($nameAvatar, $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'));
								}
							}

							if ($this->config->item('cms_large_size_w') > 0 || $this->config->item('cms_large_size_h') > 0) {
								if(!in_array($nameAvatar . '-' . $this->config->item('cms_large_size_w') . 'x' . $this->config->item('cms_large_size_h'), $dirThumb)) {
									create_thumbnail($nameAvatar, $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'));
								}
							}
						}

						// Agregamos metadatos a la tabla postmeta
						$serie = array(
							'contact'			=>	$contact
						);

						$data = array(
							'user_id'		=>	$last_id,
							'meta_key'		=>	'mb_customer_data',
							'meta_value'	=>	serialize($serie),
							'created'		=>	$this->user->id,
							'created_at'	=>	date('Y-m-d H:i:s')
						);
						if ($this->Customers_model->add('usermeta', $data)) {
							// Enviar email a cliente con sus datos
							$subject = 'Registro en Tracking de Shanoc';
							$message = '<h2>Cuenta nueva</h2>'
										.'<p>Se ha generado una nueva cuenta con la cual podrá verificar el estado de sus pedidos.</p>'
										.'<p>Sus datos de acceso son:</p>'
										.'<ul>'
										.'<li>Nombre de usuario: ' . $email . '</li>'
										.'<li>Contraseña: ' . $pass . '</li>'
										.'</ul>';
							if (send_email($email, $subject, $message)) {
								echo TRUE;
							} else {
								echo FALSE;
							}
						}
					} else {
						echo FALSE;
					}
				} else {
					echo FALSE;
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	/**
	* Editar información de cliente
	*
	* @access public
	* @param  $id 			int 		Id del cliente
	*/
	public function edit($id = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if ((int)$id <= 0) {
			redirect('extranet/customers');
		}

		if (!$this->user->has_permission('edit_customers')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Customers_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library(array('form_validation'));
			$rules = array(
				array(
					'field'		=>	'customer_name',
					'label'		=>	'lang:cms_general_title_name_customer',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'customer_ruc',
					'label'		=>	'RUC',
					'rules'		=>	'trim|required|exact_length[11]'
				),
				array(
					'field'		=>	'customer_email',
					'label'		=>	'lang:cms_general_label_email',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'customer_contact',
					'label'		=>	'lang:cms_general_title_name_contact',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->library(array('image_lib'));
				$this->load->helper(array('functions', 'directory'));

				$name = $this->input->post('customer_name');
				$email = $this->input->post('customer_email');
				$contact = $this->input->post('customer_contact');
				$ruc = $this->input->post('customer_ruc');
				$avatar = $this->input->post('customer_avatar');

				$data = array(
					'name'			=>	$name,
					'email'			=>	$email,
					'user'			=>	$ruc,
					'avatar'		=>	$avatar,
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->Customers_model->edit(NULL, array('id' => $id), $data)) {
					if (!empty($avatar)) {
						$avatarArr = explode('/', $avatar);
						$nameAvatar = $avatarArr[count($avatarArr) - 1];

						$dirThumb = directory_map('./ad-content/thumbs/');
						if ($this->config->item('cms_thumbnail_size_w') > 0 || $this->config->item('cms_thumbnail_size_h') > 0) {
							if(!in_array($nameAvatar . '-' . $this->config->item('cms_thumbnail_size_w') . 'x' . $this->config->item('cms_thumbnail_size_h'), $dirThumb)) {
								create_thumbnail($nameAvatar, $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'));
							}
						}

						if ($this->config->item('cms_medium_size_w') > 0 || $this->config->item('cms_medium_size_h') > 0) {
							if(!in_array($nameAvatar . '-' . $this->config->item('cms_medium_size_w') . 'x' . $this->config->item('cms_medium_size_h'), $dirThumb)) {
								create_thumbnail($nameAvatar, $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'));
							}
						}

						if ($this->config->item('cms_large_size_w') > 0 || $this->config->item('cms_large_size_h') > 0) {
							if(!in_array($nameAvatar . '-' . $this->config->item('cms_large_size_w') . 'x' . $this->config->item('cms_large_size_h'), $dirThumb)) {
								create_thumbnail($nameAvatar, $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'));
							}
						}
					}

					$serie = array(
						'contact'			=>	$contact
					);

					$data = array(
						'meta_value'	=>	serialize($serie),
						'modified'		=>	$this->user->id,
						'modified_at'	=>	date('Y-m-d H:i:s')
					);

					if ($this->Customers_model->edit('usermeta', array('user_id' => $id, 'meta_key' => 'mb_customer_data'), $data)) {
						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
					} else {
						$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
					}
				} else {
					$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		// Obtener datos a de cliente a editar
		$customer = $this->Customers_model->getCustomer($id);
		if ($customer) {
			$this->template->set('_customer', $customer);
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_customer'));
		$this->template->add_js('view', 'extranet/customers/script.min');
		$this->template->render('customers/edit', 'customers');
	}

	/**
	* Cambiar password de cliente. El nuevo password es enviado al cliente vía correo electrónico.
	*
	* @access public
	* @param  $id 			int 		Id del cliente.
	* @param  $email 		str 		Email del cliente.
	* @return 				bol 		Si se cambia el password correctamente, se envia el password al cliente vía email y devolvemos TRUE. Caso contrario devolvemos FALSE.
	*/
	public function changePass()
	{
		$id = $this->input->post('id');
		$email = $this->input->post('email');

		if ($this->user->has_permission('edit_customers')) {
			if ((int)$id > 0) {
				$this->load->model('Customers_model');
				$this->load->library(array('encrypt', 'email'));
				$this->load->helper('functions');
				$pass = randomString(6);

				$data = array(
					'password'		=>	$this->encrypt->password($pass, 'sha256'),
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->Customers_model->edit(NULL, array('id' => $id), $data)) {
					// Enviar email a cliente con sus datos
					$subject = 'Cambio password en Tracking de Shanoc';
					$message = '<h2>Password nuevo</h2>'
								.'<p>Se ha generado un nuevo password con la cual podrá verificar el estado de sus pedidos.</p>'
								.'<p>Sus datos de acceso son:</p>'
								.'<ul>'
								.'<li>Nombre de usuario: ' . $email . '</li>'
								.'<li>Contraseña: ' . $pass . '</li>'
								.'</ul>';
					if (send_email($email, $subject, $message)) {
						echo TRUE;
					} else {
						echo TRUE;
					}
				} else {
					echo FALSE;
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	/**
	* Eliminar cuenta de cliente
	*
	* @access public
	* @param  $id 			int 		Id del cliente.
	* @return 				bol 		Si eliminamos correctamente devolvemos TRUE. Caso contrario devolvemos FALSE.
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		if ($this->user->has_permission('del_customers')) {
			if ((int)$id > 0) {
				$this->load->model('Customers_model');

				if ($this->Customers_model->delete('usermeta', array('user_id' => $id, 'meta_key' => 'mb_customer_data'))) {
					if ($this->Customers_model->delete(NULL, array('id' => $id))) {
						echo TRUE;
					} else {
						echo FALSE;
					}
				} else {
					echo FALSe;
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

		exit;
	}
}

/* End of file customers.php */
/* Location: ./application/modules/extranet/controllers/customers.php */