<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class InfoWidget extends Widget {
		private $model;

		public function __construct()
		{
			parent::__construct();
			$this->CI->load->library('widget');
			$this->model = 'info';
		}

		public function getConfig($menu)
	    {
	        $menus['sidebar'] = array(
				'position' => 'sidebar',
				'show'     => 'all',
	        );

	        $menus['orders'] = array(
				'position' => 'sidebar',
				'show'     => 'all',
				'hide'     => array('main')
	        );

	        $menus['publi'] = array(
				'position' => 'sidebar',
				'show'     => array('main'),
	        );

	        return $menus[$menu];
	    }

	    public function displayInfo($view, $model)
	    {
	    	$data = array();
	    	$this->CI->load->model('widgets/' . ucfirst($this->model) . '_model', $model);

	    	$result = $this->_getInfo($model);
	    	$total = $this->_getTotalOrders($model);

	    	if ($result && $total) {
	    		$data['info'] = $result;
	    		$data['total'] = $total;
	    		return $this->render($view, $data);
	    	}

	    	return FALSE;
	    }

	    private function _getInfo($model)
	    {
			$result = $this->CI->$model->getInfoCustomer();

			if ($result) {
				return $result;
			}

			return FALSE;
	    }

	    private function _getTotalOrders($model)
	    {
			$result = $this->CI->$model->getTotalOrders();

			if ($result) {
				return $result;
			}

			return FALSE;
	    }

	    public function displayOrders($view, $model)
	    {
	    	$data = array();
	    	$this->CI->load->model('widgets/' . ucfirst($this->model) .'_model', $model);

	    	$result = $this->_getOrders($model);

	    	if ($result) {
	    		$data['orders'] = $result;

	    		return $this->render($view, $data);
	    	}

	    	return FALSE;
	    }

	   private function _getOrders($model)
	   {
	   		$result = $this->CI->$model->getOrders();

	   		if ($result) {
	   			return $result;
	   		}

	   		return FALSE;
	   }

	   public function displayPublicidad($view, $model)
	   {
	   		$data = array();
	   		$this->CI->load->model('widgets/' . ucfirst($this->model) . '_model', $model);

	   		$result = $this->_getPublicidad($model);

	   		if ($result)
	   		{
	   			$data['publi'] = $result;
	   			return $this->render($view, $data);
	   		}

	   		return FALSE;
	    }

	    private function _getPublicidad($model)
	    {
	    	$result = $this->CI->$model->getPublicidad();

	    	if ($result)
	    	{
	    		return $result;
	    	}

	    	return FALSE;
	    }

	}