<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
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

class Customers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customers_model', 'customers');
        $this->load->library("Aauth");
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');        
        $this->load->model('country_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        // if (!$this->aauth->premission(3)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->load->library("Custom");
        $this->li_a = 'crm';
    }

    public function index()
    {
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Customers';
        $data['permissions'] = load_permissions('CRM','Customers','Manage Customers');
       ;
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/clist',$data);
        $this->load->view('fixed/footer');
    }

    public function create()
    {
        $customer_id = $this->input->get('id');
        $this->load->library("Common");
        $data['salesmanlist'] = $this->customers->saleman_list(); 
        $data['langs'] = $this->common->languages();        
        $data['countries'] = $this->country_model->country_list(); 
        $data['customergrouplist'] = $this->customers->group_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['customerid'] = "";
        if($customer_id)
        {            
            $data['customerid'] = $customer_id;
            $data['customer'] = $this->customers->customer_details_by_id($customer_id);
            $data['customergroup'] = $this->customers->group_info($data['customer']['customer_group_id']);
            $data['permissions'] = load_permissions('CRM','Customers','Manage Customers','View Page');            
            $head['title'] = 'Edit Customer';           
            $page = "customer";
            $data['detailed_log']= get_detailed_logs($customer_id,$page);
            $histories = $data['detailed_log'];
            $groupedBySequence = [];

            foreach ($histories as $history) {
                $sequence = $history['seqence_number'];
                $groupedBySequence[$sequence][] = $history; 
            }
            
            $data['groupedDatas'] = $groupedBySequence;
        }
        else{
            load_assigned_permissions();
            $data['permissions'] = load_permissions('CRM','Customers','New Customer');
            $head['title'] = 'Create Customer';        
        }       
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/create', $data);
        $this->load->view('fixed/footer');
    }

    public function view()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $custid = $this->input->get('id');
        $data['details'] = $this->customers->customer_details_by_id($custid);
        $data['id'] = $custid;
        $data['customergroup'] = $this->customers->group_info($data['details']['customer_group_id']);
        $data['money'] = $this->customers->money_details($custid);
        $data['due'] = $this->customers->due_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['activity'] = $this->customers->activity($custid);
        $data['custom_fields'] = $this->custom->view_fields_data($custid, 1);
        $head['title'] = 'View Customer';
        // $data['log'] = $this->customers->gethistory($custid);
        $data['permissions'] = load_permissions('CRM','Customers','Manage Customers','View Page');
        
        //erp2024 06-01-2025 detailed history log starts
        $page = "customer";
        $data['detailed_log']= get_detailed_logs($custid,$page);
        $loadhistory = $data['detailed_log'];
        $groupedBySequence = []; 
         foreach ($loadhistory as $product) {
             $sequence = $product['seqence_number'];
             $groupedBySequence[$sequence][] = $product; 
         }
         $data['groupedDatas'] = $groupedBySequence;
          $this->load->view('fixed/header', $head);
        $this->load->view('customers/view', $data);
        $this->load->view('fixed/footer');
    }

    public function load_list()
    {
        $no = $this->input->post('start');
       
        $permissions = load_permissions('CRM','Customers','Manage Customers');
        $functions = array_column($permissions, 'function');
        $editcls = !in_array('Edit', $functions) ? 'd-none' : '';
        $deletecls = !in_array('Delete', $functions) ? 'd-none' : '';
        $linkcls = !in_array('Link', $functions) ? '' : 'Linked';
        // echo "<pre>"; print_r($linkcls); print_r($functions); die();
        $list = $this->customers->get_datatables();
     
        $data = array();
        if ($this->input->post('due')) {
            foreach ($list as $customers) {
                $no++;
                $row = array();
                $row[] = $no . ' <div class="chkbox"></div><input type="checkbox" name="cust[]" class="checkbox" value="' . $customers->customer_id . '"></div> ';
                $row[] = '<div class="text-center"><img class="rounded-circle1" src="' . base_url() . 'userfiles/customers/' . $customers->picture . '" style="width:50px; height:50px;"></div>';                
                $row[] = ($linkcls) ?'<a href="'. base_url().'customers/view?id=' . $customers->customer_id . '">' . $customers->name . '</a>': $customers->name ;                
                $row[] = number_format($customers->total - $customers->pamnt,2);
                $row[] = $customers->address . ', ' . $customers->city . ', ' . $customers->country;
                $row[] = $customers->email;
                $row[] = $customers->phone;
                $row[] = (!empty($customers->expiry_date)) ?date('d-m-Y', strtotime($customers->expiry_date)):"";
                $row[] = $customers->salesman;
                $row[] = $customers->credit_limit." / ".$customers->avalable_credit_limit;
                $row[] = $customers->status;
                $row[] = '<a href="'. base_url().'customers/create?id=' . $customers->id . '" class="btn btn-secondary btn-sm '.$editcls.'"><span class="fa fa-pencil" title="Edit"></span>  </a> <a href="#" data-object-id="' . $customers->id . '" class="btn btn-secondary btn-sm delete-object '.$deletecls.'" title="Delete"><span class="fa fa-trash"></span></a>';
                $data[] = $row;
            }
        } else {
            foreach ($list as $customers) {
                $no++;
                $row = array();
                $row[] = $customers->id . ' <div class="chkbox"><input type="checkbox" name="cust[]" class="checkbox" value="' . $customers->id . '"></div>';
                $row[] = '<div class="text-center"><img class="rounded-circle1" src="' . base_url() . 'userfiles/customers/' . $customers->picture . '" style="width:50px; height:50px;"></div>';

                $row[] = ($linkcls) ?'<a href="'. base_url().'customers/view?id=' . $customers->customer_id . '">' . $customers->name . '</a>': $customers->name ;          

                $row[] = $customers->address . ', ' . $customers->city . ', ' . $customers->country;
                $row[] = $customers->email;
                $row[] = $customers->phone;
                $row[] = (!empty($customers->expiry_date)) ?date('d-m-Y', strtotime($customers->expiry_date)):"";
                $row[] = $customers->salesman;
                $row[] = $customers->credit_limit." / ".$customers->avalable_credit_limit;    
                $row[] = $customers->status;
                $row[] = ' </a> <a href="'. base_url().'customers/create?id='  . $customers->customer_id . '" class="btn btn-secondary btn-sm '.$editcls.'"><span class="fa fa-pencil"  title="Edit"></span></a> <a href="#" data-object-id="' . $customers->id . '" class="btn btn-secondary btn-sm delete-object '.$deletecls.'" title="Delete"><span class="fa fa-trash"></span></a>';
                // $row[] = '<a href="'. base_url().'customers/view?id=' . $customers->customer_id . '" class="btn btn-secondary btn-sm" title="View"><span class="fa fa-eye"></span>  </a> <a href="edit?id=' . $customers->id . '" class="btn btn-secondary btn-sm"><span class="fa fa-pencil"  title="Edit"></span></a> <a href="#" data-object-id="' . $customers->id . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
                $data[] = $row;
            }
        }


        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->count_all(),
            "recordsFiltered" => $this->customers->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    //edit section
    public function edit()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $this->load->library("Common");
        $pid = $this->input->get('id');
        $data['customer'] = $this->customers->details($pid);
        $data['customergroup'] = $this->customers->group_info($data['customer']['customer_group_id']);
        $data['permissions'] = load_permissions('CRM','Customers','Manage Customers','View Page');
        $data['customergrouplist'] = $this->customers->group_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['custom_fields'] = $this->custom->view_edit_fields($pid, 1);
        $data['salesmanlist'] = $this->customers->saleman_list();
        $head['title'] = 'Edit Customer';
        $data['langs'] = $this->common->languages();        
        $data['countries'] = $this->country_model->country_list();
        $page = "customer";
        $data['detailed_log']= get_detailed_logs($pid,$page);
        $products = $data['detailed_log'];
        $groupedBySequence = []; // Initialize an empty array for grouping

        foreach ($products as $product) {
            $sequence = $product['seqence_number'];
            $groupedBySequence[$sequence][] = $product; // Group by sequence number
        }
        
        $data['groupedDatas'] = $groupedBySequence;
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/edit', $data);
        $this->load->view('fixed/footer');
    }

    public function addcustomer()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        // erp2024 modified 03-06-2024
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('phone', 'phone', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');

        // Run validation
        if ($this->form_validation->run() == FALSE) {            
            echo json_encode(array('status' => 'Error', 'message' => 'Enter Required Fields'));
            die();
        }
        $config['upload_path'] = FCPATH . 'userfiles/customers/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2048; 
        $this->load->library('upload', $config);
        $files = ['computer_card_image', 'sponser_image', 'picture'];
        $file_data = [];  
        if($files) 
        {
            foreach ($files as $file) {
                if (!$this->upload->do_upload($file)) {
                    continue;
                } else {
                    $file_data[$file] = $this->upload->data();
                }
            }
        }
            
        // $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : '';
        // $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : '';
        // $profile_pic = isset($file_data['picture']) ? $file_data['picture']['file_name'] : 'example.png';
        // erp2024 modified 03-06-2024

 
        $billing_data = [
            'billing_name' => $this->input->post('billing_name', true),
            'billing_contact_person' => $this->input->post('billing_contact_person', true),
            'billing_phone' => $this->input->post('billing_phone', true),
            'billing_email' => $this->input->post('billing_email', true),
            'billing_address_1' => $this->input->post('billing_address_1', true),
            'billing_address_2' => $this->input->post('billing_address_2', true),
            'billing_city' => $this->input->post('billing_city', true),
            'billing_region' => $this->input->post('billing_region', true),
            'billing_country' => $this->input->post('billing_country', true),
            'billing_postal_code' => $this->input->post('billing_postal_code', true),
        ];


        $shipping_data = [
            'shipping_name' => $this->input->post('shipping_name', true),
            'shipping_contact_person' => $this->input->post('shipping_contact_person', true),
            'shipping_phone' => $this->input->post('shipping_phone', true),
            'shipping_email' => $this->input->post('shipping_email', true),
            'shipping_address_1' => $this->input->post('shipping_address_1', true),
            'shipping_address_2' => $this->input->post('shipping_address_2', true),
            'shipping_city' => $this->input->post('shipping_city', true),
            'shipping_region' => $this->input->post('shipping_region', true),
            'shipping_country' => $this->input->post('shipping_country', true),
            'shipping_postal_code' => $this->input->post('shipping_postal_code', true),
        ];

        $customerid = $this->input->post('customerid', true);
        if(empty($customerid))
        {
            $computer_card_image_text="";
            $sponser_image_text="";
            $picture_text="example.png";
        }
        else{
            detailed_log_history('customer',$customerid,'Updated', $_POST['changedFields']);
        }
        if($customerid && empty($file_data['computer_card_image']['file_name']))
        {
            $computer_card_image_text = $this->input->post('computer_card_image_text', true);
        }
        if($customerid && empty($file_data['sponser_image']['file_name']))
        {
            $sponser_image_text = $this->input->post('sponser_image_text', true);
        }
        if($customerid && empty($file_data['picture']['file_name']))
        {
            $picture_text = $this->input->post('picture_text', true);
        }
        $master_data = [
            'name' => $this->input->post('name', true),
            'company' => $this->input->post('company', true),
            'phone' => $this->input->post('phone', true),
            'email' => $this->input->post('email', true),
            'address' => $this->input->post('address', true),
            'city' => $this->input->post('city', true),
            'region' => $this->input->post('region', true),
            'country' => $this->input->post('country', true),
            'postbox' => $this->input->post('postbox', true),
            'tax_id' => $this->input->post('tax_id', true),
            'language' => $this->input->post('language', true),
            // 'password' => $this->input->post('password_c', true),
            'document_id' => $this->input->post('document_id', true),
            // 'custom' => $this->input->post('c_field', true),
            'discount' => $this->input->post('discount', true),
            'customer_group_id' => $this->input->post('customer_group_id', true),

            // ERP2024 new fields
            'registration_number' => $this->input->post('registration_number', true),
            'expiry_date' => $this->input->post('expiry_date', true),
            'computer_card_number' => $this->input->post('computer_card_number', true),
            'sponser_id' => $this->input->post('sponser_id', true),
            'credit_limit' => $this->input->post('credit_limit', true),
            'credit_period' => $this->input->post('credit_period', true),
            'contact_person' => $this->input->post('contact_person', true),
            'land_line' => $this->input->post('land_line', true),
            'contact_phone1' => $this->input->post('contact_phone1', true),
            'contact_phone2' => $this->input->post('contact_phone2', true),
            'contact_email1' => $this->input->post('contact_email1', true),
            'contact_email2' => $this->input->post('contact_email2', true),
            'contact_designation' => $this->input->post('contact_designation', true),
            'status' => $this->input->post('status', true),
            'salesman_id' => $this->input->post('salesman_id', true),
            'computer_card_image' => isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : $computer_card_image_text,
            'sponser_image' => isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : $sponser_image_text,
            'picture' => isset($file_data['picture']) ? $file_data['picture']['file_name'] : $picture_text,
        ];
      
        if($file_data['computer_card_image']['file_name'])

          $login_data = [
            'create_login' => $this->input->post('c_login', true),
            'password' => $this->input->post('password_c', true)
          ];

        // $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : '';
        // $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : '';
        // $profile_pic = isset($file_data['picture']) ? $file_data['picture']['file_name'] : 'example.png';
        //erp2024 newly added field 09-10-2024
       
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

       
        $response = $this->customers->add($master_data, $billing_data, $shipping_data,$login_data,$customerid);        
       
        //erp2024 new fields customer table
        echo $response;

    }

    function sendSelected()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        if ($this->input->post('cust')) {
            $ids = $this->input->post('cust');

            $subject = $this->input->post('subject', true);
            $message = $this->input->post('text');
            $attachmenttrue = false;
            $attachment = '';
            $recipients = $this->customers->recipients($ids);
            $this->load->model('communication_model');
            $this->communication_model->group_email($recipients, $subject, $message, $attachmenttrue, $attachment);
        }
    }

    function sendSmsSelected()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }

        if ($this->input->post('cust')) {
            $ids = $this->input->post('cust');
            $message = $this->input->post('message', true);
            $recipients = $this->customers->recipients($ids);
            $this->config->load('sms');
            $this->load->model('sms_model');
            foreach ($recipients as $row) {

                $this->sms_model->send_sms($row['phone'], $message);

            }
        }
    }

    public function editcustomer()
    {

        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // echo '</pre>';
        // echo "here";
        // erp2024 modified 03-06-2024
        $config['upload_path'] = FCPATH . 'userfiles/customers/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 2048; 
        $this->load->library('upload', $config);
        $files = ['computer_card_image', 'sponser_image'];
        $file_data = [];   
        foreach ($files as $file) {
            if (!$this->upload->do_upload($file)) {
                continue;
            } else {
                $file_data[$file] = $this->upload->data();
            }
        }
        $computer_card_image = isset($file_data['computer_card_image']) ? $file_data['computer_card_image']['file_name'] : '';
        $sponser_image = isset($file_data['sponser_image']) ? $file_data['sponser_image']['file_name'] : '';
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $customer_id = $this->input->post('customer_id');
       
        $name = $this->input->post('name', true);
        $company = $this->input->post('company', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $address = $this->input->post('address', true);
        $city = $this->input->post('city', true);
        $region = $this->input->post('region', true);
        $country = $this->input->post('country', true);
        $postbox = $this->input->post('postbox', true);
        $customergroup = $this->input->post('customergroup', true);
        $tax_id = $this->input->post('tax_id', true);
        $shipping_name = $this->input->post('shipping_name', true);
        $shipping_phone = $this->input->post('shipping_phone', true);
        $shipping_email = $this->input->post('shipping_email', true);
        $shipping_address_1 = $this->input->post('shipping_address_1', true);
        $shipping_city = $this->input->post('shipping_city', true);
        $shipping_region = $this->input->post('shipping_region', true);
        $shipping_country = $this->input->post('shipping_country', true);
        $shipping_postbox = $this->input->post('shipping_postbox', true);
        $document_id = $this->input->post('document_id', true);
        $custom = $this->input->post('c_field', true);
        $language = $this->input->post('language', true);
        $discount = $this->input->post('discount', true);

        // erp2024 new fields customer table 03-06-2024
        $registration_number = $this->input->post('registration_number', true);
        $expiry_date = $this->input->post('expiry_date', true);
        $computer_card_number = $this->input->post('computer_card_number', true);
        $sponser_id = $this->input->post('sponser_id', true);
        $credit_limit = $this->input->post('credit_limit', true);
        $credit_period = $this->input->post('credit_period', true);        
        $contact_person = $this->input->post('contact_person', true);
        $land_line = $this->input->post('land_line', true);
        $contact_phone1 = $this->input->post('contact_phone1', true);
        $contact_phone2 = $this->input->post('contact_phone2', true);
        $contact_email1 = $this->input->post('contact_email1', true);
        $contact_email2 = $this->input->post('contact_email2', true); 
        $contact_designation = $this->input->post('contact_designation', true); 
        //erp2024 new fields customer table  03-06-2024     

        $status = $this->input->post('status', true); 
        $salesman_id = $this->input->post('salesman_id', true); 
        // echo $salesman_id; die();
        //erp2024 new parameters 03-06-2024         
        //$response = json_encode(array('status' => 'Error', 'message' => 'Somthing went wrong'));  
        if ($customer_id) {
            // log_table_data('cberp_customers','cberp_customers_log', 'id' ,'customer_id','Update',$id);

            //erp2024 07-01-2025 detailed history log starts
            detailed_log_history('customer',$customer_id,'Updated', $_POST['changedFields']);
            //erp2024 07-01-2025 detailed history log ends 
            //********* */
 
            $response = $this->customers->edit($customer_id, $name, $company, $phone, $email, $address, $city, $region, $country, $postbox, $customergroup, $tax_id, $shipping_name, $shipping_phone, $shipping_email, $shipping_address_1, $shipping_city, $shipping_region, $shipping_country, $shipping_postbox, $document_id, $custom, $language, $discount, $registration_number, $expiry_date, $computer_card_number, $sponser_id,  $credit_limit, $credit_period, $computer_card_image, $sponser_image, $contact_person, $land_line, $contact_phone1, $contact_phone2, $contact_email1, $contact_email2, $contact_designation,$status,$salesman_id);
            echo $response;
        }
       
    }

    public function changepassword()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }


        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $password = $this->input->post('password', true);
            if ($id) {
                $this->customers->changepassword($id, $password);
            }
        } else {
            $pid = $this->input->get('id');
            $data['customer'] = $this->customers->customer_details_by_id($pid);
            $data['customergroup'] = $this->customers->group_info($pid);
            $data['customergrouplist'] = $this->customers->group_list();
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Change Customer Password';
            $this->load->view('fixed/header', $head);
            $this->load->view('customers/edit_password', $data);
            $this->load->view('fixed/footer');
        }
    }


    public function delete_i()
    {
        // if ($this->aauth->premission(11)) {

            $id = $this->input->post('cust');
            // if ($id > 1) {
            //     if ($this->customers->delete($id)) {
            //         echo json_encode(array('status' => 'Success', 'message' => 'Customer details deleted Successfully!'));
            //     } else {
            //         echo json_encode(array('status' => 'Error', 'message' => 'Error!'));
            //     }
            // } else 
            if ($this->input->post('cust')) {
                $customers = $this->input->post('cust');
                foreach ($customers as $row) {
                    $this->customers->delete($row);
                }
                echo json_encode(array('status' => 'Success', 'message' => 'Customer details deleted Successfully!'));
            }
        // } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //         $this->lang->line('ERROR')));
        // }
    }

    public function displaypic()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $id = $this->input->get('id');
        $this->load->library("uploadhandler", array(
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 'upload_dir' => FCPATH . 'userfiles/customers/'
        ));
        $img = (string)$this->uploadhandler->filenaam();
        if ($img != '') {
            $this->customers->editpicture($id, $img);
        }
    }


    public function translist()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $cid = $this->input->post('cid');
        $list = $this->customers->trans_table($cid);
        $data = array();
        // $no = $_POST['start'];
        $no = $this->input->post('start');
        foreach ($list as $prd) {
            $no++;
            $row = array();
            $pid = $prd->id;
            $row[] = $prd->date;
            $row[] = amountExchange($prd->debit, 0, $this->aauth->get_user()->loc);
            $row[] = amountExchange($prd->credit, 0, $this->aauth->get_user()->loc);
            $row[] = $prd->account;

            $row[] = $this->lang->line($prd->method);
            $row[] = '<a href="' . base_url() . 'transactions/view?id=' . $pid . '" class="btn btn-secondary btn-sm" title="View"><span class="fa fa-eye"></span>  </a> <a href="#" data-object-id="' . $pid . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->trans_count_all($cid),
            "recordsFiltered" => $this->customers->trans_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function inv_list()
    {

        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $cid = $this->input->post('cid');
        $tid = $this->input->post('tyd');

        $list = $this->customers->inv_datatables($cid, $tid);
        // echo "<pre>";
        // print_r($list); die();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = '<a href="' . base_url("invoices/create?id=$invoices->invoice_number") . '"  title="View Invoice" target="_blank">'.$invoices->invoice_number.'</a>';
            $row[] = '<div class="text-center">'.$invoices->invoice_date.'<div>';
            $row[] = '<div class="text-right">'.number_format($invoices->total, 2).'</div>';
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("invoices/printinvoice?id=$invoices->invoice_number") . '&d=1" class="btn btn-secondary btn-sm"  title="Download"><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->invoice_number . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a> ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->inv_count_all($cid),
            "recordsFiltered" => $this->customers->inv_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function transactions()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $custid = $this->input->get('id');
        $data['details'] = $this->customers->details($custid);
        $data['money'] = $this->customers->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Customer Transactions';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/transactions', $data);
        $this->load->view('fixed/footer');
    }

    public function invoices()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $custid = $this->input->get('id');
        $data['details'] = $this->customers->details($custid);
        $data['money'] = $this->customers->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Customer Invoices';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/invoices', $data);
        $this->load->view('fixed/footer');
    }

    public function quotes()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $custid = $this->input->get('id');
        $data['details'] = $this->customers->details($custid);
        $data['money'] = $this->customers->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Customer Quotes';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/quotes', $data);
        $this->load->view('fixed/footer');
    }

    public function qto_list()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $cid = $this->input->post('cid');
        $tid = $this->input->post('tyd');
        $list = $this->customers->qto_datatables($cid, $tid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $dateqt = (!empty($invoices->quote_date)) ? date('d-m-Y',strtotime($invoices->quote_date)) :"";
            $row[] = '<a href="' . base_url("quote/create?id=$invoices->quote_number") . '"  title="View Invoice" target="_blank">'.$invoices->quote_number.'</a>';
            $row[] = '<div class="text-center">'.$dateqt.'</div>';
            $row[] = '<div class="text-right">'.number_format($invoices->total, 2).'</div>';
            $row[] = '<div class="text-center"><span class="st-' . $invoices->status . '">' . $invoices->status . '</span></div>';
            $row[] = ' <a href="' . base_url("billing/printquote?id=$invoices->quote_number") . '&token=1" class="btn btn-secondary btn-sm"  title="Download" target="_blank" ><span class="fa fa-download"></span></a> <a href="#" data-object-id="' . $invoices->quote_number . '" class="btn btn-secondary btn-sm delete-object" title="Delete"><span class="fa fa-trash"></span></a> ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->qto_count_all($cid),
            "recordsFiltered" => $this->customers->qto_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function balance()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $amount = $this->input->post('amount', true);
            if ($this->customers->recharge($id, $amount)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Balance Added')));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => 'Error!'));
            }
        } else {
            $custid = $this->input->get('id');
            $data['details'] = $this->customers->details($custid);
            $data['customergroup'] = $this->customers->group_info($data['details']['customer_group_id']);
            $data['money'] = $this->customers->money_details($custid);
            $head['usernm'] = $this->aauth->get_user()->username;
            $data['activity'] = $this->customers->activity($custid);
            $head['title'] = 'View Customer';
            $this->load->view('fixed/header', $head);
            $this->load->view('customers/recharge', $data);
            $this->load->view('fixed/footer');
        }
    }

    public function projects()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $custid = $this->input->get('id');
        $data['details'] = $this->customers->details($custid);
        $data['money'] = $this->customers->money_details($custid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Customer Invoices';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/projects', $data);
        $this->load->view('fixed/footer');
    }

    public function prj_list()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $cid = $this->input->post('cid');


        $list = $this->customers->project_datatables($cid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $project) {
            $no++;
            $name = '<a href="' . base_url() . 'projects/explore?id=' . $project->id . '">' . $project->name . '</a>';

            $row = array();
            $row[] = $no;
            $row[] = $name;
            $row[] = dateformat($project->sdate);
            $row[] = $project->customer;
            $row[] = '<span class="project_' . $project->status . '">' . $this->lang->line($project->status) . '</span>';

            $row[] = '<a class="btn btn-secondary btn-sm" href="' . base_url() . 'projects/edit?id=' . $project->id . '" data-object-id="' . $project->id . '" title="Edit"> <i class="fa fa-pencil"></i> </a>&nbsp;<a class="btn btn-secondary btn-sm delete-object" href="#" data-object-id="' . $project->id . '" title="Delete"> <i class="fa fa-trash"></i> </a>';


            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->project_count_all($cid),
            "recordsFiltered" => $this->customers->project_count_filtered($cid),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function notes()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $custid = $this->input->get('id');
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['details'] = $this->customers->details($custid);
        $this->session->set_userdata("cid", $custid);
        $head['title'] = 'Notes';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/notes', $data);
        $this->load->view('fixed/footer');
    }

    public function notes_load_list()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $cid = $this->input->post('cid');
        $list = $this->customers->notes_datatables($cid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $note) {
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $note->title;
            $row[] = dateformat($note->cdate);

            $row[] = '<a href="editnote?id=' . $note->id . '&cid=' . $note->fid . '" class="btn btn-secondary btn-sm" title="Edit"><span class="fa fa-pencil"></span> </a> <a class="btn btn-secondary btn-sm" href="#" data-object-id="' . $note->id . '" title="Delete"> <i class="fa fa-trash"></i> </a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->notes_count_all($cid),
            "recordsFiltered" => $this->customers->notes_count_filtered($cid),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function editnote()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $title = $this->input->post('title', true);
            $content = $this->input->post('content');
            $cid = $this->input->post('cid');
            if ($this->customers->editnote($id, $title, $content, $cid)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('UPDATED') . " <a href='notes?id=$cid' class='btn btn-secondary btn-sm' title='View'><span class='icon-user' aria-hidden='true'></span>  </a> <a href='editnote?id=$id&cid=$cid' class='btn btn-secondary btn-sm' title='Edit'><span class='icon-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $id = $this->input->get('id');
            $cid = $this->input->get('cid');
            $data['note'] = $this->customers->note_v($id, $cid);
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Edit';
            $this->load->view('fixed/header', $head);
            $this->load->view('customers/editnote', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function addnote()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        if ($this->input->post('title')) {

            $title = $this->input->post('title', true);
            $cid = $this->input->post('id');
            $content = $this->input->post('content');

            if ($this->customers->addnote($title, $content, $cid)) {
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ADDED') . "  <a href='addnote?id=" . $cid . "' class='btn btn-secondary btn-sm' title='Add'><span class='icon-plus-circle' aria-hidden='true'></span>  </a> <a href='notes?id=" . $cid . "' class='btn btn-secondary btn-sm' title='View'><span class='icon-eye' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            }
        } else {
            $data['id'] = $this->input->get('id');
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Add Note';
            $this->load->view('fixed/header', $head);
            $this->load->view('customers/addnote', $data);
            $this->load->view('fixed/footer');
        }

    }

    public function delete_note()
    {
        $id = $this->input->post('deleteid');
        $cid = $this->session->userdata('cid');
        if ($this->customers->deletenote($id, $cid)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    function statement()
    {

        if ($this->input->post()) {

            $this->load->model('reports_model');


            $customer = $this->input->post('customer');
            $trans_type = $this->input->post('trans_type');
            $sdate = datefordatabase($this->input->post('sdate'));
            $edate = datefordatabase($this->input->post('edate'));
            $data['customer'] = $this->customers->details($customer);


            $data['list'] = $this->reports_model->get_customer_statements($customer, $trans_type, $sdate, $edate);


            $html = $this->load->view('customers/statementpdf', $data, true);


            ini_set('memory_limit', '64M');
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output('Statement' . $customer . '.pdf', 'I');
        } else {
            $data['id'] = $this->input->get('id');
            $this->load->model('transactions_model');

            $data['details'] = $this->customers->details($data['id']);
            $head['title'] = "Account Statement";
            $head['usernm'] = $this->aauth->get_user()->username;
            $this->load->view('fixed/header', $head);
            $this->load->view('customers/statement', $data);
            $this->load->view('fixed/footer');
        }

    }


    public function documents()
    {
        $data['id'] = $this->input->get('id');
        $data['details'] = $this->customers->details($data['id']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->session->set_userdata("cid", $data['id']);
        $head['title'] = 'Documents';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/documents', $data);
        $this->load->view('fixed/footer');
    }

    public function document_load_list()
    {
        $cid = $this->input->post('cid');
        $list = $this->customers->document_datatables($cid);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $document) {
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $document->title;
            $row[] = dateformat($document->cdate);

            $row[] = '<a href="' . base_url('userfiles/documents/' . $document->filename) . '" class="btn btn-secondary btn-sm" title="View"><i class="fa fa-eye"></i> </a> <a class="btn btn-secondary btn-sm delete-object" href="#" data-object-id="' . $document->id . '" title="Delete"> <i class="fa fa-trash"></i> </a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->customers->document_count_all($cid),
            "recordsFiltered" => $this->customers->document_count_filtered($cid),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function adddocument()
    {
        $data['id'] = $this->input->get('id');
        $this->load->helper(array('form'));
        $data['response'] = 3;
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Add Document';

        $this->load->view('fixed/header', $head);

        if ($this->input->post('title')) {
            $title = $this->input->post('title', true);
            $cid = $this->input->post('id');
            $config['upload_path'] = './userfiles/documents';
            $config['allowed_types'] = 'docx|docs|txt|pdf|xls';
            $config['encrypt_name'] = TRUE;
            $config['max_size'] = 3000;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('userfile')) {
                $data['response'] = 0;
                $data['responsetext'] = 'File Upload Error';

            } else {
                $data['response'] = 1;
                $data['responsetext'] = 'Document Uploaded Successfully. <a href="documents?id=' . $cid . '"
                                       class="btn btn-secondary btn-sm" title="View"><i
                                                class="icon-folder"></i>
                                    </a>';
                $filename = $this->upload->data()['file_name'];
                $this->customers->adddocument($title, $filename, $cid);
            }

            $this->load->view('customers/adddocument', $data);
        } else {


            $this->load->view('customers/adddocument', $data);


        }
        $this->load->view('fixed/footer');


    }


    public function delete_document()
    {
        $id = $this->input->post('deleteid');
        $cid = $this->session->userdata('cid');

        if ($this->customers->deletedocument($id, $cid)) {
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    public function bulkpayment()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $data['id'] = $this->input->get('id');
        $data['details'] = $this->customers->details($data['id']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $this->session->set_userdata("cid", $data['id']);
        $head['title'] = 'Bulk Payment Invoices';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/bulkpayment', $data);
        $this->load->view('fixed/footer');
    }

    public function bulk_post()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $csd = $this->input->post('customer', true);
        $sdate = datefordatabase($this->input->post('sdate'));
        $edate = datefordatabase($this->input->post('edate'));
        $trans_type = $this->input->post('trans_type', true);
        $data['details'] = $this->customers->sales_due($sdate, $edate, $csd, $trans_type);

        $due = $data['details']['total'] - $data['details']['pamnt'];
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Calculated') . ' ' . amountExchange($due), 'due' => amountExchange_s($due)));
    }

    public function bulk_post_payment()
    {
        // if (!$this->aauth->premission(8)) {
        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        // }
        $csd = $this->input->post('customer', true);
        $account = $this->input->post('account', true);
        $pay_method = $this->input->post('pmethod', true);
        $amount = numberClean($this->input->post('amount', true));
        $sdate = datefordatabase($this->input->post('sdate_2'));
        $edate = datefordatabase($this->input->post('edate_2'));

        $trans_type = $this->input->post('trans_type_2', true);
        $note = $this->input->post('note', true);
        $data['details'] = $this->customers->sales_due($sdate, $edate, $csd, $trans_type, false, $amount, $account, $pay_method, $note);

        $due = 0;
        echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Paid') . ' ' . amountExchange($amount), 'due' => amountExchange_s($due)));
    }

    public function customer_details_byid(){
        $details = $this->customers->customer_details_byid($this->input->post('cid', true));
        echo json_encode(array('status' => 'Success', 'data' => $details['address']));

    }

    public function current_credit_limits(){
        $details = $this->customers->customer_credit_limit($this->input->post('customer_id', true));

        echo json_encode(array('status' => 'Success', 'data' => $details));

    }
    public function update_credit_limits(){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $customer_id = $this->input->post('customer_id', true);
        $available_creditlimit_val = $this->input->post('available_creditlimit_val', true);
        $creditlimit_val = $this->input->post('creditlimit_val', true);
        $new_credit_limit = $this->input->post('new_credit_limit', true);
        $updated_credit_limit_val = $this->input->post('updated_credit_limit_val', true);
        log_table_data('cberp_customers','cberp_customers_log', 'id' ,'customer_id','Update Credit Limit',$customer_id);
        $this->db->update("cberp_customers", [
            'credit_limit' => $new_credit_limit,
            'avalable_credit_limit' => $updated_credit_limit_val
        ], ['id' => $customer_id]);
        
        $data = [
            'customer_id' => $customer_id,
            'prev_credit_limit' => $creditlimit_val,
            'prev_avalable_credit_limit' => $available_creditlimit_val,
            'credit_limit' => $new_credit_limit,
            'avalable_credit_limit' => $updated_credit_limit_val,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('id')
        ];


        $changedFields = json_encode([
            [
                'fieldlabel' => 'CreditLimit',
                'field_name' => 'credit_limit',
                'oldValue' => $creditlimit_val,
                'newValue' => $new_credit_limit
            ]
        ]);
        
        // ($pagename,$item_no,$action_nane,$changedFields)
        // //erp2024 06-01-2025 detailed history log starts
        detailed_log_history('customer',$customer_id,'Updated',$changedFields);
        //erp2024 06-01-2025 detailed history log ends 
       
        $this->db->insert("customer_credit_limits", $data);
        // $details = $this->customers->customer_credit_limit($this->input->post('customer_id', true));
        echo json_encode(array('status' => 'Success', 'message'=>'Credit Limit Updated Successfully'));

    }
    public function min_max_creditlimit(){
        $details = $this->customers->min_max_creditlimit();;
        echo json_encode(array('status' => 'Success', 'data' => $details));
    }
    public function salesman_list()
    {
        $category_list = $this->customers->saleman_list();
        $catoption = "";
        if(!empty($category_list))
        {
            foreach($category_list as $row){
                $catoption .= "<option value='".$row['id']."'>".$row['name']."</option>";
            }
        }
        echo json_encode(array('data' => $catoption));
    }
}