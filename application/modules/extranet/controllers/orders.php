<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MY_Controller
{
	/**
	* Agregar pedido
	*
	* Agregamos pedido en la tabla `posts`. Se relaciona con el cliente y se crea un metadato en la postmeta conteniendo las fechas de pedido y entrega
	*
	* @access public
	*/
	public function index()
	{
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if (!$this->user->has_permission('add_orders')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Orders_model');

		// Traer los estados activos
		$states = $this->Orders_model->get('posts', 'id, post_title', array('post_status' => 'publish', 'post_type' => 'state_item'), array('menu_order' => 'asc'));
		if ($states) {
			$this->template->set('_states', $states);
		}

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library(array('form_validation'));
			$rules = array(
				array(
					'field'		=>	'order_title',
					'label'		=>	'lang:cms_general_title_order',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_detail',
					'label'		=>	'lang:cms_general_title_order_detail',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_site',
					'label'		=>	'lang:cms_general_title_place_origin',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_customer',
					'label'		=>	'Cliente',
					'rules'		=>	'callback_verifySelect'
				),
				array(
					'field'		=>	'order_date',
					'label'		=>	'lang:cms_general_title_order_date',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_deliverydate',
					'label'		=>	'lang:cms_general_title_delivery_date',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$error = array();
				$now = new DateTime("now");
				$orderDate = new DateTime($this->input->post('order_date'). ' 23:59:59');
				$deliveryDate = new DateTime($this->input->post('order_deliverydate'). ' 23:59:59');

				// Validamos que fecha de pedido sea mayor o igual a la fecha actual
				if ($orderDate < $now || $deliveryDate < $now) {
					$error[] = $this->lang->line('error_order_date');
				}

				$states = $this->input->post('order_obs_state');
				$stateOrderStart = $this->input->post('order_obs_date');
				$stateOrderEnd = $this->input->post('state-date');

				$meta = array();
				$errorState = FALSE;
				foreach ($states as $key => $value) {
					$meta[$key] = array($value);
				}

				foreach ($stateOrderStart as $key => $value) {
					$errorState = (empty($value)) ? TRUE : $errorState;
					array_push($meta[$key], $value);
				}

				foreach ($stateOrderEnd as $key => $value) {
					$errorState = (empty($value)) ? TRUE : $errorState;
					array_push($meta[$key], $value);
				}

				if ($errorState) {
					$error[] = $this->lang->line('error_date_state');
				}

				if (count($error)) {
					foreach ($error as $key => $value) {
						$this->template->add_message(array('error' => $value));
					}
				} else {
					// Agregamos el nuevo pedido
					$orderTitle = $this->input->post('order_title');
					$orderDetail = $this->input->post('order_detail');
					$orderStatus = ($this->input->post('order_status') === '1') ? 'initiated' : 'finalized';
					$orderSite = $this->input->post('order_site');
					$orderCustomer = $this->input->post('order_customer');
					$orderDate = $this->input->post('order_date');
					$orderDeliveryDate = $this->input->post('order_deliverydate');

					// Falta agregar las observaciones que se realicen por estado
					$data = array(
						'post_title'		=>	$orderTitle,
						'post_content'		=>	$orderDetail,
						'post_status'		=>	$orderStatus,
						'post_excerpt'		=>	$orderSite,
						'post_author'		=>	$orderCustomer,
						'post_type'			=>	'orders',
						'created'			=>	$this->user->id,
						'created_at'		=>	$orderDate . ' 23:59:59',
						'published_at'		=>	$orderDeliveryDate . ' 23:59:59'
					);

					$last_id = $this->Orders_model->add('posts', $data);

					if ((int)$last_id > 0) {
						if (count($meta)) {
							$data = array(
								'post_id'		=>	$last_id,
								'meta_key'		=>	'mb_post_state_obs',
								'meta_value'	=>	serialize($meta),
								'created'		=>	$this->user->id,
								'created_at'	=>	date('Y-m-d H:i:s')
							);

							$this->Orders_model->add('postmeta', $data);
						}

						// Enviamos correo al cliente informándole sobre a creación de su pedido
						$this->load->library('email');
						$this->load->helper('functions');

						$user = $this->Orders_model->getRow('users', 'name, email', array('id' => $orderCustomer));

						$subject = 'Nueva orden';
						$body = '<tr>'
								.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;">'
		                		.'Se ha generado una nueva orden de pedido: <strong>' . $orderTitle . '</strong>. Puede ingresar a nuestro sistema para verificar el estado de su pedido.'
		                		.'</td>'
		                		.'</tr>';
						$message = body_email($user->name, $body);
						send_email($user->email, $subject, $message);

						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_add')));
						redirect('extranet/orders/edit/' . $last_id);
					} else {
						$this->template->add_message(array('error' => $this->lang->line('cms_general_label_error_action')));
					}
				}
			}
		}

		// Traer los clientes activos
		$customers = $this->Orders_model->get('users', 'id, name', array('role' => 5, 'status' => 1, 'active' => 1));
		if ($customers) {
			$this->template->set('_customers', $customers);
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', $this->lang->line('cms_general_title_add_orders'));
		$this->template->add_js('view', 'extranet/orders/script');
		$this->template->render('orders/add', 'orders');
	}

	/**
	* Editar información de pedido
	*
	* @access public
	* @param  $id 			int 		Id de pedido
	*/
	public function edit($id = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if ((int)$id <= 0) {
			redirect('extranet/main');
		}

		if (!$this->user->has_permission('edit_orders')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Orders_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library(array('form_validation'));
			$rules = array(
				array(
					'field'		=>	'order_title',
					'label'		=>	'lang:cms_general_title_order',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_detail',
					'label'		=>	'lang:cms_general_title_order_detail',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_site',
					'label'		=>	'lang:cms_general_title_place_origin',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_customer',
					'label'		=>	'Cliente',
					'rules'		=>	'callback_verifySelect'
				),
				array(
					'field'		=>	'order_date',
					'label'		=>	'lang:cms_general_title_order_date',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'order_deliverydate',
					'label'		=>	'lang:cms_general_title_delivery_date',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$error = array();
				$now = new DateTime("now");
				$orderDate = new DateTime($this->input->post('order_date'). ' 23:59:59');
				$deliveryDate = new DateTime($this->input->post('order_deliverydate'). ' 23:59:59');

				// Validamos que fecha de pedido sea mayor o igual a la fecha actual
				//if ($orderDate < $now || $deliveryDate < $now) {
				if ($deliveryDate < $now) {
					$error[] = $this->lang->line('error_order_date');
				}

				$states = $this->input->post('order_obs_state');
				$stateOrderStart = $this->input->post('order_obs_date');
				$stateOrderEnd = $this->input->post('state-date');

				$meta = array();
				$errorState = FALSE;
				foreach ($states as $key => $value) {
					$meta[$key] = array($value);
				}

				foreach ($stateOrderStart as $key => $value) {
					$errorState = (empty($value)) ? TRUE : $errorState;
					array_push($meta[$key], $value);
				}

				foreach ($stateOrderEnd as $key => $value) {
					$errorState = (empty($value)) ? TRUE : $errorState;
					array_push($meta[$key], $value);
				}

				if ($errorState) {
					$error[] = $this->lang->line('error_date_state');
				}

				if (count($error)) {
					foreach ($error as $key => $value) {
						$this->template->add_message(array('error' => $value));
					}
				} else {
					// Agregamos el nuevo pedido
					$orderTitle = $this->input->post('order_title');
					$orderDetail = $this->input->post('order_detail');
					$orderStatus = ($this->input->post('order_status') === '1') ? 'initiated' : 'finalized';
					$orderSite = $this->input->post('order_site');
					$orderCustomer = $this->input->post('order_customer');
					$orderDate = $this->input->post('order_date');
					$orderDeliveryDate = $this->input->post('order_deliverydate');

					// Falta agregar las observaciones que se realicen por estado
					$data = array(
						'post_title'		=>	$orderTitle,
						'post_content'		=>	$orderDetail,
						'post_status'		=>	$orderStatus,
						'post_excerpt'		=>	$orderSite,
						'post_author'		=>	$orderCustomer,
						'post_type'			=>	'orders',
						'modified'			=>	$this->user->id,
						'modified_at'		=>	date('Y-m-d H:i:s'),
						'created_at'		=>	$orderDate . ' 23:59:59',
						'published_at'		=>	$orderDeliveryDate . ' 23:59:59'
					);

					if ($this->Orders_model->edit(NULL, array('id' => $id), $data)) {
						if (count($meta)) {
							$data = array(
								'meta_value'	=>	serialize($meta),
							);

							if ($this->Orders_model->getRow('postmeta', 'meta_id', array('post_id' => $id, 'meta_key' => 'mb_post_state_obs'))) {
								$data['modified'] = $this->user->id;
								$data['modified_at'] = date('Y-m-d H:i:s');
								$this->Orders_model->edit('postmeta', array('post_id' => $id, 'meta_key' => 'mb_post_state_obs'), $data);
							} else {
								$data['post_id'] = $id;
								$data['meta_key'] = 'mb_post_state_obs';
								$data['created'] = $this->user->id;
								$data['created_at'] = date('Y-m-d H:i:s');
								$this->Orders_model->add('postmeta', $data);
							}
						}

						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
					} else {
						$this->template->add_message(array('error' => $this->lang->line('cms_general_label_error_action')));
					}
				}
			}
		}

		// Obtener datos a de pedido
		$order = $this->Orders_model->getRow(NULL, 'post_title, post_content, post_excerpt, post_status, created_at, published_at, post_author', array('id' => $id));
		if ($order) {
			$this->template->set('_order', $order);
		}

		// Traemos las observaciones de los estados
		$obs = $this->Orders_model->getRow('postmeta', 'meta_value', array('post_id' => $id, 'meta_key' => 'mb_post_state_obs'));
		if ($obs) {

			/*$observations = unserialize($obs->meta_value);
			var_dump(end($observations));
			echo end($observations)[2]; exit;*/


			$this->template->set('_obs', unserialize($obs->meta_value));
		}

		// Traer los clientes activos
		$customers = $this->Orders_model->get('users', 'id, name', array('role' => 5, 'status' => 1, 'active' => 1));
		if ($customers) {
			$this->template->set('_customers', $customers);
		}

		// Traer los estados activos
		$states = $this->Orders_model->get('posts', 'id, post_title', array('post_status' => 'publish', 'post_type' => 'state_item'), array('menu_order' => 'asc'));
		if ($states) {
			$this->template->set('_states', $states);
		}


		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_customer'));
		$this->template->add_js('view', 'extranet/orders/script.min');
		$this->template->render('orders/edit', 'orders');
	}

	/**
	* Verificar si hemos seleccionado un cliente
	*
	* @access public
	* @param  $value 		STR 		Id del cliente seleccionado
	* @return 				BOOL 		Si el id seleccionado es diferente de 0 devolvemos TRUE, si es 0 devolvemos TRUE
	*/
	public function verifySelect($value = '0')
	{
		if ($value === '0') {
			$this->form_validation->set_message('verifySelect', $this->lang->line('error_select_customer'));
			return FALSE;
		}

		return TRUE;
	}
}

/* End of file orders.php */
/* Location: ./application/modules/extranet/controllers/orders.php */