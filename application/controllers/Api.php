<?php
/**
 * Cloud Biz Erp -  Accounting,  Invoicing  and CRM Software
 * Copyright (c) Cloud Biz Erp. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@cloudbizerp.com
 *  Website: https://www.cloudbizerp.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *   * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Aauth');
        $this->load->library('session');
        
        // If you want to protect all API endpoints except login
        // You might want to exclude the login method from this check
        if (!$this->aauth->is_loggedin() && $this->router->fetch_method() != 'login') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ]));
            exit;
        }
        
        $this->li_a = 'API';
    }

    public function login() 
    {
        // Set validation rules
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => 'error',
                    'errors' => $this->form_validation->error_array()
                ]));
            return;
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember'); 

        if ($this->aauth->apilogin($email, $password, $remember="")) {
            $this->load->model('employee_model', 'employee');
            $id = $this->aauth->get_user()->id;
            $details = $this->employee->employee_details($id);
            // $user = $this->aauth->get_user($this->aauth->get_user_id($email));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hour expiration
            $token = $this->generate_bearer_token();
            $this->db->insert('cberp_api_tokens', [
                'user_id' => $id,
                'token' => $token,
                'expires_at' => $expires_at,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'token' => $token,
                    'user' => [
                        'id' => $id,
                        'name' => $details['name'],
                        'email' => $email,
                        'username' => $details['username']
                        // Add other user fields as needed
                    ]
                ]));
        } else {
            // Login failed
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => "Invalid Username or Password"
                ]));
        }
    }
    
    public function generate_bearer_token() {
        return bin2hex(random_bytes(32)); 
    }
     public function web_login() {
        $auth_header = $this->input->get_request_header('Authorization');
        $token = str_replace('Bearer ', '', $auth_header);
        if($token)
        {
            $this->db->select('user_id');
            $this->db->from('cberp_api_tokens');
            $this->db->where('is_active', 1);
            $this->db->where('token', $token);
            $query = $this->db->get();
            $user_id ="";
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $user_id = $row->user_id;
            }
            if($user_id)
            {
                $this->load->model('employee_model', 'employee');
                $details = $this->employee->employee_details($id);
                $this->session->set_userdata("orgname", $details['name']);
                $this->aauth->applog("[Logged In] $user_id");
                configured_data();
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Redirect to dashboard'
                ]));
            }
            else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Redirect to login'
                ]));
            }           
           
        } 
        else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Redirect to login'
            ]));
        }
    }
    public function logout()
    {
        // Check Authorization Header
        $headers = $this->input->request_headers();
        if (!isset($headers['Authorization'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authorization token missing'
                ]));
        }

        $auth_header = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $auth_header);
        if ($token) {
            $this->db->where('token', $token)
                     ->update('cberp_api_tokens', ['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]));
    }
    
   
}
  