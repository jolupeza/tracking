<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {

	private $_table;
	private $_tableMeta;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'posts';
		$this->_tableMeta = 'postmeta';
	}

	/**
	* Obtener listado de pedidos
	*
	* @access public
	* @param  $status 		int 		Indicamos el estado del pedido que queremos obtener
	* @param  $limit 		int 		Indicamos el número de registros a obtener
	* @param  $offset 		int 		Indicamos desde que registro obtenemos los resultados
	* @param  $sort_by 		str 		Indicamos por que campo queremos ordenar
	* @param  $sort_order	str 		Indicamos si queremos ordenar ascendentemente o descentemente
	* @param  $search		str 		Indicamos el parámetro de búsqueda
	* @return $res 			Arr 		Si encontramos registros devolvemos un array conteniendo la data, y el número total de registros. Si no encontramos devolvemos false.
	*/
	public function getAll($status = 1, $limit = 0, $offset = 0, $sort_by, $sort_order, $search = array())
	{
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('id', 'post_title', 'created_at', 'published_at');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'id';

		$result = array();
		$res = FALSE;

		$this->db->select('id, post_title, created_at, published_at, post_excerpt, post_status');

		// Status
		if ((int)$status === 1) {
			$this->db->where('post_status', 'initiated');
		}
		if ((int)$status === 2) {
			$this->db->where('post_status', 'finalized');
		}
		if ((int)$status === 3) {
			$this->db->where_in('post_status', array('initiated', 'finalized'));
		}

		$this->db->where('post_type', 'orders');

		// LIKE
		if (count($search)) {
			foreach ($search as $key => $value) {
				$this->db->where('month(' . $key . ')', $value);
			}
		}

		$this->db->order_by($sort_by, $sort_order);

		if ((int)$limit > 0) {
			$result = $this->db->get($this->_table, $limit, $offset);
		} else {
			$result = $this->db->get($this->_table);
		}

		if ($result->num_rows() > 0) {
			$res['data'] = $result->result();

			$this->db->select('id');
			// Status
			if ((int)$status === 1) {
				$this->db->where('post_status', 'initiated');
			}
			if ((int)$status === 2) {
				$this->db->where('post_status', 'finalized');
			}
			if ((int)$status === 3) {
				$this->db->where_in('post_status', array('initiated', 'finalized'));
			}

			$this->db->where('post_type', 'orders');

			if (count($search)) {
				foreach ($search as $key => $value) {
					$this->db->like($key, $value);
				}
			}

			$result = $this->db->get($this->_table);
			$res['num_rows'] = $result->num_rows();
		}

		return $res;
	}

	public function getOrder($id)
	{
		$this->db->select('p.post_title, p.created_at, p.published_at, p.post_excerpt, p.post_content, pm.meta_value');
		$this->db->from($this->_table . ' p');
		$this->db->join($this->_tableMeta . ' pm', 'p.id = pm.post_id', 'left');

		$where = array(
			'p.post_type' => 'orders',
			'p.id'        => $id,
			'meta_key'    => 'mb_post_state_obs'
		);
		$this->db->where($where);
		$result = $this->db->get($this->_table);

		if ($result->num_rows() > 0) {
			return $result->row();
		}

		return FALSE;
	}

	public function getStates()
	{
		$this->db->select('id, post_title, guid');
		$where = array(
			'post_type'   => 'state_item',
			'post_status' => 'publish',
		);
		$this->db->where($where);
		$this->db->order_by('menu_order', 'asc');
		$result = $this->db->get($this->_table);

		if ($result->num_rows() > 0) {
			return $result->result();
		}

		return FALSE;
	}

	/**
	* Obtener listado de órdenes que no tengan status finalized
	*
	* @access public
	* @return Arr 		Array con los id de las órdenes que tienen estado initiated
	*/
	public function getOrdersNotFinalized()
	{
		$this->db->select('p.id, pm.meta_value');
		$this->db->from($this->_table . ' p');
		$this->db->join($this->_tableMeta . ' pm', 'p.id = pm.post_id', 'left');
		$where = array(
			'p.post_status' => 'initiated',
			'p.post_type'   => 'orders',
			'pm.meta_key'   => 'mb_post_state_obs'
		);

		$this->db->where($where);
		$result = $this->db->get();

		if ($result->num_rows() > 0)
		{
			return $result->result();
		}

		return FALSE;
	}

	public function getRow($table = '', $select = '', $where = array())
	{
		$table = (empty($table)) ? $this->_table : $table;

		if (!empty($select))
		{
			$this->db->select($select);
		}

		if (count($where))
		{
			$this->db->where($where);
		}

		$result = $this->db->get($table);
		if ($result->num_rows() > 0)
		{
			return $result->row();
		}

		return FALSE;
	}

	/**
	* Método para editar algún registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $where 		Indicamos a través de un array que registro se modificará
	* @param  $data 		Array con los datos a modificar
	* @return boolean 		Si se actualiza los datos devuelve TRUE caso contrario devuelve FALSE
	*/
	public function edit($table = NULL, $where = array(), $data = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (count($where) > 0) {
			$result = $this->db->where($where)->get($table);

			if ($result->num_rows() > 0) {
				if (count($data) > 0) {
					$this->db->where($where);
					$this->db->update($table, $data);
					return TRUE;
				}
			}
		}

		return FALSE;
	}

}

/* End of file main_model.php */
/* Location: ./application/models/main_model.php */