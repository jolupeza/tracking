<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/Acl.php';

class User
{
    private $CI;
    private $table = 'users';
    private $lang;
    private $acl;
    private $errors = array();
    private $user_id;
    private $user_user;
    private $user_name;
    private $user_email;
    private $user_status;
    private $user_active;
    private $user_role;
    private $user_avatar;
    private $pattern = "/^([-a-z0-9\._-])+$/i";
    //private $pattern = "/^([-a-z0-9_-])+$/i";

    public function __construct($options = array())
    {
        $this->CI =& get_instance();

        $this->_set_language(isset($options['lang']) ? $options['lang'] : null);

        $row = null;

        if(isset($options['id']) && (int)$options['id'] > 0) {
            $row = $this->_row(array('id' => (int)$options['id']));

            if(sizeof($row) == 0) {
                show_error($this->CI->lang->line('user_error_invalid_user'));
            }
        } elseif((int) $this->CI->session->userdata('user_id') > 0) {
            $row = $this->_row(array('id' => $this->CI->session->userdata('user_id')));

            if(sizeof($row) == 0 || $row->active != 1 || $row->status != 1) {
                $this->CI->session->sess_destroy();
                $this->_load(null);
                return;
            }
        }

        $this->_load($row);
    }

    public function __get($name)
    {
        $property = 'user_' . $name;

        if(isset($this->$property)) {
            return $this->$property;
        }
    }

    public function token()
    {
        $token = md5(uniqid(rand(), true));
        $this->CI->session->set_userdata('token', $token);
        return $token;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function permissions()
    {
        return $this->acl->permissions;
    }

    public function site_permissions()
    {
        return $this->acl->site_permissions;
    }

    public function has_permission($name)
    {
        return $this->acl->has_permission($name);
    }

    public function is_logged_in()
    {
        if($this->user_id > 0) {
            return $this->user_id == (int) $this->CI->session->userdata('user_id');
        }

        return FALSE;
    }

    public function login($user, $password, $hash = 'sha256')
    {
        if(empty($user) || !preg_match($this->pattern, $user)) {
            $this->errors[] = $this->CI->lang->line('user_error_username');
        }

        if(empty($password)) {
            $this->errors[] = $this->CI->lang->line('user_error_empty_password');
        }

        if(count($this->errors)) {
            return FALSE;
        }

        $this->CI->load->library('encrypt');

        $row = $this->_row(array('user' => $user, 'password' => $this->CI->encrypt->password($password, $hash)));

        if(sizeof($row) == 0 || $row->active != 1 || $row->status != 1) {
            $this->errors[] = $this->CI->lang->line('user_error_wrong_credentials');
            return FALSE;
        }

        $this->_load($row);
        return TRUE;
    }

    public function loginGeneral($email, $password, $hash = 'sha256', $role = 0)
    {
        if(empty($email)) {
            $this->errors[] = $this->CI->lang->line('user_error_email');
        }

        if(empty($password)) {
            $this->errors[] = $this->CI->lang->line('user_error_empty_password');
        }

        if(count($this->errors)) {
            return FALSE;
        }

        $this->CI->load->library('encrypt');

        if ((int)$role > 0) {
            $row = $this->_row(array('email' => $email, 'password' => $this->CI->encrypt->password($password, $hash), 'role' => $role));
        } else {
            $row = $this->_row(array('email' => $email, 'password' => $this->CI->encrypt->password($password, $hash)));
        }


        if(sizeof($row) == 0 || $row->active != 1 || $row->status != 1) {
            $this->errors[] = $this->CI->lang->line('user_error_wrong_credentials');
            return FALSE;
        }

        $this->_load($row);
        return TRUE;
    }

    private function _load($row = null)
    {
        if($row == null || sizeof($row) == 0) {
            $this->user_id = 0;
            $this->user_user = $this->CI->lang->line('cms_general_label_site_visitor_user');
            $this->user_name = $this->CI->lang->line('cms_general_label_site_visitor_name');
            $this->user_email = '';
            $this->user_active = 0;
            $this->user_status = 0;
            $this->user_role = 0;
            $this->user_avatar = '';
            $this->acl = new Acl(array('lang' => $this->lang));

            return;
        }

        $this->user_id = $row->id;
        $this->user_user = $row->user;
        $this->user_name = $row->name;
        $this->user_email = $row->email;
        $this->user_active = $row->active;
        $this->user_status = $row->status;
        $this->user_role = $row->role;
        $this->user_avatar = $row->avatar;
        $this->acl = new Acl(array('id' => $row->id,'lang' => $this->lang));
    }

    private function _row($where = null, $select = null)
    {
        if(is_array($where)) {
            $this->CI->db->where($where);
        }

        if(is_array($select)) {
            $this->CI->db->select($select);
        }

        return $this->CI->db->get($this->table)->row();
    }

    private function _set_language($lang = null)
    {
        $languages = array('english', 'spanish');

        if( !$lang) {
            if(in_array($this->CI->config->item('language'), $languages)) {
                $lang = $this->CI->config->item('language');
            } else {
                $lang = $languages[0];
            }
        } else {
            if( !in_array($lang, $languages)) {
                $lang = $languages[0];
            }
        }

        $this->lang = $lang;
        $this->CI->load->language('user', $lang);
    }
}

/* End of file User.php */
/* Location: ./application/libraries/User.php */