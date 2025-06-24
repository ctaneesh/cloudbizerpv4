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
        //for app
        //header("Access-Control-Allow-Headers: *"); // Allow all headers
        
         // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }
    public function forgot_password()
    {
                
        // if ($this->aauth->is_loggedin()) {
        //     redirect('/dashboard/', 'refresh');
        // }
        $url = base_url('api/forgot_password');
        $data['response'] = '';
        $email = $this->input->post('email');
        
        $out = $this->aauth->remind_password($email);
         $contentType = $this->input->get_request_header('Content-Type', TRUE);
         $headers = $this->input->request_headers();
        $this->insert_to_logs($url, json_encode($headers), 'Incoming Headers');
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
    public function login1() 
    {
         $url = base_url('api/login');
         $contentType = $this->input->get_request_header('Content-Type', TRUE);
         $headers = $this->input->request_headers();
         $this->insert_to_logs($url,http_build_query($headers),'Incoming');
         
        
          // Try JSON input first
        if (strpos($contentType, 'application/json') !== false) {
            $rawInput = file_get_contents('php://input');
            $postData = json_decode($rawInput, true);
        } else {
            // Fallback to regular POST
            $postData = $this->input->post();
            $rawInput = http_build_query($postData); // Convert array to string format
        }
        
        $this->insert_to_logs($url,$rawInput,'Incoming');
        // Set validation rules
       
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $postData = $this->input->post();
         // Get validated data
    $email = $postData['email'];
    $password = $postData['password'];
    $remember = isset($postData['remember']) ? $postData['remember'] : false;
        // $email = $this->input->post('email');
        // $password = $this->input->post('password');
        // $remember = $this->input->post('remember'); 
           

       
        
        
        
        // if ($this->form_validation->run() == FALSE) {
        //     $response = json_encode([
        //             'status' => 'error',
        //             'errors' => $this->form_validation->error_array()
        //     ]);
        //     $this->insert_to_logs($url,$response);
        //     $this->output
        //         ->set_content_type('application/json')
        //         ->set_status_header(422)
        //         ->set_output($response);
        //     return;
        // }

        
        

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
       // 1. Log incoming request headers
        $headers = $this->input->request_headers();
        $this->insert_to_logs($url, json_encode($headers), 'Incoming Headers');
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
                 $this->insert_to_logs($url,$response,'Redirect to dashboard');
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
                $this->insert_to_logs($url,$response,'User ID is not found');
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
            $this->insert_to_logs($url,$response,'Token Not Found');
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
         
        // 1. Log incoming request headers
        $headers = $this->input->request_headers();
        $this->insert_to_logs($url, json_encode($headers), 'Incoming Headers');
        
        
        if (!isset($headers['Authorization'])) {
            $response = json_encode([
                    'status' => 'error',
                    'message' => 'Authorization token missing'
            ]);
            $this->insert_to_logs($url,$response,'Authorization token missing');

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
        $this->insert_to_logs($url,$response,'Logout');
        return $this->output
            ->set_content_type('application/json')
            ->set_output($response);
    }
    

    public function insert_to_logs($url,$response,$apitype="")
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
            'api_type' => $apitype,
            'message' => $response,
            'date' => date('Y-m-d H:i:s'),
            'ip_address' => $ip
        ]);
    }
    public function login() 
{
    $url = base_url('api/login');
    
    // 1. Log incoming request headers
    $headers = $this->input->request_headers();
    $this->insert_to_logs($url, json_encode($headers), 'Incoming Headers');
    
    // 2. Process input based on content type
    $contentType = $this->input->get_request_header('Content-Type', TRUE);
    $rawInput = file_get_contents('php://input');
    
    $headersJson = json_encode($contentType); // Convert array to JSON string

        // Get the part before the first comma
    $firstPart = explode(',', $headersJson)[0];
     $this->insert_to_logs($url, $firstPart, 'Content-Type');
    if (strpos($contentType, 'application/json') !== false) {
        $postData = json_decode($rawInput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->insert_to_logs($url, 'Invalid JSON Input', 'Error');
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON format'
                ]));
        }
    } else {
        $postData = $this->input->post();
    }
    
    // 3. Log incoming data
    $this->insert_to_logs($url, json_encode($postData), 'Incoming Data');
    
    // 4. Validate input
    $this->form_validation->set_data($postData);
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
    
    if ($this->form_validation->run() == FALSE) {
        $errors = $this->form_validation->error_array();
         $response = json_encode([
                    'status' => 'error',
                    'message' => $errors
        ]);
        $this->insert_to_logs($url, $response, 'Validation Errors');
        return $this->output
            ->set_status_header(422)
            ->set_content_type('application/json')
            ->set_output($response);
    }
    
    // 5. Attempt login
    $email = $postData['email'];
    $password = $postData['password'];
    $remember = isset($postData['remember']) ? $postData['remember'] : false;
    
    if ($this->aauth->apilogin($email, $password, $remember)) {
        // 6. Handle successful login
        $this->load->model('employee_model', 'employee');
        $id = $this->aauth->get_user()->id;
        $details = $this->employee->employee_details($id);
        
        // Generate and store token
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $token = $this->generate_bearer_token();
        
        $this->db->where('user_id', $id)->delete('cberp_api_tokens');
        $this->db->insert('cberp_api_tokens', [
            'user_id' => $id,
            'token' => $token,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Prepare success response
        $response = [
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $id,
                'name' => $details['name'],
                'email' => $email,
                'username' => $details['username']
            ]
        ];
        
        $this->insert_to_logs($url, json_encode($response), 'Login Success');
        // header('Content-Type: application/json');
        // echo json_encode($response);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    // 7. Handle failed login
    $response = json_encode([
                    'status' => 'error',
                    'message' => "Invalid Username or Password"
        ]);
    $this->insert_to_logs($url, $response, 'Login Failed');
    return $this->output
        ->set_status_header(401)
        ->set_content_type('application/json')
        ->set_output( $response);
}

private function handle_successful_login($email, $log_url) 
{
    $this->load->model('employee_model', 'employee');
    $id = $this->aauth->get_user()->id;
    $details = $this->employee->employee_details($id);
    
    // Generate token
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $token = $this->generate_bearer_token();
    
    // Store token (replace existing if any)
    $this->db->where('user_id', $id)->delete('cberp_api_tokens');
    $this->db->insert('cberp_api_tokens', [
        'user_id' => $id,
        'token' => $token,
        'expires_at' => $expires_at,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    
    // Prepare success response
    $response = [
        'status' => 'success',
        'token' => $token,
        'user' => [
            'id' => $id,
            'name' => $details['name'],
            'email' => $email,
            'username' => $details['username']
        ]
    ];
    
    $this->insert_to_logs($log_url, json_encode($response), 'Login Success');
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

private function return_validation_errors($log_url) 
{
    $response = [
        'status' => 'error',
        'errors' => $this->form_validation->error_array()
    ];
    
    $this->insert_to_logs($log_url, json_encode($response), 'Validation Errors');
    $this->output
        ->set_content_type('application/json')
        ->set_status_header(422)
        ->set_output(json_encode($response));
}

private function return_error_response($status_code, $message, $log_url) 
{
    $response = [
        'status' => 'error',
        'message' => $message
    ];
    
    $this->insert_to_logs($log_url, json_encode($response), 'Login Failed');
    $this->output
        ->set_content_type('application/json')
        ->set_status_header($status_code)
        ->set_output(json_encode($response));
}
    
   
}
  