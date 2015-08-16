<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class States_model extends CI_Model
{
	private $_table;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'posts';
	}

	/**
	* Obtener listado de estados
	*
	* @access public
	* @param  $status 		int 		Indicamos el estado del estado que queremos obtener
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
		$sort_columns = array('id', 'post_title', 'menu_order');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'menu_order';

		$result = array();
		$res = FALSE;

		$this->db->select('id, post_title, post_status, menu_order');
		// Status
		if ((int)$status === 1) {
			$this->db->where('post_status', 'publish');
		}
		if ((int)$status === 0) {
			$this->db->where('post_status', 'draft');
		}
		if ((int)$status === 3) {
			$this->db->where_in('post_status', array('publish', 'draft'));
		}

		$this->db->where('post_type', 'state_item');

		// LIKE
		if (count($search)) {
			foreach ($search as $key => $value) {
				$this->db->like($key, $value);
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
				$this->db->where('post_status', 'publish');
			}
			if ((int)$status === 0) {
				$this->db->where('post_status', 'draft');
			}
			if ((int)$status === 3) {
				$this->db->where_in('post_status', array('publish', 'draft'));
			}

			$this->db->where('post_type', 'state_item');

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

	/**
	* Método para agregar un item a la tabla
	*
	* @access public
	* @param $table 		Nombre de la tabla
	* @param $data 			Array con los datos a agregar
	* @return bool 			En caso de insertar devuelve el id del nuevo registro, caso contrario devuelve FALSE.
	*/
	public function add($table = NULL, $data = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (sizeof($data)) {
			$this->db->insert($table, $data);
			return $this->db->insert_id();;
		}

		return FALSE;
	}

	/**
	* Método para obtener un registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $fields 		Indicamos los campos que queremos obtener
	* @param  $where 		Indicamos en un array el registro que queremos obtener
	* @return boolean 		En caso de obtener el resultado lo devolvemos, caso contrario devuelve FALSE
	*/
	public function get($table = NULL, $fields = '', $where = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (!empty($fields)) {
			$this->db->select($fields);
		}

		if (count($where) > 0) {
			$this->db->where($where);
		}

		$result = $this->db->get($table);

		if ($result->num_rows() > 0) {
			return $result->result();
		}

		return FALSE;
	}

	/**
	* Obtener un solo registro
	*
	* @access public
	* @param  $table 		STR 		Nombre de tabla
	* @param  $select 		STR 		Campos a obtener
	* @param  $where 		ARR 		Array que contiene las condiciones que debe cumplir la consulta
	*/
	public function getRow($table = NULL, $select = '', $where = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (!empty($select)) {
			$this->db->select($select);
		}

		if (count($where) > 0) {
			$this->db->where($where);
		}

		$result = $this->db->get($table);

		if ($result->num_rows() > 0) {
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

	/**
	* Eliminar un registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $where 		Indicamos el registro a eliminar
	* @return boolean 		Si se elimina el registro devuelve TRUE caso contrario devuelve FALSE
	*/
	public function delete($table = NULL, $where = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (count($where) > 0) {
			if ($this->db->where($where)->get($table)->num_rows() > 0) {
				$this->db->where($where);
				$this->db->delete($table);
				return TRUE;
			}
		}

		return FALSE;
	}
}

/* End of file states_model.php */
/* Location: ./application/modules/extranet/models/states_model.php */ ?>