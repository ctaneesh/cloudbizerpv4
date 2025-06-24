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

class Apilogin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model', 'products_cat');
        $this->load->model('brand_model', 'brand');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(2)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->li_a = 'stock';
    }

    public function index()
    {
        
        $head['title'] = "API Login";
        $this->load->view('fixed/header', $head);
        $this->load->view('products/apilogin');
        $this->load->view('fixed/footer');
    }
    public function applogin()
    {
        
        $url = "https://cloudbizerp.com/devapp4/api/login";

        // Set your Basic Auth username and password
        $username = 'superadmin@cloudbizerp.com';  // Replace with your actual username
        $password = 'Superadmin@2025';  // Replace with your actual password

        // Initialize cURL session
        $ch = curl_init();

        $postData = [
            'email'    => $this->input->post('email'),
            'password' => $this->input->post('password'),
        ];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // or use json_encode() and set Content-Type: application/json
        // curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // only for testing
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // only for testing
        
        // Optional: verbose output
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Output response
        // echo "HTTP Status Code: $httpCode\n";
        // echo $response;
        
        // Output verbose log
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        // echo json_encode(array('HTTP' => $httpCode, 'response'=>$response,'verbos'=>$verboseLog));
        // echo "<pre>cURL Verbose Output:\n$verboseLog</pre>";

        echo json_encode([
        'HTTP' => $httpCode,
        'response' => $response, // <<< no json_encode here!
        'verbos' => $verboseLog
        ]);


        die();
    }



}
