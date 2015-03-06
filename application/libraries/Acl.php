<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl
{
    private $CI;
    private $tables = array(
        'users' => 'users',
        'roles' => 'roles',
        'perms' => 'permissions',
        'user_perms' => 'user_permissions',
        'role_perms' => 'role_permissions'
    );
    private $user_id;
    private $user_role_id;
    private $user_permissions;
    private $user_site_permissions;

    public function __construct($options = array())
    {
        $this->CI =& get_instance();
        $this->CI->load->config('acl');

        $this->user_id = isset($options['id']) ? (int)$options['id'] : 0;

        if($this->user_id > 0) {
            // carga role de usuario
            $user_role = $this->CI->db->select('role')->get_where($this->tables['users'], array('id' => $this->user_id))->row();

            // setear id de role
            $this->user_role_id = isset($user_role->role) ? (int)$user_role->role : 0;
        }

        // setear el lenguaje
        $this->_set_language(isset($options['lang']) ? $options['lang'] : null);

        // setear permisos de usuario
        $this->user_permissions = array_merge($this->role_permissions(), $this->user_permissions());

        // setear permisos del sitio
        $this->user_site_permissions = $this->_permissions('acl_site_permissions', 'public');
    }

    public function __get($name)
    {
        $property = 'user_' . $name;

        if(isset($this->$property)) {
            return $this->$property;
        }
    }

    public function role_permissions_ids()
    {
        $ids = array();

        if($this->user_role_id > 0) {
            $perms = $this->CI->db
                    ->select('permission')
                    ->get_where($this->tables['role_perms'], array('role' => $this->user_role_id))
                    ->result_array();

            $ids = array_map(function($item){
                return $item['permission'];
            }, $perms);

            array_filter($perms);
        }

        return $ids;
    }

    public function role_permissions()
    {
        if($this->user_role_id > 0) {
            $permissions = $this->CI->db
                    ->from($this->tables['role_perms'] . ' r')
                    ->select(['r.permission', 'r.value', 'p.name', 'p.title'])
                    ->join($this->tables['perms'] . ' p', 'r.permission = p.id')
                    ->where(array('r.role' => $this->user_role_id))
                    ->get()->result();

            if(sizeof($permissions) > 0) {
                $data = array();

                foreach($permissions as $permission) {
                    if(trim($permission->name) == '') continue;

                    $data[$permission->name] = array(
                        'permission' => $permission->name,
                        'title' => $permission->title,
                        'value' => $permission->value == 1 ? TRUE : FALSE,
                        'inherited' => TRUE,
                        'id' => $permission->permission,
                    );
                }

                if(sizeof($data)) {
                    return $data;
                }
            }
        }

        return $this->_permission('public');
    }

    public function user_permissions()
    {
        $data = array();

        if($this->user_id > 0 && $this->user_role_id > 0) {
            $ids = $this->role_permissions_ids();

            if(sizeof($ids) > 0) {
                $permissions = $this->CI->db
                    ->from($this->tables['user_perms'] . ' u')
                    ->select(array('u.permission', 'u.value', 'p.name', 'p.title'))
                    ->join($this->tables['perms'] . ' p', 'u.permission = p.id')
                    ->where(array('u.user' => $this->user_id))
                    ->where_in('u.permission', $ids)
                    ->get()->result();

                if(sizeof($permissions) > 0) {
                    foreach($permissions as $permission) {
                        if(trim($permission->name) == '') continue;

                        $data[$permission->name] = array(
                            'permission' => $permission->name,
                            'title' => $permission->title,
                            'value' => $permission->value == 1 ? TRUE : FALSE,
                            'inherited' => FALSE,
                            'id' => $permission->permission,
                        );
                    }
                }
            }
        }

        return $data;
    }

    public function has_permission($name)
    {
        if(array_key_exists($name, $this->user_permissions)) {
            if($this->user_permissions[$name]['value'] == TRUE) {
                return TRUE;
            }
        }

        return FALSE;
    }

    private function _permissions($line, $default)
    {
        $permissions = $this->CI->config->item($line);

        $result = array();

        if(is_array($permissions) && sizeof($permissions) > 0) {
            foreach($permissions as $permission) {
                if($this->has_permission($permission) === TRUE) {
                    $result[] = $permission;
                }
            }
        }

        if(sizeof($result) == 0) {
            $result[] = $default;
        }

        return $result;
    }

    private function _permission($name)
    {
        $name = trim($name);
        //echo $name; exit;

        if( ! empty($name)) {
            $permission = $this->CI->db->get_where($this->tables['perms'], array('name' => $name))->row();

            if(sizeof($permission) > 0) {
                return [$permission->name = [
                            'permission' => $permission->name,
                            'title' => $permission->title,
                            'value' => TRUE,
                            'inherited' => TRUE,
                            'id' => $permission->id
                        ]];
            }
        }

        show_error($this->CI->lang->line('acl_error_permission_not_found'));
    }

    private function _set_language($lang = null)
    {
        $languages = array('english', 'spanish');

        if(!$lang) {
            if(in_array($this->CI->config->item('language'), $languages)) {
                $lang = $this->CI->config->item('language');
            } else {
                $lang = $languages[0];
            }
        } else {
            if( ! in_array($lang, $languages)) {
                $lang = $languages[0];
            }
        }

        $this->CI->load->language('acl', $lang);
    }
}

/* End of file Acl.php */
/* Location: ./application/libraries/Acl.php */