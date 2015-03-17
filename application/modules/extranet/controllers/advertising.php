<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertising extends MY_Controller
{
	/**
	* Dashboard Publicidad
	*
	* @access public
	*/
	public function index()
	{
		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if (!$this->user->has_permission('view_advertising')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Publicidad');
		$this->template->add_js('view', 'extranet/other/script');
		$this->template->render('advertising/index', 'advertising');
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
	public function displayAjax($status = 'all', $sort_by = 'id', $sort_order = 'asc', $limit = 10, $search = 'all', $offset = 0)
	{
		$total = 0;
		$like = array();
		$this->load->model('Advertising_model');
		$this->load->helper('form');

		if ($search !== 'all') {
			$like = array('post_title' => urldecode($search));
		}

		$post_status = ($status === 'all') ? array() : $status;

		$result = $this->Advertising_model->getAll($post_status, $limit, $offset, $sort_by, $sort_order, $like);
		$advertising = $result['data'];
		$total = $result['num_rows'];

		if (count($advertising)) {
			$this->template->set('_advertisings', $advertising);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('extranet/advertising/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
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
		$this->template->renderAjax('advertising/display');
	}

	/**
	* Agregar Publicidad
	*
	* @access public
	* @param  $name 		str 		Nombre del cliente
	* @return 				bol 		Si podemos agregar la publicidad correctamene devolvemos TRUE, caso contrario devolvemos FALSE. El nombre del contacto se almacena como metadato en tabla usermeta.
	*/
	public function add()
	{
		$token  = $this->input->post('token');
		$name   = $this->input->post('name');
		$url    = $this->input->post('url');
		$avatar = $this->input->post('avatar');

		$result = array(
			'valid'	=> FALSE
		);

		if ($this->user->has_permission('add_advertising')) {
			if ($this->input->post('token') == $this->session->userdata('token'))
			{
				if (!empty($name)) {
					$this->load->model('Advertising_model');
					$this->load->library(array('encrypt', 'email', 'image_lib'));
					$this->load->helper(array('functions', 'directory'));

					$data = array(
						'post_title'   =>	$name,
						'post_status'  => 	'draft',
						'post_excerpt' =>	$url,
						'post_type'    => 	'publicidad',
						'guid'         =>	$avatar,
						'created'      =>	$this->user->id,
						'created_at'   =>	date('Y-m-d H:i:s')
					);
					$last_id = $this->Advertising_model->add(NULL, $data);

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

						$result['valid'] = TRUE;
					}
				}
			}
		}

		echo json_encode($result);

		exit;
	}

	/**
	* Editar publicidad
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

		if (!$this->user->has_permission('edit_advertising')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Advertising_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library(array('form_validation'));
			$rules = array(
				array(
					'field'		=>	'publi_name',
					'label'		=>	'Nombre',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->library(array('image_lib'));
				$this->load->helper(array('functions', 'directory'));

				$name = $this->input->post('publi_name');
				$url = $this->input->post('publi_url');
				$avatar = $this->input->post('publi_avatar');

				$data = array(
					'post_title'   =>	$name,
					'post_excerpt' =>	$url,
					'guid'         =>	$avatar,
					'modified'     =>	$this->user->id,
					'modified_at'  =>	date('Y-m-d H:i:s')
				);

				if ($this->Advertising_model->edit(NULL, array('id' => $id), $data)) {
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
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				} else {
					$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		// Obtener datos de publicidad
		$advertising = $this->Advertising_model->getAdvertising($id);
		if ($advertising) {
			$this->template->set('_advertising', $advertising);
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', 'Editar publicidad');
		$this->template->add_js('view', 'extranet/other/script.min');
		$this->template->render('advertising/edit', 'advertising');
	}

	public function activePubli()
	{
		$id = $this->input->post('id');

		$result = array('result' => FALSE);

		if ((int)$id > 0)
		{
			$this->load->model('Advertising_model');
			// Buscar la publicidad activa actualmente
			if ($this->Advertising_model->activePubli())
			{
				$data = array(
					'post_status' => 'publish',
					'modified'    => $this->user->id,
					'modified_at' => date('Y:m:d H:i:s')
				);

				if ($this->Advertising_model->edit(NULL, array('id' => $id), $data)) {
					$result['result'] = TRUE;
				}
			}
		}

		echo json_encode($result);
		exit;
	}

	/**
	* Eliminar publicidad
	*
	* @access public
	* @param  $id 			int 		Id del cliente.
	* @return 				bol 		Si eliminamos correctamente devolvemos TRUE. Caso contrario devolvemos FALSE.
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		$result = array(
			'result' => FALSE
		);

		if ($this->user->has_permission('del_customers')) {
			if ((int)$id > 0) {
				$this->load->model('Advertising_model');

				if ($this->Advertising_model->delete(NULL, array('id' => $id))) {
					$result['result'] = TRUE;
				}
			}
		}

		echo json_encode($result);

		exit;
	}
}

/* End of file advertising.php */
/* Location: ./application/modules/extranet/controllers/advertising.php */