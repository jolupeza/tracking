<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class States extends MY_Controller
{

	/**
	* Dashboard Estados
	*
	* @access public
	*/
	public function index()
	{
		$this->load->model('States_model');

		if (!$this->user->is_logged_in()) {
			redirect();
		}

		if (!$this->user->has_permission('view_states')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->helper('form');
		$this->template->set('_token', $this->user->token());
		$this->template->set('_title', $this->lang->line('cms_general_label_status'));
		$this->template->add_js('view', 'extranet/states/script.min');
		$this->template->render('states/index', 'states');
	}

	/**
	* Mostrar grilla de estados vía ajax
	*
	* @access public
	* @param  $status 		int 		Status de estado que queremos mostrar. Por defecto mostramos los estados activos
	* @param  $sort_id 		str 		Indicamos a través de que campo vamos a realizar el ordenamiento de los datos a mostrar
	* @param  $sort_order	str 		Indicamos si vamos a ordenar ascendete o descentemente
	* @param  $limit		int 		Indicamos el número de registros a mostrar por página
	* @param  $search		str 		Indicamos el parámetro de búsqueda. Por defecto se indica 'all', lo que indica que no realizaremos búsqueda
	* @param  $offset		int 		Indicamos a partir de que registro mostraremos los resultados
	* @return display 		vista
	*/
	public function displayAjax($status = 3, $sort_by = 'menu_order', $sort_order = 'asc', $limit = 10, $search = 'all', $offset = 0)
	{
		$total = 0;
		$like = array();
		$this->load->model('States_model');
		$this->load->helper('form');

		if ($search !== 'all') {
			$like = array('post_title' => urldecode($search));
		}

		$result = $this->States_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $like);
		$states = $result['data'];
		$total = $result['num_rows'];

		if (count($states)) {
			$this->template->set('_states', $states);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('extranet/states/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $limit . '/' . $search);
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
		$this->template->renderAjax('states/display');
	}

	/**
	* Actualizar campo del Estado
	*
	* @access public
	* @param  $id 			int 		Id del estado a actualizar el status
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
}

/* End of file states.php */
/* Location: ./application/modules/extranet/controllers/states.php */ ?>