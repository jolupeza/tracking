<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_model extends CI_Model {
	private $table;
	private $tablePosts;

	public function __construct()
	{
		parent::__construct();
		$this->table = 'users';
		$this->tablePosts = 'posts';
	}

	public function getInfoCustomer()
	{
		$result = FALSE;

		if ($this->user->is_logged_in()) {
			$this->db->select('u.name, u.user, p.created_at');
			$this->db->from($this->table . ' u');
			$this->db->join($this->tablePosts . ' p', 'u.id = p.post_author', 'left');

			$where = array(
				'u.role' => 5,
				'u.id'   => $this->user->id
			);
			$this->db->where($where);
			$this->db->order_by('p.created_at', 'desc');
			$result = $this->db->get('', 1);

			if ($result->num_rows() > 0) {
				return $result->row();
			}
		}

		return $result;
	}

	public function getTotalOrders()
	{
		if ($this->user->is_logged_in()) {
			$this->db->select('count(id) as total');
			$where = array(
				'post_author' => $this->user->id,
				'post_type'   => 'orders'
			);
			$this->db->where($where);

			$result = $this->db->get($this->tablePosts);

			if ($result->num_rows() > 0) {
				return $result->row();
			}

			return FALSE;
		}
	}

	public function getOrders()
	{
		$this->db->select('id, post_title');
		$where = array(
			'post_author' => $this->user->id,
			'post_type'   => 'orders'
		);
		$this->db->where($where);

		if ($this->session->userdata('orderId')) {
			$this->db->where_not_in('id', array($this->session->userdata('orderId')));
		}

		$this->db->order_by('created', 'desc');
		$result = $this->db->get($this->tablePosts, 5);

		if ($result->num_rows() > 0) {
			return $result->result();
		}

		return FALSE;
	}

	public function getPublicidad()
	{
		$this->db->select('id, guid');
		$where = array(
			'post_status' => 'publish',
			'post_type' => 'publicidad',
		);

		$this->db->where($where);
		$result = $this->db->get($this->tablePosts);

		if ($result->num_rows() > 0) {
			return $result->row();
		}

		return FALSE;
	}
}

/* End of file info_model.php */
/* Location: ./application/models/widgets/info_model.php */