<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loading
{
	protected $CI;

	public function __construct()
	{
		//parent::__construct();
		$this->CI =& get_instance();
	}

	public function setConfiguration()
	{
		//$this->CI =& get_instance();

		$this->CI->load->model('Configuration_model');
		$this->CI->config->set_item('cms_site_name', $this->CI->Configuration_model->getRow('blogname')->option_value);
		$this->CI->config->set_item('cms_site_desc', $this->CI->Configuration_model->getRow('blogdescription')->option_value);
		$this->CI->config->set_item('cms_thumbnail_size_w', $this->CI->Configuration_model->getRow('thumbnail_size_w')->option_value);
		$this->CI->config->set_item('cms_thumbnail_size_h', $this->CI->Configuration_model->getRow('thumbnail_size_h')->option_value);
		$this->CI->config->set_item('cms_medium_size_w', $this->CI->Configuration_model->getRow('medium_size_w')->option_value);
		$this->CI->config->set_item('cms_medium_size_h', $this->CI->Configuration_model->getRow('medium_size_h')->option_value);
		$this->CI->config->set_item('cms_large_size_w', $this->CI->Configuration_model->getRow('large_size_w')->option_value);
		$this->CI->config->set_item('cms_large_size_h', $this->CI->Configuration_model->getRow('large_size_h')->option_value);
		$this->CI->config->set_item('cms_thumbnail_crop', $this->CI->Configuration_model->getRow('thumbnail_crop')->option_value);
	}

	// Verificar si alguna orden ha cumplido fecha de finalización. De ser así cambiamos el status a finalized
	public function verifyOrders()
	{
		$this->CI->load->model('Main_model');
		$orders = $this->CI->Main_model->getOrdersNotFinalized();

		if ($orders)
		{
			foreach ($orders as $order) {
				$meta = unserialize($order->meta_value);

				$now = new DateTime("now");
				$dateFinish = new DateTime($meta['6'][2] . ' 00:00:00');

				if ($now > $dateFinish)
				{
					// Actualizamos status de orden
					$this->CI->Main_model->edit(NULL, array('id' => $order->id), array('post_status' => 'finalized'));

					// Verificamos si hemos enviado correo al administrador como al cliente
					$verify = $this->CI->Main_model->getRow('', 'id, post_title, post_author', array('id' => $order->id, 'post_favorite' => 0));
					if ($verify)
					{
						// Enviamos correo
						$this->CI->load->library('email');
						$this->CI->load->helper('functions');

						// Cliente
						// Obtener nombre de cliente
						$customer = $this->CI->Main_model->getRow('users', 'name, email', array('id' => $verify->post_author));

						$subject = 'Orden Finalizada';
						$body = '<tr>'
								.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;">'
		                		.'Su pedido: <strong>' . $verify->post_title . '</strong> se ha completado.'
		                		.'</td>'
		                		.'</tr>';
						$message = body_email($customer->name, $body);
						send_email($customer->email, $subject, $message);

						// Administrador
						$admin = $this->CI->Main_model->getRow('options', 'option_value', array('option_name' => 'admin_email'));

						$subject = 'Orden Finalizada';
						$body = '<tr>'
								.'<td class="bodycopy" style="color: #484848; font-family: Arial, sans-serif; font-size: 14px; line-height: 22px; text-align: center; padding: 50px 50px 50px 50px;">'
		                		.'El pedido: <strong>' . $verify->post_title . '</strong> se ha completado.'
		                		.'</td>'
		                		.'</tr>';
						$message = body_email($customer->name, $body);
						send_email($admin->option_value, $subject, $message);

						// Actualizar campo post_favorite
						$this->CI->Main_model->edit(NULL, array('id' => $order->id), array('post_favorite' => 1));
					}
				}
			}
		}
	}

}

/* End of file Loading.php */
/* Location: ./application/hooks/Loading.php */