<?php

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->users_id : '1';
    }

    /**
     * This function is used authenticate user at login
     */

     

    function auth_user()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $this->db->select('users.*, cberp_customers.picture');
        $this->db->from('users');
        $this->db->where('users.is_deleted', '0');
        $this->db->where('users.email', $email);
        $this->db->join('cberp_customers', 'users.cid = cberp_customers.customer_id', 'left');
        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            if (password_verify($password, $result[0]->password)) {
                if ($result[0]->status != 'active') {
                    return 'not_varified';
                }
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * This function is used to delete user
     * @param: $id - id of user table
     */
    function delete($id = '')
    {
        $this->db->where('users_id', $id);
        $this->db->delete('users');
    }

    /**
     * This function is used to load view of reset password and varify user too
     */
    function mail_varify()
    {
        $ucode = $this->input->get('code');
        $this->db->select('email as e_mail');
        $this->db->from('users');
        $this->db->where('var_key', $ucode);
        $query = $this->db->get();
        $result = $query->row();
        if (!empty($result->e_mail)) {
            return $result->e_mail;
        } else {
            return false;
        }
    }


    /**
     * This function is used Reset password
     */
    function ResetPpassword()
    {
        $code = $this->input->post('n_code');
        if ($this->input->post('n_password') == $this->input->post('n_password2')) {
            $npass = password_hash($this->input->post('n_password'), PASSWORD_DEFAULT);
            $data['password'] = $npass;
            $data['code'] = '';
            return $this->db->update('users', $data, "code = '$code'");
        }
    }

    /**
     * This function is used to select data form table
     */
    function get_data_by($tableName = '', $value = '', $colum = '', $condition = '')
    {
        if ((!empty($value)) && (!empty($colum))) {
            $this->db->where($colum, $value);
        }
        $this->db->select('*');
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to check user is alredy exist or not
     */
    function check_exists($table = '', $colom = '', $colomValue = '')
    {
        $this->db->where($colom, $colomValue);
        $res = $this->db->get($table)->row();
        if (!empty($res)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function is used to get users detail
     */
    function get_users($userID = '')
    {
        $this->db->select('users.*,cberp_customers.picture');
        $this->db->from('users');
        $this->db->where('users.is_deleted', '0');
        if (isset($userID) && $userID != '') {
            $this->db->where('users.users_id', $userID);
        } else if ($this->session->userdata('user_details')[0]->user_type == 'admin') {
            $this->db->where('users.user_type', 'admin');
        } else {
            $this->db->where('users.users_id !=', '1');
        }

        $this->db->join('cberp_customers', 'users.cid = cberp_customers.customer_id', 'left');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * This function is used to get email template
     */
    function get_template($code)
    {
        $this->db->where('code', $code);
        return $this->db->get('templates')->row();
    }

    /**
     * This function is used to Insert record in table
     */
    public function insertRow($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * This function is used to Update record in table
     */
    public function updateRow($table, $col, $colVal, $data)
    {
        $this->db->where($col, $colVal);
        $this->db->update($table, $data);
        return true;
    }

        public function template_info($id)
    {
        $this->db->from('univarsal_api');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row_array();
    }

        function get_data_by_row($tableName = '', $colum = '',  $value = '',$condition = '')
    {

        $this->db->select('*');
        $this->db->from($tableName);
        $this->db->where($colum, $value);
        $query = $this->db->get();
        return $query->row_array();
    }

        function get_users_full($userID = '')
    {
        $this->db->select('users.*,cberp_customers.*,cberp_country.name as country_name');
        $this->db->from('users');
        $this->db->where('users.is_deleted', '0');
        if (isset($userID) && $userID != '') {
            $this->db->where('users.users_id', $userID);
        } else if ($this->session->userdata('user_details')[0]->user_type == 'admin') {
            $this->db->where('users.user_type', 'admin');
        } else {
            $this->db->where('users.users_id !=', '1');
        }

        $this->db->join('cberp_customers', 'users.cid = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country', 'left');
        $result = $this->db->get()->row_array();
        return $result;
    }


}