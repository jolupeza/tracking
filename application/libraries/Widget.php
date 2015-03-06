<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widget
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    protected function render($view, $data = array())
    {
        return $this->CI->load->view($view, $data, TRUE);
    }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */