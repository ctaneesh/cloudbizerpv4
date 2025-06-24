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
        header('Access-Control-Allow-Origin: *');
    }
    public function forgot_password()
    {
       
        $url = base_url('api/forgot_password');
        $data['response'] = '';
        $email = $this->input->post('email');
        
        $out = $this->aauth->remind_password($email);
        if ($out) {
            $this->load->model('communication_model');

            $mailtoc = $out['email'];
            $mailtotilte = $out['username'];
            $subject = '[' . $this->config->item('ctitle') . '] Password Reset Link';
            $link = base_url('user/reset_pass?code=' . $out['vcode'] . '&email=' . $email);

            $message = "<h4>Dear $mailtotilte</h4>, <p>We have generated a password reset request for you. You can reset the password using following link.</p> <p><a href='$link'>$link</a></p><p>Reagrds,<br>Team " . $this->config->item('ctitle') . "</p>";
            $attachmenttrue = false;
            $attachment = '';
            $this->communication_model->send_email($mailtoc, $mailtotilte, $subject, $message, $attachmenttrue, $attachment);
            // return $this->output
            //     ->set_content_type('application/json')
            //     ->set_status_header(200)
            //     ->set_output(json_encode([
            //         'status' => 'success',
            //         'message' => 'Mail Sent Successfully'
            //     ]));
        } else {

           $response = json_encode([
                    'status' => 'error',
                    'message' => "Mail Not Sent"
           ]);
           $this->insert_to_logs($url,$response);
           $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output($response);
        }
    }
    public function login() 
    {
        // Set validation rules
        $url = base_url('api/login');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $response = json_encode([
                    'status' => 'error',
                    'errors' => $this->form_validation->error_array()
            ]);
            $this->insert_to_logs($url,$response);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output($response);
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
            $this->db->where('user_id', $id)->delete('cberp_api_tokens');
            $this->db->insert('cberp_api_tokens', [
                'user_id' => $id,
                'token' => $token,
                'expires_at' => $expires_at,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
              $response = json_encode([
                    'status' => 'success',
                    'token' => $token,
                    'user' => [
                        'id' => $id,
                        'name' => $details['name'],
                        'email' => $email,
                        'username' => $details['username']
                        // Add other user fields as needed
                    ]
                ]);             
            $this->insert_to_logs($url,$response);
            $this->output
                ->set_content_type('application/json')
                ->set_output($response);
        } 
        else {
            // Login failed
            $response = json_encode([
                    'status' => 'error',
                    'message' => "Invalid Username or Password"
            ]);
            $this->insert_to_logs($url,$response);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output($response);
        }
    }
    
    public function generate_bearer_token() {
        return bin2hex(random_bytes(32)); 
    }
     public function web_login() {
        // $auth_header = $this->input->get_request_header('Authorization');
        $url = base_url('api/web_login');
        $headers = $this->input->request_headers();
        $auth_header = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $auth_header);
       
        if($token)
        {
            $this->db->select('user_id');
            $this->db->from('cberp_api_tokens');
            $this->db->where('is_active', 1);
            $this->db->where('token', $token);
            $query = $this->db->get();
            
            $user_id ="";
            //check api token is valid
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
                $target_url = base_url().'user/apilogin?userid='.$user_id;
                $response = json_encode([
                    'status' => 'success',
                    'url' => $target_url,
                    'message' => 'Redirect to dashboard'
                ]);
                 $this->insert_to_logs($url,$response);
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output($response);
            }
            else{
                $response = json_encode([
                    'status' => 'error',
                    'token' => $token,
                    'message' => 'User ID is not found in api tokens'
                ]);
                $this->insert_to_logs($url,$response);
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output($response);
            }           
           
        }         
        else {
            $response = json_encode([
                    'status' => 'error',
                    'message' => 'Token Not Found'
            ]);
            $this->insert_to_logs($url,$response);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output($response);
        }
    }



    public function logout()
    {
        // Check Authorization Header
        $url = base_url('api/logout');
        $headers = $this->input->request_headers();
        if (!isset($headers['Authorization'])) {
            $response = json_encode([
                    'status' => 'error',
                    'message' => 'Authorization token missing'
            ]);
            $this->insert_to_logs($url,$response);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output($response);
        }

        $auth_header = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $auth_header);
        if ($token) {
            $this->db->where('token', $token)->delete('cberp_api_tokens');
        }
        $response = json_encode([
                'status' => 'success',
                'message' => 'Logged out successfully'
        ]);
        $this->insert_to_logs($url,$response);
        return $this->output
            ->set_content_type('application/json')
            ->set_output($response);
    }
    

    public function insert_to_logs($url,$response)
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP address from shared internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP address passed from a proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // IP address from the remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->db->insert('cberp_app_logs', [
            'api' => $url,
            'message' => $response,
            'date' => date('Y-m-d H:i:s'),
            'ip_address' => $ip
        ]);
    }

   
}
  