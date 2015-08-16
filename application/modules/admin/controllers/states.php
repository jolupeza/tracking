<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class States extends MY_Controller
{

	public function index()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('view_states')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_label_status'));
		$this->template->add_js('view', 'admin/states/script');
		$this->template->render('states/index', 'states');
	}

	/**
	* Método que vía ajax obtiene y muestra la lista de estados
	*
	* @access public
	* @param  $status 		Indicamos el status (confirmado o no confirmado)
	* @param  $sort_by 		Indicamos a través de que campo se ordena
	* @param  $sort_order 	Indicamos si se ordena ascendente o descendentemente
	* @param  $search 		Indicamos si se pasa un parámetro de búsqueda
	* @param  $offset 		Indicamos desde que registro obtenemos la lista
	*/
	public function displayAjax($status = 'all', $sort_by = 'menu_order', $sort_order = 'asc', $limit = 10, $search = 'all',  $offset = 0)
	{
		$total = 0;
		$like = array();
		$statusArr = array();
		$statusElements = array('publish', 'draft', 'trash');
		$statusArr = (in_array($status, $statusElements)) ? array($status) : array('publish', 'draft');

		$this->load->model('States_model');
		$this->load->helper('form');

		if ($search !== 'all') {
			$like = array('post_title' => urldecode($search));
		}

		$result = $this->States_model->getAll($statusArr, $limit, $offset, $sort_by, $sort_order, $like);
		$states = $result['data'];
		$total = $result['num_rows'];

		if (count($states) > 0) {
			$this->template->set('_states', $states);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/states/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 9;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_num_total', $this->States_model->countRows(array('publish', 'draft')));
		$this->template->set('_active', $this->States_model->countRows(array('publish')));
		$this->template->set('_no_active', $this->States_model->countRows(array('draft')));
		$this->template->set('_trash', $this->States_model->countRows(array('trash')));

		$this->template->set('_status', $status);
		$this->template->set('_total', $total);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->set('_offset', $offset);

		$this->template->renderAjax('states/displayAjax');
	}

	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('add_states')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('States_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'state_name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$name = $this->input->post('state_name');
				$status = (null != $this->input->post('state_status')) ? $this->input->post('state_status') : 'draft';
				$avatar = $this->input->post('state_avatar');

				$states = $this->States_model->get(NULL, 'menu_order', array('post_type' => 'state_item'));
				$menu_order = 1;
				if (count($states)) {
					foreach ($states as $state) {
						$menu_order = ($state->menu_order > $menu_order) ? $state->menu_order : $menu_order;
					}
					$menu_order++;
				}

				$data = array(
					'post_title'	=>	$name,
					'post_status'	=>	$status,
					'menu_order'	=>	$menu_order,
					'guid'			=>	$avatar,
					'post_type'		=>	'state_item',
					'created'		=>	$this->user->id,
					'created_at'	=>	date('Y-m-d H:i:s')
				);

				$last_id = $this->States_model->add(NULL, $data);

				if ((int)$last_id > 0) {
					if (!empty($avatar)) {
						$this->load->library(array('image_lib'));
						$this->load->helper(array('functions', 'directory'));

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

					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/states/edit/' . $last_id);
				} else {
					$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'admin/states/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_add_status'));
		$this->template->set('_token', $this->user->token());
		$this->template->render('states/add', 'states');
	}

	public function edit($id = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if ((int)$id <= 0) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('edit_states')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('States_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'state_name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$name = $this->input->post('state_name');
				$status = (null != $this->input->post('state_status')) ? $this->input->post('state_status') : 'draft';
				$avatar = $this->input->post('state_avatar');

				$data = array(
					'post_title'	=>	$name,
					'post_status'	=>	$status,
					'guid'			=>	$avatar,
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->States_model->edit(NULL, array('id' => $id), $data)) {
					if (!empty($avatar)) {
						$this->load->library(array('image_lib'));
						$this->load->helper(array('functions', 'directory'));

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

		// Traer datos del estado a editar
		$select = 'post_title, post_status, guid';
		$state = $this->States_model->getRow(NULL, $select, array('id' => $id, 'post_type' => 'state_item'));
		if ($state) {
			$this->template->set('_state', $state);
		} else {
			show_error('¡Acceso restringido');
		}

		$this->output->enable_profiler(TRUE);

		$this->load->helper('form');
		$this->template->add_js('view', 'admin/states/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_state'));
		$this->template->set('_token', $this->user->token());
		$this->template->render('states/edit', 'states');
	}

	/**
	* Actualizar orden del Estado
	*
	* @access public
	* @param  $id 			int 		Id del estado a actualizar el orden
	* @param  $order 		int 		El orden por el que se cambiará
	* @param  $action 		str 		Indicamos si realizaremos un up o down
	* @return 				bol 		Si se produce el cambio se devuelve TRUE, caso contrario devolvemos FALSE
	*/
	public function change()
	{
		$id = $this->input->post('id');
		$order = $this->input->post('order');
		$action = $this->input->post('action');

		if ((int)$id > 0) {
			$other = 0;
			if ($action === 'down') {
				$other = $order - 1;
			} elseif ($action == 'up') {
				$other = $order + 1;
			}

			$this->load->model('States_model');
			$otherState = $this->States_model->getRow(NULL, 'id', array('menu_order' => $other, 'post_type' => 'state_item',  'post_status' => 'publish'));
			if ($otherState) {
				if ($this->States_model->edit(NULL, array('id' => $otherState->id), array('menu_order' => $order, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')))) {
					if ($this->States_model->edit(NULL, array('id' => $id), array('menu_order' => $other, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')))) {
						echo TRUE;
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
	* Actualizar campo del Estado
	*
	* @access public
	* @param  $id 			int 		Id del estado
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
			$this->load->model('States_model');
			$data = array(
				$field				=>	$value,
				'modified'			=>	$this->user->id,
				'modified_at'		=>	date('Y-m-d H:i:s')
			);

			if ($this->States_model->edit(NULL, array('id' => $id), $data)) {
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
	* Eliminamos registro
	*
	* Realizar eliminación de registro, para lo cual primero comprobamos que el registro exista. Si existe procedemos a eliminar y devolvemos TRUE.
	* Si no existe devolvemos FALSE.
	*
	* @access public
	* @param  $id 			int 		Id del registro a eliminar
	* @return 				bol 		TRUE si eliminamos el registro. FALSE si no se elimina el registro
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('States_model');

			$state = $this->States_model->getRow(NULL, 'menu_order', array('id' => $id, 'post_type' => 'state_item'));
			if ($state) {
				// Obtenemos el último menu_order de estados
				$max = $this->States_model->maxMenuOrder();
				$max = $max->menu_order;

				if ($max != $state->menu_order) {
					// Obtener los estado que siguen al estado a eliminar
					$next = $this->States_model->nextStateMenuOrder($state->menu_order + 1, $max);
					if ($next) {
						foreach ($next as $row) {
							$this->States_model->edit(NULL, array('id' => $row->id), array('menu_order' => $row->menu_order - 1));
						}
					}
				}

				if ($this->States_model->delete(NULL, $where = array('id' => $id, 'post_type' => 'state_item'))) {
					echo TRUE;
				} else {
					echo FALSE;
				}
			}
			//SELECT menu_order FROM posts WHERE id = 39;
			//SELECT max(menu_order) FROM posts WHERE post_type = "state_item";
			//SELECT id FROM posts WHERE menu_order BETWEEN 6 AND 9;
		} else {
			echo FALSE;
		}

		exit;
	}
}

/* End of file states.php */
/* Location: ./application/modules/admin/controllers/states.php */