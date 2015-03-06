<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getReminders($year = NULL, $month = NULL, $day = NULL)
	{
		$types = array('birthday', 'anniversary', 'holiday', 'weekend');

		$this->db->select('published_at, post_title, post_type');
		$this->db->where('post_status', 'publish');
		$this->db->where_in('post_type', $types);
		$this->db->where('created', $this->user->id);

		if (!is_null($day) && !is_null($month) && !is_null($year)) {
			$this->db->where('LEFT(`published_at`, 10) =', $year . '-' . $month . '-' . $day);
		} else if (is_null($day) && !is_null($month) && !is_null($year)) {
			$this->db->where('LEFT(`published_at`, 7) =', $year . '-' . $month);
		} else {
			$this->db->where('LEFT(`published_at`, 10) =', date('Y-m-d'));
		}

		$this->db->order_by('post_favorite', 'desc');
		$this->db->order_by('published_at', 'asc');

		return $this->db->get('posts')->result();
	}
}

/* End of file calendar_model.php */
/* Location: ./application/models/widgets/calendar_model.php */